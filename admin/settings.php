<?php
	ob_start();
	session_start();
	$pageTitle = 'Settings';
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
        <h2 class="special-heading">Settings</h2>
        <form action="<?php echo $cont."AdminController.php?method=editProfile"?>" method="POST" class="form" enctype="multipart/form-data">
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
                <label for="email">Email</label>
                <input type="email" name="email" id="email" title="Enter Email" placeholder="Enter Email" required
                value="<?php if(isset($_SESSION['errors'])){ echo $email; } else { echo $_SESSION['admin']['email'];}?>">
            </div>
            <div>
                <label for="name">Name</label>
                <input type="text" name="name" id="name" title="Enter name" placeholder="Enter name" required
                value="<?php if(isset($_SESSION['errors'])) { echo $name ;} else { echo $_SESSION['admin']['name'];}?>">
            </div>
            <?php if ($_SESSION['admin']['role'] == 'admin') { ?>
            <div>
                <label for="civil_id">Civil Id</label>
                <input type="text" pattern="[0-9]{12}" maxlength="12" name="civil_id" id="civil_id" title="Enter Civil Id" placeholder="Enter Civil Id" required
                value="<?php if(isset($_SESSION['errors'])) { echo $civil_id ;} else { echo $_SESSION['admin']['civil_id'];}?>">
            </div>
            <?php } ?>
            <div>
                <label for="photo">Photo</label>
                <input type="file" title="upload photo" name="photo" id="photo" accept="image/jpg,image/jpeg,image/png"/>
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
                <input type="submit" name="edit_profile" value="Save Changes">
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