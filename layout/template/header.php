<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		<title><?php echo $pageTitle?></title>

		<link rel="stylesheet" href="<?php echo $css?>normalize.css" />
    <link rel="stylesheet" href="<?php echo $css?>all.min.css" />
    	<link rel="stylesheet" href="<?php echo $css?>style.css" />
     

      
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;700&display=swap" rel="stylesheet" />
    </head>
	
	<body>

  
    <!-- Start Header -->
    <div class="header" style="position:sticky; top:0; background:white; z-index:99999">
      <div class="container">
        <img class="logo" style="width:160px;height:70px" src="<?php echo $imgs.'logo.jpeg' ?>" alt="logo photo" />
        <div class="links">
          <ul>
            <li><a href="<?php echo $app?>index.php">Home</a></li>
            <li><a href="<?php echo $cont.'HomeController.php?method=showAboutUs'?>">About Us</a></li>
          </ul>
        </div>
        <div class="links">
		  <?php if(!isset($_SESSION['username'])) { ?>
          <ul>
            <li><a href="<?php echo $cont.'AdminController.php?method=showLogin'?>">Admin Login</a></li>
            <li><a href="<?php echo $cont.'ProfessorController.php?method=showLogin'?>">Professor Login</a></li>
          </ul>
		  <?php } ?>

        
      <?php if(isset($_SESSION['admin']) && $_SESSION['admin']['role'] == 'admin') { ?>
          <ul>
            <li><a href="<?php echo $cont.'AdminController.php?method=showProfile'?>">Profile</a></li>
            <li><a href="<?php echo $cont.'AdminController.php?method=dashboard'?>">Dashboard</a></li>
            <li><a href="<?php echo $cont.'AdminController.php?method=logout'?>">Logout</a></li>
          </ul>
		  <?php } ?>

      <?php if(isset($_SESSION['admin']) && $_SESSION['admin']['role'] == 'supervisor') { ?>
          <ul>
            <li><a href="<?php echo $cont.'AdminController.php?method=showProfile'?>">Profile</a></li>
            <li><a href="<?php echo $cont.'AdminController.php?method=dashboard'?>">Dashboard</a></li>
            <li><a href="<?php echo $cont.'AdminController.php?method=logout'?>">Logout</a></li>
          </ul>
		  <?php } ?>
 

		  <?php if(isset($_SESSION['professor']) && $_SESSION['professor']['role'] == 'professor') { ?>
          <ul>
            <li><a href="<?php echo $cont.'ProfessorController.php?method=showProfile'?>">Profile</a></li>
            <li><a href="<?php echo $cont.'ProfessorController.php?method=dashboard'?>">Dashboard</a></li>
            <li><a href="<?php echo $cont.'ProfessorController.php?method=logout'?>">Logout</a></li>
          </ul>
		  <?php } ?>

      <?php if(isset($_SESSION['professor']) && $_SESSION['professor']['role'] == 'head') { ?>
          <ul>
            <li><a href="<?php echo $cont.'ProfessorController.php?method=showProfile'?>">Profile</a></li>
            <li><a href="<?php echo $cont.'ProfessorController.php?method=dashboard'?>">Dashboard</a></li>
            <li><a href="<?php echo $cont.'ProfessorController.php?method=logout'?>">Logout</a></li>
          </ul>
		  <?php } ?>
      </div>
      </div>
    </div>
