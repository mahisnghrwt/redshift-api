<?php
require_once('database.php');
require_once(__DIR__ . '/__config.php');


class Authenticator {
    private $database;

    public function __construct($_database) {
        $this->database = $_database;
    }

    public function Check($token, &$email = "") {
        if (is_null($token) || !is_string($token))
            return false;

        $token = (string)$token;
        $password = NULL;
        $email = NULL;

        $this->_extract($token, $email, $password);

        if (is_null($email) || is_null($password))
            return false;

        return $this->database->CheckUserExists($email, $password);
    }

    private function _extract($token, &$email, &$password) {
        if (is_null($token))
            return;

        $plaintext = openssl_decrypt($token, cipherMethod, key, $options = 0, iv);

        $i = 0;
        $emailEndIndex = -1;
        $passwordEndIndex = -1;
        for($i; $i < strlen($plaintext); $i++) {
            if ($plaintext[$i] === ':') {
                if ($emailEndIndex === -1)
                    $emailEndIndex = $i;
                else {
                    $passwordEndIndex = $i;
                    break;
                }
            }
        }

        if ($passwordEndIndex === -1 || $emailEndIndex === -1)
            return;

        $email = substr($plaintext, 0, $emailEndIndex);
        $password = substr($plaintext, $emailEndIndex + 1, $passwordEndIndex - $emailEndIndex - 1);
    }
};
?>