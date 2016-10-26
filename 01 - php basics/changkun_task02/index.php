<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Word Sfuhle!</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
    integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>
<style type="text/css">
#app {
    padding: 20px;
    margin: 20px;
}
textarea {
    height: 500px;
}
#app2 {
    padding: 20px;
    margin: 20px;
}
</style>

<body>

<div id="app">
    <form method="post">
        <div class="form-group">
            <label>Please put your text here...</label>
            <textarea class="form-control" rows="8" name="text"></textarea>
        </div>
        <input class="btn btn-primary btn-block center-block"
                type="submit" name="submit" value="Sffuhle it!">
    </form>
</div>

<?php

//
// shuffle a string
// $str: string reference to be shuffled
//
function shuffle_str($str) {
    // use regexp find all matche words, not include punctuation
    preg_match_all('/([a-zA-Z]+)/', $str, $matches);

    $new_str = $str;
    $find_len = 0;
    // shuffle all matches
    foreach ($matches[1] as $word) {
        $random = shuffle_word($word);

        // find $word position in $new_str from $find_len
        $pos = stripos($new_str, $word, $find_len);

        // replace from $pos to $ random
        $new_str = substr_replace($new_str, $random, $pos, strlen($random));

        // only search the remaining part
        $find_len += strlen($word)+1;
    }
    return $new_str;
}

//
// shuffle a word
// $word: word to be shuffled
//
function shuffle_word($word) {
    // shuffle the middle part of a word
    $middle = substr($word, 1, strlen($word)-2);
    $middle = str_shuffle($middle);

    // joint a new word
    $random = substr_replace($word, $middle, 1, strlen($word)-2);
    return $random;
}

if(isset($_POST['text'])) {
    echo '<div id="app2" class="form-group">
            <label>Shuffled results: </label>';
    $result = shuffle_str($_POST['text']);
    echo '<p>' . $result . '</p>';
    echo '</div>';
}

?>

</body>
</html>
