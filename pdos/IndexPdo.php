<?php

function createTeacher($id, $password, $name, $bday)
{
    $pdo = pdoSqlConnect();

    $query = "INSERT INTO Teacher (id, password, name, birthday) VALUES (?, ?, ?, ?);";

    $st = $pdo->prepare($query);
    $st->execute([$id, $password, $name, $bday]);

    $recruitId = $pdo->lastInsertId();
    $st = null;
    $pdo = null;

    return $recruitId;
}

function isCodeExists($code)
{
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(SELECT * FROM Code WHERE code = ? and isDeleted = 'N') AS exist;";

    $st = $pdo->prepare($query);
    $st->execute([$code]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return intval($res[0]["exist"]);
}

function codeUsed($code)
{
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(SELECT * FROM Code WHERE code = ? and isDeleted = 'Y') AS exist;";

    $st = $pdo->prepare($query);
    $st->execute([$code]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return intval($res[0]["exist"]);
}

function verifyTeacher($code)
{
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(SELECT * FROM Code WHERE code = ? and isDeleted = 'N') AS exist;";

    $st = $pdo->prepare($query);
    $st->execute([$code]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return intval($res[0]["exist"]);
}

function isValidTeacher($id, $password)
{
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(SELECT * FROM Teacher WHERE id = ? and password = ? and isDeleted = 'N') AS exist;";

    $st = $pdo->prepare($query);
    $st->execute([$id, $password]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return intval($res[0]["exist"]);
}

function isValidStudent($id,$password){
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(SELECT * FROM students WHERE id = ? and password = ? and isDeleted = 'N') AS exist;";

    $st = $pdo->prepare($query);
    $st->execute([$id, $password]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return intval($res[0]["exist"]);
}

function isValidFoodType($foodIdx){
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(SELECT * FROM foodCategory WHERE categoryIdx = ?) AS exist;";

    $st = $pdo->prepare($query);
    $st->execute([$foodIdx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return intval($res[0]["exist"]);
}