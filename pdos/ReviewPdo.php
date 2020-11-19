<?php

function createReview($foodIdx, $date, $studentIdx, $score, $content)
{
    $pdo = pdoSqlConnect();

    $query = "INSERT INTO review (foodIdx, date, studentIdx, score, content) VALUES (?, ?, ?, ?, ?);";

    $st = $pdo->prepare($query);
    $st->execute([$foodIdx, $date, $studentIdx, $score, $content]);

    $recruitId = $pdo->lastInsertId();
    $st = null;
    $pdo = null;

    return $recruitId;
}

function isMenuExists($menuIdx)
{
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(SELECT * FROM menu WHERE menuIdx = ? and isDeleted = 'N') AS exist;";

    $st = $pdo->prepare($query);
    $st->execute([$menuIdx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return intval($res[0]["exist"]);
}

function isFoodExists($foodIdx, $date)
{
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(SELECT * FROM MenuTable WHERE foodCategoryIdx = ?  and date = ? and isDeleted = 'N') AS exist;";

    $st = $pdo->prepare($query);
    $st->execute([$foodIdx, $date]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return intval($res[0]["exist"]);
}

function isDateExists($date)
{
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(SELECT * FROM review WHERE date = ? and isDeleted = 'N') AS exist;";

    $st = $pdo->prepare($query);
    $st->execute([$date]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return intval($res[0]["exist"]);
}

function isReviewExists($studentIdx, $foodIdx, $date)
{
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(SELECT * FROM review WHERE studentIdx = ? and foodIdx = ? and date = ? and isDeleted = 'N') AS exist;";

    $st = $pdo->prepare($query);
    $st->execute([$studentIdx, $foodIdx, $date]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return intval($res[0]["exist"]);
}

function modifyReview($foodIdx, $date, $studentIdx, $score, $content)
{
    $pdo = pdoSqlConnect();

    $query = "UPDATE review SET score = ?, content = ? WHERE studentIdx = ? and foodIdx = ? and date = ?;";

    $st = $pdo->prepare($query);
    $st->execute([$score, $content, $studentIdx, $foodIdx, $date]);

    $st = null;
    $pdo = null;
}

function isMenuExistsByDate($foodIdx, $date)
{
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(SELECT * FROM review WHERE foodIdx = ? and date = ? and isDeleted = 'N') AS exist;";

    $st = $pdo->prepare($query);
    $st->execute([$foodIdx, $date]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return intval($res[0]["exist"]);
}


function isReviewExistsByMenu($foodIdx, $date)
{
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(SELECT * FROM review WHERE foodIdx = ? and date = ? and isDeleted = 'N') AS exist;";

    $st = $pdo->prepare($query);
    $st->execute([$foodIdx, $date]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return intval($res[0]["exist"]);
}

function getReview($foodIdx, $date)
{
    $pdo = pdoSqlConnect();
    $query = "select reviewIdx, score, content,
                       case
                           when (timestampdiff(hour, createdAt, now()) > 24 )
                               then concat(YEAR(createdAt), '.', MONTH(createdAt), '.', DAY(createdAt))
                           when (timestampdiff(minute , createdAt, now()) > 60)
                               then concat(timestampdiff(hour, createdAt, now()),'시간전')
                           when (timestampdiff(second, createdAt, now()) > 60)
                               then concat(timestampdiff(minute , createdAt, now()),'분전')
                           end as createdAt
                from review
                where foodIdx = ? and date = ? and isDeleted = 'N';";

    $st = $pdo->prepare($query);
    $st->execute([$foodIdx, $date]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

function getReviewTotal($foodIdx, $date)
{
    $pdo = pdoSqlConnect();
    $query = "select AVG(score) as totalScore from review where foodIdx = ? and date = ? and isDeleted = 'N' group by foodIdx, date;";

    $st = $pdo->prepare($query);
    $st->execute([$foodIdx, $date]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0];
}