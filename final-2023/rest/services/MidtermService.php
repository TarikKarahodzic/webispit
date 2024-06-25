<?php
require_once __DIR__."/../dao/MidtermDao.php";

class MidtermService {
    protected $dao;

    public function __construct() {
        $this->dao = new MidtermDao();
    }

    /** TODO
    * Implement service method to add new investor to investor table and cap-table
    */
    public function investor($first_name, $last_name, $email, $company, $created_at) {
        return $this->dao->investor($first_name, $last_name, $email, $company, $created_at);
    }

    /** TODO
    * Implement service method to validate email format and check if email exists
    */
    public function investor_email($email) {
        return $this->dao->investor_email($email);
    }

    /** TODO
    * Implement service method to return list of investors according to instruction in MidtermRoutes.php
    */
    public function investors($id) {
        return $this->dao->investors($id);
    }
}
