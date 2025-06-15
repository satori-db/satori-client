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
