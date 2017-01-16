<?php 

class RedisClass {

    private $redis;

    /**
    * Constructor
    */
    public function __construct( $config ){
        if ( !is_array( $config )){
            throw new Exception('Missing config'); 
        }
        $this->connect( $config );   
    }

    /**
    * Connection to Redis
    * @param array()
    */
    private function connect( $config ){
        if ( !class_exists( 'Redis' )){
            throw new Exception('Redis extension not exist'); 
        }

        $this->redis = new Redis();
        $this->redis->connect($config['host'], $config['port']);

        if( $config['password'] != '' ){
            $result = $this->redis->auth($config['password']);
            if( $result == false ) {
                throw new Exception('Redis authentication fail'); 
            }
        }
    }

    public function disconnect( ){
        $redis->close();
    }

    /**
     * Set task in Redis 
     * @param $key String
     * @param $data String
     * @return boolean
     */
    public function set( $key, $data ){

        if ( !is_string( $key ) || !is_string( $data ) ) {
            throw new Exception('Missing key or data'); 
        }
        $result = $this->redis->set( $key, $data );
        return true;
    }

    /**
    * Add key in list
    * @param $list String
    * @param $key String
    * @return boolean
    */
    public function addToList( $list,  $key ){

        if ( !is_string( $list ) || !is_string( $key ) ) {
            throw new Exception('Missing list or key'); 
        }
        return $this->redis->sAdd( $list, $key );
    }

    /**
    * Get keys+value in list 
    * @param $list String
    * @return boolean
    */
    public function getFromList( $list ){

        if ( !is_string( $list ) ) {
            throw new Exception('Missing list'); 
        }
        $keys = $this->redis->sMembers( $list );
        return $keys;
    }

    /**
    * Remove key in list Redis
    * @param $list String
    * @param $key String 
    * @return boolean
    */
    public function removeFromList( $list,  $key ){

        if ( !is_string( $list ) || !is_string( $key ) ) {
            throw new Exception('Missing list or key'); 
        }
        return $this->redis->sRem( $list, $key );
    }
    
    /**
    * Get keys from list with scores Redis
    * @param $list String
    * @param $score String 
    * @return boolean
    */
    public function getFromListWithScore( $list, $score ){
        
        if ( !is_string( $list ) ) {
            throw new Exception('Missing list, key or score'); 
        }
        $keys = $this->redis->zRangeByScore($list, $score , $score );
        return $keys;
    }

    /**
    * Add key in list with scores
    * @param $list String
    * @param $score String
    * @param $key String
    * @return boolean
    */
    public function addToListWithScore( $list, $score, $key ){

        if ( !is_string( $list ) || !is_string( $key )) {
            throw new Exception('Missing list, key or score'); 
        }
        return $this->redis->zAdd($list, $score->format('Ymd'), $key );
    }

    /**
    * Remove key in list with scores Redis
    * @param $list String
    * @param $key String
    * @return boolean
    */
    public function removeFromListWithScores( $list,  $key ){

        if ( !is_string( $list ) || !is_string( $key ) ) {
            throw new Exception('Missing list or key'); 
        }
        $this->redis->zRem( $list, $key );
        return true;
    }

    /**
    * Get task by key
    * @param $key String
    * @return boolean
    */
    public function get( $key ){

        if ( !is_string( $key ) ) {
            throw new Exception('Missing key'); 
        }
        return $this->redis->get( $key );
    }

    /**
    * Get multiple tasks by key
    * @param $key array() 
    * @return boolean
    */
    public function mget( $keys ){

        if ( !is_array( $keys ) ) {
            throw new Exception('Missing key'); 
        }
        return $this->redis->mget( $keys );
    }

    /**
    * Remove task by key
    * @param $key String
    * @return boolean
    */
    public function remove( $key ){

        if ( !is_string( $key ) ) {
            throw new Exception('Missing key'); 
        }
        return $this->redis->del( $key );
    }

}   


