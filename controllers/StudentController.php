<?php
require 'function.php';

const JWT_SECRET_KEY = "TEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEY";

$res = (object)Array();
header('Content-Type: json');
$req = json_decode(file_get_contents("php://input"));
try {
    addAccessLogs($accessLogs, $req);
    switch ($handler) {
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

        case "createStudent":
            http_response_code(200);
            $id = $req -> id;
            $id = isset($id) ? $req->id : null;
            $password = $req -> password;
            $password = isset($password) ? $req->password : null;
            $name = $req -> name;
            $name = isset($name) ? $req->name : null;
            $grade = $req -> grade;
            $grade = isset($grade) ? $req->grade : null;
            $className = $req -> className;
            $className = isset($className) ? $req->className : null;
            $bday = $req -> bday;
            $bday = isset($bday) ? $req->bday : null;

            if(gettype($id) != 'string'){
                badRequest($res,"body","id",$id,"TypeError");
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(is_null($id)){
                badRequest($res,"body","id",$id,"Null");
                echo json_encode($res,JSON_NUMERIC_CHECK);
                break;
            }
            if(count($id) > 20){
                badRequest($res,"body","id",$id,"LengthExceed");
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(gettype($password) != 'string'){
                badRequest($res,"body","password",$password,"TypeError");
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(is_null($password)){
                badRequest($res,"body","password",$password,"Null");
                echo json_encode($res,JSON_NUMERIC_CHECK);
                break;
            }
            if(count($password) > 30){
                badRequest($res,"body","password",$password,"LengthExceed");
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(isValidPassword($password) != 1){
                badRequest($res,"body","password",$password,"정규표현식 오류");
                echo json_encode($res,JSON_NUMERIC_CHECK);
                break;
            }
            if(gettype($name) != 'string'){
                badRequest($res,"body","name",$name,"TypeError");
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(is_null($name)){
                badRequest($res,"body","name",$name,"Null");
                echo json_encode($res,JSON_NUMERIC_CHECK);
                break;
            }
            if(count($name) > 45){
                badRequest($res,"body","name",$name,"LengthExceed");
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(gettype($grade) != 'integer'){
                badRequest($res,"body","grade",$grade,"TypeError");
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(is_null($grade)){
                badRequest($res,"body","grade",$grade,"Null");
                echo json_encode($res,JSON_NUMERIC_CHECK);
                break;
            }
            if(isValidGrade($grade) != 1){
                badRequest($res,"body","grade",$grade,"존재하지 않는 학년");
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(gettype($className) != 'string'){
                badRequest($res,"body","className",$className,"TypeError");
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(is_null($className)){
                badRequest($res,"body","className",$className,"Null");
                echo json_encode($res,JSON_NUMERIC_CHECK);
                break;
            }
            if(count($className) > 1){
                badRequest($res,"body","className",$className,"LengthExceed");
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            #### className 존재하지 않는 분반 에러코드 추가해야됨
            if(gettype($bday) != 'string'){
                badRequest($res,"body","bday",$bday,"TypeError");
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(is_null($bday)){
                badRequest($res,"body","bday",$bday,"Null");
                echo json_encode($res,JSON_NUMERIC_CHECK);
                break;
            }
            if(count($bday) > 10){
                badRequest($res,"body","bday",$bday,"LengthExceed");
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(isValidDate($bday) != 1){
                badRequest($res,"body","bday",$bday,"정규 표현식 오류");
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            createStudent($id,$password,$name,$grade,$className,$bday);

            $res->isSuccess = true;
            $res->code = 200;
            $res->message = "학생 회원가입 성공";

            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;
    }
} catch (\Exception $e) {
    return getSQLErrorException($errorLogs, $e, $req);
}
