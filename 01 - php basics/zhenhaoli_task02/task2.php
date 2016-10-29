<?php
if (isset($_POST['textToScramble'])) {
    $scramble_word = function ($word) {
        if(strlen($word)<=3) return $word;
        $chars = preg_split('//u', $word, -1, PREG_SPLIT_NO_EMPTY); //avoiding multibyte handling by using array...
        $chars_to_scramble = array_slice($chars, 1, count($chars)-2);
        shuffle($chars_to_scramble);
        return reset($chars).join("",$chars_to_scramble).end($chars);
    };
    $input = $_POST['textToScramble'];
    preg_match_all('/\p{L}+|./u', $input, $m); //divide text by words and all other stuffs (supports utf-8)
    $output = join("", array_map($scramble_word, $m[0]));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MMN - Assignment 1 - Task 2 - Scrambler</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootswatch/3.3.7/journal/bootstrap.min.css">
</head>
<body class="container-fluid">
<form method="post" class="col-xs-12 col-sm-6" >
    <h3>Input text below: </h3>
    <textarea rows=10 class="form-control" name="textToScramble"><?= isset($input) ? $input : "" ?></textarea>
    <button type="submit" class="btn btn-info btn-block" style="margin-top: 10px">Scramble!</button>
</form>
<div class="col-xs-12 col-sm-6">
    <h3>Output: </h3><p><?= isset($output) ? $output : "" ?></p>
</div>
</body>
</html>