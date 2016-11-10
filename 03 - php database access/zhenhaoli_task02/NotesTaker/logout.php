<?php

require_once './Utils.php';

Utils::start_session_onlyif_no_session();

if(isset($_SESSION['user'])){ //user is logged in
  unset($_SESSION['user']);
  $_SESSION['logged_out_msg'] = 'Sucessfully logged out!';
}
Utils::redirect('./login.php');