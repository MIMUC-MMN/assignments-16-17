<?php
  // start the session.
  session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Codebreaker</title>
    <meta name="author" content="Marius Pollin">
    <link rel="stylesheet" href="codebreaker.css">
</head>
<body>
  <h2>Codebreaker - Break my Code, not your neck</h2>

  <p>Choose between letters {A, B, C, D, E, F, G}.<br/>
    Each letter is used at most once.<br/>
    You have ten attempts.<br/>
    Red Dots indicate correct letters at the correct position.<br/>
    Black Dots indicate letters contained in the code but at a false position.<br/>
    White Dots indicate letters not contained in the code at all.
  </p>

  <?php

  $alphabet = ["A","B","C","D","E","F","G"];
  $maximumAttempts = 10;

  /**
   * Use this function to lazily instantiate a session-variable if it's not there yet.
   *
   * @param $key {string} session-variable key, preferably a string
   * @param $value {*} the value to initialize the variable with, e.g. array(), 1, ''
   */
  function lazyInitSessionVariable($key, $value) {
      if (!isset($_SESSION[$key])) {
          $_SESSION[$key] = $value;
      }
  }

  // restart the game.
  if (isset($_POST['restart'])) {
      // destroy the session
      session_destroy();
      $_SESSION = array();
  }

  // creates the random code if necessary.
  if (!isset($_SESSION['code'])) {
    $_SESSION['code'] = array();
    $randoms = array_rand($alphabet, 4);
    foreach ($randoms as $random) {
      array_push($_SESSION['code'], $alphabet[$random]);
    }
  }

  // debugging ;-)
  //echo "<p>Code: " . implode($_SESSION['code']) . "</p>";

  // init the session variable holding all the attempts of the current user.
  lazyInitSessionVariable('attempts', array());

  // check if all the keys of the post are set.
  if (isset($_POST['check']) &&
      isset($_POST['letter1']) &&
      isset($_POST['letter2']) &&
      isset($_POST['letter3']) &&
      isset($_POST['letter4'])) {

    // concatenate all values to a string and save this string in an array.
    $codeAttempt = strtoupper($_POST['letter1']) . strtoupper($_POST['letter2']) . strtoupper($_POST['letter3']) . strtoupper($_POST['letter4']);
    $codeAttempt = str_split($codeAttempt);
    array_push($_SESSION['attempts'], $codeAttempt);
  }

  echo '<section>';

  // display an area for the finished attempts.
  echo '<div id="output">';

  // dynamically create the html for the finished attempts.
  $numberOfAttempts = count($_SESSION['attempts']);

  if ($numberOfAttempts > 0) {
    $correct = false;

    // iterate over all attempts of the current session.
    foreach ($_SESSION['attempts'] as $attempt) {
      echo '<p>';
      foreach ($attempt as $attemptLetter) {
        echo "$attemptLetter" . str_repeat('&nbsp;', 5);
      }

      // this var holds the number of correct letters for each attempt. later on, it is checked vs. the length of the code. actually poor coding :-D
      $correctLetters = 0;
      for ($i=0; $i < count($_SESSION['code']); $i++) {

        if ($_SESSION['code'][$i] == $attempt[$i]) {
          // letter is the same.
          $correctLetters += 1;
          // generate a red circle. (see css-file)
          echo '<div id="circle" class="red"></div>';

        } else {
          // letter is not the same.

          if (in_array($attempt[$i], $_SESSION['code'])) {
            // letter in the code, but at wrong position.
            //generate a black circle. (see css-file)
            echo '<div id="circle" class="black"></div>';

          } else {
            // letter not in the code.
            // generate a white circle. (see css-file)
            echo '<div id="circle" class="white"></div>';
          }
        }
      }

      //more debugging
      //echo "correctLetters: $correctLetters" . "<br/>";

      $correct = $correctLetters == 4;
      echo '</p>';
    }

    // auxiliary function for destroying the current session.
    function destroySession() {
      session_destroy();
      $_SESSION = array();
    }

    if ($correct) {
      // player has won.
      echo '<p>Congrats, you won! Try again with new code? Hit "restart" or begin entering new letters!';
      echo "<p>Code was: " . implode($_SESSION['code']) . "</p>";
      // destroy the session
      destroySession();

    } else if ($numberOfAttempts == 10) {
      // player has lost.
      echo '<p>Oh no, you lost! Try again with new code? Hit "restart" or begin entering new letters!';
      echo "<p>Code was: " . implode($_SESSION['code']) . "</p>";
      // destroy the session
      destroySession();
    }
  }
  ?>
    </div>

    <div id="input">
      <form method="POST" action="index.php">
        <input type="text" size="5" maxlength="1" minlength="1" name="letter1" autofocus />

        <input type="text" size="5" maxlength="1" minlength="1" name="letter2" />

        <input type="text" size="5" maxlength="1" minlength="1" name="letter3" />

        <input type="text" size="5" maxlength="1" minlength="1" name="letter4" />

        <button type="submit" name="check">Check</button>

        <button type="submit" name="restart">Restart</button>

      </form>
    </div>
  </section>

</body>
</html>
