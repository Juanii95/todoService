<?php

class Task implements \JsonSerializable{

	private $_id;
	private $title;
	private $description;
	private $due_date;
	private $completed;
	private $created_at;
	private $updated_at;
	
	public function __construct( $params ) {
		if( empty( $params['title'] ) ) {
			throw new Exception('Empty title'); 
		}
		if( empty( $params['due_date'] ) ) {
			throw new Exception('Empty due_date'); 
		}
		if( empty( $params['completed'] ) ) {
			$params['completed'] = false;
		}		
		if( isset( $params['_id'] ) && !empty( $params['_id'] )){
			$this->_id = $params['_id'];
		} 

		if( isset( $params['description'] )){
			$this->description = $params['description'];
		}
		if( isset( $params['updated_at'] )){
			$this->updated_at = $params['updated_at'];
		}

		$this->title = $params['title'];
		$this->due_date = $params['due_date'];
		$this->completed = $params['completed'];
		$this->created_at = $params['created_at'];
	}

	public function __get($property) {
		if (property_exists($this, $property)) {
      		return $this->$property;
    	}
  	}

  	public function __set($property, $value) {
    	
    	if (property_exists($this, $property)) {
      		$this->$property = $value;
    		return true;
    	}
		return false;
  	}

	public function jsonSerialize() {
        return get_object_vars($this);
    }
	
	public function toArray() {
		return  array(
			'title' => $this->title,
			'due_date' => $this->due_date,
			'completed' => $this->completed,
			'created_at' => $this->created_at,
			'description' => $this->description,
			'updated_at' => $this->updated_at
		);
	}

}