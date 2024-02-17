<?php
    session_start();
    require 'functions.php';

    $_SESSION['id'] = $_GET['id'];
    $conn = getPDO();
    $authorized_user = $_SESSION['user'];
    $user = getUserByID($_SESSION['id'], $conn);

    if(!is_admin($user, $authorized_user)){
        $_SESSION['error'] = 'У вас недостаточно прав';
        header('Location:../users.php');
    }

    if(!is_logged_in()){
        header('Location:page_login.php');
    }

    if($authorized_user[0]['id'] == $user[0]['id']){
        deleteUser($user[0]['id'], $conn);
        logout();
        header('Location:../page_register.php');
    }
    deleteUser($user[0]['id'], $conn);
    $_SESSION['success'] = 'Профиль удален';
    header('Location:../users.php');
?>