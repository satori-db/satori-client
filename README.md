# 📚 Satori PHP SDK

This library allows you to easily and efficiently interact with the Satori database via WebSockets, supporting CRUD operations, real-time notifications, advanced queries, and graph-like relations.

---

## ✨ Main Features

- **Ultra-fast CRUD operations** ⚡
- **Advanced queries** using `field_array` 🔍
- **Real-time notifications** 📢
- **Graph-like relations** (vertices and references) 🕸️
- **Data encryption and decryption** 🔐

---

## 🚀 Installation

Install the required dependencies (for example, using Composer):

```bash
composer require textalk/websocket
```

Copy the `Satori.php` file to your project and make sure Composer's autoload is configured.

---

## 🏁 Basic Usage

```php
require_once 'vendor/autoload.php';
require_once 'Satori.php';
use Satori\Satori;

$client = new Satori('ws://localhost:8000', 'username', 'password');
$client->connect();
```

If you are inserting a vector you must specify data to a [f32] and type to vector


---


## 🗃️ CRUD Operations

### Create Data

```php
$client->set([
    'key' => 'user:123',
    'data' => ['name' => 'John', 'email' => 'john@example.com'],
    'type' => 'user'
]);
```

### Read Data

```php
$user = $client->get(['key' => 'user:123']);
```

### Modify a Field

```php
$client->put([
    'key' => 'user:123',
    'replace_field' => 'name',
    'replace_value' => 'Peter'
]);
```

### Delete Data

```php
$client->delete(['key' => 'user:123']);
```

---

## 🧩 Advanced Queries with `field_array` 🔍

You can perform operations on multiple objects that meet certain conditions using the `field_array` field:

```php
$results = $client->get([
    'field_array' => [
        ['field' => 'email', 'value' => 'john@example.com']
    ]
]);
```

- **`field_array`** is an array of conditions `['field' => ..., 'value' => ...]`.
- You can combine it with `'one' => true` to get only the first matching result.

---

## 🔔 Real-time Notifications

Receive automatic updates when an object changes:

```php
$client->notify('user:123', function($data) {
    echo "User updated!\n";
    print_r($data);
});
```

---

## 🕸️ Relations and Graphs

You can create relationships between objects (vertices):

```php
$client->setVertex([
    'key' => 'user:123',
    'vertex' => 'friend:456',
    'relation' => 'friend',
    'encryption_key' => 'secret'
]);
```

And traverse the graph with DFS:

```php
$client->dfs(['node' => 'user:123', 'encryption_key' => 'secret']);
```

Get all neighbors of an object:

```php
$client->getVertex([
    'key' => 'user:123',
    'encryption_key' => 'secret',
    'relation' => 'friends'
]);
```

Remove a specific neighbor:

```php
$client->deleteVertex([
    'key' => 'user:123',
    'vertex' => 'user:512',
    'encryption_key' => 'secret'
]);
```

---

## 🔐 Encryption and Security

Easily encrypt and decrypt data:

```php
$client->encrypt(['key' => 'user:123', 'encryption_key' => 'secret']);
$client->decrypt(['key' => 'user:123', 'encryption_key' => 'secret']);
```

---

## 📦 Array Manipulation Methods

Below are the available methods to manipulate arrays in the Satori database using the PHP client:

### 🔹 push
Adds a value to an existing array in an object.
```php
$client->push(['key' => 'user:123', 'array' => 'friends', 'value' => 'user:456']);
```
- **key**: Object key.
- **array**: Name of the array.
- **value**: Value to add.

### 🔹 pop
Removes the last element from an array in an object.
```php
$client->pop(['key' => 'user:123', 'array' => 'friends']);
```
- **key**: Object key.
- **array**: Name of the array.

### 🔹 splice
Modifies an array in an object (for example, to cut or replace elements).
```php
$client->splice(['key' => 'user:123', 'array' => 'friends']);
```
- **key**: Object key.
- **array**: Name of the array.

