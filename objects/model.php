<?php

/**
 * This class describes the Model object, used to steer objects
 *
 * @author     Emiel Suilen
 * @copyright  Derelict Studios
 * @category   objects
 * @package    worldmap
 * @subpackage mvc
 */
class model
{
    /**
     * var \controller  The controller to handle DB requests
     */
    protected $controller = NULL;

    /**
     * var \view        The view to handle html rendering
     */
    protected $view = NULL;

    public function __construct()
    {
        $this->controller = new controller();
        $this->view       = new view();
    }
}
