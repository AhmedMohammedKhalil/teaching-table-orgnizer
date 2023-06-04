<?php
	ob_start();
	session_start();
	$pageTitle = 'Supervisor Dashboard';
	include 'init.php';
	include $tmp.'header.php';
  
?>
  <?php if(isset($_SESSION['msg'])) { ?>
        <p style="color:black;background:#8bfa8b;padding:20px;margin:0">
            <?php 
                echo $_SESSION['msg'] ;
                unset($_SESSION['msg']);
            ?>
        </p>
  <?php } ?>
	<div class="section" style="min-height: calc(100vh - 172px);">
        <div class="container">
          <h2 class="special-heading">Control</h2>
          <div style="display:flex;justify-content:space-evenly;margin:30px">
            <a class="button" href="<?php echo $cont.'SemesterController.php?method=showSemesters' ?>">All Semesters</a>
            <a class="button" href="<?php echo $cont.'DepartmentController.php?method=showDepartments' ?>">All Departments</a>  
            <a class="button" href="<?php echo $cont.'CourseController.php?method=showCourses' ?>">All Courses</a>  
            <a class="button" href="<?php echo $cont.'TableController.php?method=showProfessorTable' ?>">All Tables</a>  
          </div>
        </div>
    </div>
<?php
	include $tmp . 'footer.php';
	ob_end_flush();
?>