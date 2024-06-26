<?php

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
      $database = 'midexam';
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
}
