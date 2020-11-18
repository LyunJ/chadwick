<?php

function createReview($menuIdx, $date, $studentIdx, $score, $content)
{
    $pdo = pdoSqlConnect();

    $query = "INSERT INTO review (menuIdx, createdAt, studentIdx, score, content) VALUES (?, ?, ?, ?, ?);";

    $st = $pdo->prepare($query);
    $st->execute([$menuIdx, $date, $studentIdx, $score, $content]);

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

function isReviewExists($studentIdx, $menuIdx)
{
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(SELECT * FROM review WHERE studentIdx = ? and menuIdx = ? and isDeleted = 'N') AS exist;";

    $st = $pdo->prepare($query);
    $st->execute([$studentIdx, $menuIdx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return intval($res[0]["exist"]);
}

function modifyReview($menuIdx, $date, $studentIdx, $score, $content)
{
    $pdo = pdoSqlConnect();

    $query = "UPDATE review SET score = ?, createdAt = ?, content = ? WHERE studentIdx = ? and menuIdx = ?;";

    $st = $pdo->prepare($query);
    $st->execute([$score, $date, $content, $studentIdx, $menuIdx]);

    $st = null;
    $pdo = null;
}

function isMenuExistsByDate($menuIdx, $date)
{
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(SELECT * FROM review WHERE menuIdx = ? and date = ? and isDeleted = 'N') AS exist;";

    $st = $pdo->prepare($query);
    $st->execute([$menuIdx, $date]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return intval($res[0]["exist"]);
}


function isReviewExistsByMenu($menuIdx)
{
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(SELECT * FROM review WHERE menuIdx = ? and isDeleted = 'N') AS exist;";

    $st = $pdo->prepare($query);
    $st->execute([$menuIdx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return intval($res[0]["exist"]);
}

function getReview($menuIdx)
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
                where menuIdx = ?;";

    $st = $pdo->prepare($query);
    $st->execute([$menuIdx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

function getReviewTotal($menuIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select AVG(score) as totalScore from review where menuIdx = ? group by menuIdx;";

    $st = $pdo->prepare($query);
    $st->execute([$menuIdx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0];
}