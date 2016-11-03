<?php
session_start();

if(isset($_POST['reset'])){
  session_unset();
  session_destroy();
  header("Refresh:0"); //reload to get a new secret code and to init other needed variables
}

if(!isset($_SESSION['secretcode'])) { //generate a secret code for the session
  $letters = range('A', 'G'); //used to generate our code
  shuffle($letters);
  $_SESSION['secretcode'] = array_slice($letters, 0, 4); //our 4 letters secret code
}

if (!isset($_SESSION['guesses'])) $_SESSION['guesses'] = []; //our guess history

if(isset($_POST['letter1'], $_POST['letter2'],  $_POST['letter3'],  $_POST['letter4'], $_POST['submit'])) {
  $guessed_code = array_map("strtoupper", array($_POST['letter1'], $_POST['letter2'],  $_POST['letter3'],  $_POST['letter4']));

  $hasGuessInvalidInput = array_reduce($guessed_code, function ($next, $letter){ //only allow letter A - G
    return $next || !ctype_alpha($letter) || !in_array($letter, range('A', 'G'));
  });

  $hasGuessDuplicates = (count($guessed_code) !== count(array_unique($guessed_code)));

  if(!$hasGuessInvalidInput && !$hasGuessDuplicates) { //sanity check
    $num_black = 0; //increases when guessed letter is correct but position wrong
    $num_red = 0; //increases when guessed position and letter both are correct
    $num_white = 0; //increases when guessed wrongly
    for ($i = 0; $i < 4; $i++) {
      if ($guessed_code[$i] === $_SESSION['secretcode'][$i]) $num_red++;
      else if (in_array($guessed_code[$i], $_SESSION['secretcode'])) $num_black++;
      else $num_white++;
    }
    $colors = [];//to color our circles depending on our guessed code
    while ($num_black--) $colors[] = "black";
    while ($num_red--) $colors[] = "red";
    while ($num_white--) $colors[] = "white";

    $guess['guessed_code'] = $guessed_code;
    $guess['colors'] = $colors;

    $_SESSION['guesses'][] = $guess;
  }
}
if(!isset($hasGuessDuplicates, $hasGuessInvalidInput))$hasGuessDuplicates = $hasGuessInvalidInput = false;
$attempts = count($_SESSION['guesses']);
$hasTried10times = $attempts>=10;
$hasWon = (end($_SESSION['guesses'])['guessed_code'] === $_SESSION['secretcode']);
$hasGameEnd = ($hasTried10times || $hasWon);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MMN - Assignment 2 - Task 2 - Codebreaker</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootswatch/3.3.7/journal/bootstrap.min.css">
  <style>
    *{color: black}
    .guessed-char, .circle {
      font-family: "Andale Mono", AndaleMono, monospace;
      font-size: 25px;
      margin-left: 10px;
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
      <div class="col-xs-6">
        <?php foreach($guess['colors'] as $color): ?>
          <span class="circle" style="color:<?=$color?>">&#11044;</span>
        <?php endforeach; ?>
      </div>
    </div>
  <?php endforeach; ?>

  <?php if($hasGuessInvalidInput || $hasGuessDuplicates): ?>
    <p style="color: red; font-size: 1.2em">Wrong input, please guess 4 unique letters from A to G!</p>
  <?php endif;?>

  <?php if(!$hasGameEnd): ?>
    <p style="color: blue; font-size: 1.2em">You have <?=10-$attempts?> attempts left!</p>
  <?php endif;?>

  <?php if($hasTried10times): ?>
    <p style="color: red; font-size: 1.4em">Game Over! Try again!</p>
  <?php endif;?>

  <?php if($hasWon): ?>
    <p style="color: green; font-size: 1.4em">Congratulations, you have won!</p>
  <?php endif;?>

  <?php if($hasGameEnd): ?>
    <p style="color: blue; font-size: 1.2em">The Code was: <?=join("&nbsp;", $_SESSION['secretcode'])?></p>
  <?php endif;?>

  <form method="post" style="margin-top: 20px">
    <input maxlength="1" style='height:2.5em; width: 2.5em' name="letter1">
    <input maxlength="1" style='height:2.5em; width: 2.5em' name="letter2">
    <input maxlength="1" style='height:2.5em; width: 2.5em' name="letter3">
    <input maxlength="1" style='height:2.5em; width: 2.5em' name="letter4">
    <button class="btn btn-success" name="submit" type="submit" <?=$hasGameEnd ? "disabled" : ""?>>Check</button>
    <button class="btn btn-danger btn-block" name="reset" type="submit" style="margin-top: 10px">New Game</button>
  </form>
</div>
</body>
</html>