/**
 * Created by jedi on 2/23/16.
 */

var randomStringGenerator = require("randomstring");

module.exports = ChatServer;

function ChatServer (data){
    if (!(this instanceof ChatServer)) return new ChatServer(data);
    this.connection = data.connection;
    this.chatRooms  = data.chatRooms;
    this.readyForPlay  = data.readyForPlay;
    this.onlinePersons  = data.onlinePersons;
}

ChatServer.prototype.databaseError = function(socket, err){
    console.log('Error while performing Query.');
    console.log(err);
    socket.emit('serverError');
};

ChatServer.prototype.auth = function (socket, data) {
    var me = this;
    var ans = {};

    if(!data || !data.hasOwnProperty('userName') || !data.hasOwnProperty('passwd') || data.userName.length <3 || data.passwd.length < 3){
        ans.isValidParams=false;
        socket.emit('authResponse', ans);
        return ;
    }

    this.connection.query('SELECT p.person_id,  p.email, p.`first_name`, p.`last_name`, p.`birth_date`, ' +
        'p.`password`, p.`phone`, p.`photo`, p.`reg_date`, p.`is_admin`, p.`last_visit` FROM `mydb`.`persons` p' +
        ' WHERE p.email =? AND p.password = ?', [data.userName, data.passwd ], function(err, rows, fields) {
        if (!err) {
            if(rows.length != 1){
                ans.authError = true;
            } else {
                ans.row = rows[0];
                ans.tokenTry = 1;
                var token = randomStringGenerator.generate();
                while(me.onlinePersons.hasOwnProperty(token)){
                    token = randomStringGenerator.generate();
                    ++ans.tokenTry;
                }
                ans.token = token;
                var date = new Date(); date.setTime(date.getTime()+30*60*1000);
                var personToken = {token: token,person_id: ans.row.person_id, expire : date };
                me.connection.query('INSERT INTO `person_tokens` SET ? ', personToken, function(err, res) {
                    if (!err) {
                        me.onlinePersons[token] = {
                            userId : ans.row.person_id,
                            isAdmin : ans.row.is_admin
                        };
                        ans.user = me.onlinePersons[token];
                        socket.user= {
                            id : ans.row.person_id
                        }
                    }  /* if err */ else databaseError(err);
                });
            }
            socket.emit('authResponse', ans);
        } /* if err */else databaseError(err);
    });
};

ChatServer.prototype.checkToken = function (socket, data) {
    var me = this;
    if (!data || !data.hasOwnProperty('token') || !data.token || data.token.length <10){
        socket.emit("checkTokenResponse",{isValid: false});
        return ;
    }

    me.connection.query('SELECT * FROM person_tokens WHERE token = ?', [data.token ],  function(err, res) {
        if (err) me.databaseError(socket, err);
        else {
            if(res && Array.isArray(res) && res.length == 1){
                var ans = res[0];
                me.connection.query('SELECT c.chat_uniq_id, r.* FROM chat_rooms r, chats c ' +
                    'where c.chat_id = r.chat_id and c.chat_status_id = 0 and r.person_id = ?', [ ans.person_id ], function(err, res) {
                    if (err) me.databaseError(socket, err);
                    else {
                        var ans = [];
                        if (res && Array.isArray(res)){
                            res.forEach(function (row){
                                ans.push({chatUniqId:row.chat_uniq_id});
                                if (chatRooms[row.chat_uniq_id]){
                                    var isAdded = false;
                                    me.chatRooms[row.chat_uniq_id].users.forEach(function(socketId){
                                        isAdded = isAdded || ( socketId == socket.id);
                                    });
                                    if(!isAdded) {
                                        me.chatRooms[row.chat_uniq_id].users.push(socket.id);
                                    }
                                }
                            });
                            socket.user= {
                                id : ans.person_id
                            }
                        }
                        socket.emit("checkTokenResponse",{isValid: true, ans: ans});
                    }
                });
            } else  socket.emit("checkTokenResponse",{isValid: false});
        }
    });
};

