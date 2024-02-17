<?php
    session_start();
    require 'functions.php';

    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $image = $_FILES['image'];
        $conn = getPDO();
        $authorized_user = $_SESSION['user'];
        $user = getUserByID($_SESSION['id'], $conn);

        if(!is_admin($user, $authorized_user)){
            $_SESSION['error'] = 'У вас недостаточно прав';
            header('Location:../users.php');
        }
        if(!empty($user[0]['img'])){
            $result = deleteImage($conn, $user);
            $error = $_FILES['image']['error'];
            checkForErrors($error);

            $image_name = $_FILES['image']['name'];
            $tmp_name = $_FILES['image']['tmp_name'];
            $filename = uploadImage($image_name, $tmp_name);
            setImage($conn, $filename, $user[0]['email']);
            
            $_SESSION['success'] = 'Аватар обновлен';
            header('Location:../page_profile.php?id=' . $user[0]["id"] .'');
            
        }else{

            $error = $_FILES['image']['error'];
            checkForErrors($error);

            $image_name = $_FILES['image']['name'];
            $tmp_name = $_FILES['image']['tmp_name'];
            $filename = uploadImage($image_name, $tmp_name);
            setImage($conn, $filename, $user[0]['email']);
            
            $_SESSION['success'] = 'Аватар обновлен';
            header('Location:../page_profile.php?id=' . $user[0]["id"] .'');
        }
    }else{
        $_SESSION['error'] = 'У вас недостаточно прав';
        header('Location:../users.php');
    }
?>