# 📚 Satori PHP SDK

A PHP client library for interacting with the Satori database via WebSockets. This SDK provides comprehensive support for CRUD operations, graph operations, AI-powered features, encryption, and mindspace operations.

---

## 📋 Table of Contents

- [✨ Features](#-features)
- [🚀 Installation](#-installation)
- [🏁 Quick Start](#-quick-start)
- [🔧 Configuration](#-configuration)
- [📦 Basic Operations](#-basic-operations)
  - [SET - Create Data](#set---create-data)
  - [GET - Read Data](#get---read-data)
  - [PUT - Update Data](#put---update-data)
  - [DELETE - Delete Data](#delete---delete-data)
- [🔢 Array Operations](#-array-operations)
  - [PUSH - Add to Array](#push---add-to-array)
  - [POP - Remove Last Element](#pop---remove-last-element)
  - [SPLICE - Remove First Element](#splice---remove-first-element)
  - [REMOVE - Remove Specific Value](#remove---remove-specific-value)
- [🔐 Encryption Operations](#-encryption-operations)
  - [ENCRYPT - Encrypt Data](#encrypt---encrypt-data)
  - [DECRYPT - Decrypt Data](#decrypt---decrypt-data)
- [🕸️ Graph Operations](#-graph-operations)
  - [SET_VERTEX - Add Relationship](#set_vertex---add-relationship)
  - [GET_VERTEX - Get Relationships](#get_vertex---get-relationships)
  - [DELETE_VERTEX - Remove Relationship](#delete_vertex---remove-relationship)
  - [DFS - Depth-First Search](#dfs---depth-first-search)
  - [GRAPH_BFS - Breadth-First Search](#graph_bfs---breadth-first-search)
  - [GRAPH_DFS - Graph DFS](#graph_dfs---graph-dfs)
  - [GRAPH_SHORTEST_PATH - Shortest Path](#graph_shortest_path---shortest-path)
  - [GRAPH_CONNECTED_COMPONENTS - Connected Components](#graph_connected_components---connected-components)
  - [GRAPH_SCC - Strongly Connected Components](#graph_scc---strongly-connected-components)
  - [GRAPH_DEGREE_CENTRALITY - Degree Centrality](#graph_degree_centrality---degree-centrality)
  - [GRAPH_CLOSENESS_CENTRALITY - Closeness Centrality](#graph_closeness_centrality---closeness-centrality)
  - [GRAPH_CENTROID - Find Centroid](#graph_centroid---find-centroid)
- [🤖 AI Operations](#-ai-operations)
  - [ASK - Ask Questions](#ask---ask-questions)
  - [ANN / GET_SIMILAR - Similarity Search](#ann--get_similar---similarity-search)
- [📊 Analytics Operations](#-analytics-operations)
  - [GET_OPERATIONS - Operation History](#get_operations---operation-history)
  - [GET_ACCESS_FREQUENCY - Access Count](#get_access_frequency---access-count)
- [🧠 Mindspace Operations](#-mindspace-operations)
  - [SET_MINDSPACE - Create Mindspace](#set_mindspace---create-mindspace)
  - [DELETE_MINDSPACE - Delete Mindspace](#delete_mindspace---delete-mindspace)
  - [CHAT_MINDSPACE - Chat with Mindspace](#chat_mindspace---chat-with-mindspace)
  - [LECTURE_MINDSPACE - Import Corpus](#lecture_mindspace---import-corpus)
- [🔔 Real-time Notifications](#-real-time-notifications)
- [🐘 Schema Class](#-schema-class)
- [📝 Response Format](#-response-format)
- [💡 Complete Example](#-complete-example)

---

## ✨ Features

- **CRUD Operations** - Create, read, update, and delete objects
- **Advanced Queries** - Field-based filtering with `field_array`
- **Graph Operations** - Full graph traversal and analysis
- **AI-Powered Features** - Natural language queries and semantic search
- **Encryption** - AES encryption for sensitive data
- **Mindspaces** - AI-powered conversational contexts
- **Real-time Notifications** - Subscribe to object changes

---

## 🚀 Installation

Install the required dependencies using Composer:

```bash
composer require textalk/websocket
```

Include the autoloader and Satori class in your PHP project:

```php
require_once 'vendor/autoload.php';
require_once 'src/Satori.php';
use Satori\Satori;
```

---

## 🏁 Quick Start

```php
require_once 'vendor/autoload.php';
require_once 'src/Satori.php';
use Satori\Satori;

// Connect to Satori server
$client = new Satori('ws://localhost:8000', 'username', 'password');
$client->connect();

// Create an object
$client->set([
    'key' => 'user:john',
    'data' => [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'age' => 30
    ],
    'type' => 'user'
]);

// Retrieve the object
$user = $client->get(['key' => 'user:john']);
```

---

## 🔧 Configuration

### Constructor Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `host` | string | Yes | WebSocket connection URL (e.g., `ws://localhost:8000`) |
| `username` | string | No | Authentication username |
| `password` | string | No | Authentication password |

---

## 📦 Basic Operations

### SET - Create Data

Creates a new object in the database. If no key is provided, a UUID is automatically generated.

```php
$client->set([
    'key' => 'user:john',
    'data' => [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'age' => 30
    ],
    'type' => 'user',
    'expires' => false,
    'expiration_time' => null
]);
```

**Parameters:**

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `key` | string | No | Object key (auto-generated if omitted) |
| `data` | array | No | Object data content (default: `{}`) |
| `type` | string | No | Object class/type (default: `"normal"`) |
| `expires` | boolean | No | Whether object expires (default: `false`) |
| `expiration_time` | integer | No | Expiration timestamp in milliseconds |

**Response:**
```json
{
    "type": "SUCCESS",
    "message": "OK",
    "id": "request-id",
    "key": "user:john"
}
```

---

### GET - Read Data

Retrieves one or more objects from the database. Use `"*"` for key to return all objects.

```php
// Get single object
$user = $client->get(['key' => 'user:john']);

// Get all objects
$allObjects = $client->get(['key' => '*']);

// Query with filters
$results = $client->get([
    'field_array' => [
        ['field' => 'age', 'value' => 30]
    ],
    'one' => true,
    'max' => 10
]);
```

**Parameters:**

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `key` | string | No* | Object key to retrieve. Use `"*"` for all objects |
| `field_array` | array | No* | Query conditions for field-based search |
| `one` | boolean | No | Return only first match (default: `false`) |
| `max` | integer | No | Maximum results to return |
| `encryption_key` | string | No | Key to decrypt encrypted objects |

*Either `key` or `field_array` must be provided, but not both.

---

### PUT - Update Data

Updates one or more fields of an existing object.

```php
$client->put([
    'key' => 'user:john',
    'replace_field' => 'age',
    'replace_value' => 31
]);

// Batch update with field_array
$client->put([
    'field_array' => [
        ['field' => 'type', 'value' => 'premium']
    ],
    'replace_field' => 'status',
    'replace_value' => 'active'
]);
```

**Parameters:**

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `key` | string | No* | Object key to update |
| `field_array` | array | No* | Query to select objects for batch update |
| `replace_field` | string | Yes | Field name to update |
| `replace_value` | any | Yes | New value for the field |
| `encryption_key` | string | No | Key for encrypted objects |

*Either `key` or `field_array` must be provided.

---

### DELETE - Delete Data

Removes one or more objects from the database.

```php
$client->delete(['key' => 'user:john']);

// Delete multiple with field_array
$client->delete([
    'field_array' => [
        ['field' => 'status', 'value' => 'inactive']
    ]
]);
```

**Parameters:**

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `key` | string | No* | Object key to delete |
| `field_array` | array | No* | Query to select objects for deletion |

*Either `key` or `field_array` must be provided.

---

## 🔢 Array Operations

### PUSH - Add to Array

Adds a value to the end of an array field.

```php
$client->push([
    'key' => 'user:john',
    'array' => 'tags',
    'value' => 'premium'
]);
```

**Parameters:**

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `key` | string | No* | Object key |
| `field_array` | array | No* | Query for batch operation |
| `array` | string | Yes | Name of the array field |
| `value` | any | Yes | Value to append |
| `encryption_key` | string | No | Key for encrypted objects |

---

### POP - Remove Last Element

Removes and returns the last element from an array field.

```php
$client->pop([
    'key' => 'user:john',
    'array' => 'notifications'
]);
```

---

### SPLICE - Remove First Element

Removes the first element from an array field.

```php
$client->splice([
    'key' => 'user:john',
    'array' => 'notifications'
]);
```

---

### REMOVE - Remove Specific Value

Removes a specific value from an array field by finding and removing the first matching element.

```php
$client->remove([
    'key' => 'user:john',
    'array' => 'tags',
    'value' => 'premium'
]);
```

---

## 🔐 Encryption Operations

### ENCRYPT - Encrypt Data

Encrypts the data field of an object using AES encryption.

```php
$client->encrypt([
    'key' => 'user:john',
    'encryption_key' => 'secret-key-123'
]);
```

**Parameters:**

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `key` | string | Yes | Object key to encrypt |
| `encryption_key` | string | Yes | Encryption key (used for decryption) |

---

### DECRYPT - Decrypt Data

Decrypts an encrypted object's data using the provided encryption key.

```php
$client->decrypt([
    'key' => 'user:john',
    'encryption_key' => 'secret-key-123'
]);
```

**Parameters:**

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `key` | string | Yes | Object key to decrypt |
| `encryption_key` | string | Yes | Encryption key (must match encryption) |

---

## 🕸️ Graph Operations

### SET_VERTEX - Add Relationship

Adds vertices (connections) to an object for graph relationships.

```php
// Simple vertex
$client->setVertex([
    'key' => 'user:john',
    'vertex' => 'user:jane'
]);

// Vertex with relation
$client->setVertex([
    'key' => 'user:john',
    'vertex' => [
        'vertex' => 'user:jane',
        'relation' => 'friend',
        'weight' => 1.0
    ]
]);

// Multiple vertices
$client->setVertex([
    'key' => 'user:john',
    'vertex' => ['user:jane', 'user:alice']
]);
```

**Parameters:**

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `key` | string | Yes | Object key to add vertices to |
| `vertex` | string\|array\|object | Yes | Vertex/vertices to add |
| `encryption_key` | string | No | Key for encrypted objects |

---

### GET_VERTEX - Get Relationships

Returns all vertices (connections) defined for an object.

```php
$vertices = $client->getVertex([
    'key' => 'user:john'
]);
```

**Response:**
```json
{
    "type": "SUCCESS",
    "message": "OK",
    "id": "request-id",
    "data": [
        {"vertex": "user:jane", "relation": "friend", "weight": 1.0},
        {"vertex": "post:123", "relation": "author", "weight": 1.0}
    ]
}
```

---

### DELETE_VERTEX - Remove Relationship

Removes a specific vertex/connection from an object.

```php
$client->deleteVertex([
    'key' => 'user:john',
    'vertex' => 'user:jane'
]);
```

---

### DFS - Depth-First Search

Performs a distributed depth-first search traversal starting from a given node.

```php
$results = $client->dfs([
    'node' => 'user:john',
    'relation' => 'friend'
]);
```

**Parameters:**

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `node` | string | Yes | Starting node key |
| `relation` | string | No | Filter by relation type |

---

### GRAPH_BFS - Breadth-First Search

Returns all nodes reachable from a starting node using breadth-first search.

```php
$nodes = $client->graph_bfs([
    'node' => 'user:john'
]);
```

**Parameters:**

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `node` | string | No | Starting node (default: empty returns all) |

---

### GRAPH_DFS - Graph DFS

Returns all nodes reachable from a starting node using depth-first search.

```php
$nodes = $client->graph_dfs([
    'node' => 'user:john'
]);
```

---

### GRAPH_SHORTEST_PATH - Shortest Path

Finds the shortest path between a start node and end node using Dijkstra's algorithm.

```php
$path = $client->graph_shortest_path([
    'node' => 'user:john',
    'target' => 'post:123'
]);
```

**Response:**
```json
{
    "type": "SUCCESS",
    "message": "OK",
    "id": "request-id",
    "data": ["user:john", "user:alice", "post:123"]
}
```

---

### GRAPH_CONNECTED_COMPONENTS - Connected Components

Identifies all connected components in the graph (groups of nodes where each node is reachable from any other node in the group).

```php
$components = $client->graph_connected_components([]);
```

**Response:**
```json
{
    "type": "SUCCESS",
    "message": "OK",
    "id": "request-id",
    "data": [
        ["node1", "node2", "node3"],
        ["node4", "node5"],
        ["node6"]
    ]
}
```

---

### GRAPH_SCC - Strongly Connected Components

Identifies strongly connected components using Tarjan's algorithm.

```php
$scc = $client->graph_scc([]);
```

---

### GRAPH_DEGREE_CENTRALITY - Degree Centrality

Calculates the degree centrality (number of connections) for each node in the graph.

```php
$centrality = $client->graph_degree_centrality([]);
```

**Response:**
```json
{
    "type": "SUCCESS",
    "message": "OK",
    "id": "request-id",
    "data": {
        "node1": 5,
        "node2": 3,
        "node3": 8
    }
}
```

---

### GRAPH_CLOSENESS_CENTRALITY - Closeness Centrality

Calculates closeness centrality for each node, which measures how close a node is to all other nodes in the graph.

```php
$centrality = $client->graph_closeness_centrality([]);
```

**Response:**
```json
{
    "type": "SUCCESS",
    "message": "OK",
    "id": "request-id",
    "data": {
        "node1": 0.45,
        "node2": 0.32,
        "node3": 0.58
    }
}
```

---

### GRAPH_CENTROID - Find Centroid

Finds the node with the highest closeness centrality (the most central node in the graph).

```php
$centroid = $client->graph_centroid([]);
```

**Response:**
```json
{
    "type": "SUCCESS",
    "message": "OK",
    "id": "request-id",
    "data": "node-with-highest-centrality"
}
```

---

## 🤖 AI Operations

> **Note:** Some AI operations require a valid license. `ANN` requires a license, while `GET_SIMILAR` is always available.

### ASK - Ask Questions

Uses AI to answer complex questions about the database. The system automatically gathers relevant context using GET, DFS, GET_VERTEX, and ANN operations.

```php
$response = $client->ask([
    'question' => 'Which user has the most posts?',
    'session' => 'global',
    'backend' => 'ollama'
]);
```

**Parameters:**

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `question` | string | Yes | The question to ask |
| `session` | string | No | Mindspace session to use (default: `"global"`) |
| `backend` | string | No | LLM backend (`"ollama"` or `"openai"`) |

**Response:**
```json
{
    "type": "SUCCESS",
    "message": "SUCCESS",
    "id": "request-id",
    "data": {
        "response": "AI-generated answer...",
        "context": [...],
        "session": "session-id"
    }
}
```

---

### ANN / GET_SIMILAR - Similarity Search

Performs approximate nearest neighbor search to find semantically similar objects using vector embeddings.

```php
// Using existing object's embedding
$results = $client->ann([
    'key' => 'user:john',
    'k' => 10
]);

// Using GET_SIMILAR (always available, no license required)
$results = $client->get_similar([
    'key' => 'user:john',
    'k' => 10
]);

// Using a vector directly
$results = $client->ann([
    'vector' => [0.1, 0.2, 0.3, 0.4, ...],
    'k' => 10,
    'ef' => 25
]);
```

**Parameters:**

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `vector` | array | No* | Query vector as array of floats |
| `key` | string | No* | Use embedding from existing object |
| `k` | integer | No | Number of neighbors to return (default: 5) |
| `ef` | integer | No | Search width parameter (default: 25) |
| `use` | string | No | For key-based: `"data"` or `"embedding"` |

*Either `vector` or `key` must be provided.

---

## 📊 Analytics Operations

### GET_OPERATIONS - Operation History

Returns a log of recent database operations.

```php
$operations = $client->get_operations([]);
```

**Response:**
```json
{
    "type": "SUCCESS",
    "message": "OK",
    "id": "request-id",
    "data": "[{\"operation\":{...},\"response\":{...},\"timestamp\":1234567890},...]"
}
```

---

### GET_ACCESS_FREQUENCY - Access Count

Returns the number of times an object has been queried or accessed.

```php
$count = $client->get_access_frequency([
    'key' => 'user:john'
]);
```

**Response:**
```json
{
    "type": "SUCCESS",
    "message": "OK",
    "id": "request-id",
    "data": 42
}
```

---

## 🧠 Mindspace Operations

Mindspaces provide AI-powered conversational contexts for semantic operations.

### SET_MINDSPACE - Create Mindspace

Creates a new mindspace (cognitive context) for AI-powered conversations.

```php
$result = $client->set_mindspace([
    'mindspace_id' => 'conversation-1'
]);
```

**Parameters:**

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `mindspace_id` | string | No | Custom mindspace ID (auto-generated if omitted) |

---

### DELETE_MINDSPACE - Delete Mindspace

Removes a mindspace and all its associated context.

```php
$client->delete_mindspace([
    'mindspace_id' => 'conversation-1'
]);
```

**Parameters:**

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `mindspace_id` | string | Yes | Mindspace ID to delete |

---

### CHAT_MINDSPACE - Chat with Mindspace

Sends a message to a mindspace and receives an AI-generated response. The mindspace maintains conversation context.

```php
$response = $client->chat_mindspace([
    'mindspace_id' => 'conversation-1',
    'message' => 'What do you know about users?'
]);
```

**Parameters:**

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `mindspace_id` | string | Yes | Mindspace ID to chat with |
| `message` | string | Yes | User message |

**Response:**
```json
{
    "type": "SUCCESS",
    "message": "OK",
    "id": "request-id",
    "response": {
        "response": "AI response text...",
        "context": [...]
    }
}
```

---

### LECTURE_MINDSPACE - Import Corpus

Imports text corpus into a mindspace for semantic search and context retrieval.

```php
$client->lecture_mindspace([
    'mindspace_id' => 'conversation-1',
    'corpus' => 'User John Doe is a software engineer who specializes in Rust and distributed systems...'
]);
```

**Parameters:**

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `mindspace_id` | string | Yes | Mindspace ID to import into |
| `corpus` | string | Yes | Text content to import |

---

## 🔔 Real-time Notifications

Subscribe to real-time updates when an object changes.

```php
$client->notify('user:123', function($data) {
    echo "User updated!\n";
    print_r($data);
});
```

---

## 🐘 Schema Class

The `Schema` class provides an object-oriented way to model your data.

```php
use Satori\Satori;
use Satori\Schema;

$satori = new Satori('ws://localhost:8000', 'username', 'password');
$satori->connect();

// Create a schema-based object
$user = new Schema($satori, "user", ["name" => "Anna"], "my_key");
$user->set();

// Available methods:
// CRUD: set(), delete(), encrypt(), decrypt()
// Graph: setVertex(), getVertex(), deleteVertex(), dfs()
// References: setRef(), getRefs(), deleteRefs()
// Arrays: push(), pop(), splice(), remove()
```

---

## 📝 Response Format

All successful responses follow this general structure:

```json
{
    "type": "SUCCESS",
    "message": "OK",
    "id": "request-id",
    "key": "optional-key",
    "data": { ... }
}
```

Error responses:

```json
{
    "type": "ERROR",
    "message": "Error description",
    "id": "request-id"
}
```

---

## 💡 Complete Example

```php
<?php
require_once 'vendor/autoload.php';
require_once 'src/Satori.php';
use Satori\Satori;

$client = new Satori('ws://localhost:8000', 'username', 'password');
$client->connect();

// Create users
$client->set([
    'key' => 'user:john',
    'data' => ['name' => 'John', 'age' => 30],
    'type' => 'user'
]);

$client->set([
    'key' => 'user:jane',
    'data' => ['name' => 'Jane', 'age' => 25],
    'type' => 'user'
]);

// Create relationship
$client->setVertex([
    'key' => 'user:john',
    'vertex' => ['vertex' => 'user:jane', 'relation' => 'friend']
]);

// Get all friends of John
$friends = $client->getVertex(['key' => 'user:john']);

// Find similar users
$similar = $client->get_similar(['key' => 'user:john', 'k' => 5]);

// Ask AI about the data
$answer = $client->ask(['question' => 'How many users do we have?']);

// Subscribe to updates
$client->notify('user:john', function($data) {
    echo "John's profile was updated!\n";
});

echo "Setup complete!\n";
```

---

## 📄 License

This SDK is part of the SatoriDB project. For licensing information, please refer to the main project documentation.

---

## 🤝 Contributing

Feel free to open issues or contribute to improve this SDK!

Built with ❤️ by the Satori team.
