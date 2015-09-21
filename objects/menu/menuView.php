<?php

/**
 * This class describes the Menu View object, used to provide HTML output capabilities to the Menu Model
 *
 * @author     Emiel Suilen
 * @copyright  Derelict Studios
 * @category   objects
 * @package    worldmap
 * @subpackage mvc
 */
class menuView extends view
{

    /**
     * Create html chunks for the menu
     *
     * @param   array       array with menu items
     *
     * @return  \htmlChunk  htmlChunk with the menu
     */
    public function createHtmlMenu($menuItems, $gameName, $gameId, $games)
    {
        $baseUrl = htmlChunk::generateBaseUrl();
        $menu = array(array());
        $gameMenu = gameView::returnLinkScreen($games, $gameId);
        $menu[0][] = $gameMenu['select'];

        foreach($menuItems as $item) {
            $link = $baseUrl . $gameName . "/menu/" . $item['key'];
            $menu[0][] = htmlChunk::generateLink($link, $item['name']);
        }
        $return = array('javascript' => $gameMenu['javascript']);
        $return['menu'] = htmlChunk::generateTableFromArray($menu);
        return $return;
    }
}
