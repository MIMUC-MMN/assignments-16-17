<?php
session_start();

if(isset($_POST['reset'])){ //when reset then load a new game
  loadNewGame();
}

if(!isset($_SESSION['secretcode'])) { //generate a secret code for the session
  $letters = range('A', 'G'); //used to generate our code
  shuffle($letters);
  $_SESSION['secretcode'] = array_slice($letters, 0, 4); //our 4 letters secret code
}

if (!isset($_SESSION['guesses'])) $_SESSION['guesses'] = []; //our guess history for the session

if(isset($_POST['letters'], $_POST['submit'])) {
  $guessed_code = array_map("strtoupper", $_POST['letters']); //to make lower capped input valid

  $has_guess_invalid_input = array_reduce($guessed_code, function ($is_in_range, $letter){ //only allow letter A - G
    return $is_in_range || !in_array($letter, range('A', 'G')); //using boolean algebra to check
  });

  $has_guess_duplicates = (count($guessed_code) !== count(array_unique($guessed_code)));

  if(!$has_guess_invalid_input && !$has_guess_duplicates) { //sanity check before game logic
    $colors = [];//to color our circles depending on our guessed code
    for ($i = 0; $i < 4; $i++) { //game logic
      if ($guessed_code[$i] === $_SESSION['secretcode'][$i]) $colors[] = "red";
      else if (in_array($guessed_code[$i], $_SESSION['secretcode'])) $colors[] = "black";
      else $colors[] = "white";
    }
    sort($colors); //so the colors will be displayed in the order as the task2 requires

    $_SESSION['guesses'][] = array('guessed_code' => $guessed_code, 'colors' => $colors); //set our guess session var
  }
}

/* creating some named variable to use in html template */
if(!isset($has_guess_duplicates, $has_guess_invalid_input))$has_guess_duplicates = $has_guess_invalid_input = false;
$attempts = count($_SESSION['guesses']);
$has_tried_10_times = $attempts>=10;
$remain = 10-$attempts;
$has_won = (end($_SESSION['guesses'])['guessed_code'] === $_SESSION['secretcode']);
$has_game_end = ($has_tried_10_times || $has_won);
$secretcode = implode(" ", $_SESSION['secretcode']);

$players = isset($_COOKIE['players']) ? json_decode($_COOKIE['players'], true) : []; //read players from cookie or init if not in cookie
if(isset($_POST['playername'], $_POST['save']) && $has_won){ //when player wins, name can be entered and saved on leaderboard
  $players[] = array("name" => $_POST['playername'], "attempts" => $attempts, "date" => date('Y-m-d H:i:s'));
  setcookie('players', json_encode($players));
  loadNewGame();
}

function loadNewGame() {
  session_unset();
  session_destroy();
  header("Refresh:0"); //reload to get a new secret code and to init other needed variables
}
function print_message_if($condition, $message, $style){ //print html only if condition is met
  return $condition ? "<p style='$style'>$message</p>" : '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MMN - Assignment 2 - Task 2 - Codebreaker</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootswatch/3.3.7/journal/bootstrap.min.css">
  <style>
    * {color: black}
    .input-guess-letter {height:2.5em; width: 2.5em}
    .guessed-char, .circle {
      font-family: "Andale Mono", AndaleMono, monospace;
      font-size: 25px;
      margin: 2px;
      color: black;
    }
  </style>
</head>
<body class="container" style="background-color: wheat">
<div class="col-sm-6 col-sm-offset-3">
  <h1>Codebreaker</h1>

  <h3 style="margin-top: 50px">Guess History</h3>

  <?php foreach($_SESSION['guesses'] as $guess): ?>
    <div class="row">
      <div class="col-xs-6">
        <?php foreach($guess['guessed_code'] as $letter): ?>
          <span class="guessed-char"><?=$letter?></span>
        <?php endforeach; ?>
      </div>
      <div class="col-xs-6 pull-right">
        <?php foreach($guess['colors'] as $color): ?>
          <span class="circle" style="color:<?=$color?>">&#11044;</span>
        <?php endforeach; ?>
      </div>
    </div>
  <?php endforeach; ?>

  <?=print_message_if($has_guess_invalid_input || $has_guess_duplicates, 'Wrong input, please guess 4 unique letters from A to G!', 'color: red; font-size: 1.2em')?>
  <?=print_message_if(!$has_game_end, "You have $remain attempts left!", 'color: blue; font-size: 1.2em')?>
  <?=print_message_if($has_tried_10_times && !$has_won, 'Game Over! Try again!', "color: red; font-size: 1.4em")?>
  <?=print_message_if($has_won, 'Congratulations, you have won!', "color: green; font-size: 1.4em")?>
  <?=print_message_if($has_game_end, "The Code was: $secretcode", "color: blue; font-size: 1.2em")?>

  <form method="post" style="margin-top: 20px">
    <input maxlength="1" class="input-guess-letter" name="letters[]">
    <input maxlength="1" class="input-guess-letter" name="letters[]">
    <input maxlength="1" class="input-guess-letter" name="letters[]">
    <input maxlength="1" class="input-guess-letter" name="letters[]">
    <button class="btn btn-success" name="submit" type="submit" <?=$has_game_end ? "disabled" : ""?>>Check</button>
    <button class="btn btn-danger btn-block" name="reset" type="submit" style="margin-top: 10px">New Game</button>
  </form>

  <?=print_message_if($has_won, "Enter your name below to be on the leaderboard!", "color: green; font-size: 1.5em;")?>

  <h3>Leaderboard</h3>
  <form method="post" style="margin-top: 20px">
    <input name="playername" style="height:2.5em;">
    <button class="btn btn-success" name="save" type="submit" <?=$has_won ? "" : "disabled"?>>Save Name</button>
  </form>
  <table class="table table-condensed">
    <tr><th>Name</th><th>Guesses</th><th>Date</th></tr>
    <?php foreach($players as $player): ?>
      <tr><td><?=$player['name']?></td><td><?=$player['attempts']?></td><td><?=$player['date']?></td></tr>
    <?php endforeach; ?>
  </table>

  <h3>How to Play</h3>
  <ul>
    <li>Guess 4 unique letters of A, B, C, D, E, F, G</li>
    <li>You have ten attempts max.</li>
    <li>Red Dots indicate correct letters are at the correct position.</li>
    <li>Black Dots indicate letters are in the secretcode but at a false position.</li>
    <li>White Dots indicate letters are not contained in the code at all.</li>
  </ul>
  <script>console.log('<?=$secretcode?>')</script> <!--debug-->
</div>
</body>
</html>