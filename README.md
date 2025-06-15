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
use Satori\Satori;

$client = new Satori('username', 'password', 'ws://localhost:8000');
$client->connect();
```

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

To stop receiving notifications:

```php
$client->unnotify('user:123');
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

## 📦 Array and Reference Manipulation Methods

Below are the available methods to manipulate arrays and references in the Satori database using the PHP client:

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

### 🔹 setRef
Sets a reference to another object.
```php
$client->setRef(['key' => 'user:123', 'ref' => 'profile:123']);
```
- **key**: Source object key.
- **ref**: Reference object key.

### 🔹 getRefs
Retrieves all references for an object.
```php
$refs = $client->getRefs(['key' => 'user:123']);
```
- **key**: Object key.

### 🔹 deleteRef
Deletes a specific reference from an object.
```php
$client->deleteRef(['key' => 'user:123', 'ref' => 'profile:123']);
```
- **key**: Source object key.
- **ref**: Reference object key to delete.

---

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

## 💬 Questions or Suggestions?

Feel free to open an issue or contribute!
With ❤️ from the Satori team.

---
