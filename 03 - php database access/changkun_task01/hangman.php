<?php
session_start();
require_once('DBHandler.php');
require_once('private.info.php');
$mydb   = new DBHandler($host, $user, $password, $db);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hangman</title>
    <meta name="author" content="Created by Tobias Seitz & Modified by Changkun Ou">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="hangman.css">
</head>
<body>

<div id="container">
    <h2>Hangman - the game.</h2>
    <section id="output">
        <?php

        /**************************************************
         *                   new stuff                    *
         **************************************************/

        if (isset($_POST['save'])) {
            $result = $mydb->insertResult($_POST['player'], $_SESSION['secretWord'], $_SESSION['attempts']);
            $_SESSION = array();
        }
        function prepareWord() {
            $word = '';
            $content = file('en.txt');
            while (strlen($word) < 8) {
                $word = $content[rand(0, count($content)-1)];
            }
            return $word;
        }

        if (!isset($_SESSION['secretWord'])) {
            $_SESSION['secretWord'] = prepareWord();
        }
        $secretWord = $_SESSION['secretWord'];

        $wordLength = strlen($secretWord);
        $maximumAttempts = 12;

        /**************************************************
         *               new stuff end                    *
         **************************************************/

        /**
         * Use this function to lazily instantiate a session-variable if it's not there yet.
         *
         * @param $key {string} session-variable key, preferably a string
         * @param $value {*} the value to initialize the variable with, e.g. array(), 1, ''
         */
        function lazyInitSessionVariable($key, $value)
        {
            if (!isset($_SESSION[$key])) {
                $_SESSION[$key] = $value;
            }
        }

        // reset the guesses.
        if (isset($_POST['reset'])) {
            $_SESSION = array();
        }

        // actually initialize the session variables
        // valid and invalid attempts.
        lazyInitSessionVariable('hits', array());
        lazyInitSessionVariable('misses', array());

        // handle a valid guess.
        if (isset($_POST['guess']) && isset($_POST['letter'])) {
            $letter = strtoupper(substr($_POST['letter'], 0, 1));

            // determine if we should move the progress forward.
            if (stristr($secretWord, $letter)) { // true means: secretWord contains the letter.
                // save the letter in the 'hits' list;
                $_SESSION['hits'][$letter] = true;
            } else {
                // save the letter in the 'misses' list;
                $_SESSION['misses'][$letter] = true;
            }
        }

        // start the game progress at 1 (not 0).
        // but since the first miss should already advance the progress, we need to add +1;
        $progress = count($_SESSION['misses']) ? count($_SESSION['misses']) + 1 : 1;

        // if you want to make the game harder, you can start it at a later stage
        // by adding a handicap to the progress.
        $handicap = 0;
        $progress += $handicap;

        $imageFile = 'hangman';
        // determine which of the 12 hangman files we pick.
        // if the progress is below 10, we need to prefix a '0' to the number.
        $imageFile .= $progress < 10 ? "0{$progress}.png" : "$progress.png";

        // show the image:
        echo "<img src='{$imageFile}' alt='Hangman - Step $progress' class='hangman'/>";

        // reveal the letters inside the 'result' div.
        echo '<div class="result">';
        // if the progress is smaller than a predefined number of attempts ==> we can play on.
        if ($progress < $maximumAttempts) {

        /**************************************************
         *                   new stuff                    *
         **************************************************/

            $guessedCounter = 0;

            // we go through each letter of the secret word and see if it's a hit or a miss.
            for ($i = 0; $i < $wordLength; $i++) {
                // make sure we use the uppercase version of the letters.
                $charAtI = strtoupper(substr($secretWord, $i, 1));

                // case 1: the letter of the word is in the guess array --> reveal the letter
                if (isset($_SESSION['hits'][$charAtI])) {
                    echo $charAtI;
                    $guessedCounter++;


        /**************************************************
         *               new stuff end                    *
         **************************************************/


                } // case 2: the letter was not guessed yet --> show an underscore
                else {
                    // print an underscore for each character in the word.
                    echo '_' . ($i != $wordLength - 1 ? ' ' : '');
                }
            }

        /**************************************************
         *                   new stuff                    *
         **************************************************/
            function renderRank($db) {
                echo '<div class="rank"><div class="inner">
                <table class="table">
                  <tr>
                    <th>ID</th>
                    <th>User Name</th>
                    <th>Guessed Word</th>
                    <th>Attempts</th>
                    <th>Dates</th>
                  </tr>';

                $ranking = $db->fetchResults();
                for ($i=0; $i < sizeof($ranking); $i++) {
                    $id     = $ranking[$i]['id'];
                    $player = $ranking[$i]['player'];
                    $guessedword = $ranking[$i]['guessedword'];
                    $attempts    = $ranking[$i]['attempts'];
                    $date        = $ranking[$i]['dates'];

                    echo "<tr>
                      <th>$id</th>
                      <th>$player</th>
                      <th>$guessedword</th>
                      <th>$attempts</th>
                      <th>$date</th>
                      </tr>
                    ";
                }
                echo '</table>
                </div></div>';
            }


            if ($guessedCounter == $wordLength) {
                $_SESSION['attempts'] = $progress;
                echo '
                <div class="win">
                    <p class="text-center">You Won! Please input your name?</p>
                </div>
                <div class="win">
                <form class="form-inline" action="hangman.php" method="post">
                  <div class="form-group">
                    <label> Name </label>
                    <input type="text" class="form-control" name="player" placeholder="Your Name">
                  </div>
                  <button type="submit" name="save" class="btn btn-default">Save</button>
                </form>
                </div>
                ';
                renderRank($mydb);

            } else {
                // now, to be a little more usable, show which letters were already guessed;
                if (count($_SESSION['misses'])) { // only give feedback, if there misses.
                    echo '<div class="misses">Those letters are wrong: ';
                    foreach ($_SESSION['misses'] as $miss => $status) {
                        echo $miss . ' ';
                    }
                    echo '</div>';
                }
            }

        /**************************************************
         *               new stuff end                    *
         **************************************************/

        } else { // oh no, the user lost!
            echo "<div class=\"youlose\"><h3>Oh No!</h3><p>You lost. The solution was \"$secretWord\". </p></div>";
            session_destroy();
            $_SESSION = array();
        }

        echo '</div>'; # .result
        ?>
    </section>

    <?php
    if ($guessedCounter == $wordLength) {
        ;
    } else
    echo '
        <section id="formContainer">
        <form method="post" action="hangman.php">
            <input type="text" maxlength="1" minlength="1" name="letter"/>
            <button type="submit" name="guess">Guess</button>
            <button type="submit" name="reset">Reset</button>
        </form>
    </section>';

    ?>
</div>

</body>
</html>
