<?php
include_once('connect.php');
function selectOne($select,$table,$where = null, $join = null) {
    global $con;
    if($where != null)
        $where = "WHERE ".$where;
    if($join != null)
        $join = "JOIN ".$join;
    try {
        $statment = $con->prepare("SELECT $select FROM $table $join $where");
        $statment->execute();
    } catch (PDOException $e){
        echo $e;
        //header('location: ../../errors/Error.php');
    }

    $row = $statment->fetch(PDO::FETCH_ASSOC);

    return $row;
}

function selectAll($select,$table,$where = null , $join = null, $order = null ) {
    global $con;
    if($where != null)
        $where = "WHERE ".$where;
    if($order != null)
        $order = "ORDER BY ".$order;
    if($join != null)
        $join = "JOIN ".$join;
    try {
        $statment = $con->prepare("SELECT $select FROM $table $join $where $order");
        $statment->execute();
    } catch (PDOException $e){
        echo $e;

        //header('location: ../../errors/Error.php');
    }
    $rows = $statment->fetchAll(PDO::FETCH_ASSOC);

    return $rows;
}

function insert($keys,$table,$values,$data ) {
    global $con;
    try {

    $statment = $con->prepare("INSERT INTO $table($keys) VALUES({$values})");
    $statment->execute($data);

    }catch (PDOException $e){
        echo $e;
        //header('location: ../../errors/Error.php');
    }
    $id = $con->lastInsertId();
    return $id;
}

function insertAll($keys,$table,$values,$data ) {
    global $con;
    try {

    $statment = $con->prepare("INSERT INTO $table($keys) VALUES({$values})");
    $statment->execute($data);

    }catch (PDOException $e){
        echo $e;
        exit();
        //header('location: ../../errors/Error.php');
    }
}


function update($updated,$table,$data,$where = null) {
    global $con;
    if($where != null)
        $where = "WHERE ".$where;
    try {
        $statment = $con->prepare("UPDATE $table SET $updated $where");
        $success = $statment->execute($data);
    } catch (PDOException $e){
        echo $e;
        //header('location: ../../errors/Error.php');
    }
    return $success;
}


function destroy($table,$where,$data) {
    global $con;
    if($where != null)
        $where = "WHERE ".$where;
    try {
        $statment = $con->prepare("DELETE FROM $table $where");
        $success = $statment->execute($data);
    } catch (PDOException $e){
        echo $e;
        //header('location: ../../errors/Error.php');
    }
    return $success;
}


function unsetAllSession() {
    $requiredSessionVar = array('admin','professor','username');
    foreach($_SESSION as $key => $value) {
        if(!in_array($key, $requiredSessionVar)) {
            unset($_SESSION[$key]);
        }
    }
}