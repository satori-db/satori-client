<?php
require 'vendor/autoload.php';

use WebSocket\Client;

class Satori {
    private $username, $password, $host, $client;
    private $pending = [];
    private $subscriptions = [];

    public function __construct($username, $password, $host) {
        $this->username = $username;
        $this->password = $password;
        $this->host = $host;
    }

    public function connect() {
        $this->client = new Client($this->host);
    }

    private function send($command, $payload) {
        $id = uniqid();
        $msg = array_merge([
            'id' => $id,
            'username' => $this->username,
            'password' => $this->password,
            'command' => $command
        ], $payload);

        $this->client->send(json_encode($msg));
        $response = json_decode($this->client->receive(), true);
        return $response;
    }

    public function set($payload) { return $this->send("SET", $payload); }
    public function get($payload) { return $this->send("GET", $payload); }
    public function put($payload) { return $this->send("PUT", $payload); }
    public function delete($payload) { return $this->send("DELETE", $payload); }
    public function setVertex($payload) { return $this->send("SET_VERTEX", $payload); }
    public function getVertex($payload) { return $this->send("GET_VERTEX", $payload); }
    public function deleteVertex($payload) { return $this->send("DELETE_VERTEX", $payload); }
    public function dfs($payload) { return $this->send("DFS", $payload); }
    public function encrypt($payload) { return $this->send("ENCRYPT", $payload); }
    public function decrypt($payload) { return $this->send("DECRYPT", $payload); }
    public function setRef($payload) { return $this->send("SET_REF", $payload); }
    public function getRefs($payload) { return $this->send("GET_REFS", $payload); }
    public function deleteRefs($payload) { return $this->send("DELETE_REFS", $payload); }
    public function deleteRef($payload) { return $this->send("DELETE_REF", $payload); }
    public function query($payload) { return $this->send("QUERY", $payload); }
    public function push($payload) { return $this->send("PUSH", $payload); }
    public function pop($payload) { return $this->send("POP", $payload); }
    public function splice($payload) { return $this->send("SPLICE", $payload); }
    public function remove($payload) { return $this->send("REMOVE", $payload); }
}
