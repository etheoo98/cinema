<?php

class Session {
    private mysqli $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }
    public function getSessionData(): array
    {
        # TODO: Remove ipify if "$_SERVER['REMOTE_ADDR']" works for clients except server
        # This function slows down sign in considerably!

        # Request IP from ipify API
        $ip_address = file_get_contents('https://api.ipify.org');

        # Request CountryCode from geoplugin API
        $api_url = "http://www.geoplugin.net/json.gp?ip=$ip_address";
        $api_response = file_get_contents($api_url);
        $api_data = json_decode($api_response, true);

        # Check if the API request was successful and the country code was returned
        if ($api_data['geoplugin_status'] == '200' && isset($api_data['geoplugin_countryCode'])) {

            # TODO: Log Country name, requires table set up (Log for both user and session I think)
            # $country_name = $api_data['geoplugin_countryName'];

            $country_code = $api_data['geoplugin_countryCode'];

            return array(
                'country_code' => $country_code,
                'ip_address' => $ip_address,
            );

        } else {
            return array (
                # Default country code if API request fails
                'country_code' => 'KP',
                'ip_address' => 'Unable to fetch',
                # $country_name = '';
            );
        }
    }

    /**
     * @throws Exception
     */
    public function addSession($sessionData): void
    {
        # Sanitize session details
        $ip_address = $sessionData['ip_address'];
        $country_code = $sessionData['country_code'];
        $user_agent = htmlspecialchars($_SERVER['HTTP_USER_AGENT'], ENT_QUOTES, 'UTF-8');
        $phpsessid = filter_var(session_id(), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $user_id = $_SESSION['user_id'];

        $this->conn->begin_transaction();

        try {
            # Insert into session table
            $sql = "INSERT INTO session (valid, date, ip_address, country_code, user_agent, phpsessid) VALUES (1, now(), ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('ssss', $ip_address, $country_code, $user_agent, $phpsessid);
            $stmt->execute();

            $session_id = $this->conn->insert_id;

            # Insert into user_session table
            $sql = "INSERT INTO user_session (user_id, session_id) VALUES (?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('ii', $user_id, $session_id);
            $stmt->execute();

            # Commit the transaction
            $this->conn->commit();
        } catch (Exception $e) {
            # Rollback the transaction if an error occurred
            $this->conn->rollback();
            throw $e;
        }
    }

    /**
     * @throws Exception
     */
    public function terminateSession($checkedBoxes): void
    {
        try {
            $this->conn->begin_transaction();

            # Lookup selected session(s) in database
            $sql = "SELECT * FROM session WHERE phpsessid = ?";
            $stmt = $this->conn->prepare($sql);

            $selectedSessions = array(); # Create an array to store the selected sessions
            foreach ($checkedBoxes as $value) {
                $stmt->execute([$value]);
                $result = $stmt->get_result();
                while ($row = mysqli_fetch_assoc($result)) {
                    $selectedSessions[] = $row; # Add the row to the selected sessions array
                }
            }

            $sql = "UPDATE `session` SET `valid` = '0' WHERE `session`.`phpsessid` = ?;";
            $stmt = $this->conn->prepare($sql);
            $rowsAffected = 0;
            foreach ($selectedSessions as $session) {
                if ($session['valid'] == 1) {
                    $stmt->execute([$session['phpsessid']]);
                    $rowsAffected += $stmt->affected_rows;
                }
            }

            if ($rowsAffected === 0) {
                throw new Exception('No rows were affected by the UPDATE statement');
            }

            $this->conn->commit();
        } catch (Exception $e) {
            $this->conn->rollback();
            throw $e;
        }
    }

    /**
     * @throws Exception
     */
    public function validateSession(): bool
    {
        # Check if the current session is valid
        $currentPhpsessid = session_id();
        $sql = "SELECT * FROM session WHERE phpsessid = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('s', $currentPhpsessid);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = mysqli_fetch_assoc($result);

        # If row is returned, and it's invalid, remove from database.
        if ($row && $row['valid'] == 0) {
            $this->conn->begin_transaction();
            try {
                # Delete row from user_session link table
                $sql = "DELETE FROM user_session WHERE `user_session`.`session_id` = ?";
                $stmt = $this->conn->prepare($sql);
                $stmt->bind_param('i', $row['session_id']);
                $stmt->execute();

                # Delete row from session table
                $sql = "DELETE FROM session WHERE `session`.`phpsessid` = ?";
                $stmt = $this->conn->prepare($sql);
                $stmt->bind_param('s', $currentPhpsessid);
                $stmt->execute();

                # Commit the transaction
                $this->conn->commit();

                # Invalidate current phpsessid
                session_unset();
                session_destroy();

                header("LOCATION: /cinema/sign-in");
            } catch (Exception $e) {
                # Rollback the transaction if an error occurred
                $this->conn->rollback();
                throw $e;
            }
        }

        # If the current phpsessid is not stored in the database, and page requires sign in, redirect.
        # TODO: When terminating current session, redirect doesn't happen unless refresh page
        elseif (!$row) {
            header("LOCATION: /cinema/sign-in");
        }

        return true;
    }

    public function updateLastSeen(): void
    {
        $stmt = $this->conn->prepare("UPDATE user SET last_seen = NOW() WHERE user_id = ?");
        $stmt->bind_param('i', $_SESSION['user_id']);
        $stmt->execute();
    }

    # Function for checking if current session id valid and is admin
    public function requireAdminRole(): bool
    {
        $currentPhpsessid = session_id();
        $user_id = $_SESSION['user_id'];
        $sql = "SELECT user.user_id, user.role, session.valid, session.phpsessid FROM `user`, `user_session`, `session` WHERE `user`.`user_id` = ? AND `session`.`phpsessid` = ? AND `user`.`user_id` = `user_session`.`user_id` AND `session`.`session_id` = `user_session`.`session_id`";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('ss', $user_id, $currentPhpsessid);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            if ($row['role'] == 'admin' && $row['valid'] == 1) {
                # Admin role is verified, proceed with further processing
                return true;
            } else {
                # Admin role is not verified
                return false;
            }
        } else {
            # No rows found
            return false;
        }
    }

}