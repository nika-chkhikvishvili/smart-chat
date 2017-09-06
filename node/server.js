/**
 * Created by jedi on 2/23/16.
 */

'use strict';

let fifo = require('fifo');

let User = require('./models/User');
let Message = require('./models/Message');

let log;
let app;

function ChatServer(data) {
    if (!(this instanceof ChatServer)) {
        return new ChatServer(data);
    }
    app = data;
    log = data.log;
}

function isNotValidDataChatUniqueId(data) {
    if (!data || !data.hasOwnProperty('chatUniqId') || !data.chatUniqId || data.chatUniqId.length < 10) {
        return true;
    }
    return !app.chats.has(data.chatUniqId);
}

//ოპერატორის იდენტიფიკაცია და პირველადი პარამეტრების ინიციალიზაცია
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

        let ans = res[0];

        if (!app.users.has(ans.person_id)) {
            app.users.set(ans.person_id, new User({userId: ans.person_id, firstName:ans.first_name, lastName: ans.last_name, isOnline: true, userName: ans.nickname}));
        }

        let user = app.users.get(ans.person_id);
        user.addSocket(socket);
        socket.user = user;
        socket.emit("userInfo", user.getLimited());

        app.connection.query('SELECT r.person_mode, c.chat_uniq_id, r.*, o.online_users_name as first_name, o.online_users_lastname as last_name ' +
            ' FROM chat_rooms r, chats c, online_users o where c.chat_id = r.chat_id and c.chat_status_id = 1 and  c.online_user_id = o.online_user_id ' +
            ' and r.person_mode in (1,2) and r.person_id = ?', [ans.person_id], function (err, resChat) {
            if (err) {
                return app.databaseError(socket, err);
            }

            let chatAns = [];
            if (resChat && Array.isArray(resChat)) {
                resChat.forEach(function (row) {
                    let chat = app.chats.get(row.chat_uniq_id);
                    chat.addUser(user, row.person_mode);

                    chatAns.push({
                        chatUniqId: row.chat_uniq_id,
                        first_name: row.first_name,
                        last_name: row.last_name,
                        joinType: row.person_mode
                    });
                })
            }
            socket.emit("checkTokenResponse", {isValid: true, ans: chatAns});

            app.checkAvailableServiceForOperator(user);
        });
    });
};

//აბრუნებს რიგში მყოფი, ოპერატორების მომლოდინეების სიას
ChatServer.prototype.getWaitingList = function (socket) {
    //TODo უნდა შეამოწმოს ასევე უფლება
    let ans = [];
    if (!!app.waitingClients && Array.isArray(app.waitingClients)) {
        app.waitingClients.forEach(function(val, idx){
            if (!!val) {
                ans[idx] = val.toArray();
            }
        });
    }
    socket.emit('getWaitingListResponse', ans);
};

//აბრუნებს მიმდინარეების სიას
ChatServer.prototype.getActiveChats = function (socket) {
    //TODo უნდა შეამოწმოს ასევე უფლება
    app.connection.query('SELECT c.*, cs.service_name_geo, ou.online_users_name as user_first_name, ou.online_users_lastname as user_last_name ' +
        'FROM chats c left join online_users ou on ou.online_user_id = c.online_user_id' +
        ' left join category_services cs on cs.category_service_id = c.service_id' +
        ' WHERE chat_status_id = 1 order by chat_id asc', [], function (err, res) {
        if (err) {
            return app.databaseError(socket, err);
        }

        let ans = [];

        res.forEach(function (item) {
            let chat = app.chats.get(item.chat_uniq_id);

            if (!!chat) {
                item.users = [];
                chat.users.forEach(function (status, userId) {
                    if (app.users.has(userId)) {
                        item.users.push(app.users.get(userId).getLimited());
                    }
                });
            }
            ans.push(item);
        });
        socket.emit('getActiveChatsResponse', ans);
    });
};

