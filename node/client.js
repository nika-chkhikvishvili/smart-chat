/**
 * Created by jedi on 2/23/16.
 */

'use strict';

let fifo = require('fifo');

let Message = require('./models/Message');
let GuestUser = require('./models/GuestUser');
let Chat = require('./models/Chat');
let app;


function ChatClient(data) {
    if (!(this instanceof ChatClient)) {
        return new ChatClient(data);
    }
    app = data;
    // log = data.log;
}

function isNotValidDataChatUniqueId(data) {
    if (!data || !data.hasOwnProperty('chatUniqId') || !data.chatUniqId || data.chatUniqId.length < 10) {
        return true;
    }
    return !app.chats.has(data.chatUniqId);
}

ChatClient.prototype.clientGetServices = function (socket) {
    app.connection.query('SELECT `cs`.`category_service_id`, `rc`.`repository_id`, `rc`.`category_name`, `cs`.`service_name_geo`, `cs`.`start_time`, `cs`.`end_time` ' +
        ' FROM `category_services` cs, `repo_categories` rc ' +
        ' WHERE cs.`repo_category_id` = rc.`repo_category_id`', function (err, rows) {
        if (err) {
            return app.databaseError(socket, err);
        }
        socket.emit('clientGetServicesResponse', rows);
    });
};

ChatClient.prototype.clientInitParams = function (socket, data) {

    if (socket.isBlocked) {
        return;
    }

    if (!data || !data.hasOwnProperty('serviceId') || !data.serviceId) {
        socket.emit("clientInitParamsResponse", {isValid: false});
        return;
    }

    //შეამოწმებს სერვისის სამუშაო პერიოდს თუ არ გასცდა
    app.connection.query('SELECT start_time, end_time  FROM category_services WHERE category_service_id = ? ', [data.serviceId], function (err, res) {
        if (err) {
            return app.databaseError(socket, err);
        }

        if (!res || !Array.isArray(res) || res.length !== 1) {
            socket.emit("clientInitParamsResponse", {isValid: false});
            return;
        }

        let startTime = Date.parse('01/01/2000 ' + res[0].start_time);
        let endTime = Date.parse('01/01/2000 ' + res[0].end_time);
        let nowTime = new Date();
        nowTime.setFullYear(2000, 0, 1);

        if (startTime !== endTime) {
            if (startTime > nowTime.getTime() || endTime < nowTime.getTime()) {
                socket.emit("clientInitParamsResponse", {serviceIsOffline: true});
                return;
            }
        }

        let guestUser = new GuestUser({firstName: data.firstName, lastName: data.lastName, ip: socket.conn.remoteAddress});

        app.connection.query('INSERT INTO `online_users` SET ? ', guestUser.getInsertObject(), function (err, res) {
            if (err) {
                return app.databaseError(socket, err);
            }

            guestUser.guestUserId = parseInt(res.insertId);
            socket.guestUserId = guestUser.guestUserId;
            socket.guestUser = guestUser;
            guestUser.addSocket(socket.id);

            if (!app.waitingClients[data.serviceId] || app.waitingClients[data.serviceId] === null) {
                app.waitingClients[data.serviceId] = fifo();
            }

            let chat = new Chat({serviceId: data.serviceId, guestUserId: guestUser.guestUserId, guestUser: guestUser});

            app.connection.query('INSERT INTO `chats` SET ? ', chat.getInsertObject(), function (err, res) {
                if (err) {
                    return app.databaseError(socket, err);
                }
                chat.chatId = res.insertId;
                chat.guests.add(socket.id);
                app.chats.set(chat.chatUniqId, chat);

                app.connection.query('INSERT INTO `chat_rooms` SET ? ', chat.getInsertGuestObject(), function (err, res1) {
                    if (err) {
                        return app.databaseError(socket, err);
                    }

                    socket.chatUniqId = chat.chatUniqId;

                    app.waitingClients[data.serviceId].push(chat);
                    //უგზავნის სუყველას შეტყობინებას რომ ახალი მომხმარებელი შემოვიდა
                    app.io.emit('checkClientCount');
                    app.io.emit('checkActiveChats');
                    socket.emit("clientInitParamsResponse", {chatUniqId: chat.chatUniqId});
                    //შეამოწმებს ვის შეუძლია უპასუხოს ამ კლიენტს და ავტომატურად დაამატებს ჩატში
                    setTimeout(function () {app.checkAvailableOperatorForService(socket, data.serviceId);}, 1000);
                });
            });
        });
    });
};

