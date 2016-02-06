<?php

/**
 * This class describes the menu Model object, used to control menu objects
 *
 * @author     Emiel Suilen
 * @copyright  Derelict Studios
 * @category   objects
 * @package    worldmap
 * @subpackage mvc
 */
class menu extends model
{
    /**
     * Construct a menu object, and handle it's printing
     */
    public function __construct()
    {
        $this->controller = new menuController("menu");
        $this->view       = new menuView();
    }

    /**
     * Returns html chunk for the desired menu
     *
     * @param int           $game       Game to get the menu for
     * @param string        $gameName   Key to create the correct link
     * @param array         $games 
     *
     * @return \htmlChunk           htmlChunk with the menu
     */
    public function returnMenu($game, $gameName, $games)
    {
        $menuItems = $this->controller->getMenuForGame($game);
        return $this->view->createHtmlMenu($menuItems, $gameName, $game, $games);
    }
}
