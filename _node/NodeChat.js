//*******************************************************
// mclowd notifications and chat server
// Developper: Dan Ursoviciu
// 
// TO DO: 
//      1. Separate chat from notifications and refactor
//      2. garbage collection
//      3. persist chat logs
//

//setup websocket
var express = require('express');
var app = express();
var server = require('http').createServer(app);
var io = require('socket.io').listen(server);

server.listen(7070);



// routing
app.get('/', function (req, res) {
    res.sendfile(__dirname + '/index.html');
});

// usernames which are currently connected to the chat
var usernames = {};
var chatlog = {};
var users = {};

// rooms which are currently available in chat
io.sockets.on('connection', function (socket) {

    // when the client emits 'adduser', this listens and executes
    socket.on('adduser', function(username, userid, room, target, targetname){
        // store the username in the socket session for this client
        socket.username = username;
        // store the room name in the socket session for this client
        socket.room = room;
        if (usernames[username] != username) {
            // add the client's username to the global list
            usernames[username] = username;
            //update users chat data
            users[username] = {
                id: 0,
                name: username,
                rooms:{}, 
                log:{},
                targets:{}
            };
            chatlog[socket.room] = "Session start.<br/>";
        } else {
            socket.emit('loadlog', chatlog[room]);
        }
        
        users[username].rooms[room] = room;
        
        //check if target is set
        if (target != null) { users[username].targets[target] = target; }
        
        //check if this is a chatroom then add us as chat target to our target
        if (room.substr(0,4)=="room") { 
            users[targetname].targets[userid]=userid; 
        }
        
        // send client to specified room
        socket.join(room);
        // echo to debug room that a person has connected
        socket.broadcast.to('admin').emit('updatechat', 'SERVER', username + ' has connected to room: '+room);
    });

    // when the client emits 'sendchat', this listens and executes
    socket.on('sendchat', function (data) {
        // we tell the client to execute 'updatechat' with 2 parameters
        io.sockets.in(socket.room).emit('updatechat', socket.username, data);
        
        //append message to rooms log
        currentChat = chatlog[socket.room];
        chatlog[socket.room] = currentChat+socket.username+": "+data+"</br>";
    });

    socket.on('addChatNotification', function(room){
        socket.broadcast.to(room).emit('addChatNotification');
    });

    // when the user disconnects.. perform this
    socket.on('dis', function(target){
        // remove the username from global usernames list
        //delete usernames[socket.username];
        // update list of users in chat, client-side
        //io.sockets.emit('updateusers', usernames);
        // echo globally that this client has left
        socket.emit('debug', users[socket.username]);
        
        delete users[socket.username].targets[target];
        
        socket.emit('debug', users[socket.username]);
        
        socket.broadcast.to('admin').emit('updatechat', 'SERVER', socket.username + ' has disconnected');
        socket.leave(socket.room);
    });
    
    //get open chat channels and other data for user
    socket.on('getchatdata', function() {
        socket.emit('chatdata', users[socket.username]);
    });
    
    
    
});

// setup network socket to receive commands from backend:
var DNode = require('dnode');

var server4php = DNode( function (remote, conn) {
    this.sendmessage = function (s) 
    { 
        io.sockets.emit('updatechat', 'UMUP', 'user has connected with message: '+s);
    }
    this.pushNotificationsCount = function (room, nr)
    {
        io.sockets.in(room).emit('updatenotifications', nr);
    }
    this.pushChatRequest = function (room, nr)
    {
        io.sockets.in(room).emit('updatechatrequests', nr);
    }
});

server4php.listen(6060);