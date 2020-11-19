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

        case "createReview":
            http_response_code(200);

            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];
            if (!isValidStudentHeader($jwt, JWT_SECRET_KEY)) {
                $res->isSuccess = FALSE;
                $res->code = 451;
                $res->message = "존재하지 않은 student 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $data = getDataByJWToken($jwt, JWT_SECRET_KEY);

            $studentIdx = getStudentIdx($data->id, $data->pw);
            $foodIdx = isset($req->foodIdx) ? $req->foodIdx : null;
            $date = isset($req->date) ? $req->date : null;
            $score = isset($req->score) ? $req->score : null;
            $content = isset($req->content) ? $req->content : null;

            if ($studentIdx == null) {
                $res->isSuccess = FALSE;
                $res->code = 411;
                $res->message = "studentIdx가 null 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if ($foodIdx == null) {
                $res->isSuccess = FALSE;
                $res->code = 412;
                $res->message = "foodIdx가 null 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if ($score == null) {
                $res->isSuccess = FALSE;
                $res->code = 413;
                $res->message = "score가 null 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if ($content == null) {
                $res->isSuccess = FALSE;
                $res->code = 414;
                $res->message = "content가 null 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if ($date == null) {
                $res->isSuccess = FALSE;
                $res->code = 415;
                $res->message = "date가 null 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!is_integer($studentIdx)) {
                $res->isSuccess = FALSE;
                $res->code = 421;
                $res->message = "studentIdx는 Int 이여야 합니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (!is_integer($foodIdx)) {
                $res->isSuccess = FALSE;
                $res->code = 422;
                $res->message = "foodIdx는 Int 이여야 합니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (!is_float($score)) {
                $res->isSuccess = FALSE;
                $res->code = 423;
                $res->message = "score는 Float 이여야 합니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (!is_string($content)) {
                $res->isSuccess = FALSE;
                $res->code = 424;
                $res->message = "content는 String 이여야 합니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (!is_string($date)) {
                $res->isSuccess = FALSE;
                $res->code = 425;
                $res->message = "date는 String 이여야 합니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if(!isValidScore($score)) {
                $res->isSuccess = FALSE;
                $res->code = 441;
                $res->message = "score는 0~5 사이 이여야 합니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(!isValidDate($date)) {
                $res->isSuccess = FALSE;
                $res->code = 442;
                $res->message = "date는 yyyy-mm-dd 이여야 합니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(!isValidFood($foodIdx)) {
                $res->isSuccess = FALSE;
                $res->code = 443;
                $res->message = "foodIdx는 1, 2, 3 이여야 합니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if(!isFoodExists($foodIdx, $date)) {
                $res->isSuccess = FALSE;
                $res->code = 452;
                $res->message = "foodIdx의 date에 대한 메뉴가 존재하지 않습니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if(isReviewExists($studentIdx, $foodIdx, $date)) {
                $res->isSuccess = FALSE;
                $res->code = 461;
                $res->message = "해당 studenIdx로 해당 foodIdx의 date에 대한 리뷰가 이미 존재 합니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            createReview($foodIdx, $date, $studentIdx, $score, $content);
            $res->isSuccess = TRUE;
            $res->code = 200;
            $res->message = "리뷰 작성 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        case "modifyReview":
            http_response_code(200);

            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];
            if (!isValidStudentHeader($jwt, JWT_SECRET_KEY)) {
                $res->isSuccess = FALSE;
                $res->code = 451;
                $res->message = "존재하지 않은 student 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $data = getDataByJWToken($jwt, JWT_SECRET_KEY);

            $studentIdx = getStudentIdx($data->id, $data->pw);
            $foodIdx = isset($req->foodIdx) ? $req->foodIdx : null;
            $date = isset($req->date) ? $req->date : null;
            $score = isset($req->score) ? $req->score : null;
            $content = isset($req->content) ? $req->content : null;

            if ($studentIdx == null) {
                $res->isSuccess = FALSE;
                $res->code = 411;
                $res->message = "studentIdx가 null 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if ($foodIdx == null) {
                $res->isSuccess = FALSE;
                $res->code = 412;
                $res->message = "foodIdx가 null 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if ($score == null) {
                $res->isSuccess = FALSE;
                $res->code = 413;
                $res->message = "score가 null 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if ($content == null) {
                $res->isSuccess = FALSE;
                $res->code = 414;
                $res->message = "content가 null 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if ($date == null) {
                $res->isSuccess = FALSE;
                $res->code = 415;
                $res->message = "date가 null 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!is_integer($studentIdx)) {
                $res->isSuccess = FALSE;
                $res->code = 421;
                $res->message = "studentIdx는 Int 이여야 합니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (!is_integer($foodIdx)) {
                $res->isSuccess = FALSE;
                $res->code = 422;
                $res->message = "foodIdx는 Int 이여야 합니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (!is_float($score)) {
                $res->isSuccess = FALSE;
                $res->code = 423;
                $res->message = "score는 Float 이여야 합니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (!is_string($content)) {
                $res->isSuccess = FALSE;
                $res->code = 424;
                $res->message = "content는 String 이여야 합니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (!is_string($date)) {
                $res->isSuccess = FALSE;
                $res->code = 425;
                $res->message = "date는 String 이여야 합니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if(!isValidScore($score)) {
                $res->isSuccess = FALSE;
                $res->code = 441;
                $res->message = "score는 0~5 사이 이여야 합니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(!isValidDate($date)) {
                $res->isSuccess = FALSE;
                $res->code = 442;
                $res->message = "date는 yyyy-mm-dd 이여야 합니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(!isValidFood($foodIdx)) {
                $res->isSuccess = FALSE;
                $res->code = 443;
                $res->message = "foodIdx는 1, 2, 3 이여야 합니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if(!isFoodExists($foodIdx, $date)) {
                $res->isSuccess = FALSE;
                $res->code = 452;
                $res->message = "foodIdx의 date에 대한 메뉴가 존재하지 않습니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if(!isReviewExists($studentIdx, $foodIdx, $date)) {
                $res->isSuccess = FALSE;
                $res->code = 453;
                $res->message = "해당 studenIdx로 해당 foodIdx의 date에 대한 리뷰가 존재하지 않습니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            modifyReview($foodIdx, $date, $studentIdx, $score, $content);
            $res->isSuccess = TRUE;
            $res->code = 200;
            $res->message = "리뷰 수정 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        case "getReview":
            http_response_code(200);

            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];
            if (!isValidStudentHeader($jwt, JWT_SECRET_KEY)) {
                $res->isSuccess = FALSE;
                $res->code = 451;
                $res->message = "존재하지 않은 student 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $data = getDataByJWToken($jwt, JWT_SECRET_KEY);

            $studentIdx = getStudentIdx($data->id, $data->pw);
            $menuIdx = $_GET["menuIdx"];
            $menuIdx = isset($menuIdx) ? intval($menuIdx) : null;
//            $date = $_GET["date"];
//            $date = isset($date) ? intval($date) : null;

            if ($studentIdx == null) {
                $res->isSuccess = FALSE;
                $res->code = 411;
                $res->message = "studentIdx가 null 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if ($menuIdx == null) {
                $res->isSuccess = FALSE;
                $res->code = 412;
                $res->message = "menuIdx가 null 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
//            if ($date == null) {
//                $res->isSuccess = FALSE;
//                $res->code = 413;
//                $res->message = "date가 null 입니다";
//                echo json_encode($res, JSON_NUMERIC_CHECK);
//                break;
//            }
            if (!is_integer($studentIdx)) {
                $res->isSuccess = FALSE;
                $res->code = 421;
                $res->message = "studentIdx는 Int 이여야 합니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (!is_integer($menuIdx)) {
                $res->isSuccess = FALSE;
                $res->code = 422;
                $res->message = "menuIdx는 Int 이여야 합니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
//            if (!is_string($date)) {
//                $res->isSuccess = FALSE;
//                $res->code = 423;
//                $res->message = "content는 String 이여야 합니다";
//                echo json_encode($res, JSON_NUMERIC_CHECK);
//                break;
//            }

//            if(!isValidDate($date)) {
//                $res->isSuccess = FALSE;
//                $res->code = 451;
//                $res->message = "date는 YYYY-MM-DD 이여야 합니다";
//                echo json_encode($res, JSON_NUMERIC_CHECK);
//                break;
//            }

            if(!isMenuExists($menuIdx)) {
                $res->isSuccess = FALSE;
                $res->code = 452;
                $res->message = "존재하지 않은 menuIdx 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
//            if(!isDateExists($date)) {
//                $res->isSuccess = FALSE;
//                $res->code = 453;
//                $res->message = "존재하지 않은 date 입니다";
//                echo json_encode($res, JSON_NUMERIC_CHECK);
//                break;
//            }
//            if(!isMenuExistsByDate($menuIdx, $date)) {
//                $res->isSuccess = FALSE;
//                $res->code = 454;
//                $res->message = "date에 menuIdx가 존재하지 않습니다";
//                echo json_encode($res, JSON_NUMERIC_CHECK);
//                break;
//            }

            if(!isReviewExistsByMenu($menuIdx)) {
                $res->isSuccess = FALSE;
                $res->code = 481;
                $res->message = "조회 할 review가 없습니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            $res->reviewList = getReview($menuIdx);
            $res->isSuccess = TRUE;
            $res->code = 200;
            $res->message = "리뷰 결과 조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        case "getReviewTotal":
            http_response_code(200);

            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];
            if (!isValidStudentHeader($jwt, JWT_SECRET_KEY)) {
                $res->isSuccess = FALSE;
                $res->code = 451;
                $res->message = "존재하지 않은 student 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $data = getDataByJWToken($jwt, JWT_SECRET_KEY);

            $studentIdx = getStudentIdx($data->id, $data->pw);
            $menuIdx = $_GET["menuIdx"];
            $menuIdx = isset($menuIdx) ? intval($menuIdx) : null;
//            $date = $_GET["date"];
//            $date = isset($date) ? intval($date) : null;

            if ($studentIdx == null) {
                $res->isSuccess = FALSE;
                $res->code = 411;
                $res->message = "studentIdx가 null 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if ($menuIdx == null) {
                $res->isSuccess = FALSE;
                $res->code = 412;
                $res->message = "menuIdx가 null 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
//            if ($date == null) {
//                $res->isSuccess = FALSE;
//                $res->code = 413;
//                $res->message = "date가 null 입니다";
//                echo json_encode($res, JSON_NUMERIC_CHECK);
//                break;
//            }
            if (!is_integer($studentIdx)) {
                $res->isSuccess = FALSE;
                $res->code = 421;
                $res->message = "studentIdx는 Int 이여야 합니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (!is_integer($menuIdx)) {
                $res->isSuccess = FALSE;
                $res->code = 422;
                $res->message = "menuIdx는 Int 이여야 합니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
//            if (!is_string($date)) {
//                $res->isSuccess = FALSE;
//                $res->code = 423;
//                $res->message = "content는 String 이여야 합니다";
//                echo json_encode($res, JSON_NUMERIC_CHECK);
//                break;
//            }

//            if(!isValidDate($date)) {
//                $res->isSuccess = FALSE;
//                $res->code = 451;
//                $res->message = "date는 YYYY-MM-DD 이여야 합니다";
//                echo json_encode($res, JSON_NUMERIC_CHECK);
//                break;
//            }

            if(!isMenuExists($menuIdx)) {
                $res->isSuccess = FALSE;
                $res->code = 452;
                $res->message = "존재하지 않은 menuIdx 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
//            if(!isDateExists($date)) {
//                $res->isSuccess = FALSE;
//                $res->code = 453;
//                $res->message = "존재하지 않은 date 입니다";
//                echo json_encode($res, JSON_NUMERIC_CHECK);
//                break;
//            }
//            if(!isMenuExistsByDate($menuIdx, $date)) {
//                $res->isSuccess = FALSE;
//                $res->code = 454;
//                $res->message = "date에 menuIdx가 존재하지 않습니다";
//                echo json_encode($res, JSON_NUMERIC_CHECK);
//                break;
//            }

            if(!isReviewExistsByMenu($menuIdx)) {
                $res->isSuccess = FALSE;
                $res->code = 481;
                $res->message = "조회 할 review가 없습니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            $res->reviewTotal = getReviewTotal($menuIdx);
            $res->isSuccess = TRUE;
            $res->code = 200;
            $res->message = "리뷰 결과 조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

    }
} catch (\Exception $e) {
    return getSQLErrorException($errorLogs, $e, $req);
}
