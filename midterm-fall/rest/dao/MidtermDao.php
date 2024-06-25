<?php

class MidtermDao
{

  private $conn;

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
       * Use $options array as last parameter to new PDO call after the password
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
   * Implement DAO method used to add cap table record
   */
  public function add_cap_table_record($share_class_id, $share_class_category_id, $investor_id, $diluted_shares)
  {
    $stmt = $this->conn->prepare("
          INSERT INTO cap_table (share_class_id, share_class_category_id, investor_id, diluted_shares)
          VALUES (:share_class_id, :share_class_category_id, :investor_id, :diluted_shares)
      ");
    $stmt->bindParam(':share_class_id', $share_class_id);
    $stmt->bindParam(':share_class_category_id', $share_class_category_id);
    $stmt->bindParam(':investor_id', $investor_id);
    $stmt->bindParam(':diluted_shares', $diluted_shares);

    $stmt->execute();

    $lastId = $this->conn->lastInsertId();

    $stmt = $this->conn->prepare("
            SELECT share_class_id, share_class_category_id, investor_id, diluted_shares, id
            FROM cap_table
            WHERE id = :id
        ");
    $stmt->bindParam(':id', $lastId);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
  }

  /** TODO
   * Implement DAO method to return list of categories with total shares amount
   */
  public function categories()
  {
    $stmt = $this->conn->query("
      SELECT scc.description AS category, SUM(ct.diluted_shares) AS total_shares
      FROM cap_table ct
      JOIN share_class_categories scc ON ct.share_class_category_id = scc.id
      GROUP BY category
    ");

    $result = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $result[] = $row;
    }
    return $result;
  }

  /** TODO
   * Implement DAO method to delete investor
   */
  public function delete_investor($id)
  {
    $stmt = $this->conn->prepare("DELETE FROM investors WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
  }
}
