let app = require('express')();
let server = require('http').Server(app);
let io = require('socket.io')(server);
let Redis = require('ioredis');
let redis = new Redis();

redis.subscribe('category-event');

redis.on('message', (subscribed, channel, message) => {
    console.log('Message Recv: ' + message);
    message = JSON.parse(message);
    io.emit(channel + ':' + message.event, message.data);
});

server.listen(3000, () => {
    console.log('listening on port:3000');
});