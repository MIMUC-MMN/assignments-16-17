<?php
session_start();
require_once('DBHandler.php');
require_once('AuthHandler.php');
require_once('private.info.php');
$mydb = new DBHandler($host, $user, $password, $db);
$auth = new AuthHandler($mydb);
?>


<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="utf-8">
    <title>Note App</title>

    <!-- bootstrap styling -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>

<header class="header">

    <div class="navbar navbar-default navbar-fixed-top">
        <div class="appname">
            Notes
        </div>

        <?php

        // 1. login processing
        if (isset($_POST['username']) && isset($_POST['password'])) {
            if ($auth->login($_POST['username'], $_POST['password'])) {
                $name = $auth->getUserName();
                echo "<div class='message success'>
                        Welcome, $name!
                      </div>";
            } else {
                echo "<div class='message success'>
                        Login failed.
                      </div>";
            }
        }

        // 2. logout processing
        if (isset($_POST['logout'])) {
            $auth->logout();
        }
        // 3. add note
        if (isset($_POST['addNote'])) {
            if (isset($_POST['title']) && isset($_POST['content']) ) {
                if ($mydb->insertNote($_POST['title'], $_POST['content'], $auth->getUserID())) {
                    echo "<div class='message success'>
                           Note saved.
                        </div>";
                } else {
                    echo "<div class='message error'>
                            Note saved.
                        </div>";
                }
            }
        }
        // 4. delete note
        if (isset($_POST['deleteNotes']) && isset($_POST['delete'])) {
            $delete = array();
            foreach ($_POST['delete'] as $noteID) {
                $delete[] = $noteID;
            }
            if ($mydb->deleteNotes($delete)) {
                echo "<div class='message success'>
                            Notes has been deleted.
                        </div>";
            } else {
                echo "<div class='message error'>
                        Sorry, we have some trouble, please try again later.
                      </div>";
            }
        }

        // 5. login status
        if ($auth->isLogged()) {
            $username = $auth->getUserName();
            echo "
            <div class='account'>
                <form method='post' class='logout'>
                    <input type='submit' value='Logout' name='logout' />
                </form>
                <div class='username'>
                    $username
                </div>
            </div>
            ";
        }

        ?>
    </div>
</header>

<div id="app">

    <?php
        if (!$auth->isLogged()) {
    ?>
            <form class="login" method="post">
                <div class="form-group">
                    <input type="text" class="form-control" name="username" placeholder="Username">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="password" placeholder="Password">
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary form-control" name="submit" value="LOG IN" />
                </div>
                <div class="form-group">
                    <a href='registration.php'><div class="btn btn-default form-control">REGISTER</div></a>
                </div>
            </form>
    <?php
        } else {
    ?>
            <form class="note" method="post">

            <div class="note">
                <div class="form-group">
                    <input type="text" class="form-control" name="title" placeholder="Title of your note.">
                </div>
                <div class="form-group">
                    <textarea type="text" class="form-control" row="6" name="content" placeholder="Content of your note."></textarea>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary form-control" name="addNote" value="SAVE" />
                </div>
            </div>
            </form>

        <form method="post">
            <input type="submit" name="deleteNotes" value="Remote Selected Notes" class="deleteNotesButton btn-primary" />
            <div class="notes">
    <?php

            $notes = $mydb->getNotesByUserID($auth->getUserID());
            foreach ($notes as $note) {
                echo "<div class='card'>
                        <input type='checkbox' value='$note->id' name='delete[]'>
                        <div class='title'>$note->title</div>
                        <div class='content'>$note->content</div>
                      </div>";
            }
        }
    ?>
            </div>
        </form>
</div>
</body>
</html>
