<?php 

class RedisController {

	private $config;

	/**
 	* Constructor
 	*/
    public function __construct( $config ){
    	$this->config = $config;
    	return $this;
    }

 	/**
     * Get task by key
     * @param $_id String
     * @return boolean
     */
    public function getTask ( $_id ) {
		$redis = new RedisClass( $this->config );
    	$result = $redis->get( $_id );
		if( $result == false ){
			return array(
				'Result' => "Task not found."
			);
		};
		return array(
			'Items' => json_decode( $result ),
			'Total' => sizeof($result) 
		);
    	return $result;
    }

     /**
     * Remove task by key
     * @param $_id String
     * @return boolean
     */
	public function removeTask( $_id ) {
		$redis = new RedisClass( $this->config );

		//Remove task
		$redis->remove( $_id );
		//Remove from lists
		$redis->removeFromList( 'tasks', $_id );
		$redis->removeFromList( 'completed', $_id );
		$redis->removeFromList( 'uncompleted', $_id );
		$redis->removeFromListWithScores( 'due_date', $_id );
		$redis->removeFromListWithScores( 'created_at', $_id );
		$redis->removeFromListWithScores( 'update_at', $_id );
		return true;
	}

	/**
	* Create new task
    * @param $task array()
	* @return boolean
	*/
	public function newTask( $task ) {
		$redis = new RedisClass( $this->config );
		
		$redis->addToList( 'tasks', (string)$task['_id'] );
		
		//Add task
		$redis->set( (string)$task['_id'], json_encode($task) );
		//Add to lists
		$redis->addToListWithScore( 'due_date', $task['due_date'], (string)$task['_id'] );
		$redis->addToListWithScore( 'created_at', $task['created_at'], (string)$task['_id'] );	
		if( $task['completed'] == true){ 
			$redis->addToList( 'completed', (string)$task['_id'] );
		} else {
			$redis->addToList( 'uncompleted', (string)$task['_id'] );
		}
	}
	
	/**
    * Update task
    * @param $task array()
    * @return boolean
    */
	public function updateTask( $task ) {
		
		$redis = new RedisClass( $this->config );
		//Add task
		$redis->set( (string)$task->_id, json_encode($task) );
		
		$redis->addToListWithScore('due_date', $task->due_date, (string)$task->_id );
		$redis->addToListWithScore( 'update_at', $task->updated_at, (string)$task->_id );

		//Add to lists
		if( $task->completed == true){ 
			$redis->addTolist( 'completed',  (string)$task->_id );
			$redis->removeFromList( 'uncompleted',  (string)$task->_id );
		} else {
			$redis->removeFromList( 'completed',  (string)$task->_id );
			$redis->addTolist( 'uncompleted',  (string)$task->_id );
		}
	}

	/**
    * Create new task
    * @param $params array()
    * @return boolean
    */
	public function listTasks ( $params ) {
		
		$redis = new RedisClass( $this->config );
		$keys  = array();

		//No filters, list all tasks
		if( empty( $params) ){
			$keys = $redis->getFromList( 'tasks' );
		}
		
		if( isset( $params['completed'] ) && !is_null( $params['completed'] ) ){
			if($params['completed'] == "true" ){
				$keys = $redis->getFromList( 'completed' );
			} else {
				$keys = $redis->getFromList( 'uncompleted' );
			}
		}

		//Filter by due date
		if( isset( $params['due_date'] ) && !is_null( $params['due_date'] ) ){			
			$keys = array_merge( $keys, $redis->getFromListWithScore( 'due_date', $params['due_date'] ) );
		}

		//Filter by update date
		if( isset( $params['updated_at'] ) && !is_null( $params['updated_at'] ) ){			
			$keys = array_merge( $keys, $redis->getFromListWithScore( 'updated_at', $params['updated_at'] ) );
		}

		//Filter by create date
		if( isset( $params['created_at'] ) && !is_null( $params['created_at'] ) ){			
			$keys = array_merge( $keys, $redis->getFromListWithScore( 'created_at', $params['created_at'] ) );
		}

		$keys = array_unique($keys);
		$result = $redis->mget( $keys );
		$data = array();

		foreach ($result as $index => $value) {
			//Pagination by offset
			if( isset( $params['offset'] ) && !is_null( $params['offset'] ) ) {
				if( $index >= intval($params['offset']) && $index < intval($params['offset']) + $this->config['queryLimit'] ){
					array_push( $data, json_decode($value));
				}
			} else {
				if( $this->config['queryLimit'] >  $index){
					array_push( $data, json_decode($value));	
				}
			}
		}

		return array(
			'Items' => $data,
			'Total' => sizeof($result) 
		);
	}

}