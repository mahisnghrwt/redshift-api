<?php
require_once('database.php');
require_once(__DIR__ . '/__config.php');


class Authenticator {
    private $database;

    public function __construct($_database) {
        $this->database = $_database;
    }

    public function Check($token) {
        if ($token == NULL) return FALSE;
        //Not authenticating, this must be removed.
        $isValid = true;
        $email = $this->_extract($token);
        return $this->database->CheckUserExists($email);
    }

    private function _extract($token) {
        //Note: Perform a regex operation on the token

        //$token = openssl_encrypt("mahi@gmail.com!", cipherMethod, key, $options = 0, iv);
        $plaintext = openssl_decrypt($token, cipherMethod, key, $options = 0, iv);

        $i = 0;
        for($i; $i < strlen($plaintext); $i++) {
            if ($plaintext[$i] === ':') {
                break;
            }
        }
        return substr($plaintext, 0, $i);
    }
};
?>