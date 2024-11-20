<?php

namespace App\Libraries;
use MongoDB\Client;

class MongoDBLibrary{

    private $client;
    private $db;

    public function __construct(){

        $dbname = "two_services";

        $this->client = new Client("mongodb://localhost:27017");
        $this->db = $this->client->$dbname;

    }

    function getDatabasename(){
        return $this->db;
    }

    function getCollection($collectionName){
        return $this->db->$collectionName;
    }

}


?>
