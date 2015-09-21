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

    public function getGame($id)
    {
        return $this->getById("game", $id);
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

    public function handleAdminPost()
    {
        if(!isset($_POST['action'])) {
            return;
        }
        switch($_POST['action']) {
        case 'editGame':
            $sql = sprintf("SELECT * FROM game WHERE `key` = '%s' AND id != %d", 
                $this->sanitize($_POST['key']),
                $_POST['id']);
            $game = $this->getRow($sql);
            if($game) {
                return -1;
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
                return -1;
            }
            $game = new mysqlObject("game");
            $values = array(
                "key" => $this->sanitize($_POST['key']),
                "name" => $this->sanitize($_POST['name']));
            $game->insert($values);
            return game::CHANGE_MENU;
        break;
        default:
            return;
        }
    }
}
