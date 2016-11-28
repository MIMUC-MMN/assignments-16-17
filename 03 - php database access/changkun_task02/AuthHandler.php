<?php
require_once('DBHandler.php');
define('authSessionKey','isAuthenticated');
class AuthHandler{
    var $dbHandler;
    function __construct($dbHandler){
        $this->dbHandler = $dbHandler;
    }
    function register($userName,$password){
        $hash = password_hash($password,PASSWORD_DEFAULT);
        return $this->dbHandler->insertUser($userName,$hash);
    }
    function login($userName,$password){
        $userInfo = $this->dbHandler->getUserByUserName($userName);
        $passwordVerificationResult = password_verify($password,$userInfo['hash']);
        if($passwordVerificationResult){
            $_SESSION[authSessionKey] = true;
            $_SESSION['userName'] = $userInfo['name'];
            $_SESSION['userID'] = $userInfo['id'];
            return true;
        }
        return false;
    }
    function logout(){
        unset($_SESSION[authSessionKey]);
    }
    function isLogged(){
        return isset($_SESSION[authSessionKey]) && $_SESSION[authSessionKey];
    }
    function getUserName(){
        return isset($_SESSION['userName']) ? $_SESSION['userName'] : "";
    }
    function getUserID(){
        return isset($_SESSION['userID']) ? $_SESSION['userID'] : -1;
    }
}
?>
