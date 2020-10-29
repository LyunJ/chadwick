<?php

function addMenu($date,$foodIdx,$menuName){
    $pdo = pdoSqlConnect();
    $query = "insert into menu (foodCategoryIdx,date,menuName) values (?,?,?)";

    $date = date('Y-m-d',strtotime($date));
    foreach ($menuName as $menu){
        $st = $pdo -> prepare($query);
        $st -> execute([$foodIdx,$date,$menu]);
    }
}