/**
 * Created by jedi on 2/23/16.
 */

var randomStringGenerator = require("randomstring");
var log;
var app;

module.exports = ChatClient;


function ChatClient(data) {
    if (!(this instanceof ChatClient)) return new ChatClient(data);
    app = data;
    log = data.log;
}

ChatClient.prototype.clientGetServices = function (socket) {
    app.connection.query('SELECT `cs`.`category_service_id`, `rc`.`repository_id`, `rc`.`category_name`, `cs`.`service_name_geo`, `cs`.`start_time`, `cs`.`end_time` ' +
        ' FROM `category_services` cs, `repo_categories` rc ' +
        ' WHERE cs.`repo_category_id` = rc.`repo_category_id`',
        function (err, rows, fields) {
            if (err) return app.databaseError(socket, err);
            socket.emit('clientGetServicesResponse', rows);
        });
};

ChatClient.prototype.clientInitParams = function (socket, data) {
    //შეამოწმებს სერვისის სამუშაო პერიოდს თუ არ გასცდა
    app.connection.query('SELECT start_time, end_time  FROM category_services WHERE category_service_id = ? ', [data.service_id], function (err, res) {
        if (err)  return app.databaseError(socket, err);

        if  (!res || !Array.isArray(res) || res.length !== 1) {
            socket.emit("clientInitParamsResponse", {isValid: false});
            return ;
        }

        var startTime = Date.parse('01/01/2000 '+ res[0].start_time);
        var endTime   = Date.parse('01/01/2000 '+ res[0].end_time);
        var nowTime = new Date();
        nowTime.setFullYear(2000,0,1);

        if (startTime !== endTime) {
            if (startTime > nowTime.getTime() || endTime < nowTime.getTime()) {
                socket.emit("clientInitParamsResponse", {serviceIsOffline: true});
                return ;
            }
        }

        var onlineUser = {first_name: data.first_name, last_name: data.last_name};

        app.connection.query('INSERT INTO `online_users` SET ? ', onlineUser, function (err, res) {
            if (err)  return app.databaseError(socket, err);

            var chatUniqId = randomStringGenerator.generate();
            var online_user_id = res.insertId;
            socket.onlineUserId = online_user_id;


            if (!app.waitingClients[data.service_id] || app.waitingClients[data.service_id] == null) {
                app.waitingClients[data.service_id] = fifo();
            }

            var chat = {online_user_id: online_user_id, service_id: data.service_id, chat_uniq_id: chatUniqId};
            app.connection.query('INSERT INTO `chats` SET ? ', chat, function (err, res) {
                if (err) return me.databaseError(socket, err);
                var chatId= res.insertId;

                var chatRoom = {chat_id: chatId, online_user_id: online_user_id};
                app.connection.query('INSERT INTO `chat_rooms` SET ? ', chatRoom, function (err, res1) {
                    if (err) return me.databaseError(socket, err);

                    app.chatRooms[chatUniqId] = {
                        chatId: chatId,
                        users : [],
                        guests: [socket.id]
                    };
                    app.waitingClients[data.service_id].push({
                        first_name: data.first_name,
                        last_name: data.last_name,
                        chat_uniq_id: chatUniqId,
                        chat_id: chatId,
                        online_user_id: online_user_id, service_id: data.service_id
                    });
                    //შეამოწმებს ვის შეუძლია უპასუხოს ამ კლიენტს და ავტომატურად დაამატებს ჩატში
                    app.checkAvailableOperatorForService(socket, data.service_id);
                    //უგზავნის სუყველას შეტყობინებას რომ ახალი მომხმარებელი შემოვიდა
                    app.io.emit('checkClientCount');
                    socket.emit("clientInitParamsResponse", {chatUniqId: chatUniqId});
                });
            });
        });
    });
};

