/**
 * Created by jedi on 2/23/16.
 */

var randomStringGenerator = require("randomstring");
var User = require('./models/User');
var Message = require('./models/Message');

var log;
var app;

module.exports = ChatServer;

function ChatServer(data) {
    if (!(this instanceof ChatServer)) return new ChatServer(data);
    app = data;
    log = data.log;
}

//აბრუნებს რიგში მყოფი, ოპერატორების მომლოდინეების სიას
ChatServer.prototype.getWaitingList = function (socket) {
    var ans = [];
    if (app.waitingClients && Array.isArray(app.waitingClients)) {
        for (i = 0; i < app.waitingClients.length; ++i) {
            if (app.waitingClients[i]) ans[i] = app.waitingClients[i].toArray();
        }
    }
    socket.emit('getWaitingListResponse', ans);
};

ChatServer.prototype.getAllChatMessages = function (socket, data) {
    if (!data || !data.hasOwnProperty('chat_uniq_id') || !data.chat_uniq_id || data.chat_uniq_id.length < 10) {
        socket.emit("getAllChatMessagesResponse", {messages: []});
        return;
    }

    app.connection.query('SELECT * FROM  `chats` WHERE  chat_uniq_id = ?', [data.chat_uniq_id], function (err, res) {
        if (err) app.databaseError(socket, err);
        else {
            if (res && Array.isArray(res) && res.length === 1) {
                var chat = res[0];
                app.connection.query('SELECT m.`chat_message_id`, m.`chat_id`, m.`person_id`, m.`online_user_id`, m.`chat_message`, m.`message_date`' +
                    'FROM `smartchat`.`chat_messages` m where m.`chat_id` = ? order by   m.`message_date` asc', [chat.chat_id], function (err, res) {
                    if (err) app.databaseError(socket, err);
                    else {
                        socket.emit("getAllChatMessagesResponse", {chat_uniq_id: data.chat_uniq_id, messages: res});
                    }
                });
            } else  socket.emit("getAllChatMessagesResponse", {messages: []});
        }
    });

};


// აბრუნებს მიმდინარეების სიას

ChatServer.prototype.getActiveChats = function (socket) {
    var ans = [];
    app.connection.query('SELECT c.*, cs.service_name_geo, ou.first_name as user_first_name, ou.last_name as user_last_name ' +
        'FROM smartchat.chats c left join online_users ou on ou.online_user_id = c.online_user_id' +
        ' left join category_services cs on cs.category_service_id = c.service_id' +
        ' WHERE chat_status_id = 1 ' +
        'order by chat_id asc', [], function (err, res) {
        if (err)  return app.databaseError(socket, err);

            var ans = [];

            res.forEach(function (item) {
                if (app.chatRooms[item.chat_uniq_id]) {
                    item.users = [];
                    var chatRoom = app.chatRooms[item.chat_uniq_id];
                    if (chatRoom.users && Array.isArray(chatRoom.users)) {
                        chatRoom.users.forEach(function (userId) {
                            item.users.push(app.onlineUsers[userId].getLimited());
                        });
                    }
                }
                ans.push(item);
            });
            socket.emit('getActiveChatsResponse', ans);
    });


    // for (var property in me.chatRooms) {
    //    if (me.chatRooms.hasOwnProperty(property)) {
    //        var a = {
    //            char_uniq_id : property,
    //            users : []
    //        };
    //
    //        var users = me.chatRooms[property].users;
    //        users.forEach(function(item){
    //            var s = io.sockets.connected[item];
    //            a.users.push(s.user);
    //        });
    //
    //        ans.push(a);
    //    }
    // }
    //
    // socket.emit('getActiveChatsResponse', ans);

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


    app.connection.query('SELECT person_id, first_name, last_name, photo, is_admin, status_id ' +
        ' FROM `persons` WHERE person_id in ' +
        '(SELECT history_person_id as person_id FROM xlog_login_history WHERE php_session_id = ? )', [data.token], function (err, res) {
        if (err) return app.databaseError(socket, err);

        if (!(res && Array.isArray(res) && res.length === 1)) {
            socket.emit("checkTokenResponse", {isValid: false});
            return;
        }

        var ans = res[0];

        if (!app.onlineUsers.hasOwnProperty(ans.person_id)) {
            app.onlineUsers[ans.person_id] = new User(ans);
            }

        var user = app.onlineUsers[ans.person_id];
        user.addSocket(socket.id);
        user.addToken(data.token);

        app.connection.query('SELECT c.chat_uniq_id, r.*, o.first_name, o.last_name FROM chat_rooms r, chats c  , online_users o ' +
            'where c.chat_id = r.chat_id and c.chat_status_id = 1 and  c.online_user_id = o.online_user_id and r.person_id = ?', [ans.person_id], function (err, resChat) {
            if (err) return app.databaseError(socket, err);

            var chatAns = [];
            if (resChat && Array.isArray(resChat)) {

                resChat.forEach(function (row) {
                    chatAns.push({
                        chatUniqId: row.chat_uniq_id,
                        first_name: row.first_name,
                        last_name: row.last_name
                    });
                })
            }
            socket.user = {
                userId: ans.person_id,
                isValid: true
            };
            socket.emit("checkTokenResponse", {isValid: true, ans: chatAns});
        });
    });
};


