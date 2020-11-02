<?php
require 'function.php';

const JWT_SECRET_KEY = "TEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEY";

$res = (Object)Array();
header('Content-Type: json');
$req = json_decode(file_get_contents("php://input"));

try{
    addAccessLogs($accessLogs,$req);
    switch ($handler){
        case "index":
            echo "API Server";
            break;
        case "ACCESS_LOGS":
            //            header('content-type text/html charset=utf-8');
            header('Content-Type: text/html; charset=UTF-8');
            getLogs("./logs/access.log");
            break;
        case "ERROR_LOGS":
            //            header('content-type text/html charset=utf-8');
            header('Content-Type: text/html; charset=UTF-8');
            getLogs("./logs/errors.log");
            break;
        case "addMenu":
            http_response_code(200);
            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];

            if(!isValidTeacherJWT($jwt,JWT_SECRET_KEY)){
                forbidden($res,'header','x-access-token',$jwt,'인증된 사용자가 아닙니다');
                echo json_encode($res,JSON_NUMERIC_CHECK);
                break;
            }

            $date = $req->date;
            $date = isset($date) ? $date : null;
            $foodIdx = $req->foodIdx;
            $foodIdx = isset($foodIdx) ? $foodIdx : null;
            $menuName = $req->menuName;
            $menuName = isset($menuName) ? $menuName : null;

            if(gettype($date) != 'string'){
                badRequest($res,"body","date",$date,"TypeError");
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(is_null($date)){
                badRequest($res,"body","date",$date,"Null");
                echo json_encode($res,JSON_NUMERIC_CHECK);
                break;
            }
            if(strlen($date) > 10){
                badRequest($res,"body","date",$date,"LengthExceed");
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(isValidDate($date) != 1){
                badRequest($res,"body","date",$date,"정규 표현식 오류");
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(gettype($foodIdx) != 'integer'){
                badRequest($res,"body","foodIdx",$foodIdx,"TypeError");
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(is_null($foodIdx)){
                badRequest($res,"body","foodIdx",$foodIdx,"Null");
                echo json_encode($res,JSON_NUMERIC_CHECK);
                break;
            }
            if(isValidFoodType($foodIdx) != 1){
                badRequest($res,"body","foodIdx",$foodIdx,"존재하지 않는 foodIdx");
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(gettype($menuName) != 'array'){
                badRequest($res,"body","menuName",$menuName,"TypeError");
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(is_null($menuName)){
                badRequest($res,"body","menuName",$menuName,"Null");
                echo json_encode($res,JSON_NUMERIC_CHECK);
                break;
            }
            foreach($menuName as $menu){
                if(strlen($menu) > 20){
                    badRequest($res,"body","menuName",$menu,"LengthExceed");
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;
                }
            }

            addMenu($date,$foodIdx,$menuName);

            $res->isSuccess = TRUE;
            $res->code = 200;
            $res->message = "메뉴 등록 성공";
            echo json_encode($res,JSON_NUMERIC_CHECK);
            break;
        case "editMenu":
            http_response_code(200);

            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];

            if(!isValidTeacherJWT($jwt,JWT_SECRET_KEY)){
                forbidden($res,'header','x-access-token',$jwt,'인증된 사용자가 아닙니다');
                echo json_encode($res,JSON_NUMERIC_CHECK);
                break;
            }

            $date = $req->date;
            $date = isset($date) ? $date : null;
            $foodIdx = $req->foodIdx;
            $foodIdx = isset($foodIdx) ? $foodIdx : null;
            $menuList = $req->menuList;
            $menuList = isset($menuList) ? $menuList : null;


            if(gettype($date) != 'string'){
                badRequest($res,"body","date",$date,"TypeError");
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(is_null($date)){
                badRequest($res,"body","date",$date,"Null");
                echo json_encode($res,JSON_NUMERIC_CHECK);
                break;
            }
            if(strlen($date) > 10){
                badRequest($res,"body","date",$date,"LengthExceed");
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(isValidDate($date) != 1){
                badRequest($res,"body","date",$date,"정규 표현식 오류");
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(gettype($foodIdx) != 'integer'){
                badRequest($res,"body","foodIdx",$foodIdx,"TypeError");
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(is_null($foodIdx)){
                badRequest($res,"body","foodIdx",$foodIdx,"Null");
                echo json_encode($res,JSON_NUMERIC_CHECK);
                break;
            }
            if(isValidFoodType($foodIdx) != 1){
                badRequest($res,"body","foodIdx",$foodIdx,"존재하지 않는 foodIdx");
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            foreach($menuList as $row){
                $menuIdx = $row["menuIdx"];
                $menuName = $row["menuName"];

                if(gettype($menuIdx) != 'integer'){
                    badRequest($res,"body","menuIdx",$menuIdx,"TypeError");
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;
                }
                if(is_null($menuIdx)){
                    badRequest($res,"body","menuIdx",$menuIdx,"Null");
                    echo json_encode($res,JSON_NUMERIC_CHECK);
                    break;
                }
                if(isValidMenuIdx($menuIdx) != 1){
                    badRequest($res,"body","menuIdx",$menuIdx,"존재하지 않는 foodIdx");
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;
                }
                if(gettype($menuName) != 'string'){
                    badRequest($res,"body","$menuName",$menuName,"TypeError");
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;
                }
                if(is_null($menuName)){
                    badRequest($res,"body","$menuName",$menuName,"Null");
                    echo json_encode($res,JSON_NUMERIC_CHECK);
                    break;
                }
                if(strlen($menuName) > 20){
                    badRequest($res,"body","$menuName",$menuName,"LengthExceed");
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;
                }
            }
            editMenu($date,$foodIdx,$menuList);
            $res->isSuccess = true;
            $res->code = 200;
            $res->message = "메뉴 등록 성공";
            echo json_encode($res,JSON_NUMERIC_CHECK);
            break;
        case "deleteMenu":
            http_response_code(200);
            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];
            if(!isValidTeacherJWT($jwt,JWT_SECRET_KEY)){
                forbidden($res,'header','x-access-token',$jwt,'인증된 사용자가 아닙니다');
                echo json_encode($res,JSON_NUMERIC_CHECK);
                break;
            }

            $date = $req->date;
            $date = isset($date) ? $date : null;
            $foodIdx = $req->foodIdx;
            $foodIdx = isset($foodIdx) ? $foodIdx : null;


            if(gettype($date) != 'string'){
                badRequest($res,"body","date",$date,"TypeError");
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(is_null($date)){
                badRequest($res,"body","date",$date,"Null");
                echo json_encode($res,JSON_NUMERIC_CHECK);
                break;
            }
            if(strlen($date) > 10){
                badRequest($res,"body","date",$date,"LengthExceed");
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(isValidDate($date) != 1){
                badRequest($res,"body","date",$date,"정규 표현식 오류");
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(gettype($foodIdx) != 'integer'){
                badRequest($res,"body","foodIdx",$foodIdx,"TypeError");
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(is_null($foodIdx)){
                badRequest($res,"body","foodIdx",$foodIdx,"Null");
                echo json_encode($res,JSON_NUMERIC_CHECK);
                break;
            }
            if(isValidFoodType($foodIdx) != 1){
                badRequest($res,"body","foodIdx",$foodIdx,"존재하지 않는 foodIdx");
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            deleteMenu($date,$foodIdx);
            $res->isSuccess = true;
            $res->code = 200;
            $res->message = "메뉴 삭제 성공";
            echo json_encode($res,JSON_NUMERIC_CHECK);
            break;
        case "getMenu":
            http_response_code(200);
            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];
            if(!isValidTeacherJWT($jwt,JWT_SECRET_KEY)){
                forbidden($res,'header','x-access-token',$jwt,'인증된 사용자가 아닙니다');
                echo json_encode($res,JSON_NUMERIC_CHECK);
                break;
            }

            $date = $req->date;
            $date = isset($date) ? $date : null;
            $foodIdx = $req->foodIdx;
            $foodIdx = isset($foodIdx) ? $foodIdx : null;
            if(gettype($date) != 'string'){
                badRequest($res,"body","date",$date,"TypeError");
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(is_null($date)){
                badRequest($res,"body","date",$date,"Null");
                echo json_encode($res,JSON_NUMERIC_CHECK);
                break;
            }
            if(strlen($date) > 10){
                badRequest($res,"body","date",$date,"LengthExceed");
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(isValidDate($date) != 1){
                notFound($res,"body","date",$date,"정규 표현식 오류");
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(gettype($foodIdx) != 'integer'){
                badRequest($res,"body","foodIdx",$foodIdx,"TypeError");
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(is_null($foodIdx)){
                badRequest($res,"body","foodIdx",$foodIdx,"Null");
                echo json_encode($res,JSON_NUMERIC_CHECK);
                break;
            }
            if(isValidFoodType($foodIdx) != 1){
                notFound($res,"body","foodIdx",$foodIdx,"존재하지 않는 foodIdx");
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            $res -> result = getMenu($date,$foodIdx);
            $res -> isSuccess = true;
            $res -> code = 200;
            $res -> message = "메뉴 조회 성공";
            echo json_encode($res,JSON_NUMERIC_CHECK);
            break;
    }
}catch (\Exception $e) {
    return getSQLErrorException($errorLogs, $e, $req);
}