<?php

require_once './dao/NoteDAO.php';
require_once './bo/AuthBO.php';
require_once './controller/NoteController.php';
require_once './utils/Utils.php';

Utils::start_session_onlyif_no_session();

$auth_bo = AuthBO::Instance(new UserDAO());

if($auth_bo->is_user_logged_in()) {

  $note_controller = NoteController::Instance(new NoteDAO());

  $userid = $auth_bo->get_user()['id'];

  $note_controller->add_new_note($userid, $msg, $err);
  $note_controller->update_note($msg, $err);
  $note_controller->delete_note($msg);
  $note_controller->delete_notes($msg);

  $notes = $note_controller->find_all_notes($userid, $err);

} else { // user not logged in
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
    <h4 class="green-text center-align"><?=isset($msg)? $msg : ''?></h4>
    <h4 class="red-text center-align"><?=isset($err)? $err : ''?></h4>
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

<div class="row">

  <div class="col s12">
    <button class="waves-effect btn pink" style="width:100%" id="delete-selected">Delete selected notes</button>
  </div>
</div>

<div class="row">

  <?php foreach ($notes as $note): ?>
    <input type="hidden" name="<?=$note['id']?>">
    <div class="col s12 m4">
      <div class="card white darken-5 z-depth-3" id="sel_<?=$note['id'];?>">
        <div class="card-content black-text">

            <span class="card-title">

              <?=$note['title'];?>

              <span class="delete pink" id="del_<?=$note['id'];?>"><i class="material-icons right">delete</i></span>

              <span class="edit pink" id="edit_<?=$note['id'];?>"> <i class="material-icons right">mode_edit</i></span>

            </span>

          <p><?=$note['text'];?></p>
        </div>
      </div>
    </div>
  <?php endforeach; ?>

</div>


<!-- Modal Structure -->
<div id="modal1" class="modal">
  <form method="post">
    <div class="modal-content">
      <h4>Edit Note</h4>

      <div class="row">
        <div class="input-field col s12">
          <input id="newtitle" name="newtitle" type="text" class="validate" placeholder="Enter title">
          <label for="newtitle">Title of your note</label>
        </div>
      </div>

      <div class="row">
        <div class="input-field col s12">
          <textarea id="newcontent" name="newcontent" class="materialize-textarea" placeholder="Enter content"></textarea>
          <label for="newcontent">Content of your note</label>
        </div>
      </div>
      <input type="hidden" name="noteid" id="editnote">
      <button type="submit" class="waves-effect btn pink" id="updateNote">Update</button>
      <a href="#!" class="modal-action modal-close waves-effect btn right pink">Cancel</a>
    </div>
  </form>
</div>

<form id="delete-note" method="post">
  <input type="hidden" name="noteid" id="deletenote">
  <input type="hidden" name="delete_note">
</form>

<form id="delete-selected-notes" method="post">
  <input type="hidden" name="noteids" id="deletenotes">
  <input type="hidden" name="delete_notes">
</form>

<script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.8/js/materialize.min.js"></script>
<script src="web/js/logic.js"></script>

</body>
</html>