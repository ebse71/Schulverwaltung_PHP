<?php

class Autohelper {

    /**
     * Generates a random password.
     *
     * @param int $length
     * @return string
     */
    public static function generateRandomPassword($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()';
        $charactersLength = strlen($characters);
        $randomPassword = '';
        for ($i = 0; $i < $length; $i++) {
            $randomPassword .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomPassword;
    }

    /**
     * Checks if the given password is strong.
     *
     * @param string $password
     * @return bool
     */
    public static function isStrongPassword($password) {
        return strlen($password) >= 10 &&
            preg_match('/[A-Z]/', $password) &&
            preg_match('/[a-z]/', $password) &&
            preg_match('/[0-9]/', $password) &&
            preg_match('/[\W]/', $password);
    }

    /**
     * Sends an email.
     *
     * @param string $to
     * @param string $subject
     * @param string $message
     * @param string $headers
     * @return bool
     */
    public static function sendEmail($to, $subject, $message, $headers = '') {
        return mail($to, $subject, $message, $headers);
    }

    /**
     * Generates a secure token.
     *
     * @return string
     */
    public static function generateToken() {
        return bin2hex(random_bytes(32));
    }

    /**
     * Validates an email address.
     *
     * @param string $email
     * @return bool
     */
    public static function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Checks if the token is valid.
     *
     * @param object $dbh
     * @return bool
     */
    public static function checkToken($dbh) {
        if (isset($_COOKIE['user_token'])) {
            $token = $_COOKIE['user_token'];
            $stmt = $dbh->prepare("SELECT * FROM users WHERE userkey = :token LIMIT 1");
            $stmt->bindParam(':token', $token);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!isset($_SESSION['last_activity']) || (time() - $_SESSION['last_activity']) > 300) {
                    session_unset();
                    session_destroy();
                    setcookie('user_token', '', time() - 3600, "/");
                    return false;
                }

                $_SESSION['last_activity'] = time();
                return true;
            }
        }

        return false;
    }
}

?>
