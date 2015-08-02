<?php

/**
 * This class describes the UsersController object, used to provide DB access to the Users model
 *
 * @author     Emiel Suilen
 * @copyright  Derelict Studios
 * @category   objects
 * @package    worldmap
 * @subpackage mvc
 */
class usersController extends controller
{
    /**
     * Verify if a session has the correct details
     *
     * @return array    Array with user details
     */
    public function checkUserSession()
    {
        $userId     = $_SESSION['userId'];
        $userSecret = $_SESSION['userSecret'];

        $user = array();

        $sql = sprintf("SELECT id, salt, username FROM users WHERE id = %d", $userId);
        $userDetails = $this->getRow($sql);
        if(!$userDetails) {
            $user['userId'] = users::NO_USER_NO_LOGIN;
            return $user;
        }
        $secret = $this->hashSessionSecret(
            $userDetails['id'], 
            $userDetails['salt'], 
            $userDetails['username']
        );
        if($secret != $userSecret) {
            $user['userId'] = users::NO_USER_NO_LOGIN;
            return $user;
        }
        $user['userId'] = $userId;
        $user['username'] = $userDetails['username'];
        return $user;
    }

    /**
     * Verify if the supplied username and password are the right login combination
     *
     * @param string    $username   The supplied username
     * @param string    $password   The supplied password
     * @return array                Array with user details
     */
    public function verifyLogin($username, $password)
    {
        $user = array();

        $sql = sprintf("SELECT id, salt, password, username FROM users WHERE username = '%s'", $this->sanitize($username));
        $userDetails = $this->getRow($sql);
        if(!$userDetails) {
            $user['userId'] = users::NO_USER_INCORRECT_LOGIN;
            return $user;
        }
        $passwordHash = $this->hashPlainTextToPassword($password, $userDetails['salt']);
        if($passwordHash != $userDetails['password']) {
            $user['userId'] = users::NO_USER_INCORRECT_LOGIN;
            return $user;
        }
        $_SESSION['userId'] = $userDetails['id'];
        $_SESSION['userSecret'] = $this->hashSessionSecret(
            $userDetails['id'], 
            $userDetails['salt'], 
            $userDetails['username']
        );
        $user['userId'] = $userDetails['id'];
        $user['username'] = $userdetails['username'];
        return $user;
    }

    /**
     * Turn a supplied password and salt into a password hash
     *
     * @param string    $password   The supplied password
     * @param string    $salt       The supplied salt
     * @return string               The hashed result
     */
    public function hashPlainTextToPassword($password, $salt)
    {
        return hash("sha512",
            hash("sha512", $password) .
            hash("sha512", $salt)
        );
    }

    /**
     * Generate a secret for the session, using the userId, the salt,
     * and the username
     *
     * @param int       $id         The userid
     * @param string    $salt       The user's salt
     * @param string    $username   The username
     * @return string               The hashed result
     */
    public function hashSessionSecret($id, $salt, $username)
    {
        return hash(
            "sha512",
            hash("sha512", $id) .
            hash("sha512", $salt) .
            hash("sha512", $username)
        );
    }

    /**
     * Generates a salt, based on a const length defined in this class
     *
     * @return string   A randomly generated 64-byte salt
     */
    public function generateSalt()
    {
        $bytes = openssl_random_pseudo_bytes(users::SALT_LENGTH);
        $hex   = bin2hex($bytes);
        return $hex;
    }

}
