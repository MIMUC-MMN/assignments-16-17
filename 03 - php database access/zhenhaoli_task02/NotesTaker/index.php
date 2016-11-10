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
      <li style="margin-right: 10px"><i class="material-icons left">account_circle</i>Wildbery</li>
      <li class="hide-on-med-and-down" ><a href="#"><i class="material-icons left">exit_to_app</i>Logout</a></li>
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
  <form class="col s12 m6 offset-m3">

    <div class="row">
      <div class="input-field col s12">
        <input id="name" type="text" class="validate">
        <label for="name">Title of your note</label>
      </div>
    </div>

    <div class="row">
      <div class="input-field col s12">
        <textarea id="textarea1" class="materialize-textarea"></textarea>
        <label for="textarea1">Content of your note</label>
      </div>
    </div>

    <a class="waves-effect btn pink" id="saveNote">Save</a>
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

    <div class="col s12 m4">
      <div class="card white darken-5 z-depth-3">
        <div class="card-content black-text">

        <span class="card-title">
          My first Note
          <i class="material-icons right">delete</i>
          <i class="material-icons right">mode_edit</i>
        </span>

          <p>Is very nice</p>
        </div>
      </div>
    </div>

    <div class="col s12 m4">
      <div class="card white darken-5 z-depth-3">
        <div class="card-content black-text">

        <span class="card-title">
          My first Note
                     <i class="material-icons right">delete</i>
          <i class="material-icons right">mode_edit</i>
        </span>

          <p>Is very nice</p>
        </div>
      </div>
    </div>

    <div class="col s12 m4">
      <div class="card white darken-5 z-depth-3">
        <div class="card-content black-text">

        <span class="card-title">
          My first Note
                     <i class="material-icons right">delete</i>
          <i class="material-icons right">mode_edit</i>

        </span>

          <p>Is very nice</p>
        </div>
      </div>
    </div>

  </div>
</form>

<script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.8/js/materialize.min.js"></script>
<script src="web/js/logic.js"></script>

</body>
</html>