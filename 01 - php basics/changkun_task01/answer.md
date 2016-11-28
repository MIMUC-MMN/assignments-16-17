1. `'lang=""'` should be in `<html>` tag;
2. `$_POST['submit']` will never be set and should also be check with `isset()`;
3. form doesn't specify a method, considering security, we should use POST request;
4. `<input>` element must set name property to passing their value when form submit.

Here is a correction:

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>What's wrong here?</title>
</head>
<body>

<?php
function loginUser($email, $password) {
    //imagine valid login routine
}

if (isset($_POST['submit'])) {
    loginUser(isset($_POST['email']), isset($_POST['password']));
} else {
?>

<form method="post">
    <label>
        Email: <input type="email" name="email">
    </label>
    <label>
        Password: <input type="password" name="password">
    </label>
    <input type="submit" name="submit">
</form>

<?php } ?>

</body>
</html>

```