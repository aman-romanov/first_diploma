<?php
    session_start();
    require "functions.php";


    $conn = getPDO();
    $email = $_POST['email'];
    $password = $_POST['password'];

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(filter_var($email, FILTER_VALIDATE_EMAIL)){
            $users = checkEmail($email, $conn);
            var_dump($users);
            if(empty($users)){
                newUser($email, $password, $conn);
                $_SESSION['success'] = "Регистрация успешна!";
                header('Location:../page_login.php');
            }else{
                $_SESSION['error'] = "Почтовый адрес уже занят";
                header('Location:../page_register.php');
            }
        }else{
            $_SESSION['error'] = 'Введите корректный почтовый адрес!';
            header('Location:../page_register.php');
        }
    }else{
        $_SESSION['error'] = 'Заполните поля!';
        header('Location:../page_register.php');
    }
?>