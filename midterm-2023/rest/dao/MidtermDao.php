<?php

class MidtermDao
{

  private $conn;
  private $table;

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

  /** TODO
   * Implement DAO method used to get cap table
   */
  public function cap_table()
  {
    $stmt = $this->conn->query("
      SELECT sc.description AS class,
              GROUP_CONCAT(scc.description) AS category,
              CONCAT(inv.first_name, ' ', inv.last_name) AS investor,
              ct.diluted_shares
      FROM cap_table ct
      JOIN share_classes sc ON ct.share_class_id = sc.id
      JOIN share_class_categories scc ON ct.share_class_category_id = scc.id
      JOIN investors inv ON ct.investor_id = inv.id
      GROUP BY class, investor
    ");

    $result = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $result[] = $row;
    }
    return $result;
  }

  /** TODO
   * Implement DAO method used to get summary
   */
  public function summary()
  {
    $stmt = $this->conn->query("
      SELECT COUNT(DISTINCT ct.investor_id) AS total_investors,
              SUM(ct.diluted_shares) AS total_diluted_shares
      FROM cap_table ct
    ");

    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  /** TODO
   * Implement DAO method to return list of investors with their total shares amount
   */
  public function investors()
  {
    $stmt = $this->conn->query("
      SELECT 
              inv.first_name AS first_name, 
              inv.last_name AS last_name, 
              inv.company AS company, 
              SUM(ct.diluted_shares) AS total_shares
      FROM investors inv
      JOIN cap_table ct ON inv.id = ct.investor_id
      GROUP BY first_name
    ");

    $result = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $result[] = $row;
    }
    return $result;
  }
}
