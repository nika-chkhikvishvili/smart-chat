/**
 * Created by jedi on 2/23/16.
 */

'use strict';

var fifo = require('fifo');

var User = require('./models/User');
var Message = require('./models/Message');

var log;
var app;

function ChatServer(data) {
    if (!(this instanceof ChatServer)) {
        return new ChatServer(data);
    }
    app = data;
    log = data.log;
}

//აბრუნებს რიგში მყოფი, ოპერატორების მომლოდინეების სიას
ChatServer.prototype.getWaitingList = function (socket) {
    var ans = [];
    if (!!app.waitingClients && Array.isArray(app.waitingClients)) {
        app.waitingClients.forEach(function(val, idx){
            if (!!val) {
                ans[idx] = val.toArray();
            }
        });
    }
    socket.emit('getWaitingListResponse', ans);
};

ChatServer.prototype.getAllChatMessages = function (socket, data) {
    if (!data || !data.hasOwnProperty('chatUniqId') || !data.chatUniqId || data.chatUniqId.length < 10) {
        socket.emit("getAllChatMessagesResponse", {messages: []});
        return;
    }

    app.connection.query('SELECT * FROM  `chats` WHERE  chat_uniq_id = ?', [data.chatUniqId], function (err, res) {
        if (err) {
            return app.databaseError(socket, err);
        }

        if (res && Array.isArray(res) && res.length === 1) {
            var chat = res[0];
            app.connection.query('SELECT m.`chat_message_id` as messageId, m.`chat_id` as chatId, ' +
                    ' m.`person_id` as userId, m.`online_user_id` as guestUserId, m.`chat_message` as message, m.`message_date` as messageDate' +
                    ' FROM `smartchat`.`chat_messages` m where m.`chat_id` = ? order by   m.`message_date` asc', [chat.chat_id], function (err, res) {
                if (err) {
                    return app.databaseError(socket, err);
                }
                socket.emit("getAllChatMessagesResponse", {chatUniqId: data.chatUniqId, messages: res});
            });
        } else {
            socket.emit("getAllChatMessagesResponse", {messages: []});
        }
    });
};


//TODO აბრუნებს მიმდინარეების სიას
ChatServer.prototype.getActiveChats = function (socket) {
    app.connection.query('SELECT c.*, cs.service_name_geo, ou.online_users_name as user_first_name, ou.online_users_lastname as user_last_name ' +
            'FROM chats c left join online_users ou on ou.online_user_id = c.online_user_id' +
            ' left join category_services cs on cs.category_service_id = c.service_id' +
            ' WHERE chat_status_id = 1 order by chat_id asc', [], function (err, res) {
        if (err) {
            return app.databaseError(socket, err);
        }

        var ans = [];

        res.forEach(function (item) {
            if (app.chatRooms[item.chat_uniq_id]) {
                item.users = [];
                var chatRoom = app.chatRooms[item.chat_uniq_id];
                chatRoom.users.forEach(function (status, userId) {
                    if (app.onlineUsers[userId]) {
                        item.users.push(app.onlineUsers[userId].getLimited());
                    }
                });
            }
            ans.push(item);
        });
        socket.emit('getActiveChatsResponse', ans);
    });
};



ChatServer.prototype.redirectToService = function (socket, data) {
    if (!data || !data.hasOwnProperty('chatUniqId') || !data.chatUniqId || data.chatUniqId.length < 10
            || !data.hasOwnProperty('serviceId') || !data.serviceId) {
        socket.emit("redirectToServiceResponse", {isValid: false});
        return;
    }

    var chatRoom = app.chatRooms[data.chatUniqId];
    chatRoom.serviceId = data.serviceId;
    chatRoom.chat.serviceId = data.serviceId;

    app.connection.query('UPDATE chats SET service_id = ?, chat_status_id= 0 WHERE  chat_id = ?', [data.serviceId, chatRoom.chatId], function(err, res){
        if (err) {
            return app.databaseError(socket, err);
        }

        app.connection.query('UPDATE chat_rooms SET person_mode = 5  WHERE  chat_id = ? and person_id = ?', [chatRoom.chat.chatId, socket.user.userId], function (err, res){
            if (err) {
                return app.databaseError(socket, err);
            }

            if (!app.waitingClients[data.serviceId]) {
                app.waitingClients[data.serviceId] = fifo();
            }

            app.waitingClients[data.serviceId].push(chatRoom);
            socket.emit("redirectToServiceResponse", {isValid: true, chatUniqId: data.chatUniqId});
            app.checkAvailableOperatorForService(socket, data.serviceId);
        });
    });

};

