<?php

//DB 정보
function pdoSqlConnect()
{
    try {
        $DB_HOST = "tastereview.cevu32mso1il.ap-northeast-2.rds.amazonaws.com";
        $DB_NAME = "TasteReview";
        $DB_USER = "lyunj";
        $DB_PW = "chadwik2020!";
        $pdo = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME", $DB_USER, $DB_PW);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
}