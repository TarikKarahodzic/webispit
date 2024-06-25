<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class BaseDao
{

  protected $conn;

  /**
   * constructor of dao class
   */
  public function __construct()
  {
    try {
      /** TODO
       * List parameters such as servername, username, password, schema. Make sure to use appropriate port
       */
      $servername = 'localhost';
      $dbUsername = 'root';
      $dbPassword = 'root';
      $database = 'midterm';
      $port = '3306';

      /** TODO
       * Create new connection
       */
      $this->conn = new PDO("mysql:host=$servername;port=$port;dbname=$database", $dbUsername, $dbPassword);
      // set the PDO error mode to exception
      $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      echo "Connected successfully";
    } catch (PDOException $e) {
      echo "Connection failed: " . $e->getMessage();
    }
  }

  protected function query($query, $params = [])
  {
    $stmt = $this->conn->prepare($query);
    $stmt->execute($params);
    return $stmt;
  }
}
