<?php

/**
 * This class describes the Users Model object, used to control Users
 *
 * @author     Emiel Suilen
 * @copyright  Derelict Studios
 * @category   objects
 * @package    worldmap
 * @subpackage mvc
 */
class users extends model
{
    /**
     * @const int SALT_LENGTH
     */
    const SALT_LENGTH = 32;

    public function __construct()
    {
        $this->controller = new usersController("users");
        $this->view       = new usersView();
    }


    public function verifyLogin($username, $password)
    {
        
    }

    public function hashPlainTextToPassword($password, $salt)
    {
        return hash("sha512",
            hash("sha512", $password) .
            hash("sha512", $salt)
        );
    }

    /**
     * Generates a salt, based on a const length defined in this class
     */
    public function generateSalt()
    {
        $bytes = openssl_random_pseudo_bytes(users::SALT_LENGTH);
        $hex   = bin2hex($bytes);
        return $hex;
    }
}
