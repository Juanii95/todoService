<?php
require "helpers/helper.php";
require "controllers/rediscontroller.php";
require "models/task.php";
require "models/redisclass.php";
require "models/mongoclass.php";
require "controllers/mogodbcontroller.php";

/*
 * Endpoint /tasks/
 * Method GET
 *
 * List tasks filtered by completed/uncompleted, due date, creation date and update date
 *
 * @param (Bool) completed
 * @param (String) due_date
 * @param (String) creation_date
 * @param (String) update_date

 * @return (Json) Tasks
 */
$app->get('/tasks/', function( $request, $response, $args ) use ($config) {
    try{
    	//Get list of tasks
		$redis = new RedisController( $config['redis'] );
		$result = $redis->listTasks( $request->getQueryParams() );		
		echo $response->withJson($result);

	} catch ( Exception $e) {
		echo $response->withJson( array( "ERROR" => $e->getMessage() ) ); 
	}
});


/*
 * Endpoint /tasks/{_id}
 * Method PUT
 *
 * Update task
 *
 * @param (String) title
 * @param (String) description
 * @param (Bool) completed
 * @param (String) due_date
 *
 * @return (Json) Result
 */
$app->put('/tasks/{_id}', function ($request, $response, $args) use ($config){
	try{
		//Get data from mongo
		$params = array (
			'title' => $request->getParam('title'),
			'due_date' => validateDate( $request->getParam('due_date') ),
			'completed' => ( empty( $request->getParam('completed') ) ) ? false : true,
			'description' => $request->getParam('description'),
			'updated_at' => new DateTime()
		);

		$mongo = new MongodbController ( $config['mongodb']);
		$result = $mongo->updateTask( $request->getAttribute( '_id' ), $params );
		if( $result ){
			//Update data in redis
			$redis = new RedisController( $config['redis'] );
			$redis->updateTask( $result );
			echo $response->withJson( array( "Result" => "Update task " . $result->_id ) );

		}

	} catch ( Exception $e){
		echo $response->withJson( array( "ERROR" => $e->getMessage() ) ); 
	}
});

/*
 * Endpoint /tasks/{_id}
 * Method GET
 *
 * Get task by id
 *
 * @param (String) _id
 *
 * @return (Json) Task
 */
$app->get('/tasks/{_id}', function( $request, $response, $args) use ($config){
    try {
    	
    	$redis = new RedisController( $config['redis'] );
		$result = $redis->getTask( $request->getAttribute('_id') );
		
		if ( isset($result['Result']) ) {
			echo $response->withJson( $result, 404 );
		} else {
			echo $response->withJson( $result );
		}

    } catch ( Exception $e ) {
		echo $response->withJson( array( "ERROR" => $e->getMessage() ), 500 );
	} 
});

/*
 * Endpoint /tasks/
 * Method POST
 *
 * Create new task
 *
 * @param (Bool) completed
 * @param (String) title
 * @param (String) description
 * @param (String) due_date

 * @return (Json) Result
 */
$app->post('/tasks/', function ($request, $response, $args) use ($config) {
    
	try{
		
		//New Task
		$params = array (
			'title' => $request->getParam('title'),
			'due_date' => validateDate( $request->getParam('due_date') ),
			'completed' => ( empty( $request->getParam('completed') ) ) ? false : true,
			'description' => $request->getParam('description'),
			'created_at' => new DateTime()
		);

		$mongo = new MongodbController ( $config['mongodb']);
		$result = $mongo->newTask( $params );
		if((string)$result['_id']){
			//Add to redis
			$redis = new RedisController ( $config['redis'] );
		 	$redis->newTask( $result );
			echo $response->withJson( array( "Result" => "Create task " . (string)$result['_id'] ), 201 ); 

		}

	} catch ( Exception $e){
		echo $response->withJson( array( "ERROR" => $e->getMessage() ), 500 ); 
	}
});

/*
 * Endpoint /tasks/{_id}
 * Method DELETE
 *
 * Delete task
 *
 * @param (String) _id
 *
 * @return (Json) Result
 */
$app->delete('/tasks/{_id}', function( $request, $response, $args ) use ($config) {
	try {

		//Remove from mongo
		$_id = $request->getAttribute('_id');
		$mongo = new MongodbController ( $config['mongodb']);
		$result = $mongo->removeTask( $_id );

		if($result['ok'] == 1){
			//Remove from redis
			$redis = new RedisController( $config['redis'] );
		 	$redis->removeTask( $_id );
			echo $response->withJson( array( "Result" => "Deleted task " . $_id ) ); 

		}

	} catch( Exception $e){
		echo $response->withJson( array( "ERROR" => $e->getMessage() ), 500 ); 
	}
});