ChatServer.prototype.getPersonsForRedirect = function (socket, data) {

    app.connection.query('SELECT person_id, first_name, last_name FROM persons where status_id = 0 AND repo_id IN ' +
            '(SELECT repo_id FROM persons WHERE person_id = 1)', [socket.user.userId], function(err, res){
        if (err) {
            return app.databaseError(socket, err);
        }

        socket.emit("getPersonsForRedirectResponse", res);
    });

};

ChatServer.prototype.redirectToPerson = function (socket, data) {

    if (!data || !data.hasOwnProperty('chatUniqId') || !data.chatUniqId || data.chatUniqId.length < 10) {
        socket.emit("redirectToPersonResponse", {isValid: false});
        return;
    }

    if (!data || !data.hasOwnProperty('personId') || !data.personId) {
        socket.emit("redirectToPersonResponse", {isValid: false});
        return;
    }

    if (!data || !data.hasOwnProperty('redirectType') || !data.redirectType) {
        socket.emit("redirectToPersonResponse", {isValid: false});
        return;
    }

    var chatRoom = app.chatRooms[data.chatUniqId];

    if (chatRoom.users.has(data.personId)) {
        socket.emit("redirectToPersonResponse", {isValid: true, chatUniqId: data.chatUniqId, redirectType: data.redirectType});
        return;
    }

    app.connection.query('INSERT INTO `chat_rooms` SET ? ', chatRoom.getInsertUserObject(data.personId, 7), function (err) {
        if (err) {
            return app.databaseError(null, err);
        }

        chatRoom.addUser(data.personId);
        var user = app.onlineUsers[data.personId];
        if (user && user.sockets) {
            Object.keys(user.sockets).forEach(function (socketId) {
                socket.broadcast.to(socketId).emit('newChatWindow', chatRoom );
            });
        }

        socket.emit("redirectToPersonResponse", {isValid: true, chatUniqId: data.chatUniqId, redirectType: data.redirectType});

        if (data.redirectType === 1) {
            app.connection.query('UPDATE `chat_rooms` SET person_mode = 5 WHERE chat_id =? AND person_id = ?', [chatRoom.chat.chatId, socket.user.userId],
                    function (err) {
                if (err) {
                    return app.databaseError(socket, err);
                }

                chatRoom.users.delete(socket.user.userId);

                var message = new Message();
                message.chatUniqId = data.chatUniqId;
                message.messageType = 'close';
                socket.emit('message', message);
            });
        }
    });
};


ChatServer.prototype.joinToRoom = function (socket, data) {

    if (!data || !data.hasOwnProperty('chatUniqId') || !data.chatUniqId || data.chatUniqId.length < 10) {
        socket.emit("joinToRoomResponse", {isValid: false});
        return;
    }

    var joinType = parseInt(data.joinType || NaN);

    if (!joinType || !isFinite(joinType)) {
        socket.emit("joinToRoomResponse", {isValid: false});
        return;
    }

    var chatRoom = app.chatRooms[data.chatUniqId];

    if (!chatRoom) {
        socket.emit("joinToRoomResponse", {isValid: false});
        return;
    }


    if (chatRoom.users.has(socket.user.userId)) {
        socket.emit("joinToRoomResponse", {isValid: true, chatUniqId: data.chatUniqId, joinType: joinType});
        return ;
    }

    var userJoinMode = 1;

    if (joinType === 1) {
        userJoinMode = 5;
    }

    if (joinType === 2) {
        userJoinMode = 4;
    }

    app.connection.query('INSERT INTO `chat_rooms` SET ? ', chatRoom.getInsertUserObject(socket.user.userId, joinType, userJoinMode), function (err, res1) {
        if (err) {
            return app.databaseError(socket, err);
        }

        var user = socket.user;
        chatRoom.addUser(user.userId, joinType, user);

        if (!!user && !!user.sockets) {
            Object.keys(user.sockets).forEach(function (socketId) {
                socket.broadcast.to(socketId).emit('newChatWindow', chatRoom);
            });
        }

        socket.emit('newChatWindow', chatRoom);

        socket.emit("joinToRoomResponse", {isValid: true, chatUniqId: data.chatUniqId, joinType: joinType});


        function disableUsers(chatId, userId){
                app.connection.query('UPDATE `chat_rooms` SET person_mode = 4 WHERE person_mode = 1 AND chat_id =? AND person_id = ?', [chatId, userId], function (err, res1) {
                    if (err) {
                        return app.databaseError(socket, err);
                    }

                    var message = new Message();
                    message.chatUniqId = data.chatUniqId;
                    message.messageType = 'leave';
                    var user = app.onlineUsers[userId];
                    if (!!user) {
                        chatRoom.removeUser(user);
                        Object.keys(user.sockets).forEach(function (socketId) {
                            socket.broadcast.to(socketId).emit('message', message);
                        });
                    }
                });

        }

        if (joinType === 1) {
            chatRoom.users.forEach(function (status, userId) {
                if (socket.user.userId !== userId && status === 1) {
                    disableUsers(chatRoom.chat.chatId, userId);
                }
            });
        }
    });
};

