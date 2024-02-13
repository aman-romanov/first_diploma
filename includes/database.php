<?php
/** 
*   Connects to the database
*
*   @return connection to MYSQL db
*/
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
?>