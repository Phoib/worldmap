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

    /**
     * @const int No logged in user, no login details
     */
    const NO_USER_NO_LOGIN = -1;

    /**
     * @const int No logged in user, incorrect login details
     */
    const NO_USER_INCORRECT_LOGIN = -2;

    /**
     * @const string Userlogin action string
     */
    const ACTION_LOGIN = 'login';

    /**
     * Construct a users object
     */
    public function __construct()
    {
        $this->controller = new usersController("users");
        $this->view       = new usersView();
    }

    /**
     * Verify if there is a user active. There are 3 possible paths:
     * 1. There is a session active, with a user. This will be used, and user 
     *    details will be returned.
     * 2. There are login details provided. These will be checked. If correct,
     *    the user details will be returned. If not, a specific user, with negative 
     *    userID, will be returned.
     * 3. There is no user. A specific user, with negative userID, will be returned.
     *
     * @return array    Array with user details
     */
    public function verifySessionOrLogin()
    {
        if(isset($_SESSION['userId'])) {
            $user = $this->controller->checkUserSession();
        } elseif(isset($_POST['action']) 
            && $_POST['action'] == self::ACTION_LOGIN
            && isset($_POST['username'])
            && isset($_POST['password'])
        ) {
           $user = $this->controller->verifyLogin($_POST['username'], $_POST['password']);
        } else{
            $user = array(
                'userId' => self::NO_USER_NO_LOGIN
            );
        }
        return $user;
    }

    /**
     * Tell the view object to return a login screen, which gets echo-ed here.
     *
     * @param string $message   Optional message to print at the top
     */
    public function printLoginScreen($message = false)
    {
        echo $this->view->printLoginScreen($message);
    }
}
