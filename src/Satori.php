<?php

namespace Satori;

use WebSocket\Client;

class Satori
{
    private string $username;
    private string $password;
    private string $host;
    private ?Client $ws = null;
    private array $pending = [];
    private array $subscriptions = [];

    public function __construct(string $username, string $password, string $host)
    {
        $this->username = $username;
        $this->password = $password;
        $this->host = $host;
    }

    public function connect(): void
    {
        $this->ws = new Client($this->host);
    }

    public function run(int $port): void{
        $command = "satori -h";
        if($this->username != "" && $this->password != ""){
           $command =  $command . " -a " . $this->username . " " . $this->password;
        }
        if($port != 0){
            $command = $command . " -p " . strval($port);
        }
        shell_exec(command);
    }

    public function update(): void{
        shell_exec("satoridb update");
    }

    private function send(array $payload): mixed
    {
        $id = uniqid('', true);
        $payload = array_merge([
            "id" => $id,
            "username" => $this->username,
            "password" => $this->password,
        ], $payload);

        $this->ws->send(json_encode($payload));

        while (true) {
            $msg = json_decode($this->ws->receive(), true);

            if (isset($msg['type']) && $msg['type'] === 'notification') {
                $key = $msg['key'];
                if (isset($this->subscriptions[$key])) {
                    ($this->subscriptions[$key])($msg['data']);
                }
                continue;
            }

            if ($msg['id'] === $id) {
                return $msg;
            }
        }
    }

    public function set(array $payload): mixed
    {
        return $this->send(['command' => 'SET'] + $payload);
    }

    public function train(): mixed {
        return $this->send([
            'command' => 'TRAIN',
            'type' => 'train'
        ]);
    }
    
    public function ask(array $payload): mixed {
        return $this->send(array_merge(['command' => 'ASK'], $payload));
    }
    
    public function ann(array $payload): mixed {
        return $this->send(array_merge(['command' => 'ANN'], $payload));
    }


    public function get(array $payload): mixed
    {
        return $this->send(['command' => 'GET'] + $payload);
    }

    public function put(array $payload): mixed
    {
        return $this->send(['command' => 'PUT'] + $payload);
    }

    public function delete(array $payload): mixed
    {
        return $this->send(['command' => 'DELETE'] + $payload);
    }

    public function setVertex(array $payload): mixed
    {
        return $this->send(['command' => 'SET_VERTEX'] + $payload);
    }

    public function getVertex(array $payload): mixed
    {
        return $this->send(['command' => 'GET_VERTEX'] + $payload);
    }

    public function deleteVertex(array $payload): mixed
    {
        return $this->send(['command' => 'DELETE_VERTEX'] + $payload);
    }

    public function query(array $payload): mixed
    {
        return $this->send(['command' => 'QUERY'] + $payload);
    }

    public function dfs(array $payload): mixed
    {
        return $this->send(['command' => 'DFS'] + $payload);
    }

    public function encrypt(array $payload): mixed
    {
        return $this->send(['command' => 'ENCRYPT'] + $payload);
    }

    public function decrypt(array $payload): mixed
    {
        return $this->send(['command' => 'DECRYPT'] + $payload);
    }

    public function setRef(array $payload): mixed
    {
        return $this->send(['command' => 'SET_REF'] + $payload);
    }

    public function getRefs(array $payload): mixed
    {
        return $this->send(['command' => 'GET_REFS'] + $payload);
    }

    public function deleteRefs(array $payload): mixed
    {
        return $this->send(['command' => 'DELETE_REFS'] + $payload);
    }

    public function deleteRef(array $payload): mixed
    {
        return $this->send(['command' => 'DELETE_REF'] + $payload);
    }

    public function push(array $payload): mixed
    {
        return $this->send(['command' => 'PUSH'] + $payload);
    }

    public function pop(array $payload): mixed
    {
        return $this->send(['command' => 'POP'] + $payload);
    }

    public function splice(array $payload): mixed
    {
        return $this->send(['command' => 'SPLICE'] + $payload);
    }

    public function remove(array $payload): mixed
    {
        return $this->send(['command' => 'REMOVE'] + $payload);
    }

    public function notify(string $key, callable $callback): void
    {
        $this->subscriptions[$key] = $callback;
        $this->send([
            "command" => "NOTIFY",
            "key" => $key,
        ]);
    }

    public function unnotify(string $key): void
    {
        unset($this->subscriptions[$key]);
        $this->send([
            "command" => "UNNOTIFY",
            "key" => $key,
        ]);
    }
}

namespace Satori;

class Schema
{
    private Satori $satori;
    private string $schemaName;
    private mixed $body;
    private mixed $key;

    public function __construct(Satori $satori, string $schemaName, mixed $body = null, mixed $key = null)
    {
        $this->satori = $satori;
        $this->schemaName = $schemaName;
        $this->body = $body;
        $this->key = $key;
    }

    public function setBody(mixed $body): self
    {
        $this->body = $body;
        return $this;
    }

    public function setKey(mixed $key): self
    {
        $this->key = $key;
        return $this;
    }

    public function set(): mixed
    {
        return $this->satori->set([
            "schema" => $this->schemaName,
            "key" => $this->key,
            "data" => $this->body,
        ]);
    }

    public function delete(): mixed
    {
        return $this->satori->delete([
            "schema" => $this->schemaName,
            "key" => $this->key,
        ]);
    }

    public function encrypt(): mixed
    {
        return $this->satori->encrypt([
            "schema" => $this->schemaName,
            "key" => $this->key,
            "data" => $this->body,
        ]);
    }

    public function decrypt(): mixed
    {
        return $this->satori->decrypt([
            "schema" => $this->schemaName,
            "key" => $this->key,
            "data" => $this->body,
        ]);
    }

    public function setVertex(): mixed
    {
        return $this->satori->setVertex([
            "schema" => $this->schemaName,
            "key" => $this->key,
            "data" => $this->body,
        ]);
    }

    public function getVertex(): mixed
    {
        return $this->satori->getVertex([
            "schema" => $this->schemaName,
            "key" => $this->key,
        ]);
    }

    public function deleteVertex(): mixed
    {
        return $this->satori->deleteVertex([
            "schema" => $this->schemaName,
            "key" => $this->key,
        ]);
    }

    public function dfs(): mixed
    {
        return $this->satori->dfs([
            "schema" => $this->schemaName,
            "key" => $this->key,
            "data" => $this->body,
        ]);
    }

    public function setRef(): mixed
    {
        return $this->satori->setRef([
            "schema" => $this->schemaName,
            "key" => $this->key,
            "data" => $this->body,
        ]);
    }

    public function getRefs(): mixed
    {
        return $this->satori->getRefs([
            "schema" => $this->schemaName,
            "key" => $this->key,
        ]);
    }

    public function deleteRefs(): mixed
    {
        return $this->satori->deleteRefs([
            "schema" => $this->schemaName,
            "key" => $this->key,
        ]);
    }

    public function push(): mixed
    {
        return $this->satori->push([
            "schema" => $this->schemaName,
            "key" => $this->key,
            "data" => $this->body,
        ]);
    }

    public function pop(): mixed
    {
        return $this->satori->pop([
            "schema" => $this->schemaName,
            "key" => $this->key,
        ]);
    }

    public function splice(): mixed
    {
        return $this->satori->splice([
            "schema" => $this->schemaName,
            "key" => $this->key,
            "data" => $this->body,
        ]);
    }

    public function remove(): mixed
    {
        return $this->satori->remove([
            "schema" => $this->schemaName,
            "key" => $this->key,
            "data" => $this->body,
        ]);
    }
}

