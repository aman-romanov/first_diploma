<?php
    session_start();
    require "functions.php";

    $conn=getPDO();
    $email = $_POST['email'];
    $password = $_POST['password'];
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(filter_var($email, FILTER_VALIDATE_EMAIL)){
            $user = checkEmail($email, $conn);
            if($user>0){
                if(password_verify($password, $user[0]['password'])){
                    $_SESSION['user'] = $user;
                    header('Location:../users.php');
                }else{
                    $_SESSION['error'] = "Почтовый адрес или пароль введен неверно!";
                    header('Location:../page_login.php');
                }
            }else{
                $_SESSION['error'] = "Почтовый адрес или пароль введен неверно!";
                header('Location:../page_login.php');
            }
        }else{
            $_SESSION['error'] = "Введите корректный почтовый адрес!";
            header('Location:../page_login.php');
        }
    }
?>