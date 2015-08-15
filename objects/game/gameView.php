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
    public function generateAdminScreen()
    {
        $this->setTitle("Worldmap admin");
        $this->addHtml("Here will be admin functionality");
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
    public function generateLinkScreen($games, $ignoreId)
    {
        $baseUrl = htmlChunk::generateBaseUrl();
        $this->setTitle("Worldmap links");
        $table = array();
        $options = array();
        foreach($games as $game) {
            if($game['id'] != $ignoreId) {
                $url = $baseUrl . $game['key'];
                $table[] = array(htmlChunk::generateLink($url, $game['name']));
                $options[$game['name']] = $game['key'];
            }
        }
        $table = htmlChunk::generateTableFromArray($table);
        $this->addHtml($table);
        $select = htmlChunk::generateSelect("gameSelect", "gameSelect", $options);
        $this->addHtml($select);
    }
}