//აბრუნებს ჩატის სურლ მიმოწერას
ChatServer.prototype.getChatAllMessages = function (socket, data) {
    if (isNotValidDataChatUniqueId(data)) {
        return socket.emit("getAllChatMessagesResponse", {messages: []});
    }

    app.connection.query('SELECT * FROM  chats WHERE chat_uniq_id = ?', [data.chatUniqId], function (err, res) {
        if (err) {
            return app.databaseError(socket, err);
        }

        if (res && Array.isArray(res) && res.length === 1) {
            let chat = res[0];
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

ChatServer.prototype.sendWelcomeMessage = function (socket, data) {
    let chat = app.getChat(data);
    if (!chat) {
        return;
    }
    let message = {
        chatUniqId: data,
        message: app.autoAnswering.getWelcomeMessage(1, chat.language),
        id: -158
    };
    this.sendMessage(socket, message)
};

//ოპერატორის მიერ აკრეფილი ტექსტი, გაეგზავნება ყველას ოთახში ვინც არის
ChatServer.prototype.sendMessage = function (socket, data) {
    if (isNotValidDataChatUniqueId(data)) {
        return socket.emit("sendMessageResponse", {isValid: false, error: 'chatUniqId', data: data});
    }

    let chat = app.chats.get(data.chatUniqId);
    let user = socket.user;

    if (chat.users.get(user.userId)!==1) {
        return socket.emit("sendMessageResponse", {isValid: false, error: 'Not Allowed'});
    }

    let message = new Message( {chatId: chat.chatId, userId: socket.user.userId, message: data.message, chatUniqId : data.chatUniqId});

    app.connection.query('INSERT INTO `chat_messages` SET ? ', message.getInsertObject(), function (err, res) {
        if (err) {
            return app.databaseError(socket, err);
        }

        message.messageId = res.insertId;
        message.messageUniqId = data.id;
        message.sender = user.userName;

        app.sendMessageToRoom(message);
        socket.emit("sendMessageResponse", {isValid: true});
    });
};

//ფაილის გაგზავნა
ChatServer.prototype.sendFile = function (socket, data) {
    let me = this;
    app.connection.query('SELECT files_id, files_repo_id, file_name, file_type, ' +
        'file_path, full_path, raw_name, orig_name, client_name, file_ext, file_size, ' +
        'is_image, image_width, image_height, image_type, image_size_str, full ' +
        'FROM files WHERE files_id = ?', [data.id], function (err, res) {
        if (err) { return app.databaseError(socket, err); }
        if (!res || res.length === 0) { return; }
        let file = res[0];
        let imageText = '';

        if ( file.is_image === '1' || file.is_image === 1 ) {
            imageText = '<img class="popover_image" src="/uploads/' + file.file_name +'" height="100" />';
        } else {
            imageText = file.file_name;
        }

        let message = {
            chatUniqId: data.chatUniqId,
            message: '<a target="_blank" href="/uploads/' + file.file_name +'">' + imageText +'</a>',
            id: -157
        };
        me.sendMessage(socket, message)
    });
};

//ოპერატორი ტექსტის აკრეფის ანიმაცია
ChatServer.prototype.operatorIsWriting = function (socket, data) {
    if (isNotValidDataChatUniqueId(data)) {
        return ;
    }

    let message = new Message({chatUniqId: data.chatUniqId, messageType: 'writing'});
    app.sendMessageToRoom(message);
};

ChatServer.prototype.joinToRoom = function (socket, data) {
    if (isNotValidDataChatUniqueId(data)) {
        return socket.emit("joinToRoomResponse", {isValid: false});
    }

    let joinType = parseInt(data.joinType || NaN);

    if (!joinType || !isFinite(joinType)) {
        return socket.emit("joinToRoomResponse", {isValid: false});
    }

    let chat = app.chats.get(data.chatUniqId);

    if (chat.users.has(socket.user.userId)) {
        return socket.emit("joinToRoomResponse", {isValid: true, chatUniqId: data.chatUniqId, joinType: joinType});
    }

    let userJoinMode = 1;

    if (joinType === 1) {
        userJoinMode = 5;
    }

    if (joinType === 2) {
        userJoinMode = 4;
    }

    app.connection.query('INSERT INTO `chat_rooms` SET ? ', chat.getInsertUserObject(socket.user.userId, joinType, userJoinMode), function (err, res1) {
        if (err) {
            return app.databaseError(socket, err);
        }

        let user = socket.user;
        chat.addUser(user, joinType);
        chat.joinType = joinType;

        user.sockets.forEach(function (socketId) {
            socket.broadcast.to(socketId).emit('newChatWindow', chat);
        });

        socket.emit('newChatWindow', chat);
        socket.emit("joinToRoomResponse", {isValid: true, chatUniqId: data.chatUniqId, joinType: joinType});
        delete chat.joinType;

        function disableUsers(chatId, userId){
            app.connection.query('UPDATE `chat_rooms` SET person_mode = 4 WHERE person_mode = 1 AND chat_id =? AND person_id = ?', [chatId, userId], function (err, res1) {
                if (err) {
                    return app.databaseError(socket, err);
                }

                let message = new Message({chatUniqId: data.chatUniqId, messageType : 'leave'});

                let user = app.users.get(userId);
                if (!!user) {
                    chat.removeUser(user);
                    user.sockets.forEach(function (socketId) {
                        socket.broadcast.to(socketId).emit('message', message);
                    });
                }
            });
        }

        if (joinType === 1) {
            chat.users.forEach(function (status, userId) {
                if (socket.user.userId !== userId && status === 1) {
                    disableUsers(chat.chatId, userId);
                }
            });
        }
    });
};

ChatServer.prototype.leaveReadOnlyRoom = function (socket, data) {
    if (isNotValidDataChatUniqueId({chatUniqId: data})) {
        return socket.emit("joinToRoomResponse", {isValid: false});
    }
    let chat = app.chats.get(data);
    let user = socket.user;
    if (!chat.users.has(user.userId) || chat.users.get(user.userId) !== 2 ) {
        return socket.emit("leaveReadOnlyRoomResponse", {success: false});
    }

    app.connection.query('UPDATE chat_rooms SET person_mode = 3 WHERE person_mode = 2 AND chat_id = ? AND person_id = ?', [chat.chatId, user.userId], function (err, res) {
        if (err) {
            return app.databaseError(socket, err);
        }
        chat.removeUser(user);
        socket.emit("leaveReadOnlyRoomResponse", {success: true});
    });
};

ChatServer.prototype.takeRoom = function (socket, data) {
    if (isNotValidDataChatUniqueId({chatUniqId: data})) {
        return socket.emit("joinToRoomResponse", {isValid: false});
    }

    let chat = app.getChat(data);
    let user = socket.user;
    if (!chat.users.has(user.userId) || chat.users.get(user.userId) !==2 ) {
        return socket.emit("takeRoomResponse", {isValid: false});
    }

    app.connection.query('UPDATE chat_rooms SET person_mode = 3 WHERE person_mode = 2 AND chat_id = ? AND person_id = ?', [chat.chatId, user.userId], function (err, res) {
        if (err) {
            return app.databaseError(socket, err);
        }

        app.connection.query('INSERT INTO `chat_rooms` SET ? ', chat.getInsertUserObject(user.userId, 1, 5), function (err, res1) {
            if (err) {
                return app.databaseError(socket, err);
            }

            function disableUsers(chatId, userId){
                app.connection.query('UPDATE `chat_rooms` SET person_mode = 4 WHERE person_mode = 1 AND chat_id =? AND person_id = ?', [chatId, userId], function (err, res1) {
                    if (err) {
                        return app.databaseError(socket, err);
                    }

                    let message = new Message({chatUniqId: data, messageType : 'leave'});

                    let user = app.users.get(userId);
                    if (!!user) {
                        chat.removeUser(user);
                        user.sockets.forEach(function (socketId) {
                            socket.broadcast.to(socketId).emit('message', message);
                        });
                    }
                });
            }

            chat.users.forEach(function (status, userId) {
                if (socket.user.userId !== userId && status === 1) {
                    disableUsers(chat.chatId, userId);
                }
            });
            chat.addUser(user, 1);
        });
    });
};

ChatServer.prototype.operatorCloseChat = function (socket,data) {
    if (isNotValidDataChatUniqueId(data)) {
        return socket.emit("operatorCloseChat", {isValid: false});
    }

    let chat = app.getChat(data.chatUniqId);
    chat.closeChat(app, socket);
};

ChatServer.prototype.redirectToService = function (socket, data) {
    if (isNotValidDataChatUniqueId( data ) || !data.hasOwnProperty('serviceId') || !data.serviceId) {
        return socket.emit("redirectToServiceResponse", {success: false});
    }

    let chat = app.getChat(data.chatUniqId);
    chat.serviceId = data.serviceId;
    chat.chatStatusId = 0;

    app.connection.query('UPDATE chats SET service_id = ?, chat_status_id= 0 WHERE  chat_id = ?', [data.serviceId, chat.chatId], function(err, res) {
        if (err) {return app.databaseError(socket, err);}

        app.connection.query('UPDATE chat_rooms SET person_mode = 5  WHERE  chat_id = ? and person_id = ?', [chat.chatId, socket.user.userId], function (err, res) {
            if (err) {return app.databaseError(socket, err);}

            app.addChatToQueue(socket, chat);
            socket.emit("redirectToServiceResponse", {success: true, chatUniqId: data.chatUniqId});

            app.io.emit('checkClientCount');
            app.io.emit('checkActiveChats');
        });
    });
};

ChatServer.prototype.getPersonsForRedirect = function (socket, data) {
    app.connection.query('SELECT person_id, first_name, last_name FROM persons where status_id = 0 AND repo_id IN ' +
            '(SELECT repo_id FROM persons WHERE person_id = ?)', [socket.user.userId], function(err, res){
        if (err) {
            return app.databaseError(socket, err);
        }

        socket.emit("getPersonsForRedirectResponse", res);
    });

};

ChatServer.prototype.redirectToPerson = function (socket, data) {
    if (isNotValidDataChatUniqueId(data) || !data.hasOwnProperty('personId') || !data.personId) {
        return socket.emit("redirectToPersonResponse", {isValid: false});
    }

    if (!data || !data.hasOwnProperty('redirectType') || !data.redirectType) {
        socket.emit("redirectToPersonResponse", {isValid: false});
        return;
    }

    let chat = app.getChat(data.chatUniqId);

    if (chat.users.has(data.personId)) {
        return socket.emit("redirectToPersonResponse", {isValid: true, chatUniqId: data.chatUniqId, redirectType: data.redirectType});
    }

    app.connection.query('INSERT INTO `chat_rooms` SET ? ', chat.getInsertUserObject(data.personId, 1, 2), function (err) {
        if (err) {
            return app.databaseError(null, err);
        }
        let user = app.users.get(data.personId);
        chat.addUser(user, 1);

        user.sockets.forEach(function (socketId) {
            socket.broadcast.to(socketId).emit('newChatWindow', chat);
        });

        socket.emit("redirectToPersonResponse", {isValid: true, chatUniqId: data.chatUniqId, redirectType: data.redirectType});

        if (data.redirectType === 1) {
            app.connection.query('UPDATE `chat_rooms` SET person_mode = 5 WHERE chat_id =? AND person_id = ?', [chat.chatId, socket.user.userId],
                    function (err) {
                if (err) {
                    return app.databaseError(socket, err);
                }

                chat.removeUser(socket.user);

                let message = new Message({chatUniqId : data.chatUniqId, messageType : 'close'});
                socket.emit('message', message);
            });
        }
    });
};

//ოპერატორის მიერ პიროვნების დაბლოკვა
ChatServer.prototype.banPerson = function (socket, data) {
    if (isNotValidDataChatUniqueId(data)) {
        return socket.emit("banPersonResponse", {isValid: false, error: 'chatUniqId', data: data});
    }

    let chat = app.getChat(data.chatUniqId);

    let banlist = {
        ban_id: null,
        ip: 1,
        ip_address: socket.handshake.address,
        chat_id: chat.chatId,
        online_user_id: chat.guestUserId,
        person_id: socket.user.userId,
        //reason_id: '',
        status: 0,
        ban_comment: data.message
    };

    app.connection.query('INSERT INTO `banlist` SET ? ', banlist, function (err) {
        if (err) {
            socket.emit("banPersonResponse", {isValid: false});
            return app.databaseError(socket, err);
        }
        socket.emit("banPersonResponse", {isValid: true});
    });

};

ChatServer.prototype.approveBan = function (socket, data) {
    if (!data || !data.hasOwnProperty('val')) {
        return;
    }
    let chatId = data.val;

    app.connection.query('SELECT * FROM chats WHERE chat_id = ?', [chatId], function (err, res) {
        if (err) {
            return app.databaseError(socket, err);
        }

        if (!res || res.length === 0) {
            return;
        }

        let messageClose = new Message({messageType: 'close'});
        messageClose.message = app.autoAnswering.getBanMessage(1);
        messageClose.chatUniqId = res[0].chat_uniq_id;
        app.sendMessageToRoom(messageClose);

        let message = new Message({messageType: 'ban'});
        message.message = app.autoAnswering.getBanMessage(1);
        message.chatUniqId = res[0].chat_uniq_id;
        app.sendMessageToRoom(message);
    });
};

//TODO
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
    if (isNotValidDataChatUniqueId(data)) {
        return ;
    }

    let message = new Message({messageType: 'operatorIsWorking', chatUniqId : data.chatUniqId});
    app.sendMessageToRoom(message);

};

module.exports = ChatServer;
