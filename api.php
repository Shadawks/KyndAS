<?php
session_start();
include("config.php");

function getConfirmCode($length) {
    $alpha = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwcyz0123456789&*$^-+.";
    $new_pass = "";
    for ($i=0; $i < $length; $i++) { 
        $new_pass.= $alpha[rand(0, strlen($alpha) - 1)];
    }
    return($new_pass);
}

if (!isset($_GET['do'])) {
    die(json_encode(array('message'=>'requête malformé.'),JSON_UNESCAPED_UNICODE));
}
if($_GET['do'] == "login") {
    if (isset($_SESSION['logged_in'])) {
        die(json_encode(array('message'=>'tu es déja connecté.'),JSON_UNESCAPED_UNICODE));
    }
    if (!empty($_POST['username']) && !empty($_POST['password'])) {
        $username = htmlspecialchars($_POST['username']);
        $password = $_POST['password'];
        $bdd = new PDO('mysql:host='.$db_host.';dbname='.$db_name.';charset=utf8', $db_user, $db_pass);
        $query = "SELECT * FROM users WHERE username = :username";
        $req = $bdd->prepare($query);
        $req->execute(array('username'=>$username));
        $count = $req->rowCount();
        if ($count > 0) {
            $resp = $req->fetch();
            $salt = $resp["password"];
            $code = $resp["confirmed"];
            if (password_verify($password, $salt)) {
                $_SESSION['logged_in'] = true;
                $_SESSION['username'] = $username;
                if ($code == '0') {
                    $_SESSION['is_confirmed'] = true;
                }
                die(json_encode(array('message'=>'succès'),JSON_UNESCAPED_UNICODE));
            } else {
                die(json_encode(array('message'=>'mot de passe incorect.'),JSON_UNESCAPED_UNICODE));
            }
        } else {
            die(json_encode(array('message'=>'utilisateur inconnu.'),JSON_UNESCAPED_UNICODE));
        }
    } else {
        die(json_encode(array('message'=>'tous les champs ne sont pas complétés.'),JSON_UNESCAPED_UNICODE));
    }
} elseif ($_GET['do'] == "register") {
    if (isset($_SESSION['logged_in'])) {
        die(json_encode(array('message'=>'tu es déja connecté.'),JSON_UNESCAPED_UNICODE));
    }
    if (!empty($_POST['username']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['confirm_password'])) {
        if ($_POST['password'] == $_POST['confirm_password']) {
            if (strpos($_POST['email'], '@') !== FALSE) {
                if (strlen($_POST['username']) < 12) {
                    $bdd = new PDO('mysql:host='.$db_host.';dbname='.$db_name.';charset=utf8', $db_user, $db_pass);
                    $query = "SELECT * FROM users WHERE username = :username OR email = :email";
                    $req = $bdd->prepare($query);
                    $req->execute(array('username' => $_POST['username'],'email' => $_POST['email']));
                    $count = $req->rowCount();
                    if ($count == 0) {
                        $password = password_hash($_POST['password'],PASSWORD_DEFAULT);
                        $username = htmlspecialchars($_POST['username']);
                        $email = htmlspecialchars($_POST['email']);
                        $confirmCode = getConfirmCode(25);
                        $query = "INSERT INTO users(username,email,password,confirmed) VALUES(:username,:mail,:pass,:confirmed)";
                        $req = $bdd->prepare($query);
                        $req->execute(array('username'=>$username,'mail'=>$email,'pass'=>$password,'confirmed'=>getConfirmCode(25)));
                        die(json_encode(array('message'=>'inscription effectué avec succès.'),JSON_UNESCAPED_UNICODE));
                    } else {
                        die(json_encode(array('message'=>'le nom d\'utilisateur ou l\'adresse email est déja utilisée.'),JSON_UNESCAPED_UNICODE));
                    }
                } else {
                    die(json_encode(array('message'=>'le nom d\'utilisateur est trop long. (12 charactères). '.strlen($_POST['username'])),JSON_UNESCAPED_UNICODE));
                }
            } else {
                die(json_encode(array('message'=>'merci de fournir une adresse e-mail valide.'),JSON_UNESCAPED_UNICODE));
            }
        } else {
            die(json_encode(array('message'=>'les mots de passes doivent être indentiques.'),JSON_UNESCAPED_UNICODE));
        }
    } else {
        die(json_encode(array('message'=>'tous les champs ne sont pas complétés.'),JSON_UNESCAPED_UNICODE));
    }
} elseif ($_GET['do'] == "logout") {
    session_destroy();
    header('Location: ../index.php');
    die(json_encode(array('message'=>'déconnecté'),JSON_UNESCAPED_UNICODE));
} elseif ($_GET['do'] == "checkCode") {
    if (isset($_SESSION['is_confirmed'])) {
        die(json_encode(array('message'=>'tu es déja connecté.'),JSON_UNESCAPED_UNICODE));
    }
    if (!empty($_POST['code'])) {
        $bdd = new PDO('mysql:host='.$db_host.';dbname='.$db_name.';charset=utf8', $db_user, $db_pass);
        $query = "SELECT confirmed FROM users WHERE username = :username";
        $req = $bdd->prepare($query);
        $req->execute(array('username'=>$_SESSION['username']));
        $count = $req->rowCount();
        if ($count > 0) {
            $resp = $req->fetch();
            $code = $resp["confirmed"];
            if ($code == $_POST['code']) {
                $_SESSION['is_confirmed'] = true;
                $query = "UPDATE users SET confirmed='0' WHERE username = :username";
                $req = $bdd->prepare($query);
                $req->execute(array('username'=>$_SESSION['username']));
                die(json_encode(array('message'=>'succès'),JSON_UNESCAPED_UNICODE));
            } else {
                die(json_encode(array('message'=>'code incorrect.'),JSON_UNESCAPED_UNICODE));
            }
        } else {
            die(json_encode(array('message'=>'erreur interne.'),JSON_UNESCAPED_UNICODE));
        }
    } else {
        die(json_encode(array('message'=>'tous les champs ne sont pas complétés.'),JSON_UNESCAPED_UNICODE));
    }
} else {
    die(json_encode(array('message'=>'requête malformé.'),JSON_UNESCAPED_UNICODE));
}
?> 