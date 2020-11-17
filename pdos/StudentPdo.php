<?php

function getStudentIdx($id, $pw) {
    $pdo = pdoSqlConnect();
    $query = "SELECT studentIdx FROM students WHERE ID = ? and password = ? and isDeleted = 'N';";

    $st = $pdo->prepare($query);
    $st->execute([$id, $pw]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return intval($res[0]["studentIdx"]);
}
function createStudent($id,$password,$name,$grade,$className,$bday){
    $pdo = pdoSqlConnect();
    $query = "insert into students (ID,password,name,grade,class,birthday) values (?,?,?,?,?,?)";
    $st = $pdo -> prepare($query);
    $st->execute([$id,$password,$name,$grade,$className,$bday]);

    $pdo = null; $st = null;
}