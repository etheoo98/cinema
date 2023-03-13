<?php
class Settings {
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }
    public function getSessions() {
        $user_id = $_SESSION['user_id'];
        $sql = "SELECT * FROM user_session, session WHERE user_session.session_id = session.session_id AND user_id = ? AND valid=1 ORDER BY date DESC;";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('s', $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $sessions = $result->fetch_all(MYSQLI_ASSOC);

        # Compare dates
        foreach ($sessions as &$session) {
            $date = new DateTime($session['date']);
            $now = new DateTime();
            $interval = $date->diff($now);
            $days = $interval->days;

            if ($days == 0) {
                $session['date'] = "Today";
            } else if ($days == 1) {
                $session['date'] = "Yesterday";
            } else if ($days > 1 && $days <= 7) {
                $session['date'] = "$days days ago";
            } else if ($days > 7 && $days <= 28) {
                $weeks = floor($days / 7);

                if ($weeks == 1) {
                    $session['date'] = "1 week ago";
                } else {
                    $session['date'] = "$weeks weeks ago";
                }
            } else if ($days > 28 && $days <= 365) {
                $months = floor($days / 30);

                if ($months == 1) {
                    $session['date'] = "1 month ago";
                } else {
                    $session['date'] = "$months months ago";
                }
            } else {
                $years = floor($days / 365);

                if ($years == 1) {
                    $session['date'] = "1 year ago";
                } else {
                    $session['date'] = "$years years ago";
                }
            }
        }

        return $sessions;
    }
}