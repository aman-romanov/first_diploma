<?php
    session_start();
    require "database.php";

    $conn=getPDO();
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
            $user = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if($user>0){
                if(password_verify($password, $user[0]['password'])){
                    session_regenerate_id(true);
                    $_SESSION['is_logged_in'] = true;
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