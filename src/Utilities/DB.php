<?php

namespace Utilities;

use Mysqlidb;

class DB
{

    protected $db = DBDATA;
    protected $local = LOCAL;

    function __construct()
    {
        $this->host = $this->db['DB']['host'];
        $this->username = $this->db['DB']['username'];
        $this->password = $this->db['DB']['password'];
        $this->db = $this->db['DB']['db'];
        $this->port = 3306;
    }

    public function start()
    {
        if ($this->local === true) {

            $dbSQL = array( //Staging DB 
                'host' => 'localhost',
                'username' => 'root',
                'password' => '',
                'db' => $this->db,
                'port' => $this->port
            );

            // Call Staging  DB
            $db = new Mysqlidb($dbSQL);

            if (!$db) {
                die("Please try again later.");
            };
        } else {

            $dbSQL = array( //Production DB
                'host' => $this->host,
                'username' => $this->username,
                'password' => $this->password,
                'db' => $this->db,
                'port' => $this->port
            );

            // Call Production  DB
            $db = new Mysqlidb($dbSQL);

            if (!$db) {
                die("Please try again later.");
            };
        }
        return $db;
    }
}
