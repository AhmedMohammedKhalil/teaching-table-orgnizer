<?php
	ob_start();
	session_start();
	$pageTitle = 'Add Course';
	include 'init.php';
	include $tmp.'header.php';
  if(isset($_SESSION['errors'])) {
    $errors = $_SESSION['errors'];
    $oldData = $_SESSION['oldData'];
    extract($oldData);
  }
  $departments = $_SESSION['departments'];
?>
	<div class="section" id="login" style="margin-bottom: 33px;">
      <div class="container">
        <h2 class="special-heading">Add Course</h2>
        <form action="<?php echo $cont."CourseController.php?method=add"?>" method="POST" class="form">
          <?php
            if(isset($_SESSION['errors'])) {
              echo '<ol style="width:fit-content;margin: 0 auto">';
              foreach($errors as $e) {
                echo '<li style="color: red">'.$e.'</li>';
              }
              echo '</ol>';
            }
          ?>
          <div style="display: flex;justify-content:center;flex-direction:column">
            
            <div>
                <label for="name">Name</label>
                <input type="text" name="name" id="name" title="Enter name" placeholder="Enter name" required
                value="<?php if(isset($_SESSION['errors'])) { echo $name ;} ?>">
            </div>

            <div>
                <label for="department_id">Departments</label>
                <select name="department_id" id="department_id" title="choose Department" aria-placeholder="choose Department">
                    <?php foreach($departments as $department) { ?> 
                        <option value="<?php echo $department['id'] ?>" <?php if(isset($_SESSION['errors']) && $department_id == $department['id']) echo 'selected'?>><?php echo $department['name']?></option>
                    <?php } ?>
                </select>
            </div>
              
            <div>
                <input type="submit" name="add_course" value="Add">
            </div>
          </div>
        </form>
      </div>
    </div>
<?php
	include $tmp . 'footer.php';
	ob_end_flush();
    unset($_SESSION['oldData']);
    unset($_SESSION['errors']);
?>