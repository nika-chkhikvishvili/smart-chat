'use strict';


var app = {};

var GuestUser = require('./models/GuestUser');
var Chat = require('./models/Chat');
var ChatRoom = require('./models/ChatRoom');
var AutoAnswering = require('./models/AutoAnswering');
var Message = require('./models/Message');

app.log = require('npmlog');
var app1 = require('express')();
var http_server = require('http').createServer(app1);
var mysql = require('mysql');
var fifo = require('fifo');
http_server.listen(3000);

app.connection = mysql.createConnection({
    host: 'localhost',
    user: 'smartchat',
    password: 'smartchat',
    database: 'smartchat'
});
app.connection.connect();

app.io = require('socket.io')(http_server);

var redis = require("redis");
var redisClient = redis.createClient();

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

app.chatRooms = {};
app.waitingClients = [];
app.onlineUsers = {};
app.autoAnswering = {};

var client = require('./client.js')(app);
var server = require('./server.js')(app);


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
        ' FROM chats c, online_users u where c.online_user_id = u.online_user_id and c.`chat_status_id` in ( 0,1) order by  c.`add_date` asc ', function (err, rows) {
    if (err) {
        console.log(err);
        process.exit(1);
    }
    rows.forEach(function (row) {
        var guestUser = new GuestUser({
            guestUserId: row.online_user_id,
            firstName: row.first_name,
            lastName: row.last_name
        });
        var chat = new Chat({
            chatId: row.chat_id,
            serviceId: row.service_id,
            guestUser: guestUser,
            guestUserId: guestUser.guestUserId
        });
        var chatRoom = new ChatRoom({chat: chat});

        app.chatRooms[row.chat_uniq_id] = chatRoom;

        (function (rowVal) {
            app.connection.query('SELECT person_id FROM chat_rooms where person_id is not null and person_mode in (1,2,7) and chat_id = ? ', [rowVal.chat_id], function (err, chatRoomRows) {
                if (err) {
                    console.log(err);
                    process.exit(1);
                }
                if (chatRoomRows !== null && Array.isArray(chatRoomRows)) {
                    chatRoomRows.forEach(function (chatRoomRow) {
                        app.chatRooms[rowVal.chat_uniq_id].users.push(chatRoomRow.person_id);
                    });
                }
                // console.log(app.chatRooms[chatRow.chat_uniq_id]);
            });
        })(row);

        if (row.chat_status_id === 0) {
            if (!app.waitingClients[row.service_id] || app.waitingClients[row.service_id] === null) {
                app.waitingClients[row.service_id] = fifo();
            }
            app.waitingClients[row.service_id].push(chatRoom);
        }
    });
});

app.sendMessageToRoomUsers = function (socket, message) {
    var chat = app.chatRooms[message.chatUniqId];
    if (!chat) {
        return;
    }

    if (!chat.users || !Array.isArray(chat.users)) {
        return;
    }

    Object.keys(chat.users).forEach(function (key) {
        var user = app.onlineUsers[chat.users[key]];
        if (user && user.sockets) {
            Object.keys(user.sockets).forEach(function (socketId) {
                socket.broadcast.to(socketId).emit('message', message);
            });
        }
    });
};

app.sendMessageToRoomGuests = function (socket, message) {
    var chat = app.chatRooms[message.chatUniqId];
    if (!chat) {
        return;
    }
    var guests = chat.guests;
    guests.forEach(function (socketId) {
        socket.broadcast.to(socketId).emit('message', message);
    });
};

