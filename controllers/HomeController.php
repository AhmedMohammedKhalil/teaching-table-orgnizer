<?php 
session_start();
include_once('../layout/functions/functions.php');
$method = $_GET['method'];
if($method != "") {
    $home = new HomeController();
    if($method == 'showAboutUs') {
        $home->showAboutUs();
    }
}

class HomeController {

    public function showAboutUs() {
        header('location: ../aboutus.php');
    }
    
}