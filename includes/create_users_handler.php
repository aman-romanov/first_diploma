<?php
    session_start();
    require 'functions.php';

    $conn = getPDO();

    if($_SERVER['REQUEST_METHOD'] !== 'POST'){
        $_SESSION['error'] = 'Заполните поля';
        header('Location:../create_user.php');
    }
    if(!empty($_FILES)){
        $error = $_FILES['image']['error'];
        checkForErrors($error);

    }
    $data = [
        'email' => $_POST['email'],
        'password' => $_POST['password'],
        'name' => $_POST['name'],
        'job_title' => $_POST['job_title'],
        'phone' => $_POST['phone'],
        'address' => $_POST['address'],
        'status' => $_POST['status'],
        'vk' => $_POST['vk'],
        'telegram' => $_POST['telegram'],
        'instagram' => $_POST['instagram']
    ];
    $is_user_exist = checkEmail($data['email'], $conn);

    if(!empty($is_user_exist)){
        $_SESSION['error'] = 'Пользователь с таким почтовым адресом уже зарегистрирован';
        header('Location:../create_user.php');
    }else{ 
        if(newUser($data['email'], $data['password'], $conn)){
            changeUserData($data, $conn);
            $image_name = $_FILES['image']['name'];
            $tmp_name = $_FILES['image']['tmp_name'];
            $filename = uploadImage($image_name, $tmp_name);
            setImage($conn, $filename, $data['email']);
            $_SESSION['success'] = "Пользователь добавлен";
            header('Location:../users.php');
        } 
    }
?>