ChatClient.prototype.clientCheckChatIfAvailable = function (socket, data) {
    if (socket.isBlocked) {
        socket.emit("clientCheckChatIfAvailableResponse", {isValid: false});
        return;
    }

    if (!data || !data.hasOwnProperty('chatUniqId') || !data.chatUniqId || data.chatUniqId.length < 10) {
        socket.emit("clientCheckChatIfAvailableResponse", {isValid: false});
        return;
    }
    app.connection.query('SELECT * FROM  `chats` WHERE chat_status_id in (0,1,2) AND chat_uniq_id = ? ', [data.chatUniqId], function (err, res) {
        if (err) {
            return app.databaseError(socket, err);
        }

        if (!(res && Array.isArray(res) && res.length === 1)) {
            socket.emit("clientCheckChatIfAvailableResponse", {isValid: false});
            return;
        }

        let ans = res[0];
        socket.guestUserId = ans.online_user_id;
        socket.chatUniqId = data.chatUniqId;

        let chat = app.chats.get(data.chatUniqId);
        chat.guests.add(socket.id);

        app.connection.query('SELECT * FROM  `online_users` WHERE online_user_id = ?', [ans.online_user_id], function (err, res) {
            if (err) {
                return app.databaseError(socket, err);
            }

            if (!(res && Array.isArray(res) && res.length === 1)) {
                socket.emit("clientCheckChatIfAvailableResponse", {isValid: false});
                return;
            }

            let user = res[0];
            app.connection.query('SELECT m.`chat_message_id` as `messageId`, m.`chat_id` as `chatId`, m.`person_id` as `userId`, ' +
                'm.`online_user_id` as `guestUserId`, m.`chat_message` as `message`, m.`message_date` as `messageDate`, ' +
                "persons.nickname as `sender`, 'message' as `messageType` " +
                'FROM chat_messages m left join persons on persons.person_id = m.person_id ' +
                ' where m.`chat_id` = ? order by   m.`message_date` asc', [ans.chat_id], function (err, res) {
                if (err) {
                    return app.databaseError(socket, err);
                }

                socket.emit("clientCheckChatIfAvailableResponse", {
                    isValid: true,
                    firstName: user.online_users_name,
                    lastName: user.online_users_lastname,
                    messages: res,
                    chatStatusId: ans.chat_status_id
                });
            });
        });
    });
};

ChatClient.prototype.clientMessage = function (socket, data) {
    if (!socket.hasOwnProperty('chatUniqId') || !socket.chatUniqId || socket.chatUniqId.length < 10) {
        socket.emit("clientMessageResponse", {isValid: false, error: 'chatUniqId', data: data});
        return;
    }

    if (!app.chats.has(socket.chatUniqId)) {
        socket.emit("clientMessageResponse", {isValid: false, error: 'chatRooms'});
        return;
    }

    let chat = app.chats.get(socket.chatUniqId);
    let message = new Message({chatId: chat.chatId, guestUserId: socket.guestUserId, message: data.message});

    app.connection.query('INSERT INTO `chat_messages` SET ? ', message.getInsertObject(), function (err, res) {
        if (err) {
            return app.databaseError(socket, err);
        }

        message.messageId = res.insertId;
        message.chatUniqId = socket.chatUniqId;
        message.messageUniqId = data.id;
        app.sendMessageToRoom(socket, message);
        socket.emit("clientMessageResponse", res);

    });
};

ChatClient.prototype.clientMessageReceived = function (socket, msgId) {
    if (!app.chatRooms || !app.chatRooms.hasOwnProperty(socket.chatUniqId)) {
        return;
    }
    app.sendMessageReceivedToRoom(socket, socket.chatUniqId, msgId);
};

ChatClient.prototype.clientCloseChat = function (socket) {
    if (isNotValidDataChatUniqueId(socket)) {
        return ;
    }

    let chat = app.getChat(socket.chatUniqId);

    app.connection.query('UPDATE  chats SET chat_status_id = 3 WHERE chat_uniq_id = ?', [socket.chatUniqId], function (err) {
        if (err) {
            return app.databaseError(socket, err);
        }
        let message = new Message();
        message.chatUniqId = socket.chatUniqId;
        message.messageType = 'close';

        app.sendMessageToRoom(socket, message, true);

        app.checkAvailableOperatorForService(socket, chat.serviceId);
        app.io.emit('checkClientCount');
        app.io.emit('checkActiveChats');
        chat.closeChat(app);
    });
};

ChatClient.prototype.userIsWriting = function (socket) {
    if (!socket || !socket.hasOwnProperty('chatUniqId') || !socket.chatUniqId || socket.chatUniqId.length < 10) {
        return;
    }

    if (!app.chatRooms || !app.chatRooms.hasOwnProperty(socket.chatUniqId)) {
        return;
    }

    var message = new Message();
    message.chatUniqId = socket.chatUniqId;
    message.messageType = 'writing';

    app.sendMessageToRoom(socket, message);
};

module.exports = ChatClient;
