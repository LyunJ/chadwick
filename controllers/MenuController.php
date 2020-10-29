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
            $foodType = $req->foodType;
            $foodType = isset($foodType) ? $foodType : null;
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
            if(gettype($foodType) != 'string'){
                badRequest($res,"body","foodType",$foodType,"TypeError");
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(is_null($foodType)){
                badRequest($res,"body","foodType",$foodType,"Null");
                echo json_encode($res,JSON_NUMERIC_CHECK);
                break;
            }
            if(strlen($foodType) > 1){
                badRequest($res,"body","foodType",$foodType,"LengthExceed");
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(isValidFoodType($foodType) != 1){
                badRequest($res,"body","foodType",$foodType,"정규 표현식 오류");
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

            addMenu($date,$foodType,$menuName);
            $res->isSuccess = true;
            $res->code = 200;
            $res->message = "메뉴 등록 성공";
            json_encode($res,JSON_NUMERIC_CHECK);
            break;
    }
}catch (\Exception $e) {
    return getSQLErrorException($errorLogs, $e, $req);
}