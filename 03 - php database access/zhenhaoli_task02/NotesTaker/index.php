<?php

require_once './NoteDAO.php';
require_once './Utils.php';

Utils::start_session_onlyif_no_session();

if(isset($_SESSION['user'])) { //user is logged in
  $userid = $_SESSION['user']['id'];
  $noteDAO = new NoteDAO();
  if (isset($_POST['title'], $_POST['content'])) {
    $title = $noteDAO->sanitize_input($_POST['title']);
    $content = $noteDAO->sanitize_input($_POST['content']);

    //TODO get value from POST
    $msg = $noteDAO->add_note($title, $content, $userid);
  }
  $notes = array_filter($noteDAO->find_all_notes_by_userid($userid), function ($note){
    return !is_null($note);
  });
  var_dump($notes);
  if (!is_array($notes)) {
    $err = 'Adding note failed, please try again!';
    $notes = [];
  }
} else {
  Utils::redirect('./login.php');
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
    <ul id="nav-mobile" class="right">
      <li style="margin-right: 10px"><i class="material-icons left">account_circle</i><?=$_SESSION['user']['username']?></li>
      <li class="hide-on-med-and-down" ><a href="logout.php"><i class="material-icons left">exit_to_app</i>Logout</a></li>
    </ul>
  </div>
</nav>

<div class="row">
  <div class="col s12">

    <h3 class="pink-text">
      Notes
      <i id="addNewNote" class="medium material-icons right">add_circle</i>
    </h3>
    <div class="divider"></div>
  </div>
</div>


<div class="row" id="newNoteForm">
  <form method="post" class="col s12 m6 offset-m3">

    <div class="row">
      <div class="input-field col s12">
        <input id="name" name="title" type="text" class="validate">
        <label for="name">Title of your note</label>
      </div>
    </div>

    <div class="row">
      <div class="input-field col s12">
        <textarea id="textarea1" name="content" class="materialize-textarea"></textarea>
        <label for="textarea1">Content of your note</label>
      </div>
    </div>

    <button type="submit" class="waves-effect btn pink" id="saveNote">Save</button>
    <a class="waves-effect btn right pink" id="cancelNote">Cancel</a>
  </form>
</div>


<form action="#">
  <div class="row">

    <div class="col s12">
      <a class="waves-effect btn pink" style="width:100%" id="saveNote">Delete selected notes</a>
    </div>
  </div>

  <div class="row">

    <?php foreach ($notes as $note): ?>
      <div class="col s12 m4">
        <div class="card white darken-5 z-depth-3">
          <div class="card-content black-text">

        <span class="card-title">
          <?=$note['title'];?>
          <i class="material-icons right">delete</i>
          <i class="material-icons right">mode_edit</i>
        </span>

            <p><?=$note['text'];?></p>
          </div>
        </div>
      </div>
    <?php endforeach; ?>

  </div>
</form>

<script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.8/js/materialize.min.js"></script>
<script src="web/js/logic.js"></script>

</body>
</html>
