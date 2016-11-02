<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Sffuhle my Wrods!</title>
</head>
<body>

	<div id="shuffle">
		<form method="post">
			<div class="shuffleInput">
				<label>Please insert text here...</label> <br>
				<textarea name="text" rows="10" cols="25"><?php echo $text;?></textarea>
			</div>
			<input type="submit" name="submit" value="Shuffle text...">
		</form>
	</div>

	<?php
	//get the typed text and show it
	$unshuffled = $_POST["text"];
	echo "<h2>Your Input:</h2>";
	echo $unshuffled . "<br>";

	//shuffle all words and show it
	echo "<h2>Your Result:</h2>";
	//split text into words array
	$data = preg_split('/ /', $unshuffled);
	for ($i = 0; $i <= count($data); $i++) {
		echo shuffleIt($data[$i]) . ' ';
	}

	function shuffleIt($input) {	
		$result = shuffleWord($input);
		return $result;
	}

	function shuffleWord($input){
		$inputLength = strlen($input);
		$strStart = $input[0];
		$strMiddle = substr($input, 1, $inputLength-2);
		$shuffleMiddle = str_shuffle($strMiddle);
		$strEnd = substr($input, -1);
		return $strStart . $shuffleMiddle . $strEnd;
	}
	?>

</body>
</html>