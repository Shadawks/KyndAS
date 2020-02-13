<?php session_start(); 
    if (!isset($_SESSION['logged_in'])) {
        header('Location: login');
    }
    else if (!isset($_SESSION['is_confirmed'])) {
        header('Location: confirm');
    }
?>