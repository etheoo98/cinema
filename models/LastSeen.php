<?php

class LastSeen
{
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }
    public function updateLastSeen() {
        $stmt = $this->conn->prepare("UPDATE user SET last_seen = NOW() WHERE user_id = ?");
        $stmt->bind_param('i', $_SESSION['user_id']);
        $stmt->execute();
    }
}