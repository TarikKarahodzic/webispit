<?php
require_once __DIR__ . "/../dao/MidtermDao.php";

class MidtermService
{
    protected $dao;

    public function __construct()
    {
        $this->dao = new MidtermDao();
    }

    /** TODO
     * Implement service method to add content to database
     */
    public function input_data($data)
    {
        $record = [
            'from' => $data['from'],
            'to' => $data['to'],
            'code' => $data['code'],
            'Country' => $data['Country'],
            'Region' => $data['Region'],
            'City' => $data['City']
        ];
        return $this->dao->input_data($record);
    }

    /** TODO
     * Implement service method for route /midterm/summary/@country
     */
    public function summary($country)
    {
        return $this->dao->summary($country);
    }


    /** TODO
     * Implement service method for route /midterm/encoded
     */
    public function encoded()
    {
        $countries = $this->dao->encoded();
        $result = [];

        foreach ($countries as $country) {
            $encodedValue = base64_encode($country['Country']);
            $result[] = [
                'country' => $country['Country'],
                'encoded' => $encodedValue
            ];
        }

        return $result;
    }

    /** TODO
     * Implement service method for route /midterm/ip
     */
    public function ip($ip_address)
    {
        return $this->dao->ip($ip_address);
    }
}
