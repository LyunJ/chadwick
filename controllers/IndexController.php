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

        case "createTeacher":
            http_response_code(200);

            $id = isset($req->id) ? $req->id : null;
            $password = isset($req->password) ? $req->password : null;
            $name = isset($req->name) ? $req->name : null;
            $bday = isset($req->bday) ? $req->bday : null;

            if ($id == null) {
                $res->isSuccess = FALSE;
                $res->code = 411;
                $res->message = "id가 null 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if ($password == null) {
                $res->isSuccess = FALSE;
                $res->code = 412;
                $res->message = "password가 null 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if ($name == null) {
                $res->isSuccess = FALSE;
                $res->code = 413;
                $res->message = "name이 null 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if ($bday == null) {
                $res->isSuccess = FALSE;
                $res->code = 414;
                $res->message = "bday가 null 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!is_string($id)) {
                $res->isSuccess = FALSE;
                $res->code = 421;
                $res->message = "id는 String 이여야 합니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (!is_string($password)) {
                $res->isSuccess = FALSE;
                $res->code = 422;
                $res->message = "password는 String 이여야 합니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (!is_string($name)) {
                $res->isSuccess = FALSE;
                $res->code = 423;
                $res->message = "name은 String 이여야 합니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (!is_string($bday)) {
                $res->isSuccess = FALSE;
                $res->code = 424;
                $res->message = "bday는 String 이여야 합니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!isValidPassword($password)) {
                $res->isSuccess = FALSE;
                $res->code = 441;
                $res->message = "password는 숫자,영어,특수문자 포함 8자리 이상이여야 합니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (!isValidDate($bday)) {
                $res->isSuccess = FALSE;
                $res->code = 442;
                $res->message = "bday는 YYYY-MM-DD 이여야 합니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            createTeacher($id, $password, $name, $bday);
            $res->isSuccess = TRUE;
            $res->code = 200;
            $res->message = "영양사/교직원 회원가입 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        case "verifyTeacher":
            http_response_code(200);

            $code = isset($req->code) ? $req->code : null;

            if ($code == null) {
                $res->isSuccess = FALSE;
                $res->code = 411;
                $res->message = "code가 null 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!is_string($code)) {
                $res->isSuccess = FALSE;
                $res->code = 421;
                $res->message = "code는 String 이여야 합니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!isCodeExists($code)) {
                $res->isSuccess = FALSE;
                $res->code = 451;
                $res->message = "존재하지 않은 code 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (codeUsed($code)) {
                $res->isSuccess = FALSE;
                $res->code = 461;
                $res->message = "이미 사용 된 code 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            verifyTeacher($code);
            $res->isSuccess = TRUE;
            $res->code = 200;
            $res->message = "영양사/교직원 인증 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        case "createTeacherJWT":
            http_response_code(200);

            $id = isset($req->id) ? $req->id : null;
            $password = isset($req->password) ? $req->password : null;

            if ($id == null) {
                $res->isSuccess = FALSE;
                $res->code = 411;
                $res->message = "id가 null 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if ($password == null) {
                $res->isSuccess = FALSE;
                $res->code = 412;
                $res->message = "password가 null 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!is_string($id)) {
                $res->isSuccess = FALSE;
                $res->code = 421;
                $res->message = "id는 String 이여야 합니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (!is_string($password)) {
                $res->isSuccess = FALSE;
                $res->code = 422;
                $res->message = "password는 String 이여야 합니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if(!isValidTeacher($id, $password)){
                $res->isSuccess = FALSE;
                $res->code = 451;
                $res->message = "존재하지 않은 교직원 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            }

            $res->jwt = getJWToken($id, $password, JWT_SECRET_KEY);
            $res->isSuccess = TRUE;
            $res->code = 200;
            $res->message = "영양사/교직원 로그인 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;
    }
} catch (\Exception $e) {
    return getSQLErrorException($errorLogs, $e, $req);
}
