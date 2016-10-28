<?php
if (isset($_POST['textToScramble'])) {
    $chars = preg_split('//u', $_POST['textToScramble'], -1, PREG_SPLIT_NO_EMPTY);
    $words = [];
    $makeWords = function ($c1, $c2) use (&$words) {
        if ((ctype_alnum($c1) && ctype_alnum($c2))) return $c1 . $c2;
        $words[] = $c1;
        return $c2;
    };
    $words[] = array_reduce($chars, $makeWords);
    $scramble_word = function ($word) {
        return strlen($word) <= 3 ? $word : $word[0] . str_shuffle(substr($word, 1, strlen($word) - 2)) . $word[strlen($word) - 1];
    };
    $input = $_POST['textToScramble'];
    $output = join("", array_map($scramble_word, $words));
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>MMN - Assignment 1 - Task 2 - Scrambler</title>
</head>
<body>
<div style="width: 50%; float: left">
    <form method="post">
        <textarea rows="30" cols="33" name="textToScramble"><?= isset($input) ? $input : "" ?></textarea>
        <p><button type="submit">Scramble!</button></p>
    </form>
</div>
<div style="width: 50%; float: right">
    <h1>Output: </h1>
    <p><?= isset($output) ? $output : "" ?></p>
</div>
</body>
</html>