//ოპერატორის იდენტიფიკაცია
ChatServer.prototype.checkToken = function (socket, data) {
    socket.user = {
        isValid: false
    };

    if (!data || !data.hasOwnProperty('token') || !data.token || data.token.length < 10) {
        socket.emit("checkTokenResponse", {isValid: false});
        return;
    }

    app.connection.query('SELECT person_id, first_name, last_name, photo, is_admin, status_id,  nickname ' +
            ' FROM `persons` WHERE person_id in ' +
            '(SELECT history_person_id as person_id FROM xlog_login_history WHERE php_session_id = ? )', [data.token], function (err, res) {
        if (err) {
            return app.databaseError(socket, err);
        }

        if (!(res && Array.isArray(res) && res.length === 1)) {
            socket.emit("checkTokenResponse", {isValid: false});
            return;
        }

        var ans = res[0];

        if (!app.onlineUsers.hasOwnProperty(ans.person_id)) {
            app.onlineUsers[ans.person_id] = new User({userId: ans.person_id, firstName:ans.first_name, lastName: ans.last_name, isOnline: true, nickname: ans.nickname});
        }

        var user = app.onlineUsers[ans.person_id];
        user.addSocket(socket.id);
        user.addToken(data.token);
        user.isOnline = true;
        socket.user = user;

        app.connection.query('SELECT r.person_mode, c.chat_uniq_id, r.*, o.online_users_name as first_name, o.online_users_lastname as last_name ' +
                ' FROM chat_rooms r, chats c, online_users o where c.chat_id = r.chat_id and c.chat_status_id = 1 and  c.online_user_id = o.online_user_id ' +
                ' and r.person_mode in (1,2) and r.person_id = ?', [ans.person_id], function (err, resChat) {
            if (err) {
                return app.databaseError(socket, err);
            }

            var chatAns = [];
            if (resChat && Array.isArray(resChat)) {
                resChat.forEach(function (row) {
                    var chatRoom = app.chatRooms[row.chat_uniq_id];
                    chatRoom.addUser(user.userId, row.person_mode, user);

                    chatAns.push({
                        chatUniqId: row.chat_uniq_id,
                        first_name: row.first_name,
                        last_name: row.last_name,
                        joinType: row.person_mode
                    });
                })
            }

            socket.emit("checkTokenResponse", {isValid: true, ans: chatAns});
        });
    });
};

ChatServer.prototype.sendWelcomeMessage = function (socket, data) {
    var message = {
        chatUniqId: data,
        message: app.autoAnswering.getWelcomeMessage(1),
        id: -158
    };
    this.sendMessage(socket, message)
};

ChatServer.prototype.sendFile = function (socket, data) {
    var me = this;

    app.connection.query('SELECT files_id, file_name FROM files WHERE files_id = ?', [data.id], function (err, res) {
        if (err) {
            return app.databaseError(socket, err);
        }

        if (!res || res.length === 0) {
            return;
        }
        var file = res[0];

        var message = {
            chatUniqId: data.chatUniqId,
            message: '<a href="#' + file.file_name +'">' + file.file_name +'</a>',
            id: -157
        };
        me.sendMessage(socket, message)
    });
};

//ოპერატორის მიერ აკრეფილი ტექსტი, გაეგზავნება ყველას ოთახში ვინც არის
ChatServer.prototype.sendMessage = function (socket, data) {
    if (!data || !data.hasOwnProperty('chatUniqId') || !data.chatUniqId || data.chatUniqId.length < 10) {
        return socket.emit("sendMessageResponse", {isValid: false, error: 'chatUniqId', data: data});
    }

    if (!app.chatRooms || !app.chatRooms.hasOwnProperty(data.chatUniqId)) {
        return socket.emit("sendMessageResponse", {isValid: false, error: 'chatRooms'});
    }

    var chatRoom = app.chatRooms[data.chatUniqId];
    var user = socket.user;

    if (chatRoom.users.get(user.userId)!==1) {
        return socket.emit("sendMessageResponse", {isValid: false, error: 'Not Allowed'});
    }



    var message = new Message( {chatId: chatRoom.chatId, userId: socket.user.userId, message: data.message, chatUniqId : data.chatUniqId});

    app.connection.query('INSERT INTO `chat_messages` SET ? ', message.getInsertObject(), function (err, res) {
        if (err) {
            return app.databaseError(socket, err);
        }

        message.messageId = res.insertId;
        message.messageUniqId = data.id;
        message.sender = app.onlineUsers[socket.user.userId].nickname;

        app.sendMessageToRoom(socket, message);
        socket.emit("sendMessageResponse", {isValid: true});
    });
};