//ოპერატორის მიერ აკრეფილი ტექსტი, გაეგზავნება ყველას ოთახში ვინც არის
ChatServer.prototype.sendMessage = function (socket, data) {
    if (!data || !data.hasOwnProperty('chat_uniq_id') || !data.chat_uniq_id || data.chat_uniq_id.length < 10) {
        socket.emit("sendMessageResponse", {isValid: false, error: 'chat_uniq_id', data: data});
        return;
    }


    if (!app.chatRooms || !app.chatRooms.hasOwnProperty(data.chat_uniq_id)) {
        socket.emit("sendMessageResponse", {isValid: false, error: 'chatRooms'});
        return;
    }


    var chat = app.chatRooms[data.chat_uniq_id];

    var chatMessage = {chat_id: chat.chatId, person_id: socket.user.userId, chat_message: data.message};

    me.connection.query('INSERT INTO `chat_messages` SET ? ', chatMessage, function (err, res) {
        if (err) return app.databaseError(socket, err);


            app.sendMessageToRoom(socket, data.chat_uniq_id, res.insertId, data.message, data.id);
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

    var chatId = me.chatRooms[data.chat_uniq_id].chatId;

    me.connection.query('SELECT * FROM chats WHERE chat_id = ?', [chatId], function (err, res) {
        if (err) me.databaseError(socket, err);
        else {
            if (res && Array.isArray(res) && res.length == 1) {

                var ans = res[0];

                var banlist = {
                    ban_id: null,
                    ip: 1,
                    ip_address: '',
                    chat_id: chatId,
                    online_user_id: ans.online_user_id,
                    person_id: socket.user.userId,
                    //reason_id: '',
                    ban_comment: data.message
                };

                me.connection.query('INSERT INTO `banlist` SET ? ', banlist, function (err, res) {
                    if (err) me.databaseError(socket, err);
                    else {
                        socket.emit("banPersonResponse", {isValid: true});
                    }
                });
            }
        }
    });
};


ChatServer.prototype.messageReceived = function (socket, data) {
    var app = this.app;
    if (!data || !data.hasOwnProperty('msgId') || !data.msgId || !data.hasOwnProperty('chatUniqId') || !data.chatUniqId) {
        return;
    }

    if (!app.chatRooms || !app.chatRooms.hasOwnProperty(data.chatUniqId)) {
        return;
    }

    app.sendMessageReceivedToRoom(socket, data.chatUniqId, data.msgId);

};


ChatServer.prototype.operatorIsWorking = function (socket, data) {
    var app = this.app;

    if (!data || !data.hasOwnProperty('chat_uniq_id') || !data.chat_uniq_id || data.chat_uniq_id.length < 10) {
        socket.emit("pingResponse", {isValid: false, error: 'chat_uniq_id', data: data});
        return;
    }
    var chat = app.chatRooms[data.chat_uniq_id];

    app.sendMessageToRoom(socket, data.chat_uniq_id, 'ping', 'ping', 'ping');

};
