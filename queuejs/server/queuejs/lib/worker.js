exports.queue = function () {
    var list = Object.keys(workers);
    list.forEach(function (value) {
        queue(value);
    });
}

exports.addWorker = function (json) {
    if (!checkWorker(json.name)) {
        workers[json.name] = {command: json.command, status: WORKER_STATUS_READY, queue: []};
        var now = new Date().toLocaleString();
        console.log(now + ' Add worker ' + json.name);
        return {code: 0, message: ''};
    } else {
        return {code: WORKER_ERROR_WORKER_ALREADY_EXISTS, message: 'Worker already exists'};
    }

}

exports.getStat = function (json) {
    var result = {};
    var list = Object.keys(workers);
    list.forEach(function (value) {
        result[value] = {
            command: workers[value].command,
            status: workers[value].status,
            queue: workers[value].queue.length
        };
    });
    return {code: 0, message: result};
}

exports.addJob = function (json) {
    if (checkWorker(json.name)) {
        workers[json.name].queue.push(json.params);
        var now = new Date().toLocaleString();
        console.log(now + ' Add job ' + json.name);
        return {code: 0, message: '', refresh: true};
    } else {
        return {code: WORKER_ERROR_WORKER_NOT_FOUND, message: 'Worker not found'};
    }
}

exports.deleteWorker = function (json) {
    if (checkWorker(json.name)) {
        delete workers[json.name];
        return {code: 0, message: ''};
    } else {
        return {code: WORKER_ERROR_WORKER_NOT_FOUND, message: 'Worker not found'};
    }
}

exports.clearWorkerJob = function (json) {
    if (checkWorker(json.name)) {
        workers[json.name].queue = [];
        workers[json.name].status = WORKER_STATUS_READY;
        return {code: 0, message: ''};
    } else {
        return {code: WORKER_ERROR_WORKER_NOT_FOUND, message: 'Worker not found'};
    }
}

const WORKER_ERROR_WORKER_ALREADY_EXISTS = 11;
const WORKER_ERROR_WORKER_NOT_FOUND = 12;

const WORKER_STATUS_READY = 0;
const WORKER_STATUS_WORK = 1;
const WORKER_STATUS_BLOCK = 2;
const WORKER_STATUS_ERROR = 3;

var spawn = require('child_process').spawn;

var workers = {};

var checkWorker = function (worker) {
    if (typeof workers[worker] == 'undefined') {
        return false;
    }
    return true;
}

var queue = function (worker) {
    if (checkWorker(worker)) {
        if (workers[worker].status == WORKER_STATUS_READY) {
            workers[worker].status = WORKER_STATUS_BLOCK;
            if (workers[worker].queue.length > 0) {
                var params = workers[worker].queue.shift();
                push(workers[worker].command, worker, params);
            } else {
                if (workers[worker].status == WORKER_STATUS_BLOCK) {
                    workers[worker].status = WORKER_STATUS_READY;
                }
            }
        }
    }
}

var push = function (command, worker, params) {
    workers[worker].status = WORKER_STATUS_WORK;
    var now = new Date().toLocaleString();
    console.log(now + ' Start ' + worker);
    command = command.replace('{name}', worker).replace('{params}', params);
    console.log(command);
    var com = command.split(' ');
    var i = 0;
    for (i = 0; i < com.length; i++) {
        com[i] = com[i].replace('{+}', ' ');
    }

    var exec = spawn(com.shift(), com);

    exec.stdout.on('data', function (data) {
        console.log('stdout ' + data);
    });

    exec.stderr.on('data', function (data) {
        console.log('stderr ' + data);
    });

    exec.on('exit', function (code) {
        console.log('exit with code ' + code);
        workers[worker].status = WORKER_STATUS_READY;
        queue(worker);
    });
}