<?php
session_start();
require_once('DBHandler.php');
require_once('AuthHandler.php');
require_once('private.info.php');
$mydb = new DBHandler($host,$user,$password,$db);
$auth = new AuthHandler($mydb);
?>

<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>Registration</title>
</head>
<body>

<?php
if(isset($_POST['submit'])){
    if( isset($_POST['password'])
        && isset($_POST['password_confirmation'])
        && isset($_POST['username'])){

        if($_POST['password'] == $_POST['password_confirmation']){
            if($auth->register($_POST['username'],$_POST['password'])){
                if($auth->login($_POST['username'],$_POST['password'])){
                    $success = true;
                    echo 'Registration successed, forwarding...';

                    echo "<script>
                            setTimeout(function(){
                                window.location.href='./index.php';
                            },1000);
                        </script>";
                };
            } else {
                echo 'Username exists, try another.';
            }
        } else {
        echo "Password not same, try input again.";
    }
    }
}
if(!isset($success)){
?>

<div id="container">
<p>Registration for Notes App</p>
<form method="post">
    <input type="text" name="username" placeholder="Username"
    value="<?php if(isset($_POST['username']))echo $_POST['username']?>"
    />
    <input type="password" name="password" placeholder="Password"
    />
    <input type="password" name="password_confirmation" placeholder="Confirm" />
    <input type="submit" name="submit" value="Register" />
</form>
</div>
<?php } ?>
</body>
</html>
