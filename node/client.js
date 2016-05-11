/**
 * Created by jedi on 2/23/16.
 */

var randomStringGenerator = require("randomstring");

module.exports = ChatClient;

function ChatClient (data){
    if (!(this instanceof ChatClient)) return new ChatClient(data);
    this.connection   = data.connection;
    this.chatRooms    = data.chatRooms;
    this.readyForPlay = data.readyForPlay;
    this.io           = data.io;
}

ChatClient.prototype.databaseError = function(socket, err){
    console.error('Error while performing Query.');
    console.error(err);
    console.trace();
    socket.emit('serverError');
};

ChatClient.prototype.clientGetRepositories = function (socket) {
    var me = this;
    me.connection.query('SELECT r.`repository_id`, r.`name`, r.`other_name` FROM `repositories` r ',
        function(err, rows, fields) {
            if (err) me.databaseError(socket, err);
            else {
                socket.emit('clientGetRepositoriesResponse', rows);
            }
        });
};

ChatClient.prototype.clientInitParams = function (socket, data) {
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
                            //io.emit('new', { will: 'be received by everyone'});
                            socket.emit("clientInitParamsResponse",{ chatUniqId: chatUniqId });
                        }
                    });
                }
            });
        }
    });
};

ChatClient.prototype.clientCheckChatIfAvariable = function (socket, data) {
    var me = this;
    if (!data || !data.hasOwnProperty('chatUniqId') || !data.chatUniqId || data.chatUniqId.length <10){
        socket.emit("clientCheckChatIfAvariableResponse",{isValid: false});
        return ;
    }

    me.connection.query('SELECT * FROM  `chats` WHERE  chat_uniq_id = ?', [data.chatUniqId ],  function(err, res) {
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

                me.connection.query('SELECT * FROM  `online_users` WHERE online_user_id = ?', [ans.online_user_id ],  function(err, res) {
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

ChatClient.prototype.clientMessage = function (socket, data, sendMessageToRoom) {
    var me = this;
    if (!data || !data.hasOwnProperty('chatUniqId') || !data.chatUniqId || data.chatUniqId.length <10){
        socket.emit("clientMessageResponse",{isValid: false, error: 'chatUniqId',data:data});
        return ;
    }

    if (!me.chatRooms || !me.chatRooms.hasOwnProperty(data.chatUniqId)){
        socket.emit("clientMessageResponse",{isValid: false, error: 'chatRooms' });
        return ;
    }

    var chat = me.chatRooms[data.chatUniqId];
    var onlineUser = { chat_id: chat.chatId, online_user_id: socket.onlineUserId, chat_message: data.message };

    me.connection.query('INSERT INTO `chat_messages` SET ? ', onlineUser, function(err, res) {
        if (err) me.databaseError(socket, err);
        else {
            sendMessageToRoom(socket, data.chatUniqId, res.insertId, data.message, data.id );
            socket.emit("clientMessageResponse",res);
        }
    });
};

ChatClient.prototype.clientMessageReceived = function (socket, data, sendMessageReceivedToRoom) {
    var me = this;
    if (!data || !data.hasOwnProperty('msgId') || !data.msgId ){
        return ;
    }

    if (!me.chatRooms || !me.chatRooms.hasOwnProperty(data.chatUniqId)){
        return ;
    }

    sendMessageReceivedToRoom(socket, data.chatUniqId, data.msgId);

};
