'use strict';


let app = {};

let GuestUser = require('./models/GuestUser');
let Chat = require('./models/Chat');
let User = require('./models/User');
let AutoAnswering = require('./models/AutoAnswering');
let Message = require('./models/Message');

app.log = require('npmlog');
let app1 = require('express')();
let http_server = require('http').createServer(app1);
let mysql = require('mysql');
let fifo = require('fifo');
http_server.listen(3000);

app.connection = mysql.createConnection({
    host: 'localhost',
    user: 'smartchat',
    password: 'smartchat',
    database: 'smartchat'
});

app.connection.connect();

app.io = require('socket.io')(http_server);

let redis = require("redis");
let redisClient = redis.createClient();

redisClient.on("error", function (err) {
    console.log("Error " + err);
});

redisClient.set("string key", "string val", redis.print);
redisClient.hset("hash key", "hashtest 1", "some value", redis.print);
redisClient.hset(["hash key", "hashtest 2", "some other value"], redis.print);

redisClient.hkeys("hash key", function (err, replies) {
    console.log(replies.length + " replies:");
    replies.forEach(function (reply, i) {
        console.log("    " + i + ": " + reply);
    });
    //redisClient.quit();
});

app.chats = new Map();
app.waitingClients = [];
app.users = new Map();
app.onlineGuests = new Map();
app.autoAnswering = {};

let client = require('./client.js')(app);
let server = require('./server.js')(app);


// for debug
global.app = app;

app.databaseError = function (socket, err) {
    console.error('Error while performing Query.');
    console.error(err);
    console.trace();
    if (socket) {
        socket.emit('serverError');
    }
};

app.getChat = function(chatUniqueId){
    return app.chats.get(chatUniqueId);
};

app.getUser = function(userId){
    return app.users.get(userId);
};

app.addChatToQueue = function(socket, chat){
    if (!chat || !chat.hasOwnProperty('serviceId')) {
        return ;
    }
    if (!app.waitingClients[chat.serviceId]) {
        app.waitingClients[chat.serviceId] = fifo();
    }
    app.waitingClients[chat.serviceId].push(chat);
    setTimeout(function () {app.checkAvailableOperatorForService(socket, chat.serviceId);}, 1000);

};

app.connection.query('SELECT * FROM  persons WHERE status_id = 0', function (err, rows, fields) {
    if (err) {
        console.log(err);
        process.exit(1);
    }
    rows.forEach(function (row) {
        app.users.set(row.person_id, new User({
            userId: row.person_id,
            isValid: true,
            userName: row.nickname,
            firstName: row.first_name,
            lastName: row.last_name,
            isAdmin: row.is_admin,
            isOnline: 0
        }));
    });
});

// initialite services
/*app.connection.query('SELECT `t`.`token`, `t`.`person_id`, `t`.`add_date`, `t`.`expire`, `t`.`expired` ' +
 'FROM `person_tokens` t', function(err, rows, fields) {
 if(err) {
 console.log(err);
 process.exit(1);
 }
 rows.forEach(function (row){
 if (!app.onlineUsers[row.person_id]) {
 app.onlineUsers[row.person_id] = {
 isAdmin : false,
 sockets : [],
 tokens :[]
 }
 }
 app.onlineUsers[row.person_id].tokens.push(row.token);
 });
 });*/

//გლობალური პარამეტრების ინიციალიზაცია
app.connection.query('SELECT auto_answering_id, repository_id, start_chating, mail_offline, connect_failed, user_block, ' +
    'auto_answering, repeat_auto_answering, time_off FROM  auto_answering', function (err, rows) {
    if (err) {
        console.log(err);
        process.exit(1);
    }
    app.autoAnswering = new AutoAnswering(rows);
});

//დაუსრულებელი ჩატების და  რიგში მდგომი სტუმრების ინიციალიზაცია
app.connection.query('SELECT `c`.`chat_id`,    `c`.`online_user_id`,    `c`.`service_id`,    `c`.`chat_uniq_id`,  c.`chat_status_id`, ' +
    ' u.online_user_id,  `u`.online_users_name as first_name,    `u`.online_users_lastname as last_name ' +
    ' FROM chats c, online_users u where c.online_user_id = u.online_user_id and c.`chat_status_id` in (0,1) order by  c.`add_date` asc ', function (err, rows) {
    if (err) {
        console.log(err);
        process.exit(1);
    }

    function getUsersForChat(chat) {
        app.connection.query('SELECT person_id, person_mode FROM chat_rooms where person_id is not null and person_mode in (1,2) and chat_id = ? ', [chat.chatId], function (err, chatRoomRows) {
            if (err) {
                console.log(err);
                process.exit(1);
            }
            if (chatRoomRows !== null && Array.isArray(chatRoomRows)) {
                chatRoomRows.forEach(function (chatRoomRow) {
                    chat.addUser(app.users.get(chatRoomRow.person_id), chatRoomRow.person_mode);
                });
            }
        });
    }

    rows.forEach(function (row) {
        let guestUser = new GuestUser({
            guestUserId: row.online_user_id,
            firstName: row.first_name,
            lastName: row.last_name
        });
        let chat = new Chat({
            chatId: row.chat_id,
            chatUniqId: row.chat_uniq_id,
            serviceId: row.service_id,
            guestUser: guestUser,
            guestUserId: guestUser.guestUserId
        });


        app.chats.set(row.chat_uniq_id, chat);

        getUsersForChat(chat);

        if (row.chat_status_id === 0) {
            if (!app.waitingClients[row.service_id] || app.waitingClients[row.service_id] === null) {
                app.waitingClients[row.service_id] = fifo();
            }
            app.waitingClients[row.service_id].push(chat);
        }
    });
});

