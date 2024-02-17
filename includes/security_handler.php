<?php
    session_start();
    require('functions.php');

    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $conn=getPDO();
        $user = getUserByID($_SESSION['id'],$conn);
        $data = [
            'id' => $_SESSION['id'],
            'email' => $_POST['email'],
            'password' => $_POST['password'],
            'verify_password' => $_POST['verify_password']
        ];
        if(empty($data['password'] || $data['password'])){
            $_SESSION['error'] = 'Заполните поля';
            header('Location:../security.php?id=' . $data["id"] .'');
            exit;
        }

        if($data['password']!==$data['verify_password']){
            $_SESSION['error'] = 'Пароль не сходится';
            header('Location:../security.php?id=' . $data["id"] .''); 
            exit;
        }

        if(password_verify($data['password'], $user[0]['password'])){
            $_SESSION['error'] = 'Введите новый пароль';
            header('Location:../security.php?id=' . $data["id"] .''); 
            exit;
        }
        if(changeSecureData($user, $data, $conn)){
            $_SESSION['success'] = 'Данные сохранены';
            header('Location:../page_profile.php?id=' . $data["id"] .'');
        }else{
            $_SESSION['error'] = 'Почтовый адрес занят';
            header('Location:../security.php?id=' . $data["id"] .''); 
        }
        
    }
   
?>