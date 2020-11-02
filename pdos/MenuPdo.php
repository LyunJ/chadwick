<?php

function addMenu($date,$foodIdx,$menuName){
    $pdo = pdoSqlConnect();
    $query = "insert into menu (foodCategoryIdx,date,menuName) values (?,?,?)";

    $date = date('Y-m-d',strtotime($date));
    foreach ($menuName as $menu){
        $st = $pdo -> prepare($query);
        $st -> execute([$foodIdx,$date,$menu]);
    }
    $st = null; $pdo = null;
}

function editMenu($date,$foodIdx,$menuList){
    $pdo = pdoSqlConnect();
    $query = "update menu set menuName = ? where date = ? and menuIdx = ? and foodCategoryIdx = ?";

    $date = date('Y-m-d',strtotime($date));
    foreach($menuList as $row){
        $menuIdx = $row["menuIdx"];
        $menuName = $row["menuName"];

        $st = $pdo->prepare($query);
        $st->execute([$menuName,$date,$menuIdx,$foodIdx]);
    }
}

function deleteMenu($menuIdx){
    $pdo = pdoSqlConnect();
    $query = "delete from menu where menuIdx = ?";
    $st = $pdo->prepare($query);
    $st->execute([$menuIdx]);
}

function getMenu($date,$foodIdx){
    $pdo = pdoSqlConnect();
}