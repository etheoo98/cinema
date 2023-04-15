<?php
class Admin {
    private mysqli $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

}