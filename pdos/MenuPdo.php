<?php

function addMenu($date,$foodIdx,$menuList){
    $pdo = pdoSqlConnect();
    foreach ($menuList as $menu){
        $query = "select menuIdx from menu where menuName = ?";
        $st = $pdo -> prepare($query);
        $st -> execute([$menu]);
        $st ->setFetchMode(PDO::FETCH_ASSOC);
        $res = $st->fetchAll();

        $menuIdx = $res[0]['menuIdx'];

        if($menuIdx > 0){
            $query = "insert into MenuTable (foodCategoryIdx,date,menuIdx) values (?,?,?)";

            $date = date('Y-m-d',strtotime($date));
            $st = $pdo -> prepare($query);
            $st -> execute([$foodIdx,$date,$menuIdx]);

        }else{
            $query = "insert into menu (menuName) values (?)";

            $st = $pdo -> prepare($query);
            $st -> execute([$menu]);

            $createdMenuIdx = $pdo -> lastInsertId();

            $query = "insert into MenuTable (menuIdx,foodCategoryIdx,date) values (?,?,?)";
            $date = date('Y-m-d',strtotime($date));
            $st = $pdo -> prepare($query);
            $st -> execute([$createdMenuIdx,$foodIdx,$date]);
        }
    }
    $st = null;
    $pdo = null;
}

function editMenu($date,$foodIdx,$menuList){
    $pdo = pdoSqlConnect();
    $query = "delete from MenuTable where date = ? and foodCategoryIdx = ?";
    $st = $pdo->prepare($query);
    $st->execute([$date,$foodIdx]);
    foreach ($menuList as $menu){
        $query = "select menuIdx from menu where menuName = ?";
        $st = $pdo -> prepare($query);
        $st -> execute([$menu]);
        $st ->setFetchMode(PDO::FETCH_ASSOC);
        $res = $st->fetchAll();

        $menuIdx = $res[0]['menuIdx'];

        if($menuIdx > 0){
            $query = "insert into MenuTable (foodCategoryIdx,date,menuIdx) values (?,?,?)";

            $date = date('Y-m-d',strtotime($date));
            $st = $pdo -> prepare($query);
            $st -> execute([$foodIdx,$date,$menuIdx]);

        }else{
            $query = "insert into menu (menuName) values (?)";

            $st = $pdo -> prepare($query);
            $st -> execute([$menu]);

            $createdMenuIdx = $pdo -> lastInsertId();

            $query = "insert into MenuTable (menuIdx,foodCategoryIdx,date) values (?,?,?)";
            $date = date('Y-m-d',strtotime($date));
            $st = $pdo -> prepare($query);
            $st -> execute([$createdMenuIdx,$foodIdx,$date]);
        }
    }
    $st = null;
    $pdo = null;
}

function deleteMenu($date,$foodIdx){
    $pdo = pdoSqlConnect();
    $query = "delete from MenuTable where date = ? and foodCategoryIdx = ?";
    $st = $pdo->prepare($query);
    $st->execute([$date,$foodIdx]);
    $st = null;
    $pdo = null;
}

function getMenu($date,$foodIdx){
    $pdo = pdoSqlConnect();
    $query = "select json_arrayagg(menu.menuName) as menuName from MenuTable left outer join menu on MenuTable.menuIdx = menu.menuIdx where MenuTable.date = ? and MenuTable.foodCategoryIdx = ? group by foodCategoryIdx, date";
    $st = $pdo->prepare($query);
    $st -> execute([$date,$foodIdx]);
    $st -> setFetchMode(PDO::FETCH_ASSOC);
    $res = $st -> fetchAll();

    $st = null;
    $pdo = null;

    return json_decode($res[0]['menuName'],true);
}