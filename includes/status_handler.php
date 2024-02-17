<?php
    session_start();
    require 'functions.php';

    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $status = $_POST['status'];
        $conn = getPDO();
        $authorized_user = $_SESSION['user'];
        $user = getUserByID($_SESSION['id'], $conn);

        if(!is_admin($user, $authorized_user)){
            $_SESSION['error'] = 'У вас недостаточно прав';
            header('Location:../users.php');
        }

        updateUserStatus($user, $status, $conn);
        $_SESSION['success'] = 'Данные сохранены';
        header('Location:../page_profile.php?id=' . $user[0]["id"] .'');
    }else{
        $_SESSION['error'] = 'У вас недостаточно прав';
        header('Location:../users.php');
    }
    
?>