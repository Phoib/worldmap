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

    /**
     * Replaces the view with a new view object
     *
     * @param \view $view   Viewobject
     */
    public function setView($view)
    {
        $this->view = $view;
    }

    /**
     * Replaces the controller with a new controller object
     *
     * @param \controller   $controller ControllerObject
     */
    public function setController($controller)
    {
        $this->controller = $controller;
    }
}
