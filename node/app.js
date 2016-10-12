
var app    = require('express')();
var http_server = require('http').createServer(app);
var io     = require('socket.io')(http_server);
var mysql  = require('mysql');
global.fifo = require('fifo');

http_server.listen(3000);
var connection = mysql.createConnection({
    host     : 'localhost',
    user     : 'smartchat',
    password : 'smartchat',
    database : 'smartchat'
});
connection.connect();

//var redis = require("redis");
//var redisClient = redis.createClient();

// if you'd like to select database 3, instead of 0 (default), call
// client.select(3, function() { /* ... */ });

//redisClient.on("error", function (err) {
//    console.log("Error " + err);
//});

//redisClient.set("string key", "string val", redis.print);
//redisClient.hset("hash key", "hashtest 1", "some value", redis.print);
//redisClient.hset(["hash key", "hashtest 2", "some other value"], redis.print);
//redisClient.hkeys("hash key", function (err, replies) {
//    console.log(replies.length + " replies:");
//    replies.forEach(function (reply, i) {
//        console.log("    " + i + ": " + reply);
//    });
//    //redisClient.quit();
//});


var chatRooms = {};
var waitingClients = [];
var onlinePersons = {};


//
var client  = require('./client.js')({mysql:mysql, connection:connection, chatRooms:chatRooms, waitingClients: waitingClients,
    io:io});
var server  = require('./server.js')({mysql:mysql, connection:connection, chatRooms:chatRooms, waitingClients: waitingClients,
    io:io, onlinePersons : onlinePersons });


// for debug
global.chatRooms      = chatRooms;
global.onlinePersons  = onlinePersons;
global.waitingClients = waitingClients;
global.client         = client;
global.io             = io;

// initialite tokens
connection.query('SELECT `t`.`token`, `t`.`person_id`, `t`.`add_date`, `t`.`expire`, `t`.`expired` ' +
    'FROM `person_tokens` t', function(err, rows, fields) {
    if(err) {
        console.log(err);
        process.exit(1);
    }
    rows.forEach(function (row){
        onlinePersons[row.token] = {
            userId : row.person_id,
            isAdmin : false
        }
    });
});

connection.query('SELECT `chat_id`, `online_user_id`, `repo_id`, `chat_uniq_id` FROM `chats` ' +
    ' where `chat_status_id` = 1 or `chat_status_id` = 0', function(err, rows, fields) {
    if(err) {
        console.log(err);
        process.exit(1);
    }
    rows.forEach(function (row){
        chatRooms[row.chat_uniq_id] = {
            chatId : row.chat_id,
            users : []
        }
    });
});

connection.query('SELECT `c`.`chat_id`,    `c`.`online_user_id`,    `c`.`service_id`,    `c`.`chat_uniq_id`,    `u`.`first_name`,    `u`.`last_name` ' +
    'FROM `smartchat`.`chats` c, smartchat.online_users u where c.online_user_id = u.online_user_id and c.`chat_status_id` = 0 order by  c.`add_date` asc ',
    function(err, rows, fields) {
        if (err) {
            console.log(err);
            process.exit(1);
        }
        rows.forEach(function (row) {
            if (!waitingClients[row.service_id] || waitingClients[row.service_id]==null){
                waitingClients[row.service_id]=fifo();
            }
            waitingClients[row.service_id].push({
                chat_id: row.chat_id,
                online_user_id: row.online_user_id,
                service_id: row.service_id,
                chat_uniq_id: row.chat_uniq_id,
                first_name: row.first_name,
                last_name: row.last_name
            });
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

    socket.on('clientGetServices',         function ()     {client.clientGetServices           (socket);} );
    socket.on('clientInitParams',          function (data) {client.clientInitParams            (socket,data);} );
    socket.on('clientCheckChatIfAvariable',function (data) {client.clientCheckChatIfAvariable  (socket,data);} );
    socket.on('clientMessage',             function (data) {client.clientMessage               (socket,data, sendMessageToRoom);} );
    socket.on('clientMessageReceived',     function (data) {client.clientMessageReceived       (socket,data, sendMessageReceivedToRoom);} );

    socket.on('test', function(){ console.dir('test'); socket.emit('testResponse'); });

    socket.on('checkToken',           function (data) {server.checkToken            (socket, data);} );
    socket.on('getWaitingList',       function ()     {server.getWaitingList        (socket);} );
    socket.on('getActiveChats',       function ()     {server.getActiveChats        (socket);} );
    socket.on('getNextWaitingClient', function (data) {server.getNextWaitingClient  (socket, data);} );
    socket.on('getAllChatMessages',   function (data) {server.getAllChatMessages    (socket, data);} );
    socket.on('sendMessage',          function (data) {server.sendMessage           (socket, data, sendMessageToRoom);} );
    socket.on('operatorIsWorking',    function (data) {server.operatorIsWorking     (socket, data, sendMessageToRoom);} );
    socket.on('banPerson',            function (data) {server.banPerson             (socket, data);} );


    socket.on('disconnect', function () {
        io.emit('userDisconnect', {
            id: socket.id
        });

    });

});


console.log("Started:"+ (new Date().toISOString()));
