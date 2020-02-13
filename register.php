<?php session_start(); 
    if (isset($_SESSION['logged_in'])) {
        header('Location: index.php');
    }
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>KyndAS</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Aclonica">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Advent+Pro">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Allerta">
    <link rel="stylesheet" href="assets/fonts/simple-line-icons.min.css">
    <link rel="stylesheet" href="assets/css/Login-Form-Dark.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body style="background-color: #282828;">
    <div class="login-dark" style="color: #282828;font-family: Allerta, sans-serif;">
        <form method="post" style="background-color: #1f1f1f;">
            <div class="illustration"><i class="icon-user-follow"></i>
                <h1 style="font-family: Aclonica, sans-serif;">Pandora's Box</h1>
            </div>
            <div id="display"></div>
            <div class="form-group"><input class="form-control" type="email" id="email" name="email" placeholder="Email"></div>
            <div class="form-group"><input class="form-control" type="text" id="username" placeholder="Username"></div>
            <div class="form-group"><input class="form-control" type="password" id="password" name="password" placeholder="Password"></div>
            <div class="form-group"><input class="form-control" type="password" id="confirm_password" name="confirm_password" placeholder="Confirm"></div>
            <div class="form-group"><button class="btn btn-primary btn-block" onclick="register()" type="button"  style="background-color: rgb(40,40,40);">Register</button></div><a class="forgot" href="login">Already an account ? Login here !</a></form>
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>

</html>