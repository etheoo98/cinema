<?php

#[AllowDynamicProperties] class ViewStatistics
{
    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /**
     * @return mixed
     *
     * This function selects the registration date of all users and returns the mysqli_result object.
     */
    public function getTotalUsers(): mixed
    {
        $sql = 'SELECT DATE(`date_of_registration`)
                AS `registration_date`
                FROM `user`;';

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getTotalTickets() {
        $sql = 'SELECT `movie_id`, DATE(`date`)
                FROM `booking`;';

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->get_result();
    }
}