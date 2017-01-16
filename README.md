# To Do Service
Todo list application. The data is stored and accessed from a RESTful web service. 
Actions to perform: create, update, delete, show and list tasks.

### Version
0.0.1

### Techs
* PHP v5.6
* MongoDB v3.4.1
* Redis v3.26

### Installation

1. Install Pecl and Composer
    [Pecl](http://php.net/manual/es/install.pecl.php)
    [Composer](https://getcomposer.org/download/)

2. Install Redis and Mongo modules to php.
    [Redis Module](https://pecl.php.net/package/redis)
    [MongoDB Module](http://php.net/manual/es/mongodb.installation.pecl.php)

3. Clone the repository:
    ```sh
    $ git clone git@github.com:agustinfrancesconi/todoService.git
    ```

4. Install Slim Framework. Navigate into your project’s root directory and execute the bash command:
    ```sh
    $ composer require slim/slim "^3.0"
    ```

5. Run this command from the directory in which you want to install:
    ```sh
    $ php composer.phar create-project slim/slim-skeleton todoService
    ```

### Configuration

Edit the connections params to Redis, Mongo and other configurations in src/config/config.php file:
```php
<?php

return array(
    'redis' => array(
        'host' => '127.0.0.1',
        'username' => '',
        'password' => '',
        'database' => 'db',
        'port' => 6379,
        'queryLimit' => 5
    ),
    'mongodb' => array(
        'connection_string'=> 'mongodb://127.0.0.1:27017',
        'host' => '127.0.0.1',
        'username' => '',
        'password' => '',
        'database' => 'db',
        'defaultCollection' => 'tasks'
    )
);

```

## Endpoints


### Create Task

#### URL: /tasks/

#### Method: POST

Create new Task.

#### Params:

| Param     | Type    | Description |
| --------|---------|-------|
| title  | String   | Task title   |
| description  | String   | Task description   |
| completed  | Bool   | true/false for completed/uncompleted   |
| due_date  | String   | Task due date   |

#### Succes Response:

```json
HTTP/1.1 201 OK
Content-Type: application/json;charset=utf-8

{
   "Result":"Created task 587c313f807f7ff6208b456b"
}


```

#### Error Response:

```js
HTTP/1.1 500 Internal Server Error
Content-Type: application/json;charset=utf-8

{
    "Error" : "Error description"
}

```

### Delete Task

#### URL: /tasks/{_id}

#### Method: DELETE

Delete Task by id.

#### Succes Response:

```json
HTTP/1.1 200 OK
Content-Type: application/json;charset=utf-8

{
   "Result":"Deleted task 587c313f807f7ff6208b456b"
}


```

#### Error Response:

```js
HTTP/1.1 500 Internal Server Error
Content-Type: application/json;charset=utf-8

{
    "Error" : "Error description"
}

```

### Update Task

#### URL: /tasks/{_id}

#### Method: PUT

Update task by id.

#### Params:


| Param     | Type    | Description |
| --------|---------|-------|
| title  | String   | Title of the task   |
| description  | String   | Description of the task   |
| completed  | Bool   | true/false for completed/uncompleted   |
| due_date  | String   | Due date of the task   |

#### Succes Response:

```json
HTTP/1.1 200 OK
Content-Type: application/json;charset=utf-8

{
   "Result":"Updated task 587c313f807f7ff6208b456b"
}


```

#### Error Response:

```js
HTTP/1.1 500 Internal Server Error
Content-Type: application/json;charset=utf-8

{
    "Error" : "Error description"
}

HTTP/1.1 404 Not Found
Content-Type: application/json;charset=utf-8

{
    "Result" : "Task not found."
}

```


### List Tasks

#### URL: /tasks/

#### Method: GET

List tasks filtered by completed/uncompleted, due date, creation date and update date. Only 5 results per page will be shown; use offset to paginate results.

#### URL Params:

All parameters are combinable and optional.

| Param     | Type    | Description |
| --------|---------|-------|
| completed  | Bool   | true/false for completed/uncompleted tasks   |
| due_date  | String   | Filter by due date "20170116"   |
| created_at  | String   | Filter by date of creation "20170116" |
| updated_at  | String   | Filter by date of update "20170116"  |
| offset  | String   | Use for pagination   |

#### Succes Response:


```json
HTTP/1.1 200 OK
Content-Type: application/json;charset=utf-8

{
    "Items":
        {
            "title":"",
            "due_date":{
                "date":"2017-01-15 15:52:45.000000",
                "timezone_type":3,
                "timezone":"America\/Buenos_Aires"
            },
            "completed":true,
            "created_at":{
                "date":"2017-01-15 23:34:39.000000",
                "timezone_type":3,
                "timezone":"America\/Buenos_Aires"
            },
            "description":"",
            "updated_at":null,
            "_id":{
                "$id":"587c313f807f7ff6208b456b"
            }
        },
    "Total":1
}


```

#### Error Response:

```js
HTTP/1.1 500 Internal Server Error
Content-Type: application/json;charset=utf-8

{
    "Error" : "Error description"
}
```

### Show Task

#### URL: /tasks/{_id}

#### Method: GET



#### Succes Response:

```json
HTTP/1.1 200 OK
Content-Type: application/json;charset=utf-8

{
    "Items":
        {
            "title":"",
            "due_date":{
                "date":"2017-01-15 15:52:45.000000",
                "timezone_type":3,
                "timezone":"America\/Buenos_Aires"
            },
            "completed":true,
            "created_at":{
                "date":"2017-01-15 23:34:39.000000",
                "timezone_type":3,
                "timezone":"America\/Buenos_Aires"
            },
            "description":"",
            "updated_at":null,
            "_id":{
                "$id":"587c313f807f7ff6208b456b"
            }
        },
    "Total":1
}


```

#### Error Response:

```js
HTTP/1.1 500 Internal Server Error
Content-Type: application/json;charset=utf-8

{
    "Error" : "Error description"
}

HTTP/1.1 404 Not Found
Content-Type: application/json;charset=utf-8

{
    "Result" : "Task not found."
}

```