app.sendMessageToRoom = function (socket, message, sendToMe) {
    if (!message) {
        return;
    }
    var chat = app.chatRooms[message.chatUniqId];
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


//სერვისის მიხედვით იღებს პირველ კლიენტს და ხსნის საუბარს, აბრუნებს ჩატის უნიკალურ იდს
app.checkAvailableOperatorForService = function (socket, serviceId) {
    var serviceIdParsed = parseInt(serviceId);
    if (isNaN(serviceIdParsed)
            || !app.waitingClients
            || !Array.isArray(app.waitingClients)
            || !app.waitingClients[serviceIdParsed]
            || app.waitingClients[serviceIdParsed].isEmpty()) {
        return;
    }

    var wh = '-1';

    Object.keys(app.onlineUsers).forEach(function (id) {
        if (app.onlineUsers[id].isOnline) {
            wh = wh + ',' + id;
        }
    });

    app.connection.query('SELECT person_id, (select count(*) from chat_rooms r where  r.person_id = p.person_id and chat_id ' +
            ' in(SELECT chat_id FROM smartchat.chats c WHERE c.chat_status_id = 1 and c.service_id = ?) ) as open_windows' +
            ' FROM smartchat.persons p WHERE person_id in ( select person_id from person_services  where service_id = ? and person_id in (' + wh + ') )' +
            ' order by open_windows asc', [serviceId, serviceId], function (err, res) {
        if (err) {
            return app.databaseError(socket, err);
        }

        if (!res || !Array.isArray(res) || res.length === 0) {
            return;
        }
        var userId = res[0].person_id;

        var waiting = app.waitingClients[serviceId].shift();
        var chatRoom;  // =  waiting ;//new ChatRoom({chat: waiting.chat, userId: userId});
        if (app.chatRooms.hasOwnProperty(waiting.chatUniqId)) {
            chatRoom = app.chatRooms[waiting.chatUniqId];
        } else {
            app.chatRooms[waiting.chatUniqId] = waiting;
            chatRoom = app.chatRooms[waiting.chatUniqId];
        }

        app.connection.query('UPDATE `smartchat`.`chats` SET `chat_status_id` = 1 WHERE `chat_id` = ?', [waiting.chatId], function (err) {
            if (err) {
                app.waitingClients[serviceId].unshift(waiting);
                app.databaseError(null, err);
                return;
            }

            app.connection.query('INSERT INTO `chat_rooms` SET ? ', chatRoom.getInsertUserObject(userId, 1), function (err) {
                if (err) {
                    return app.databaseError(null, err);
                }
                chatRoom.addUser(userId);

                var user = app.onlineUsers[userId];
                if (user && user.sockets) {
                    Object.keys(user.sockets).forEach(function (socketId) {
                        socket.broadcast.to(socketId).emit('newChatWindow', chatRoom);
                    });
                }

                Object.keys(chatRoom.guests).forEach(function (socketId) {
                    if (socket.id === chatRoom.guests[socketId]) {
                        socket.emit('operatorJoined', app.autoAnswering.getWelcomeMessage(1));
                    } else {
                        socket.broadcast.to(chatRoom.guests[socketId]).emit('operatorJoined', {
                            userName: user.firstName,
                            message: app.autoAnswering.getWelcomeMessage(1)
                        });
                    }
                });

            });
        });
    });
};

app.io.on('connection', function (socket) {
    // check if blocked
    app.connection.query('SELECT count(*) as cou FROM banlist WHERE ip_address = ? ' +
            'AND banlist.status = 1 AND banlist.add_date > now() - INTERVAL 1 month', [socket.handshake.address], function (err, res) {
        if (err) {
            return app.databaseError(socket, err);
        }
        var isBlocked = (res[0].cou === '1' || res[0].cou === 1);
        socket.blockCheckCount = socket.hasOwnProperty('blockCheckCount') ? socket.blockCheckCount + 1 : 0;

        if (isBlocked) {
            socket.isBlocked = true;
            var message = new Message({messageType: 'ban'});
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
    socket.on('clientCloseChat', function (data) {
        client.clientCloseChat(socket, data);
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
    socket.on('getAllChatMessages', function (data) {
        server.getAllChatMessages(socket, data);
    });
    socket.on('sendMessage', function (data) {
        server.sendMessage(socket, data);
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

    socket.on('disconnect', function () {
        if (socket.hasOwnProperty('user')) {
            delete app.onlineUsers[socket.user.userId].sockets[socket.id];
            app.onlineUsers[socket.user.userId].isOnline = Object.keys(app.onlineUsers[socket.user.userId].sockets).length > 0;
        }
        app.io.emit('userDisconnect', {
            id: socket.id
        });
    });
});

console.log("Started:" + (new Date().toISOString()));
