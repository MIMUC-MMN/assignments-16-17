<?php

require_once './dao/UserDAO.php';
require_once './utils/Utils.php';

class AuthBO
{

  private $userDAO = null;

  public static function Instance($userDAO)
  {
    static $inst = null;
    if ($inst === null) {
      $inst = new AuthBO($userDAO);
    }
    return $inst;
  }

  private function __construct($userDAO)
  {
    $this->userDAO = $userDAO;
  }

  function is_user_logged_in(){
    return isset($_SESSION['user']) && $_SESSION['user'];
  }

  function get_user(){
    if($this->is_user_logged_in()) {
      return $_SESSION['user'];
    }
    return null;
  }

  function login(&$msg, &$err){
    $name = $this->userDAO->sanitize_input($_POST['username']);
    $password = $_POST['password'];
    $user = $this->userDAO->find_user_by_name($name);
    if(!isset($user)) {
      $err = 'Username does not exist, please try again or register!';
    } else if(is_array($user)){
      if(password_verify($password, $user['password'])){
        $msg = 'Successfully logged in!';
        $_SESSION['user'] = $user;
        Utils::redirect('./index.php');
      } else {
        $err = 'Wrong password, please try again!';
      }
    } else {
      $err = $user;
    }
  }
}