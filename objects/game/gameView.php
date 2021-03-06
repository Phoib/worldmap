<?php

/**
 * This class describes the Game View object, used to provide HTML output capabilities to the Game Model
 *
 * @author     Emiel Suilen
 * @copyright  Derelict Studios
 * @category   objects
 * @package    worldmap
 * @subpackage mvc
 */
class gameView extends view
{

    //@var string
    private $gameKey = "";

    /**
     * Sets the gamekey
     *
     * @param string 
     */
    public function setGameKey($key)
    {
        $this->gameKey = $key;
    }

    /**
     * Prepare the view for the development screen
     *
     * @param  \htmlChunk   $testHtml   htmlChunk object with all the testoutput
     */
    public function generateDevelScreen($testHtml)
    {
        $this->setTitle("Worldmap tests");
        $this->addHtml($testHtml);
    }

    /**
     * Prepare the view for the admin screen
     */
    public function generateAdminScreen($counts)
    {
        $this->setTitle("Worldmap admin");
        $namedCounts = array(
            array(
                "Amount of games",
                $counts['game']
            ),
            array(
                "Amount of users",
                $counts['users']
            )
        );
        $table = htmlChunk::generateTableFromArray($namedCounts);
        $this->addHtml($table);
    }

    /**
     * Prepare the view for the game screen
     *
     * @param array $game   All the game information
     */
    public function generateGameScreen($game)
    {
        $this->setTitle($game['name']);
        $table = array();
        foreach($game as $key => $value) {
            $table[] = array($key, $value);
        }
        $table = htmlChunk::generateTableFromArray($table);
        $this->addHtml($table);
    }

    /**
     * Generate the links screen for all games
     *
     * @param array     $games      Array with all the game info
     * @param int       $ignoreId   ID to ignore
     */
    public function generateLinkScreen($games, $ignoreId, $return = false)
    {
        $baseUrl = htmlChunk::generateBaseUrl();
        $javascript = sprintf("
            function selectGame(obj) {
                var urlString = '%s';
                var selectedGame = obj.options[obj.selectedIndex];
                if (selectedGame != '') {
                    window.location = urlString + selectedGame.value;
                }
            }"
            , $baseUrl);

        if($return) {
            $return = array('javascript' => $javascript);
        } else{
            $this->setTitle("Worldmap links");
            $this->setJavascript($javascript);
        }

        $options = array("Select a game" => "");
        foreach($games as $game) {
            if($game['id'] != $ignoreId) {
                $options[$game['name']] = $game['key'];
            }
        }
        $select = htmlChunk::generateSelect("gameSelect", "gameSelect", $options, false, "selectGame(this)");
        if($return) {
            $return['select'] = $select;
            return $return;
        }
        $this->addHtml($select);
    }

    public function generateGameEditScreen($games)
    {
        $text = "Please select a game to edit";
        $baseUrl = htmlChunk::generateBaseUrl() . $this->gameKey . "/menu/game/";

        $newLink = htmlChunk::generateLink($baseUrl . "id/new", "New");
        $table = array(array($text, $newLink));
        foreach ($games as $game) {
            $url = $baseUrl . "id/" . $game['id'];
            $link = htmlChunk::generateLink($url, "Edit");
            $row = array($game['name'], $link);
            $table[] = $row;
        }
        $table = htmlChunk::generateTableFromArray($table);
        $this->addHtml($table);
    }

    public function generateUserEditScreen($users)
    {
        $text = "Please select an user to edit";
        $baseUrl = htmlChunk::generateBaseUrl() . $this->gameKey . "/menu/user/";

        $newLink = htmlChunk::generateLink($baseUrl . "id/new", "New");
        $table = array(array($text, $newLink));
        foreach ($users as $user) {
            $url = $baseUrl . "id/" . $user['id'];
            $link = htmlChunk::generateLink($url, "Edit");
            $row = array($user['username'], $link);
            $table[] = $row;
        }
        $table = htmlChunk::generateTableFromArray($table);
        $this->addHtml($table);
    }

    public function editUser($user, $permissions)
    {
        $action = "editUser";
        if($_GET['id'] == 'new') {
            $action = 'newUser';
        }
        $table = array();
        if (key_exists('warning', $user) && $user['warning'] == game::KEY_EXISTS) {
            $table[] = array(
                "Username",
                htmlChunk::generateInput("text", "username", "username", $user['username']),
                htmlChunk::generateBold("Username already exists!") 
            );
        } else{
            $table[] = array(
                "Username",
                htmlChunk::generateInput("text", "username", "username", $user['username'])
            );
        }
        $table[] = array(
            "Password",
            htmlChunk::generateInput("password", "password", "password")
        );
        $table[] = array(
            "Permission Level",
            htmlChunk::generateSelect("permission", "permission", $permissions, $user['permission'])
        );
        $table[] = array(
            htmlChunk::generateInput("submit", "submit", "submit", "Save"),
            htmlChunk::generateInput("submit", "cancel", "cancel", "Cancel"),
            htmlChunk::generateInput("hidden", "id", "id", $user['id']),
            htmlChunk::generateInput("hidden", "action", "action", $action),
        );
        $table = htmlChunk::generateTableFromArray($table);

        $baseUrl = htmlChunk::generateBaseUrl() . $this->gameKey . "/menu/user/";
        $form = htmlChunk::generateForm($baseUrl, $action, $action);
        $form->addHtml($table);
        $this->addHtml($form);
    }

    public function editGame($game, $permissions) 
    {
        $action = "editGame";
        if($_GET['id'] == 'new') {
            $action = 'newGame';
        }
        $table = array(
            array(
                "Name",
                htmlChunk::generateInput("text", "name", "name", $game['name'])
            )
        );
        if (key_exists('warning', $game) && $game['warning'] == game::KEY_EXISTS) {
            $table[] = array(
                "Key",
                htmlChunk::generateInput("text", "key", "key", $game['key']),
                htmlChunk::generateBold("Warning, key exists!") 
            );
        } else{
            $table[] = array(
                "Key",
                htmlChunk::generateInput("text", "key", "key", $game['key'])
            );
        }
        $table[] = array(
            "Required Permission",
            htmlChunk::generateSelect("permission", "permission", $permissions, $game['permission'])
        );
        $table[] = array(
            htmlChunk::generateInput("submit", "submit", "submit", "Save"),
            htmlChunk::generateInput("submit", "cancel", "cancel", "Cancel"),
            htmlChunk::generateInput("hidden", "id", "id", $game['id']),
            htmlChunk::generateInput("hidden", "action", "action", $action),
        );
        $table = htmlChunk::generateTableFromArray($table);

        $baseUrl = htmlChunk::generateBaseUrl() . $this->gameKey . "/menu/game/";
        $form = htmlChunk::generateForm($baseUrl, $action, $action);
        $form->addHtml($table);
        $this->addHtml($form);
    }

    public static function returnLinkScreen($games, $ignoreId)
    {
        return self::generateLinkScreen($games, $ignoreId, true);
    }
}
