<?php

/**
 * This class describes the GameController object, used to provide DB access to the Game model
 *
 * @author     Emiel Suilen
 * @copyright  Derelict Studios
 * @category   objects
 * @package    worldmap
 * @subpackage mvc
 */
class gameController extends controller
{

    /**
     * Determines the game, on the basis of the GET - game variable.
     *
     * @return array  $game   The game object
     */
    public function determineGame()
    {
        $gameName = "";
        if(isset($_GET['game'])) {
            $gameName = $_GET['game'];
        }
        $sql = sprintf("SELECT * FROM game WHERE `key` = '%s'", $this->sanitize($gameName));
        $game = $this->getRow($sql);
        if($game) {
            return $game;
        }
        return $this->getById("game", -1);
    }

    /**
     * Return a specific game by id
     *
     * @var int $id
     * @return array
     */
    public function getGame($id)
    {
        return $this->getById("game", $id);
    }

    /**
     * Return a specific user by id
     *
     * @var int $id
     * @return array
     */
    public function getUser($id)
    {
       return $this->getById("users", $id);
    }

    /**
     * Return all the games
     *
     * @return array
     */
    public function getAllGames()
    {
        return $this->getWholeTable("game");
    }

    /**
     * Return all the users
     *
     * @return array
     */
    public function getAllUsers()
    {
        return $this->getWholeTable("users");
    }

    public function handleAdminPost()
    {
        if(!isset($_POST['action'])) {
            return;
        }
        switch($_POST['action']) {
        case 'editGame':
            $sql = sprintf("SELECT * FROM game WHERE `key` = '%s' AND id != %d", 
                $this->sanitize($_POST['key']),
                $_POST['id']
            );
            $game = $this->getRow($sql);
            if($game) {
                return game::KEY_EXISTS;
            }
            $game = array(
                'key' => $_POST['key'],
                'name' => $_POST['name'],
                'id' => $_POST['id']
            );
            $success = $this->update($game, 'id');
            if($success) {
                return game::CHANGE_MENU;
            }
        break;
        case 'newGame':
            $sql = sprintf("SELECT * FROM game WHERE `key` = '%s'", 
                $this->sanitize($_POST['key']));
            $game = $this->getRow($sql);
            if($game) {
                return game::KEY_EXISTS;
            }
            $game = new mysqlObject("game");
            $values = array(
                "key" => $this->sanitize($_POST['key']),
                "name" => $this->sanitize($_POST['name']));
            $game->insert($values);
            return game::CHANGE_MENU;
        break;
        case 'editUser':
            $username = strtolower($this->sanitize($_POST['username']));
            $sql = sprintf("SELECT * FROM users WHERE `username` = '%s' AND id != %d", 
                $username,
                $_POST['id']
            );
            $user = $this->getRow($sql);
            if($user) {
                return game::KEY_EXISTS;
            }
            $user = new mysqlObject("users");
            $userController = new UsersController("users");
            if (empty($_POST['password'])) {
                $user = array(
                    "username" => $username,
                    "id" => $_POST['id']
                );
            } else{
                $salt = $userController->generateSalt();
                $user = array(
                    "username" => $username,
                    "salt" => $salt,
                    "password" => $userController->hashPlainTextToPassword($_POST['password'], $salt),
                    "id" => $_POST['id']
                );
            }
            return $userController->update($user, 'id');
        break;
        case 'newUser':
            $username = strtolower($this->sanitize($_POST['username']));
            $sql = sprintf("SELECT * FROM users WHERE `username` = '%s'", 
                $username);
            $user = $this->getRow($sql);
            if($user) {
                return game::KEY_EXISTS;
            }
            if (empty($_POST['password'])) {
                return game::EMPTY_PASSWORD;
            }
            $user = new mysqlObject("users");
            $userController = new UsersController("users");
            $salt = $userController->generateSalt();
            $values = array(
                "username" => $username,
                "salt" => $salt,
                "password" => $userController->hashPlainTextToPassword($_POST['password'], $salt)
            );
            return $user->insert($values);
        break;
        default:
            var_dump($_POST);
            return;
        }
    }
}
