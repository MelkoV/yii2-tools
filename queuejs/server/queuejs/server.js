/*
 QueueJs server.
 run: node server.js (screen node server.js)
 */

var net = require('net');
var api = require('./lib/api');

// Create TCP-server on port 8124
var server = net.createServer(function (conn) {

    //console.log('connected');

    // On request
    conn.on('data', function (data) {
        console.log(data + ' from ' + conn.remoteAddress + ' ' + conn.remotePort);

        api.init(conn, data);
    });
    conn.on('close', function () {
        //console.log('client closed connection');
    });
}).listen(8124);

console.log('listening on port 8124');