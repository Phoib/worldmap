<?php

session_start();

require_once("include.php");

$users = new users();

$user = $users->verifySessionOrLogin();

switch($user['userId']) {
case users::NO_USER_NO_LOGIN:
    $users->printLoginScreen();
    exit(0);
    break;
case users::NO_USER_INCORRECT_LOGIN:
    $users->printLoginScreen("Wrong login details supplied!");
    exit(0);
    break;
}

$game = new game();

echo $game->returnHtml();
