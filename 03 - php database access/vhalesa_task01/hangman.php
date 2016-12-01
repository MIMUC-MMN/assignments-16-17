<?php
// start the session.
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hangman</title>
    <meta name="author" content="Tobias Seitz">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>

<div id="container">
    <h2>Hangman - the game.</h2>
    <section id="output">
        <?php

		#connect to database and create one, if it doesn't exist
		$servername = "localhost";
		$username = "root";
		$password = "";

		#connect to mysql
		$connection = mysqli_connect($servername, $username, $password);
		if (!$connection) {
			die("Connection failed: " . mysqli_connect_error());
		}

		#make database and create a table, if it doesn't exist
		$sql = 'CREATE DATABASE IF NOT EXISTS hangman';
		mysqli_query($connection, $sql);
		mysqli_select_db($connection, "hangman"); #connect to the database

		#create table
		$sqlTable = "CREATE TABLE IF NOT EXISTS Highscore
			(
				Name varchar(255) NOT NULL,
				Word varchar(255) NOT NULL,
				Attempts int NOT NUll,
				Date varchar(255) NOT NULL 
			)";
		mysqli_query($connection, $sqlTable);

        $maximumAttempts = 12; // the number of hangman images that we have...

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
            // reset the session
           session_destroy();
           $_SESSION = array();
		}

		#function to generate the secret code (min. 8 letters) 
		function genSecretWord() {
			$f_contents = file("dict.txt"); 
			$line = "";
			while (strlen($line) < 8) {
    			$line = trim($f_contents[rand(0, count($f_contents) - 1)]);
			}
			return $line;
		}

		# generate the secretWord (if it's not already generated)
		if (!isset($_SESSION['secretWord'])) {
			$_SESSION['secretWord'] = genSecretWord(); #generate random 4 letter code word from (A,B,C,D,E,F,G)
		}
		
		$secretWord = $_SESSION['secretWord'];
        $wordLength = strlen($secretWord);

        // actually initialize the session variables
        // valid and invalid attempts.
        lazyInitSessionVariable('hits', array()); // creates $_SESSION['hits']
        lazyInitSessionVariable('misses', array()); // creates $_SESSION['misses']
        lazyInitSessionVariable('attempts', 0); // creates $_SESSION['attempts'] - counts the attempts

		# Submitted name, secretWord, attempts and date to the table/database
		if (isset($_POST['user']) && isset($_POST['name-submit'])) {
			$pName = $_POST['user']; #submitted username
			$attempts = $_SESSION['attempts'];
			date_default_timezone_set('Europe/Berlin');
			$date = date('Y-m-d H:i:s');
			$sql = "INSERT INTO Highscore (Name, Word, Attempts, Date)
					VALUES ('$pName', '$secretWord', '$attempts', '$date')";
			mysqli_query($connection, $sql);
		}

        // handle a valid guess.
        if (isset($_POST['guess']) && isset($_POST['letter'])) { // make sure that 'guess' was submitted, as well as 'letter'
            $letter = strtoupper($_POST['letter']); // read the letter from the POST data.
			$_SESSION['attempts'] += 1; #attempts + 1

            // determine if we should move the progress forward.
            if (stristr(strtoupper($secretWord), $letter)) { // true means: secretWord contains the letter.
                // save the letter in the 'hits' list;
                // write the letter into the 'hits' list
                array_push($_SESSION['hits'], $letter);
            } else {
                // save the letter in the 'misses' list;
                // write the letter into the 'misses' list
                array_push($_SESSION['misses'], $letter);
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
        if ($progress < 10) {
        	$imageFile .= '0'; // 
        }
        $imageFile .= "$progress.png"; // 

        // show the image:
        echo "<img src='$imageFile' alt='Hangman - Step $progress' class='hangman'/>";
		echo "<br>";
		echo "<br>";

        // reveal the letters inside the 'result' div.
        echo '<div class="result">';
        // if the progress is smaller than a predefined number of attempts ==> we can play on.
        if ($progress < $maximumAttempts) {

			$win = 0; #wie viele buchstaben sind richtig geraten?

            // we go through each letter of the secret word and see if it's a hit or a miss.
            for ($i = 0; $i < $wordLength; $i++) {
                // make sure we use the uppercase version of the letters.
                $charAtI = strtoupper($secretWord[$i]); // determine the char at index $i in the $secretWord.

                // case 1: the letter of the word is in the guess array --> reveal the letter
                if (in_array($charAtI,$_SESSION['hits'])) { // insert the actual condition.
                    echo $charAtI;
					$win += 1;
                } // case 2: the letter was not guessed yet --> show an underscore
                else {
                    // print an underscore for each character in the word.
                    echo '_' . ($i != $wordLength - 1 ? ' ' : '');
                }
            }

            // now, to be a little more usable, show which letters were already guessed;
            // : Optionally inform the user which letters were wrong, using $_SESSION['misses']
            foreach ($_SESSION['misses'] as $miss) {
            	echo "<br>";
            	echo "Wrong guesses: ";
           		echo "$miss, "; 
            }

			#Player won the game
			if ($win == $wordLength) {
            	echo "<div class=\"youwin\"><h3>Congratulations!</h3><p>You won. The solution was \"$secretWord\". </p></div>";
				# user should sumbit name to highscore table
            	echo "<div class=\"username\"><p>Submit your name to the highscore table:</p>";
				echo '<form action="hangman.php" method="post">';
				echo '<input type="text" name="user" maxlength="20" minlength="1"> ';
            	echo '<button type="submit" name="name-submit">Submit name</button>';
				echo '</form>';
				echo '<br>';
				echo '</div>';
			}

	
        } else { // oh no, the user lost!
            echo "<div class=\"youlose\"><h3>Oh No!</h3><p>You lost. The solution was \"$secretWord\". </p></div>";
            //reset the session.
            session_destroy();
            $_SESSION = array();
        }

        echo '</div>'; # .result
        ?>
    </section>
    <section id="formContainer">
        <form method="post" action="hangman.php">
			<br>
            <input type="text" maxlength="1" minlength="1" name="letter"/>
            <button type="submit" name="guess">Guess</button>
            <button type="submit" name="reset">Reset</button>
        </form>
    </section>
</div>


<?php
	#Show Highscore Table of the game
	echo "<div class=\"table\"><h3>Highscore Table</h3>";
	$result = mysqli_query($connection,"SELECT * FROM Highscore");
	echo "<table border='1'>
	<tr>
	<th>Name</th>
	<th>Word</th>
	<th>Attempts</th>
	<th>Date</th>
	</tr>";

	while($row = mysqli_fetch_array($result))
	{
	echo "<tr>";
	echo "<td>" . $row['Name'] . "</td>";
	echo "<td>" . $row['Word'] . "</td>";
	echo "<td>" . $row['Attempts'] . "</td>";
	echo "<td>" . $row['Date'] . "</td>";
	echo "</tr>";
	}
	echo "</table>";
	echo "</div>";
?>

</body>
</html>