//ოპერატორის მიერ პიროვნების დაბლოკვა
ChatServer.prototype.banPerson = function (socket, data) {
    if (!data || !data.hasOwnProperty('chat_uniq_id') || !data.chat_uniq_id || data.chat_uniq_id.length < 10) {
        socket.emit("banPersonResponse", {isValid: false, error: 'chat_uniq_id', data: data});
        return;
    }

    if (!app.chatRooms || !app.chatRooms.hasOwnProperty(data.chat_uniq_id)) {
        socket.emit("banPersonResponse", {isValid: false, error: 'chatRooms'});
        return;
    }

    var chatId = app.chatRooms[data.chat_uniq_id].chatId;

    app.connection.query('SELECT * FROM chats WHERE chat_id = ?', [chatId], function (err, res) {
        if (err) {
            return app.databaseError(socket, err);
        }
        if (res && Array.isArray(res) && res.length === 1) {
            var ans = res[0];
            var banlist = {
                ban_id: null,
                ip: 1,
                ip_address: socket.handshake.address,
                chat_id: chatId,
                online_user_id: ans.online_user_id,
                person_id: socket.user.userId,
                //reason_id: '',
                ban_comment: data.message
            };

            app.connection.query('INSERT INTO `banlist` SET ? ', banlist, function (err) {
                if (err) {
                    return app.databaseError(socket, err);
                }
                socket.emit("banPersonResponse", {isValid: true});
            });
        }
    });
};

ChatServer.prototype.approveBan = function (socket, data) {
    if (!data || !data.hasOwnProperty('val')) {
        return;
    }
    var chatId = data.val;

    app.connection.query('SELECT * FROM chats WHERE chat_id = ?', [chatId], function (err, res) {
        if (err) {
            return app.databaseError(socket, err);
        }

        if (!res || res.length === 0) {
            return;
        }

        var messageClose = new Message({messageType: 'close'});
        messageClose.message = app.autoAnswering.getBanMessage(1);
        messageClose.chatUniqId = res[0].chat_uniq_id;
        app.sendMessageToRoom(socket, messageClose);

        var message = new Message({messageType: 'ban'});
        message.message = app.autoAnswering.getBanMessage(1);
        message.chatUniqId = res[0].chat_uniq_id;
        app.sendMessageToRoom(socket, message);
    });
};

ChatServer.prototype.messageReceived = function (socket, data) {
    if (!data || !data.hasOwnProperty('msgId') || !data.msgId || !data.hasOwnProperty('chatUniqId') || !data.chatUniqId) {
        return;
    }

    if (!app.chatRooms || !app.chatRooms.hasOwnProperty(data.chatUniqId)) {
        return;
    }

    app.sendMessageReceivedToRoom(socket, data.chatUniqId, data.msgId);
};


ChatServer.prototype.operatorIsWorking = function (socket, data) {
    if (!data || !data.hasOwnProperty('chatUniqId') || !data.chatUniqId || data.chatUniqId.length < 10) {
        return;
    }

    var message = new Message({messageType: 'operatorIsWorking'});
    message.chatUniqId = data.chatUniqId;
    app.sendMessageToRoom(socket, message);

};

ChatServer.prototype.operatorIsWriting = function (socket, data) {
    if (!data || !data.hasOwnProperty('chatUniqId') || !data.chatUniqId || data.chatUniqId.length < 10) {
        return;
    }

    if (!app.chatRooms || !app.chatRooms.hasOwnProperty(data.chatUniqId)) {
        return;
    }

    var message = new Message();
    message.chatUniqId = data.chatUniqId;
    message.messageType = 'writing';

    app.sendMessageToRoom(socket, message);
};

ChatServer.prototype.operatorCloseChat = function (socket,data) {
    if (!data || !data.hasOwnProperty('chatUniqId') || !data.chatUniqId) {
        return;
    }

    if (!app.chatRooms || !app.chatRooms.hasOwnProperty(data.chatUniqId)) {
        return;
    }

    app.connection.query('UPDATE  chats SET chat_status_id = 3 WHERE chat_uniq_id = ?', [data.chatUniqId], function (err) {
        if (err) {
            return app.databaseError(data, err);
        }
        var message = new Message();
        message.chatUniqId = data.chatUniqId;
        message.messageType = 'close';

        app.sendMessageToRoom(socket, message, true);
        app.checkAvailableServiceForOperator(socket);
        app.io.emit('checkClientCount');
        app.io.emit('checkActiveChats');
    });
};

module.exports = ChatServer;
