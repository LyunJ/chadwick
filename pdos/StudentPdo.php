<?php

function createStudent($id,$password,$name,$grade,$className,$bday){
    $pdo = pdoSqlConnect();
    $query = "insert into students (ID,password,name,grade,class,birthday) values (?,?,?,?,?,?)";
    $st = $pdo -> prepare($query);
    $st->execute([$id,$password,$name,$grade,$className,$bday]);

    $pdo = null; $st = null;
}