app.sendMessageToRoomUsers = function (socket, message) {
    let chat = app.chats.get(message.chatUniqId);
    if (!chat) {
        return;
    }

    chat.users.forEach(function (status, userId) {
        let user = app.users.get(userId);
        if (!!user) {
            user.sockets.forEach(function (socketId) {
                socket.broadcast.to(socketId).emit('message', message);
            });
        }
    });
};

app.sendMessageToRoomGuests = function (socket, message) {
    let chat = app.chats.get(message.chatUniqId);
    if (!chat) {
        return;
    }
    chat.guests.forEach(function (socketId) {
        socket.broadcast.to(socketId).emit('message', message);
    });
};

app.sendMessageToRoom = function (socket, message, sendToMe) {
    if (!message) {
        return;
    }
    let chat = app.chats.get(message.chatUniqId);
    if (!chat) {
        return;
    }
    app.sendMessageToRoomUsers(socket, message);
    app.sendMessageToRoomGuests(socket, message);
    if (sendToMe) {
        socket.emit('message', message);
    }
};

app.sendMessageReceivedToRoom = function (socket, chatUniqId, msgId) {
    // var chat = app.chatRooms[chatUniqId];
    // var users = chat.users;
    // users.forEach(function (socketId) {
    //     if (socketId != socket.id) {
    //         socket.broadcast.to(socketId).emit('messageReceived', {msgId: msgId});
    //     }
    // });
};

app.checkAvailableServiceForOperator = function (socket) {
    let user = socket.user;
    if (!user.canTakeMore()) {
        return;
    }
    app.connection.query('SELECT service_id FROM person_services WHERE person_id = ?', [user.userId], function (err, res) {
        if (err) {
            return app.databaseError(socket, err);
        }

        res.forEach(function (item) {
            let serviceQuee = app.waitingClients[item.service_id];
            if (!!serviceQuee && !serviceQuee.isEmpty() && user.canTakeMore()) {
                app.addOperatorToService(socket, user.userId, item.service_id, 1)
            }
        });
        app.checkAvailableServiceForOperator(socket);
    })
};

app.addOperatorToService = function (socket, userId, serviceId, joinedModeId) {
    if (app.waitingClients[serviceId].length === 0) {
        return ;
    }

    let waiting = app.waitingClients[serviceId].shift();
    let chat = app.getChat(waiting.chatUniqId);

    app.connection.query('UPDATE chats SET chat_status_id = 1 WHERE chat_id = ?', [waiting.chatId], function (err) {
        if (err) {
            app.waitingClients[serviceId].unshift(waiting);
            return app.databaseError(socket, err);
        }

        app.connection.query('INSERT INTO chat_rooms SET ? ', chat.getInsertUserObject(userId, 1, joinedModeId), function (err) {
            if (err) {
                return app.databaseError(socket, err);
            }

            let user = app.users.get(userId);

            if (!!user) {
                chat.addUser(user, 1);
                user.sockets.forEach(function (socketId) {
                    socket.broadcast.to(socketId).emit('newChatWindow', chat);
                });
                if (socket.hasOwnProperty('user')) {
                    socket.emit('newChatWindow', chat);
                }
            }

            chat.guests.forEach(function (socketId) {
                if (socket.id === socketId) {
                    socket.emit('operatorJoined', app.autoAnswering.getWelcomeMessage(1));
                } else {
                    socket.broadcast.to(socketId).emit('operatorJoined', app.autoAnswering.getWelcomeMessage(1));
                }
            });
            app.io.emit('checkActiveChats');
        });
    });
};

