<?php
	ob_start();
	session_start();
	$pageTitle = 'Edit Supervisor';
	include 'init.php';
	include $tmp.'header.php';
  if(isset($_SESSION['errors'])) {
    $errors = $_SESSION['errors'];
    $oldData = $_SESSION['oldData'];
    extract($oldData);
  }
?>
	<div class="section" id="login" style="margin-bottom: 33px;">
      <div class="container">
        <h2 class="special-heading">Edit Supervisor</h2>
        <form action="<?php echo $cont."SupervisorController.php?method=edit"?>" method="POST" class="form">
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
            <input type="hidden" name="id" value="<?php echo $_SESSION['supervisor']['id']?>">
            <div>
                <label for="email">Email</label>
                <input type="email" name="email" id="email" title="Enter Email" placeholder="Enter Email" required
                value="<?php if(isset($_SESSION['errors'])){ echo $email; } else { echo $_SESSION['supervisor']['email'];}?>">
            </div>
            <div>
                <label for="name">Name</label>
                <input type="text" name="name" id="name" title="Enter name" placeholder="Enter name" required
                value="<?php if(isset($_SESSION['errors'])) { echo $name ;} else { echo $_SESSION['supervisor']['name'];}?>">
            </div>
            <div>
                <label for="current_password">Cuurent Password</label>
                <input type="password" name="current_password" id="current_password" title="Enter Cuurent Password" placeholder="Enter Cuurent Password">
            </div>
            <div>
                <label for="new_password">New Password</label>
                <input type="password" name="new_password" id="new_password" title="Enter New Password" placeholder="Enter New Password">
            </div>
            <div>
                <label for="confirm_password">Confirm Password</label>
                <input type="password" title="Enter Password again" name="confirm_password" id="confirm_password" placeholder="Enter Password again">
            </div>
            <div>
                <input type="submit" name="edit_supervisor" value="Save Changes">
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