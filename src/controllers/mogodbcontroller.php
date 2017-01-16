<?php

class MongodbController {

	private $config;

	/**
 	* Constructor
 	*/
    public function __construct( $config ){
    	$this->config = $config;
    	return $this;
    }

	/**
	* Create new task
    * @param $task array()
	* @return boolean
	*/
	function newTask ( $params ) {
		$task = new Task( $params );  	
	  	//Save in mongo
	  	$mongo = new _MongoDB( $this->config );
		$mongo->selectCollection( $this->config['defaultCollection'] );
		$_task = $task->toArray();
		$mongo->create( $_task );
		return $_task;
	}

	/**
	* Remove task by key
	* @param $_id String
	* @return boolean
	*/
	function removeTask( $_id) {
		$mongo = new _MongoDB( $this->config );
		$mongo->selectCollection( $this->config['defaultCollection'] );
		$result = $mongo->delete( $_id );
		return $result;
	}

	/**
    * Update task
    * @param $_id String
    * @param $task array()
    * @return boolean
    */
	function updateTask ( $_id, $params) {
		$mongo = new _MongoDB( $this->config );
		$mongo->selectCollection( $this->config['defaultCollection'] );
		$result = $mongo->getById( $_id);
		$task = new Task( $result );

		//Update task fields
		$task->title = $params['title'];
		$task->completed = ( empty( $params['completed'] )  ) ? true : false;
		$task->description = $params['description'];
		$task->due_date = $params['due_date'];
		$task->updated_at = new DateTime();

		//Update mongo
		$mongo->update( $task );
		
		return $task;
	}
}