//სერვისის მიხედვით იღებს პირველ კლიენტს და ხსნის საუბარს, აბრუნებს ჩატის უნიკალურ იდს
app.checkAvailableOperatorForService = function (socket, serviceId) {
    let serviceIdParsed = parseInt(serviceId);
    if (isNaN(serviceIdParsed)
        || !app.waitingClients
        || !Array.isArray(app.waitingClients)
        || !app.waitingClients[serviceIdParsed]
        || app.waitingClients[serviceIdParsed].isEmpty()) {
        return;
    }

    let wh = '-1';

    app.users.forEach(function (user) {
        if (user.isOnline && user.canTakeMore()) {
            wh = wh + ',' + user.userId;
        }
    });

    app.connection.query('SELECT person_id, (select count(*) from chat_rooms r where  r.person_id = p.person_id and chat_id ' +
        ' in(SELECT chat_id FROM smartchat.chats c WHERE c.chat_status_id = 1 ) ) as open_windows' +
        ' FROM smartchat.persons p WHERE person_id in ( select person_id from person_services  where service_id = ? and person_id in (' + wh + ') ) ' +
        ' order by open_windows asc', [serviceId], function (err, res) {
        if (err) {
            return app.databaseError(socket, err);
        }

        if (!res || !Array.isArray(res) || res.length === 0) {
            return;
        }

        if (res[0].open_windows < 5) {
            app.addOperatorToService(socket, res[0].person_id, serviceIdParsed, 1);
        }
    });
};

app.io.on('connection', function (socket) {
    // check if blocked
    app.connection.query('SELECT count(*) as cou FROM banlist WHERE ip_address = ? ' +
        'AND banlist.status = 1 AND banlist.add_date > now() - INTERVAL 1 month', [socket.handshake.address], function (err, res) {
        if (err) {
            return app.databaseError(socket, err);
        }
        let isBlocked = (res[0].cou === '1' || res[0].cou === 1);
        socket.blockCheckCount = socket.hasOwnProperty('blockCheckCount') ? socket.blockCheckCount + 1 : 0;

        if (isBlocked) {
            socket.isBlocked = true;
            let message = new Message({messageType: 'ban'});
            message.message = app.autoAnswering.getBanMessage(1);
            socket.emit("message", message);
        }
    });

    socket.on('clientGetServices', function () {
        client.clientGetServices(socket);
    });
    socket.on('clientInitParams', function (data) {
        client.clientInitParams(socket, data);
    });
    socket.on('clientCheckChatIfAvailable', function (data) {
        client.clientCheckChatIfAvailable(socket, data);
    });
    socket.on('clientMessage', function (data) {
        client.clientMessage(socket, data);
    });
    socket.on('clientMessageReceived', function (data) {
        client.clientMessageReceived(socket, data);
    });
    socket.on('clientCloseChat', function () {
        client.clientCloseChat(socket);
    });
    socket.on('userIsWriting', function (data) {
        client.userIsWriting(socket, data);
    });

    socket.on('test', function () {
        console.dir('test');
        socket.emit('testResponse', socket.blockCheckCount);
    });

    socket.on('checkToken', function (data) {
        server.checkToken(socket, data);
    });
    socket.on('getWaitingList', function () {
        server.getWaitingList(socket);
    });
    socket.on('getActiveChats', function () {
        server.getActiveChats(socket);
    });
    socket.on('joinToRoom', function (data) {
        server.joinToRoom(socket, data);
    });
    socket.on('redirectToPerson', function (data) {
        server.redirectToPerson(socket, data);
    });
    socket.on('redirectToService', function (data) {
        server.redirectToService(socket, data);
    });
    socket.on('getPersonsForRedirect', function (data) {
        server.getPersonsForRedirect(socket, data);
    });
    socket.on('getChatAllMessages', function (data) {
        server.getChatAllMessages(socket, data);
    });
    socket.on('sendMessage', function (data) {
        server.sendMessage(socket, data);
    });
    socket.on('sendFile', function (data) {
        server.sendFile(socket, data);
    });
    socket.on('sendWelcomeMessage', function (data) {
        server.sendWelcomeMessage(socket, data);
    });
    socket.on('operatorIsWorking', function (data) {
        server.operatorIsWorking(socket, data);
    });
    socket.on('operatorIsWriting', function (data) {
        server.operatorIsWriting(socket, data);
    });
    socket.on('operatorCloseChat', function (data) {
        server.operatorCloseChat(socket, data);
    });
    socket.on('banPerson', function (data) {
        server.banPerson(socket, data);
    });
    socket.on('approveBan', function (data) {
        server.approveBan(socket, data);
    });
    socket.on('leaveReadOnlyRoom', function (data) {
        server.leaveReadOnlyRoom(socket, data);
    });
    socket.on('takeRoom', function (data) {
        server.takeRoom(socket, data);
    });
    socket.on('disconnect', function () {
        if (socket.hasOwnProperty('user')) {
            socket.user.removeSocket(socket);
        }

        if (socket.hasOwnProperty('guestUser')) {
            let guestUser = socket.guestUser;
            guestUser.removeSocket(socket.id);

        }
        app.io.emit('userDisconnect', {
            id: socket.id
        });
    });
});

console.log("Started:" + (new Date().toISOString()));
