## Task 2: PHP

### a) Implement "guess-my-number" game with PHP

```php
<?php
session_start();

if(!isset($_SESSION['counter']) || !isset($_SESSION['guess'])) {
  $_SESSION['guess']   = 24;
  $_SESSION['counter'] = 0;
}

if(isset($_POST['destory'])) {
  session_destroy();
  $_SESSION = array();
}
?>

<!DOCTYPE html>
<html>
<head lang="en">
  <title>Guess a Number</title>
</head>
<body>
  <form method="post">
      <input type="number" name="guess" />
      <input type="submit" value="Check!" />
  </form>

<?php
  if(isset($_POST['guess'])) {
    if($_POST['guess'] != $_SESSION['guess']) {
      $_SESSION['counter'] += 1;
      echo 'Incorrect! You tried '.$_SESSION['counter'].' times!';
    } else {
      echo 'Correct!';
?>
      <form method="post">
        <input type="submit" name="destory" value="restart">
      </form>
<?php
    }
  }
?>

</body>
</html>
```

### b) How are sessions destroyed in PHP?

```php
session_destory();
$_SESSION = array();
```

### c) How does PHP identify users to keep track of individual sessions?

Use session cookie ID.

### d) How do you transparently transmit form data to the server, i.e. without the user immediately seeing the data?

Use POST method.