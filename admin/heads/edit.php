<?php
	ob_start();
	session_start();
	$pageTitle = 'Edit Department Head';
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
        <h2 class="special-heading">Edit Department Head</h2>
        <form action="<?php echo $cont."HeadController.php?method=edit"?>" method="POST" class="form">
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
          <input type="hidden" name="id" value="<?php echo $_SESSION['head']['id'];?>">
          <div>
                <label for="email">Email</label>
                <input type="email" name="email" id="email" title="Enter Email" placeholder="Enter Email" required
                value="<?php if(isset($_SESSION['errors'])){ echo $email; } else { echo $_SESSION['head']['email'];}?>">
            </div>
            <div>
                <label for="name">Name</label>
                <input type="text" name="name" id="name" title="Enter name" placeholder="Enter name" required
                value="<?php if(isset($_SESSION['errors'])) { echo $name ;} else { echo $_SESSION['head']['name'];}?>">
            </div>

            <div>
                <label for="mobile">Mobile</label>
                <input type="text" name="mobile" id="mobile" title="Enter Mobile" placeholder="Enter Mobile" required
                value="<?php if(isset($_SESSION['errors'])) { echo $mobile ;} else { echo $_SESSION['head']['mobile'];}?>" pattern="[0-9]{8}" maxlength="8">
            </div>

            <div>
                <label for="office_phone">Office Phone</label>
                <input type="text" name="office_phone" id="office_phone" title="Enter Office Phone" placeholder="Enter Office Phone" required
                value="<?php if(isset($_SESSION['errors'])) { echo $office_phone ;} else { echo $_SESSION['head']['office_phone'];}?>" pattern="[0-9]{8}" maxlength="8">
            </div>

            <div>
                <label for="office_location">Office Location</label>
                <input type="text" name="office_location" id="office_location" title="Enter Office Location" placeholder="Enter Office Location" required
                value="<?php if(isset($_SESSION['errors'])) { echo $office_location ;} else { echo $_SESSION['head']['office_location'];}?>">
            </div>
            <div>
              <label for="department_id">Departments</label>
              <select name="department_id" id="department_id" title="choose Department" aria-placeholder="choose Department">
                  <?php foreach($departments as $department) { ?> 
                      <option value="<?php echo $department['id'] ?>" <?php if((isset($_SESSION['errors']) && $department_id == $department['id']) || ($department['id'] == $_SESSION['head']['department_id'])) echo 'selected'?>><?php echo $department['name']?></option>
                  <?php } ?>
              </select>
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
                <input type="submit" name="edit_head" value="Save Changes">
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