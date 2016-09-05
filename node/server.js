/**
 * Created by jedi on 2/23/16.
 */

var randomStringGenerator = require("randomstring");

module.exports = ChatServer;

function ChatServer (data){
    if (!(this instanceof ChatServer)) return new ChatServer(data);
    this.connection = data.connection;
    this.chatRooms  = data.chatRooms;
    this.waitingClients  = data.waitingClients;
    this.onlinePersons  = data.onlinePersons;
}

// ბაზის შეცდომის დროს
ChatServer.prototype.databaseError = function(socket, err){
    console.log('Error while performing Query.');
    console.log(err);
    socket.emit('serverError');
};

//აბრუნებს რიგში მყოფი, ოპერატორების მომლოდინეების სიას
ChatServer.prototype.getWaitingList = function (socket) {
    var me = this;
    var ans=[];
    if ( me.waitingClients &&  Array.isArray(me.waitingClients)) {
        for (i = 0; i < me.waitingClients.length; ++i) {
            if(me.waitingClients[i]) ans[i] = me.waitingClients[i].toArray();
        }
    }
    socket.emit('getWaitingListResponse', ans);

};

// აბრუნებს მიმდინარეების სიას
ChatServer.prototype.getActiveChats = function (socket) {
    var me = this;
    var ans=[];

    me.connection.query('SELECT * FROM smartchat.chats where chat_status_id = 1 ', [ ], function(err, res) {
        if (err) me.databaseError(socket, err);
        else {
            var ans = [];

            res.forEach(function(item){
                ans.push(item);
            });
            socket.emit('getActiveChatsResponse', ans);

        }
    });



    //for (var property in me.chatRooms) {
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
    //}
    //
    //socket.emit('getActiveChatsResponse', ans);

};

//სერვისის მიხედვით იღებს პირველ კლიენტს და ხსნის საუბარს, აბრუნებს ჩატის უნიკალურ იდს
ChatServer.prototype.getNextWaitingClient = function (socket,data) {
    var me = this;
    var ans = {};
    var serviceId = parseInt(data.serviceId);
    if (isNaN(serviceId)
        || !me.waitingClients
        || !Array.isArray(me.waitingClients)
        || !me.waitingClients[serviceId]
        || me.waitingClients[serviceId].isEmpty()
        || !socket.user
        || !socket.user.isValid
        || !socket.user.userId
    ) {
        socket.emit('getNextWaitingClientResponse', ans);
        return ;
    }

    var waiting = me.waitingClients[serviceId].shift();

    me.connection.query('UPDATE `smartchat`.`chats` SET `chat_status_id` = 1 WHERE `chat_id` = ?',[ waiting.chat_id], function(err, res) {
        if (err)  { me.waitingClients[serviceId].unshift(waiting); me.databaseError(socket, err);  }
        else {
            var chatRoom = { chat_id: waiting.chat_id , person_id: socket.user.userId };
            me.connection.query('INSERT INTO `chat_rooms` SET ? ', chatRoom,  function(err, res1) {
                if (err) me.databaseError(socket, err);
                else {
                    var isAdded = false;
                    me.chatRooms[waiting.chat_uniq_id].users.forEach(function(socketId){
                        isAdded = isAdded || ( socketId == socket.id);
                    });
                    if(!isAdded) {
                        me.chatRooms[waiting.chat_uniq_id].users.push(socket.id);
                    }
                    ans = {
                        chat_uniq_id : waiting.chat_uniq_id,
                        first_name : waiting.first_name,
                        last_name : waiting.last_name
                    };

                    socket.emit('getNextWaitingClientResponse', ans);
                }
            });
        }
    });

};

//ოპერატორის იდენტიფიკაცია
ChatServer.prototype.checkToken = function (socket, data) {
    var me = this;
    socket.user = {
        isValid: false
    };

    if (!data || !data.hasOwnProperty('token') || !data.token || data.token.length <10){
        socket.emit("checkTokenResponse",{isValid: false});
        return ;
    }

    me.connection.query('SELECT * FROM person_tokens WHERE token = ?', [data.token ],  function(err, res) {
        if (err) me.databaseError(socket, err);
        else {
            if(res && Array.isArray(res) && res.length == 1){
                var ans = res[0];
                me.connection.query('SELECT c.chat_uniq_id, r.*, o.first_name, o.last_name FROM chat_rooms r, chats c  , online_users o ' +
                    'where c.chat_id = r.chat_id and c.chat_status_id = 1 and  c.online_user_id = o.online_user_id and r.person_id = ?', [ ans.person_id ], function(err, resChat) {
                    if (err) me.databaseError(socket, err);
                    else {
                        var chatAns = [];
                        if (resChat && Array.isArray(resChat)){
                            resChat.forEach(function (row){
                                chatAns.push({chatUniqId:row.chat_uniq_id,
                                first_name: row.first_name,
                                    last_name : row.last_name

                                });
                                if (chatRooms[row.chat_uniq_id]){
                                    var isAdded = false;
                                    me.chatRooms[row.chat_uniq_id].users.forEach(function(socketId){
                                        isAdded = isAdded || ( socketId == socket.id);
                                    });
                                    if(!isAdded) {
                                        me.chatRooms[row.chat_uniq_id].users.push(socket.id);
                                    }
                                }
                            })
                        }
                        socket.user= {
                            userId : ans.person_id,
                            isValid: true
                        };
                        socket.emit("checkTokenResponse",{isValid: true, ans: chatAns});
                    }
                });
            } else   socket.emit("checkTokenResponse",{isValid: false});
        }
    });
};


//ოპერატორის მიერ აკრეფილი ტექსტი, გაეგზავნება ყველას ოთახში ვინც არის
ChatServer.prototype.sendMessage = function (socket, data, sendMessageToRoom) {
    var me = this;
    if (!data || !data.hasOwnProperty('chat_uniq_id') || !data.chat_uniq_id || data.chat_uniq_id.length <10){
        socket.emit("sendMessageResponse",{isValid: false, error: 'chat_uniq_id',data:data});
        return ;
    }


    if (!me.chatRooms || !me.chatRooms.hasOwnProperty(data.chat_uniq_id)) {
        socket.emit("sendMessageResponse", {isValid: false, error: 'chatRooms'});
        return;
    }


    var chat = me.chatRooms[data.chat_uniq_id];

    var chatMessage = { chat_id: chat.chatId, person_id: socket.user.userId, chat_message: data.message };

    me.connection.query('INSERT INTO `chat_messages` SET ? ', chatMessage, function(err, res) {
        if (err) me.databaseError(socket, err);
        else {
            sendMessageToRoom(socket, data.chat_uniq_id, res.insertId, data.message, data.id);
            socket.emit("sendMessageResponse",{isValid: true });
        }
    });
};



ChatServer.prototype.messageReceived = function (socket, data, sendMessageReceivedToRoom) {
    var me = this;
    if (!data || !data.hasOwnProperty('msgId') || !data.msgId || !data.hasOwnProperty('chatUniqId') || !data.chatUniqId ){
        return ;
    }

    if (!me.chatRooms || !me.chatRooms.hasOwnProperty(data.chatUniqId)){
        return ;
    }

    sendMessageReceivedToRoom(socket, data.chatUniqId, data.msgId);

};


