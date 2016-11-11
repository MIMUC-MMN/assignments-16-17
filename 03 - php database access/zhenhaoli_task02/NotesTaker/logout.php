<?php

require_once './bo/AuthBO.php';
require_once './utils/Utils.php';

Utils::start_session_onlyif_no_session();

$auth_bo = AuthBO::Instance(new UserDAO());

if($auth_bo->is_user_logged_in()) {
  unset($_SESSION['user']);
  $_SESSION['logged_out_msg'] = 'Sucessfully logged out!';
}
Utils::redirect('./login.php');