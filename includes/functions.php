<?php
    function getPDO() {
        $db_host = "localhost";
        $db_name = "first_diploma";
        $db_user = "tester";
        $db_pass = "vOJ1Cls7Q52GTIaT";

        $dsn = 'mysql:host=' . $db_host . ';dbname=' . $db_name . ';charset=utf8';
        try {
            $conn = new PDO($dsn, $db_user, $db_pass);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch (PDOException $e){
            echo $e->getMessage();
            exit;
        }

    }

    function is_logged_in(){
        if(isset($_SESSION['user'])){
            return true;
        }
    }

    function checkEmail($email,$conn){
        $sql = "SELECT *
                FROM users
                WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function newUser($email,$password,$conn){
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (email, password)
                VALUES (:email, :password)";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':password', $hash, PDO::PARAM_STR);
        return $stmt->execute();
    }

    function getAllUsers($conn){
        $sql = "SELECT *
                FROM users
                ORDER BY id
                LIMIT 10000
                OFFSET 3";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchALL(PDO::FETCH_ASSOC);
    }

    function changeUserData($data, $conn){
        $sql = "UPDATE users
                SET name = :name,
                    job_title = :job_title,
                    phone = :phone, 
                    address = :address,
                    status = :status,
                    vk = :vk,
                    telegram = :telegram,
                    instagram = :instagram
                WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':email', $data['email'], PDO::PARAM_STR);
        $stmt->bindValue(':name', $data['name'], PDO::PARAM_STR);
        $stmt->bindValue(':job_title', $data['job_title'], PDO::PARAM_STR);
        $stmt->bindValue(':phone', $data['phone'], PDO::PARAM_STR);
        $stmt->bindValue(':address', $data['address'], PDO::PARAM_STR);
        $stmt->bindValue(':status', $data['status'], PDO::PARAM_STR);
        $stmt->bindValue(':vk', $data['vk'], PDO::PARAM_STR);
        $stmt->bindValue(':telegram', $data['telegram'], PDO::PARAM_STR);
        $stmt->bindValue(':instagram', $data['instagram'], PDO::PARAM_STR);
        $stmt->execute();
    }

    function checkForErrors($error){
        switch($error){
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                $_SESSION['error'] = "Прикрепите файл";
                header('Location:../create_user.php');
                exit;
            case UPLOAD_ERR_INI_SIZE:
                $_SESSION['error'] = "Размер изображения не должно превышать 2M";
                header('Location:../create_user.php');
                exit;
            case UPLOAD_ERR_NO_TMP_DIR:
                $_SESSION['error'] = "Папка не найденa";
                header('Location:../create_user.php');
                exit;
            case UPLOAD_ERR_CANT_WRITE:
                $_SESSION['error'] = "Изображение не переместилось";
                header('Location:../create_user.php');
                exit;
            default:
                $_SESSION['error'] = "Возникла ошибка";
                header('Location:../create_user.php');
                exit;
        }
        $mime_types = ['image/jpg', 'image/jpeg', 'image/png'];
        if($_FILES['image']['tmp_name']>0){
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime_type = finfo_file($finfo, $_FILES['image']['tmp_name']);
            if(!in_array($mime_type, $mime_types)){
                $_SESSION['error'] = "Изображение должно соответствовать форматам: jpeg/jpg/png";
                header('Location:../create_user.php');
            }
        }
        return true;
    }

    function uploadImage($image_name, $tmp_name){
        $pathinfo = pathinfo($image_name);
        $base = $pathinfo['filename'];
        $base = preg_replace('/[^a-zA-Z0-9_-]/', '_', $base);
        $filename = $base . "." . $pathinfo['extension'];
        $destination = __DIR__ . '/../img/demo/avatars/' . $filename;

        $i = 1;
        while(file_exists($destination)){
            $filename = $base . "($i)." . $pathinfo['extension'];
            $destination = __DIR__ . '/../img/demo/avatars/' . $filename;
            $i++;
            return $filename;
        }
        move_uploaded_file($tmp_name, $destination);
        return $filename;
    }

    function setImage($conn, $filename, $email){
        
        $sql = "UPDATE users 
                SET img = :image
                WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':image', $filename, PDO::PARAM_STR);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        return $stmt->execute();
        }

?>