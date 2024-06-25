<?php
require_once "BaseDao.php";

class MidtermDao extends BaseDao
{

    public function __construct()
    {
        parent::__construct();
    }

    /** TODO
     * Implement DAO method used add new investor to investor table and cap-table
     */
    public function investor($first_name, $last_name, $email, $company, $created_at)
    {
        $stmt = $this->conn->prepare("
            INSERT INTO investors (first_name, last_name, email, company, created_at) 
                    VALUES (:first_name, :last_name, :email, :company, :created_at)
        ");

        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':company', $company);
        $stmt->bindParam(':created_at', $created_at);
        $stmt->execute();

        $investorId = $this->conn->lastInsertId();

        $stmt = $this->conn->prepare("
            SELECT first_name, last_name, email, company, created_at, id
            FROM investors
            WHERE id = :id
        ");
        $stmt->bindParam(':id', $investorId);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    /** TODO
     * Implement DAO method to validate email format and check if email exists
     */
    public function investor_email($email)
    {
        /** TODO
         * This endpoint is used to check if investor email is in valid format
         * and if it exists in investors table
         * If format is not valid, output should be 'Invalid email format' message
         * If format is valid, return either
         * 'Investor first_name last_name' uses this email address' (replace first_name and last_name with data from database)
         * or 'Investor with this email does not exists in database'
         * Output example is given in figure 2 (message should be updated according to the result)
         * This endpoint should return output in JSON format
         
         */
        $stmt = $this->conn->prepare("
            SELECT first_name, last_name, email, company, created_at, id
            FROM investors
            WHERE email = :email
        ");

        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            Flight::json(['success' => "Investor " . $result['first_name'] . " " . $result['last_name'] . " uses this email address"]);
        } else {
            Flight::json(['error' => 'Investor with this email does not exists in database']);
        }
    }

    /** TODO
     * Implement DAO method to return list of investors according to instruction in MidtermRoutes.php
     */
    public function investors($id)
    {
        $stmt = $this->conn->prepare("
            SELECT sc.description, sc.equity_main_currency, sc.price, sc.authorized_assets, i.first_name, i.last_name, i.email, i.company, SUM(ct.diluted_shares) AS total_diluted_assets
            FROM share_classes sc
            JOIN cap_table ct ON ct.share_class_id = sc.id
            JOIN investors i ON ct.investor_id = i.id
            WHERE sc.id = :id
            GROUP BY sc.id, i.id;
        ");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $row;
        }
        return $result;
    }
}