ChatServer.prototype.sendMessage = function (socket, data, sendMessageToRoom) {
    var me = this;
    if (!data || !data.hasOwnProperty('chatUniqId') || !data.chatUniqId || data.chatUniqId.length <10){
        socket.emit("sendMessageResponse",{isValid: false, error: 'chatUniqId',data:data});
        return ;
    }

    if (!me.chatRooms || !me.chatRooms.hasOwnProperty(data.chatUniqId)){
        socket.emit("sendMessageResponse",{isValid: false, error: 'chatRooms' });
        return ;
    }

    var chat = me.chatRooms[data.chatUniqId];
    var chatMessage = { chat_id: chat.chatId, person_id: socket.user.id, chat_message: data.message };

    me.connection.query('INSERT INTO `chat_messages` SET ? ', chatMessage, function(err, res) {
        if (err) me.databaseError(socket, err);
        else {
            sendMessageToRoom(socket, data.chatUniqId, res.insertId, data.message, data.id);
            socket.emit("sendMessageResponse",res);

            /*
             var chatUniqId = randomStringGenerator.generate();
             var online_user_id = res.insertId;
             var chat = { online_user_id: online_user_id , repo_id: data.repo_id, chatUniqId : chatUniqId };
             me.connection.query('INSERT INTO `chats` SET ? ', chat,  function(err, res) {
             if (err) me.databaseError(socket, err);
             else {
             var chatRoom = { chat_id: res.insertId , online_user_id: online_user_id };
             me.connection.query('INSERT INTO `chat_rooms` SET ? ', chatRoom,  function(err, res) {
             if (err) me.databaseError(socket, err);
             else {
             me.chatRooms[chatUniqId] = [];
             me.chatRooms[chatUniqId].push(socket.id);
             socket.emit("clientInitParamsResponse",{ chatUniqId: chatUniqId });
             }
             });
             }
             });
             */
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

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

ChatServer.prototype.clientGetRepositories = function (socket) {
    this.connection.query('SELECT r.`repository_id`, r.`name`, r.`other_name` FROM `repositories` r ',
        function(err, rows, fields) {
            if (err) databaseError(err);
            else {
                socket.emit('clientGetRepositoriesResponse', rows);
            }
        });
};

ChatServer.prototype.clientInitParams = function (socket, data) {
    var me = this;
    var onlineUser = { first_name: data.first_name, last_name: data.last_name };
    me.connection.query('INSERT INTO `online_users` SET ? ', onlineUser, function(err, res) {
        if (err) me.databaseError(socket, err);
        else {
            var chatUniqId = randomStringGenerator.generate();
            var online_user_id = res.insertId;
            socket.onlineUserId= online_user_id;
            var chat = { online_user_id: online_user_id , repo_id: data.repo_id, chat_uniq_id : chatUniqId };
            me.connection.query('INSERT INTO `chats` SET ? ', chat,  function(err, res) {
                if (err) me.databaseError(socket, err);
                else {
                    var chatRoom = { chat_id: res.insertId , online_user_id: online_user_id };
                    me.connection.query('INSERT INTO `chat_rooms` SET ? ', chatRoom,  function(err, res) {
                        if (err) me.databaseError(socket, err);
                        else {
                            me.chatRooms[chatUniqId] = {
                                chatId : res.insertId,
                                users : [socket.id]
                            };

                            socket.emit("clientInitParamsResponse",{ chatUniqId: chatUniqId });
                        }
                    });
                }
            });
        }
    });
};

ChatServer.prototype.clientCheckChatIfAvariable = function (socket, data) {
    var me = this;
    if (!data || !data.hasOwnProperty('chatUniqId') || !data.chatUniqId || data.chatUniqId.length <10){
        socket.emit("clientCheckChatIfAvariableResponse",{isValid: false});
        return ;
    }

    me.connection.query('SELECT * FROM  mydb.`chats` WHERE  chat_uniq_id = ?', [data.chatUniqId ],  function(err, res) {
        if (err) me.databaseError(socket, err);
        else {
            if(res && Array.isArray(res) && res.length == 1){
                var ans = res[0];
                if( me.chatRooms[data.chatUniqId] && me.chatRooms[data.chatUniqId].users && me.chatRooms[data.chatUniqId].users.length>0 ){
                    var isAdded = false;
                    me.chatRooms[data.chatUniqId].users.forEach(function(socketId){
                        isAdded = isAdded || ( socketId == socket.id);
                        });
                    if(!isAdded) {
                        me.chatRooms[data.chatUniqId].users.push(socket.id);
                    }
                } else {
                    me.chatRooms[data.chatUniqId] = {
                        chatId : ans.chat_id,
                        users : [socket.id]
                    };
                }

                me.connection.query('SELECT * FROM  mydb.`online_users` WHERE online_user_id = ?', [ans.online_user_id ],  function(err, res) {
                    if (err) me.databaseError(socket, err);
                    else {
                        if(res && Array.isArray(res) && res.length == 1) {
                            var user = res[0];
                            socket.emit("clientCheckChatIfAvariableResponse", {isValid: true, first_name :user.first_name,
                                last_name :user.last_name });

                        } else  socket.emit("clientCheckChatIfAvariableResponse",{isValid: false});
                    }
                });
            } else  socket.emit("clientCheckChatIfAvariableResponse",{isValid: false});
        }
    });
};



