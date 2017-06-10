<?php

namespace melkov\queuejs;

use melkov\queuejs\lib\QueueJsException;
use yii\base\Component;

class QueueJs extends Component
{
    public $host = 'localhost';
    public $port = '8124';
    public $key;
    public $prefix;

    public function init()
    {
        if ($this->prefix) {
            $this->prefix .= '_';
        }
        parent::init();
    }

    public function getConfig()
    {
        $array = array(
            'host' => $this->host,
            'port' => $this->port,
            'key' => $this->key,
            'prefix' => $this->prefix,
        );
        return $array;
    }

    public function addJob($name, $params = array())
    {
        return $this->query('addJob', array('name' => $this->prefix . $name, 'params' => urlencode(json_encode($params))));
    }

    public function addWorker($name, $command)
    {
        return $this->query('addWorker', array('name' => $this->prefix . $name, 'command' => $command));
    }

    public function clearWorkerJob($name)
    {
        return $this->query('clearWorkerJob', array('name' => $this->prefix . $name));
    }

    public function deleteWorker($name)
    {
        return $this->query('deleteWorker', array('name' => $this->prefix . $name));
    }

    public function getStat()
    {
        $response = $this->query('getStat');
        return $response['message'];
    }

    private function query($method, $params = array())
    {
        $params['action'] = $method;
        $response = $this->request($params);

        if (!$response) {
            throw new QueueJsException(\Yii::t('queueJs', 'Server not available'));
        }

        return $response;
    }

    public function getCount($name)
    {
        $result = $this->getStat();
        foreach ($result as $qname => $value) {
            if (strpos($qname, $name) !== false) {
                return $value["status"];
            }
        }
        return -1;
    }

    private function request($params)
    {
        $params['signature'] = md5($this->arrayToString($params) . $this->key);
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if (!$socket) {
            return false;
        }
        if (!@socket_connect($socket, $this->host, $this->port)) {
            return false;
        }
        socket_write($socket, json_encode($params));

        $result = '';

        while ($read = socket_read($socket, 1024)) {
            $result .= $read;
        }
        socket_close($socket);

        return json_decode($result, true);
    }

    private function arrayToString($array)
    {
        ksort($array);

        $return = '';

        if (is_array($array)) {
            foreach ($array as $val) {
                if (is_array($val)) {
                    $return .= $this->arrayToString($val);
                } else {
                    $return .= $val;
                }
            }
        } else {
            $return .= $array;
        }

        return $return;
    }
} 