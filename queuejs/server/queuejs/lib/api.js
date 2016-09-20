exports.init = function (conn, data) {
    try {
        var json = JSON.parse(data);
    } catch (e) {
        end(conn, API_ERROR_NOT_VALID_REQUEST, 'Request not valid');
        return false;
    }

    if (typeof json.signature == 'undefined') {
        end(conn, API_ERROR_NOT_VALID_SIGNATURE, 'Signature not valid');
        return false;
    }

    var signature = json.signature;

    delete json.signature;

    if (getSignature(json) != signature) {
        end(conn, API_ERROR_NOT_VALID_SIGNATURE, 'Signature not valid');
        return false;
    }

    if (typeof json.action == 'undefined') {
        end(conn, API_ERROR_NOT_VALID_ACTION, 'Action not valid');
        return false;
    }

    var action = methods[json.action];

    if (typeof action == 'undefined') {
        end(conn, API_ERROR_ACTION_NOT_FOUND, 'Action not found');
        return false;
    }

    var error = 0;

    action.forEach(function (value) {
        if (typeof json[value] == 'undefined') {
            error++;
        }
    });

    if (error > 0) {
        end(conn, API_ERROR_ACTION_PARAMS_NOT_VALID, 'Action params not valid');
        return false;
    }

    var result = worker[json.action](json);

    end(conn, result.code, result.message);

    if (typeof result.refresh != 'undefined' && result.refresh == true) {
        worker.queue();
    }

    return true;
}

/*exports.queue = function () {
 worker.queue();
 }*/

const API_KEY = 'ghjDCvcv657vcvDOnmv740';
const API_ERROR_NOT_VALID_REQUEST = 1;
const API_ERROR_NOT_VALID_SIGNATURE = 2;
const API_ERROR_NOT_VALID_ACTION = 3;
const API_ERROR_ACTION_NOT_FOUND = 4;
const API_ERROR_ACTION_PARAMS_NOT_VALID = 5;

var methods = {
    addWorker: ['name', 'command'],
    addJob: ['name', 'params'],
    getStat: [],
    deleteWorker: ['name'],
    clearWorkerJob: ['name']
};


var worker = require('./worker');

var crypto = require('crypto');

/**
 * Return signature for request
 * @param json
 * @returns string
 */
var getSignature = function (json) {
    return crypto.createHash('md5').update(jsonValues(json) + API_KEY).digest('hex');
}

var jsonValues = function (obj) {
    var keys = Object.keys(obj).sort(),
        result = '';
    keys.forEach(function (value) {
        result += obj[value];
    });
    return result;
}

var end = function (conn, code, message) {
    conn.write(JSON.stringify({code: code, message: message}));
    conn.end();
}
