<?php
    session_start();
    require "database.php";

    $conn = getPDO();
    $email = $_POST['email'];
    $password = $_POST['password'];

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(filter_var($email, FILTER_VALIDATE_EMAIL)){
            $sql = "SELECT *
                FROM users
                WHERE email = :email";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            if($stmt->rowCount() == 0){
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $sql = "INSERT INTO users (email, password)
                        VALUES (:email, :password)";
                $stmt = $conn->prepare($sql);
                $stmt->bindValue(':email', $email, PDO::PARAM_STR);
                $stmt->bindValue(':password', $hash, PDO::PARAM_STR);
                $stmt->execute();
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