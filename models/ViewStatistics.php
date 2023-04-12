<?php

#[AllowDynamicProperties] class ViewStatistics
{
    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getTotalUsers() {
        $sql = 'SELECT DATE(`date_of_registration`) as `registration_date` FROM `user`;';

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getTotalTickets() {
        $sql = 'SELECT `movie_id`, DATE(`date`) FROM `booking`;';

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->get_result();
    }
}