### 🔹 remove
Removes a specific value from an array in an object.
```php
$client->remove(['key' => 'user:123', 'array' => 'friends', 'value' => 'user:456']);
```
- **key**: Object key.
- **array**: Name of the array.
- **value**: Value to remove.


---

## 🤖 AI Methods
Satori has AI features integrated that boost developers productivity. 

### 🔹 set_middleware
Make the LLM analyze incoming querys and decide if it must reject them, accept them or modify them.
```php
$client->set_middleware([
    "operation" => "SET",
    "middleware" => "Only accept requests that have the amount field specified, and convert its value to dollars"
]);
```


### 🔹 ann
Perform an Aproximate Nearest Neighbors search
```php
$client->ann(['key' => 'user:123', 'top_k' => '5']);
```
- **key**: Source object key.
- **vector**: Vector of f32 instead of key
- **top_k**: Number of nearest neighbors to return

### 🔹 query
Make querys in natural language
```php
$client->query(['query' => 'Insert the value 5 into the grades array of user:123', 'backend' => 'openai:gpt-4o-mini']);
```
- **query**: Your query in natural language.
- **ref**: The LLM backend. Must be `openai:model-name` or `ollama:model-name`, if not specified `openai:gpt-4o-mini` will be used as default. If you're using OpenAI as your backend you must specify the `OPENAI_API_KEY` env variable.

### 🔹 ask
Ask question about your data in natural language
```php
$client->query(['question' => 'How many user over 25 years old do we have. Just return the number.', 'backend' => 'openai:gpt-4o-mini']);
```
- **question**: Your question in natural language.
- **ref**: The LLM backend. Must be `openai:model-name` or `ollama:model-name`, if not specified `openai:gpt-4o-mini` will be used as default. If you're using OpenAI as your backend you must specify the `OPENAI_API_KEY` env variable.

## Analytics

### 🔹 get_operations

Returns all operations executed on the database.

### 🔹 get_access_frequency

Returns the number of times an object has been queried or accessed.
```php
$client->get_access_frequency(['key' : 'jhon'])
```


# 🐘 Schema Class (Data Model)

You can use the `Schema` class to model your data in an object-oriented way:

```php
use Satori\Satori;
use Satori\Schema;

$satori = new Satori("username", "password", "ws://localhost:1234");
$satori->connect();

$user = new Schema($satori, "user", ["name" => "Anna"], "my_key");
$user->set();
```


It includes useful methods such as:

- `set`, `delete`, `encrypt`, `decrypt`, `set_vertex`, `get_vertex`, `delete_vertex`, `dfs`

- Reference methods: `set_ref`, `get_refs`, `delete_refs`

- Array methods: `push`, `pop`, `splice`, `remove`


## 📝 Complete Example

```php
use Satori\Satori;

$client = new Satori('username', 'password', 'ws://localhost:8000');
$client->connect();

$client->set([
    'key' => 'user:1',
    'data' => ['name' => 'Carlos', 'age' => 30],
    'type' => 'user'
]);

$client->notify('user:1', function($data) {
    echo "Real-time update: ";
    print_r($data);
});
```

---

## 🧠 Key Concepts

- **key**: Unique identifier of the object.
- **type**: Object type (e.g., 'user').
- **field_array**: Advanced filters for bulk operations.
- **notifications**: Subscription to real-time changes.
- **vertices**: Graph-like relationships between objects.

---

## Responses
All responses obbey the following pattern:

```ts
{
  data: any //the requested data if any
  message: string //status message
  type: string //SUCCESS || ERROR
}
```

AI responses obbey a different patern:
## ask
```ts
{
  response: string //response to the question
}
```

## query
```ts
{
  result: string //response from the operation made in the db
  status: string //status
}
```

## ann
```ts
{
  results: array //response from the operation made in the db
}
```
## 💬 Questions or Suggestions?

Feel free to open an issue or contribute!
With ❤️ from the Satori team.

---
