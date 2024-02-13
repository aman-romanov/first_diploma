<?php
    session_start();
    if(!isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in']!==true){
        header('Location:page_login.php');
    }
?>