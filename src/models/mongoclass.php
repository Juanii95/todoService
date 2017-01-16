<?php 

class _MongoDB {

    private $mongodb;
    private $table;

    /**
    * Return mongodb
    * @var object
    */
    public function __construct( $config ){
        $this->connect( $config );    
    }

    private function connect( $config ){
        if ( !class_exists( 'Mongo' )){
            throw new Exception('Mongo is not available'); 
        }
        if($config['username'] != '' && $config['password'] != ''){
           //Seccure connection
            $connection = new MongoClient( $config['connection_string'],
            array("username" => $config['username'], "password" => $config['password']) );
        } else {
            $connection = new MongoClient( $config['connection_string'] );
        }
        return $this->mongodb = $connection->selectDB( $config['database'] );
    }

    /**
     * Set Collection to use
     */
    public function selectCollection( $collection ){
        if( empty( $collection ) ){
            throw new Exception('Empty collection'); 
        }
        $this->table = new MongoCollection( $this->mongodb , $collection );
    }

    /**
     * Create task
     * @return boolean
     */
    public function create( $task ){
        return $result = $this->table->insert( $task );
    }

    /**
     * Delete task
     * @return boolean
     */
    public function delete( $_id ){
        if( empty( $_id ) ){
            throw new Exception('Empty _id'); 
        }
        // Convert strings of right length to Mongo_id
        if (strlen($_id) == 24){
           $_id = new \MongoId($_id);
        }

        return $result = $this->table->remove(array('_id' => $_id));
    }

    /**
     * Update task
     * @return boolean
     */
    public function update( $task ){
        $result = $this->table->update(
            array('_id' => $task->_id), 
            $task->toArray()
        );
        return $result;
    }

    /**
     * Get task by _id
     * @return array
     */
    public function getById( $_id ){

        if( empty( $_id ) ){
            throw new Exception('Empty _id'); 
        }
        // Convert strings of right length to Mongo_id
        if (strlen($_id) == 24){
           $_id = new \MongoId($_id);
        }

        //$cursor  = $this->table->find();
        $cursor  = $this->table->find(array('_id' => $_id));
        foreach ($cursor as $doc) {
            $task = $doc;
        }
        if ( empty( $task ) ){
            throw new Exception('Missing document '); 
        }
        return $task;
    }

     /**
     * Get all tasks
     * @return array
     */
    public function getAll( ){

        $cursor  = $this->table->find();
        if ( empty( $cursor ) ){
            throw new Exception('Empty collection '); 
        }
        return $cursor;
    }

}