<?php
    session_start();
    require('functions.php');

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $conn = getPDO();
        $data = [
            'id' => $_SESSION['id'],
            'name' => $_POST['name'],
            'job_title' => $_POST['job_title'],
            'phone' => $_POST['phone'],
            'address' => $_POST['address'],
        ];
        if(editDataByID($conn, $data)){
            $_SESSION['success'] = "Данные сохранены";
            header('Location:../users.php');
        }
    }
?>