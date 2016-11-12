<?php

require_once './controller/AuthController.php';

$auth_controller = AuthController::Instance();
$auth_controller->logout();