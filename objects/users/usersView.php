<?php

/**
 * This class describes the Users View object, used to provide HTML output capabilities to the Users Model
 *
 * @author     Emiel Suilen
 * @copyright  Derelict Studios
 * @category   objects
 * @package    worldmap
 * @subpackage mvc
 */
class usersView extends view
{

    public function printLoginScreen($message)
    {
        $html = new html();
        $html->head->setTitle("User login");

        $loginForm = htmlChunk::generateForm("index.php", "login", "login");
        $usernameField = htmlChunk::generateInput("text", "username", "username");
        $passwordField = htmlChunk::generateInput("password", "password", "password");
        $loginActionField = htmlChunk::generateInput("hidden", "action", "action", users::ACTION_LOGIN);
        $buttonField = htmlChunk::generateInput("submit", "login", "login", "Login");

        $preTable = array(
            array(
                "Username:",
                $usernameField
            ),
            array(
                "Password:",
                $passwordField
            ),
            array(
                $loginActionField,
                $buttonField
            )
        );
        $table = htmlChunk::generateTableFromArray($preTable);
        $loginForm->addHtml($table);
        $html->addHtml($loginForm);


        $html->render();
        echo $html->getHtml();
    }

}
