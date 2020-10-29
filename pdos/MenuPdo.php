<?php

function addMenu($date,$foodType,$menuName){
    $pdo = pdoSqlConnect();
    $query = "insert into menu (foodCategoryIdx,date,menuName) values (?,?,?)";
    foreach ($menuName as $menu){
        $st = $pdo -> prepare($query);
        $st -> execute([$foodType,$date,$menu]);
    }
}