<?php
/* 1. get submiited vars
   2. insert into db
   3. if success redirect to login and message sucecesfull register
   4. if fail then register again
*/

require_once './UserDAO.php';
require_once './Utils.php';

if(isset($_POST['username'], $_POST['password'], $_POST['rpassword'])) {
  if (Utils::empty_some($_POST['username'], $_POST['password'], $_POST['rpassword'])) {
    $msg = 'Some fields were empty, please make sure to fill all fields!';
  } else if ($_POST['password'] === $_POST['rpassword']) {

    //db operations
    $userDAO = new UserDAO();

    $username = $userDAO->sanitize_input($_POST['username']);
    $password = $userDAO->sanitize_input($_POST['password']);

    $msg = $userDAO->add_user($username, $password);
    if(Utils::contains('Successfully', $msg)){
      include ("./login.php"); //TOASK: how to redirect better
      return;
    }


  } else {
    $msg = 'Password do not match, please try again!';
  }

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Notes Taker</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.8/css/materialize.min.css">
</head>
<body>

<nav>
  <div class="nav-wrapper pink">
    <a href="#" class="brand-logo">&nbsp;Notes</a>
  </div>
</nav>

<div class="row" style="margin-top: 10px">
  <form method="post" class="col s12 m4 offset-m4">
    <p class="red-text center-align"><?=isset($msg)? $msg : ''?></p>

    <div class="row">
      <div class="input-field col s12">
        <input id="name" name="username" type="text" class="validate" placeholder="Enter username">
        <label for="name">Username</label>
      </div>
    </div>

    <div class="row">
      <div class="input-field col s12">
        <input id="password" name="password" type="password" class="validate" placeholder="Enter password">
        <label for="password">Password</label>
      </div>
    </div>

    <div class="row">
      <div class="input-field col s12">
        <input id="password2" name="rpassword" type="password" class="validate" placeholder="Enter password again">
        <label for="password">Repeat Password</label>
      </div>
    </div>

    <button type="submit" class="waves-effect btn pink" id="saveNote" style="width: 100%">Register user</button>
  </form>
</div>


<script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.8/js/materialize.min.js"></script>
</body>
</html>