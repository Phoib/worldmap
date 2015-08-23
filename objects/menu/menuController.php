<?php

/**
 * This class describes the MenuController object, used to provide DB access to the Menu model
 *
 * @author     Emiel Suilen
 * @copyright  Derelict Studios
 * @category   objects
 * @package    worldmap
 * @subpackage mvc
 */
class menuController extends controller
{
    /**
     * Get the menu items from SQL
     *
     * @param   int     Game to fetch the menu for
     *
     * @return array    Array with the menu data
     */
    public function getMenuForGame($game)
    {
        $sql = sprintf("SELECT * FROM menu 
                        WHERE game = %d OR game = 0 
                        ORDER BY `order` ASC",
                        $game);
        return $this->getRows($sql);
    }
}
