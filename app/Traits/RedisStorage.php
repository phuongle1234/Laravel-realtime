<?php

namespace App\Traits;
use Illuminate\Support\Facades\Redis;

// use Vimeo;

trait RedisStorage {

    // private $_key;
    // private $_value;

    public function setValue( $_key_name, $_value){
        Redis::set( $_key_name , $_value );
    }


    public function getValue( $_key_name ){
        return Redis::get( $_key_name);
    }

    public function delete( $_key_name ){
        $_key = Redis::keys( $_key_name );
        return Redis::del( $_key );
    }
}
