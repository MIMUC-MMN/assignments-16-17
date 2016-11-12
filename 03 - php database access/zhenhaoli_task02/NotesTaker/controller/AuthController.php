<?php

require_once './dao/UserDAO.php';
require_once './bo/AuthBO.php';
require_once './utils/Utils.php';

class AuthController
{
  private $auth_bo = null;

  public static function Instance()
  {
    static $inst = null;
    if ($inst === null) {
      $inst = new AuthController();
    }
    return $inst;
  }

  private function __construct()
  {
    $this->auth_bo = AuthBO::Instance(new UserDAO());
    Utils::start_session_onlyif_no_session();
  }

  function register(&$msg){
    if(isset($_POST['username'], $_POST['password'], $_POST['rpassword'])) {
      if (Utils::empty_some($_POST['username'], $_POST['password'], $_POST['rpassword'])) {
        $msg = 'Some fields were empty, please make sure to fill all fields!';
      } else {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $rpassword = $_POST['rpassword'];
        $this->auth_bo->register($username, $password, $rpassword, $msg);
      }
    }
  }

  private function check_login_request(&$err) {
    if(isset($_POST['username'], $_POST['password'])){
      if (Utils::empty_some($_POST['username'], $_POST['password'])) {
        $err = 'Please enter all fields!!';
      } else {
        return true;
      }
    }
    return false;
  }

  function login(&$msg, &$err){
    if($this->check_login_request($err)) {
      $name = $_POST['username'];
      $password = $_POST['password'];
      $this->auth_bo->login($name, $password, $msg, $err);
    }
  }

  function get_auth_status_msg(){
    $msg = '';
    if(isset($_SESSION['user_registered_msg'])) {
      $msg =  $_SESSION['user_registered_msg'];
      unset($_SESSION['user_registered_msg']);
    }

    if(isset($_SESSION['logged_out_msg'])){
      $msg = $_SESSION['logged_out_msg'];
      unset($_SESSION['logged_out_msg']);
    }
    return $msg;
  }

  function logout(){
    if($this->auth_bo->is_user_logged_in()) {
      unset($_SESSION['user']);
      $_SESSION['logged_out_msg'] = 'Sucessfully logged out!';
    }
    Utils::redirect('./login.php');
  }

}