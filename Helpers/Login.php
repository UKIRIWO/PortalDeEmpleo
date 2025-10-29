<?php
include_once "Session.php";


class Login {


public static function estaLogeado() {
    Session::abrirsesion(); 
    return isset($_SESSION['user']);
}



public static function logout() {
    unset($_SESSION['user']);
    Session::cerrarsesion();        
}

public static function login($user){
    Session::abrirsesion();
    $_SESSION['user']=$user;
}


}