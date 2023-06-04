<?php
	ob_start();
	session_start();
	$pageTitle = 'Edit Department';
	include 'init.php';
	include $tmp.'header.php';
  if(isset($_SESSION['errors'])) {
    $errors = $_SESSION['errors'];
    $oldData = $_SESSION['oldData'];
    extract($oldData);
  }
  $department = $_SESSION['department'];
?>
	<div class="section" id="login" style="margin-bottom: 33px;">
      <div class="container">
        <h2 class="special-heading">Edit Department</h2>
        <form action="<?php echo $cont."DepartmentController.php?method=edit"?>" method="POST" class="form">
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
            <input type="hidden" name="id" value="<?php echo $department['id']?>">
            </div>
            <div>
                <label for="name">Name</label>
                <input type="text" name="name" id="name" title="Enter name" placeholder="Enter name" required
                value="<?php if(isset($_SESSION['errors'])) { echo $name ;} else { echo $department['name'];} ?>">
            </div>
            <div>
                <label for="code">Code</label>
                <input type="text" name="code" id="code" title="Enter code" placeholder="Enter code" required
                value="<?php if(isset($_SESSION['errors'])) { echo $code ;} else { echo $department['code'];} ?>">
            </div>
            <div>
                <input type="submit" name="edit_department" value="Save Changes">
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