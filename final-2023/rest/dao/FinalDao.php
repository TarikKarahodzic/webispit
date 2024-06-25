<?php
require_once "BaseDao.php";

class FinalDao extends BaseDao
{

    public function __construct()
    {
        parent::__construct();
    }

    /** TODO
     * Implement DAO method used login user
     */
    public function login($email, $password)
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && $password === $user['password']) {
            return $user;
        }
        return false;
    }

    /** TODO
     * Implement DAO method used add new investor to investor table and cap-table
     */
    public function investor()
    {
        $stmt = $this->conn->prepare("
            INSERT INTO investors(first_name, last_name, email, company, created_at)
                VALUES(:first_name, :last_name, :email, :company, :created_at);
        ");
        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':company', $company);
        $stmt->bindParam(':created_at', $created_at);
        $stmt->execute();

        
    }

    /** TODO
     * Implement DAO method to return list of all share classes from share_classes table
     */
    public function share_classes()
    {
        $stmt = $this->conn->query("SELECT * FROM share_classes");
        
        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $row;
        }
        return $result;

        // OR return $this->conn->query("SELECT * FROM share_classes");
    }

    /** TODO
     * Implement DAO method to return list of all share class categories from share_class_categories table
     */
    public function share_class_categories()
    {
        $stmt = $this->conn->query("
            SELECT *
            FROM share_class_categories;
        ");

        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $row;
        }
        return $result;
    }
}
