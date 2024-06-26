<?php
require_once "BaseDao.php";

class MidtermDao extends BaseDao
{

    public function __construct()
    {
        parent::__construct();
    }

    /** TODO
     * Implement DAO method used to add content to database
     */
    public function input_data($data)
    {
        /** TODO
         * This endpoint is used to insert IP2LOCATION.json file content to database table locations
         * This endpoint should return output in JSON format
         * 10 points
         */
        $sql = "INSERT INTO locations (`from`, `to`, code, Country, Region, City)
                VALUES (:from, :to, :code, :Country, :Region, :City)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':from', $data['from']);
        $stmt->bindValue(':to', $data['to']);
        $stmt->bindValue(':code', $data['code']);
        $stmt->bindValue(':Country', $data['Country']);
        $stmt->bindValue(':Region', $data['Region']);
        $stmt->bindValue(':City', $data['City']);
        $stmt->execute();
        $data['id'] = $this->conn->lastInsertId();
        return $data;
    }

    /** TODO
     * Implement DAO method to return summary as requested within route /midterm/summary/@country
     */
    public function summary($country)
    {
        /** TODO
         * This endpoint is used to return total number of regions and cities from locations table
         * by country given as parameter
         * This endpoint should return output in JSON format
         * 30 points
         */

        $stmt = $this->conn->prepare("
            SELECT country, COUNT(DISTINCT(region)) AS total_num_of_regions, COUNT(DISTINCT(city)) AS total_number_of_cities
            FROM locations
            WHERE country = :country
            GROUP BY :country
        ");
        $stmt->bindValue(':country', $country);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /** TODO
     * Implement DAO method to return list as requested within route /midterm/encoded
     */
    public function encoded()
    {
        /** TODO
         * This endpoint is used to create report that lists first 10 countries and their hashed values
         * Sample data for one country: ['contry' => 'United States', 'encoded' => 'VW5pdGVkIFN0YXRlcw=='];
         * There is php function used to encode string
         * This endpoint should return output in JSON format
         * 30 points
         */

        $stmt = $this->conn->prepare("
            SELECT DISTINCT Country 
            FROM locations
            ORDER BY Country
            LIMIT 10
        ");

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** TODO
     * Implement DAO method to return location(s) as requested within route /midterm/ip
     */
    public function ip($ip_address)
    {
        $stmt = $this->conn->prepare("
            SELECT Country, Region, City FROM locations
            WHERE :ip_address BETWEEN `from` AND `to`
        ");
        $stmt->bindValue(':ip_address', $ip_address);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