ChatClient.prototype.clientCheckChatIfAvailable = function (socket, data) {
    if (!data || !data.hasOwnProperty('chatUniqId') || !data.chatUniqId || data.chatUniqId.length < 10) {
        socket.emit("clientCheckChatIfAvailableResponse", {isValid: false});
        return;
    }

    app.connection.query('SELECT * FROM  `chats` WHERE  chat_uniq_id = ?', [data.chatUniqId], function (err, res) {
        if (err) return app.databaseError(socket, err);

        if  (!(res && Array.isArray(res) && res.length === 1)) {
            socket.emit("clientCheckChatIfAvailableResponse", {isValid: false});
            return ;
        }

        var ans = res[0];
        socket.onlineUserId = ans.online_user_id;

        if (! app.chatRooms[data.chatUniqId] ) {
            app.chatRooms[data.chatUniqId] = {
                chatId: ans.chat_id,
                users : [],
                guests: []
            };
        }

        //ამოწმებს არის თუ არა ეს მომხმარებელი დამატებული ჩატის ოთახში
        var isAdded = false;

        app.chatRooms[data.chatUniqId].guests.forEach(function (socketId) {
            isAdded = isAdded || ( socketId == socket.id);
        });

        if (!isAdded) {
            //თუ არ არის დაამატებს
            app.chatRooms[data.chatUniqId].guests.push(socket.id);
        }


        app.connection.query('SELECT * FROM  `online_users` WHERE online_user_id = ?', [ans.online_user_id], function (err, res) {
            if (err) return me.databaseError(socket, err);

            if (!(res && Array.isArray(res) && res.length == 1)) {
                socket.emit("clientCheckChatIfAvailableResponse", {isValid: false});
                return ;
            }

            var user = res[0];
            me.connection.query('SELECT m.`chat_message_id`, m.`chat_id`, m.`person_id`, m.`online_user_id`, m.`chat_message`, m.`message_date`' +
                'FROM `smartchat`.`chat_messages` m where m.`chat_id` = ? order by   m.`message_date` asc', [ans.chat_id], function (err, res) {
                if (err) return me.databaseError(socket, err);

                socket.emit("clientCheckChatIfAvailableResponse", {
                    isValid: true, first_name: user.first_name,
                    last_name: user.last_name, messages: res
                });
            });
        });
    });
};

ChatClient.prototype.clientMessage = function (socket, data) {
    if (!data || !data.hasOwnProperty('chatUniqId') || !data.chatUniqId || data.chatUniqId.length < 10) {
        socket.emit("clientMessageResponse", {isValid: false, error: 'chatUniqId', data: data});
        return;
    }

    if (!app.chatRooms || !app.chatRooms.hasOwnProperty(data.chatUniqId)) {
        socket.emit("clientMessageResponse", {isValid: false, error: 'chatRooms'});
        return;
    }

    var chat = app.chatRooms[data.chatUniqId];
    var chatMessage = {chat_id: chat.chatId, online_user_id: socket.onlineUserId, chat_message: data.message};

    app.connection.query('INSERT INTO `chat_messages` SET ? ', chatMessage, function (err, res) {
        if (err) me.databaseError(socket, err);
        else {
            app.sendMessageToRoom(socket, data.chatUniqId, res.insertId, data.message, data.id);
            socket.emit("clientMessageResponse", res);
        }
    });
};

ChatClient.prototype.clientMessageReceived = function (socket, data) {
    if (!data || !data.hasOwnProperty('msgId') || !data.msgId) {
        return;
    }

    if (!app.chatRooms || !app.chatRooms.hasOwnProperty(data.chatUniqId)) {
        return;
    }

    app.sendMessageReceivedToRoom(socket, data.chatUniqId, data.msgId);
};

ChatClient.prototype.clientCloseChat = function (socket, data) {
    if (!data || !data.hasOwnProperty('chatUniqId') || !data.chatUniqId) {
        return;
    }

    if (!app.chatRooms || !app.chatRooms.hasOwnProperty(data.chatUniqId)) {
        return;
    }

    app.connection.query('UPDATE  chats SET chat_status_id = 3 WHERE chat_uniq_id = ?', [data.chatUniqId], function (err, res) {
        if (err) return me.databaseError(socket, err);
        app.sendMessageToRoom(socket, data.chat_uniq_id, 'close', 'close', 'close');
    });
};
