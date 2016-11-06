<?php
session_start();
ob_start();
class Codebreaker {
    function generate() {
        $input = array("A", "B", "C", "D", "E", "F", "G");
        $random_keys = array_rand($input, 4);
        $code = "";
        foreach ($random_keys  as $key) {
            $code .= $input[$key];
        }
        return $code;
    }
    function renderRank() {
        echo '<div class="rank"><div class="inner">
        <table class="table">
          <tr>
            <th>Ranking</th>
            <th>User Name</th>
            <th>Attempts</th>
          </tr>';
          $count = 1;
          for ($i=0; $i < 10; $i++) {
              if (isset($_COOKIE[(string)$i])) {
                  $name = $_COOKIE[(string)$i];
                  echo "<tr>
                    <th>$count</th>
                    <th>$name</th>
                    <th>$i</th></tr>
                  ";
                  $count++;
              }
          }
        echo '</table>
        </div></div>';
    }
    function check($input) {
        $input_array = str_split($input);
        $code_array  = str_split($_SESSION['code']);
        if ($input_array == $code_array) {

            echo '<p class="text-center">Congratulations! You Win!</p>';
            echo '<div class="app"><div class="button"><a href="index.php">
                <input class="btn btn-danger" value="Do you want Restart?"></a></div></div>';
            echo '<div class="app"><p class="text-center">Do you want input your name?</p></div>';
            echo '
            <div class="app">
            <form class="form-inline" action="index.php" method="post">
              <div class="form-group">
                <label> Name </label>
                <input type="text" class="form-control" name="userName" placeholder="Your Name">
              </div>
              <button type="submit" name="save" class="btn btn-default">Save</button>
            </form>
            </div>
            ';
            $this->renderRank();
            $_SESSION['finished'] = 'finished';
        } else {
            $results = array();
            $red = $black = $white = 0;
            for ($i=0; $i < 4; $i++) {
                if ($input_array[$i] == $code_array[$i]) {
                    $red++;
                } elseif (in_array($input_array[$i], $code_array)) {
                    $black++;
                } else {
                    $white++;
                }
            }
            $results = array_pad($results, $red, 'red');
            $results = array_pad($results, $red+$black, 'black');
            $results = array_pad($results, $red+$black+$white, 'white');
            return $results;
        }
    }
    function renderResults() {
        echo '<div class="result">Your guess history:</div>';
        foreach ($_SESSION['results'] as $key => $result_array) {
            echo '<div class="result">';
            echo '<div class="result-text"><p>'.$result_array[0].'</p></div>';
            foreach ($result_array[1] as $value) {
                switch ($value) {
                    case 'red':
                        echo '<div class="red-spot"></div>';
                        break;
                    case 'black':
                        echo '<div class="black-spot"></div>';
                        break;
                    case 'white':
                        echo '<div class="white-spot"></div>';
                        break;
                    default:
                        echo 'nothing';
                        break;
                }
            }
            echo '</div>';
        }

    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Codebreaker</title>
        <meta name="author" content="Changkun Ou">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- use bootstarp for styling -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
    <body>
        <h1 class="text-center">Codebreaker</h1>
        <?php
        // save in rank
        if (isset($_POST['save'])) {
            setcookie((string)(10-$_SESSION['maxAttempts']), $_POST['userName']);
            session_destroy();
            $_SESSION = array();
        }

        // start a new game
        $codebreaker = new Codebreaker();

        // if enable restart, then clear all results
        if (isset($_POST['restart'])) {
            session_destroy();
            $_SESSION = array();
        }
        // if code is not generated then do it and use session save the code.
        if (empty($_SESSION['code'])) {
            $_SESSION['code'] = $codebreaker->generate();
        }
        if (empty($_SESSION['results'])) {
            $_SESSION['results'] = array();
        }

        // if all letters are inputted
        if (isset($_POST['submit']) && isset($_POST['input1'])
         && isset($_POST['input2']) && isset($_POST['input3'])
         && isset($_POST['input4'])) {
            // get all inputs
            $input = strtoupper($_POST['input1'].$_POST['input2'].$_POST['input3'].$_POST['input4']);
            // check input correction
            if (preg_match('/(.)\\1{1}/', $input)) {
                echo '<p class="alert text-center">Please do not input repeat letters.</p>';
            } elseif (preg_match('/[^A-G]+/', $input)) {
                echo '<p class="alert text-center">Please only input letters from A to G.</p>';
            } elseif (strlen($input) < 4) {
                echo '<p class="alert text-center">Please input whole letters.</p>';
            } else {
                // check input letters and render the guess result\
                $result = $codebreaker->check($input);

                // store hisotry input and result
                $_SESSION['results'][10-$_SESSION["maxAttempts"]] = array($input, $result);
                $_SESSION["maxAttempts"] -= 1;
            }

            if (!isset($_SESSION['finished'])) {
                $codebreaker->renderResults();
            }
        }

        if (!isset($_SESSION['finished'])) {
            // initial maxAttempts if it's not set
            if (!isset($_SESSION["maxAttempts"])) {
                echo '<div class="app"><div class="button"><a href="index.php">
                    <input class="btn btn-primary" value="Start Game!"></a></div></div>';
                // session_destroy();
                $_SESSION = array();
                $_SESSION['maxAttempts'] = 10;
            } else {
                // if dead, restart the game
                if ($_SESSION["maxAttempts"] == 0) {
                    echo '<p class="text-center">the correct code was:'.$_SESSION['code'] . "</p>";
                    echo '<div class="app"><div class="button"><a href="index.php">
                        <input class="btn btn-danger" value="Restart?"></a></div></div>';
                    $codebreaker->renderRank();
                    session_destroy();
                    $_SESSION = array();
                    return;
                }
                if ($_SESSION["maxAttempts"] > 0) {
                    echo '<p class="text-center">Remain of attempts: '.$_SESSION['maxAttempts'].'</p>';
        ?>
                    <div class="app">
                        <form class="form form-inline" action="index.php" method="post">
                            <p class="text-center">Please input your guess:</p>
                            <div class="row txtgroup">
                                <input class="form-control" align="center" type="text" name="input1" maxlength="1" minlength="1" autofocus>
                                <input class="form-control" type="text" name="input2" maxlength="1" minlength="1">
                                <input class="form-control" type="text" name="input3" maxlength="1" minlength="1">
                                <input class="form-control" type="text" name="input4" maxlength="1" minlength="1">
                            </div>
                            <div class="button">
                                <input class="btn btn-primary" type="submit" name="submit" value="Check">
                                <input class="btn btn-danger" type="submit" name="restart" value="Restart">
                            </div>
                        </form>
                    </div>
        <?php
                }
            }
        }
        ?>
        <script src="auto.js"></script>
    </body>
</html>
