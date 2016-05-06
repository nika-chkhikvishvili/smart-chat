
var app    = require('express')();
var http_server = require('http').createServer(app);
var io     = require('socket.io')(http_server);
var mysql  = require('mysql');
http_server.listen(3000);
var connection = mysql.createConnection({
    host     : 'localhost',
    user     : 'smartchat',
    password : 'smartchat',
    database : 'smartchat',
});
connection.connect();







var chatRooms = {};
var readyForPlay = require('fifo')();
var onlinePersons = {};

// for debug
global.chatRooms = chatRooms;
global.onlinePersons = onlinePersons;
//
var client  = require('./client.js')({mysql:mysql, connection:connection, chatRooms:chatRooms, readyForPlay: readyForPlay,
    io:io});
var server  = require('./server.js')({mysql:mysql, connection:connection, chatRooms:chatRooms, readyForPlay: readyForPlay,
    io:io, onlinePersons : onlinePersons });

// initialite tokens
connection.query('SELECT `t`.`token`, `t`.`person_id`, `t`.`add_date`, `t`.`expire`, `t`.`expired` ' +
    'FROM `mydb`.`person_tokens` t', function(err, rows, fields) {
    if(err) process.exit(1);
    rows.forEach(function (row){
        onlinePersons[row.token] = {
            userId : row.person_id,
            isAdmin : false
        }
    });
});

connection.query('SELECT `chat_id`, `online_user_id`, `repo_id`, `chat_uniq_id` FROM `chats` ' +
    ' where `chat_status_id` = 0', function(err, rows, fields) {
    if(err) process.exit(1);
    rows.forEach(function (row){
        chatRooms[row.chat_uniq_id] = {
            chatId : row.chat_id,
            users : []
        }
    });
});

var sendMessageToRoom = function (socket, chatUniqId, msgId, msg, ran){
    var chat = chatRooms[chatUniqId];
    var users = chat.users;
    users.forEach(function(socketId){
        if(socketId!=socket.id){
            socket.broadcast.to(socketId).emit('message',{message:msg,msgId : msgId, sender:'system', ran:ran , chatUniqId : chatUniqId});
        }
    });
};

var sendMessageReceivedToRoom = function (socket, chatUniqId, msgId ){
    var chat = chatRooms[chatUniqId];
    var users = chat.users;
    users.forEach(function(socketId){
        if(socketId!=socket.id){
            socket.broadcast.to(socketId).emit('messageReceived',{ msgId : msgId});
        }
    });
};

//6 17:08

io.on('connection', function (socket) {

    socket.on('clientGetRepositories', function ()     {client.clientGetRepositories(socket);} );
    socket.on('clientInitParams',      function (data) {client.clientInitParams     (socket,data);} );
    socket.on('clientCheckChatIfAvariable',function (data) {client.clientCheckChatIfAvariable (socket,data);} );
    socket.on('clientMessage',         function (data) {client.clientMessage        (socket,data, sendMessageToRoom);} );
    socket.on('clientMessageReceived', function (data) {client.clientMessageReceived(socket,data, sendMessageReceivedToRoom);} );

    socket.on('test', function(){ console.dir('test'); socket.emit('testResponse'); });


    socket.on('auth',            function (data) {server.auth         (socket, data);} );
    socket.on('checkToken',      function (data) {server.checkToken   (socket, data);} );
    socket.on('sendMessage',     function (data) {server.sendMessage  (socket, data, sendMessageToRoom);} );
    socket.on('messageReceived', function (data) {server.messageReceived(socket,data, sendMessageReceivedToRoom);} );




/*
    socket.on('message', function (data) {
        if (data.id % 3 >0){
            for(i=0; i<= chatRooms[socket.roomId].users.length; ++i ){
                var usr = chatRooms[socket.roomId].users[i];
                if (usr!=socket.id)
                    socket.broadcast.to(usr).emit('message',data);
            }
            socket.emit('message_received', {id:data.id});
        }
    });

    socket.on('operator_message', function (data) {
        if (! data) return;

        socket.emit('message_received', {id:data.id});
        if (! data.roomId) return;
        if (! data.message) return;
        if (! chatRooms[data.roomId]) return;
        if (! chatRooms[data.roomId].users) return;

        for(i=0; i< chatRooms[ data.roomId].users.length; ++i ){
            socket.broadcast.to(chatRooms[ data.roomId].users[i]).emit('message',{message:data.message, sender:'operator'});
        }
    });


    socket.on('disconnect', function () {
        //if (socket.otherId) socket.broadcast.to(socket.otherId).emit('stopGame');
        delete chatRooms[socket.roomId];
    });


    socket.on('log', function () {
        console.log(chatRooms);
    });



    */
});


console.log("Started:"+ (new Date().toISOString()));
