<?php
	ob_start();
	session_start();
	$pageTitle = 'Professor Register';
	include 'init.php';
	include $tmp.'header.php';
  if(isset($_SESSION['errors'])) {
    $errors = $_SESSION['errors'];
    $oldData = $_SESSION['oldData'];
    extract($oldData);
  }
  $departments = $_SESSION['departments'];
?>
	<div class="section" id="register" style="margin-bottom: 33px;">
      <div class="container">
        <h2 class="special-heading">Professor Register</h2>
        <form action="<?php echo $cont."ProfessorController.php?method=register"?>" method="POST" class="form">
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
              <label for="name">name</label>
              <input type="text" name="name" id="name" title="Enter Name" placeholder="Enter Name" required
                value="<?php if(isset($_SESSION['errors'])) echo $name?>">
            </div>
            <div>
              <label for="email">Email</label>
              <input type="email" name="email" id="email" title="Enter Email" placeholder="Enter Email" required
                value="<?php if(isset($_SESSION['errors'])) echo $email?>">
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
              <label for="office_location">Office Location</label>
              <input type="text" name="office_location" id="office_location" title="Enter Office Location" placeholder="Enter Office Location" required
                value="<?php if(isset($_SESSION['errors'])) echo $office_location?>">
            </div>
            <div>
              <label for="office_phone">Office Phone</label>
              <input type="text" name="office_phone" id="office_phone" title="Enter Office Phone" placeholder="Enter Office Phone" required
                value="<?php if(isset($_SESSION['errors'])) echo $office_phone?>"  pattern="[0-9]{8}" maxlength="8">
            </div>
            <div>
                <label class="label" for="mobile">mobile</label>
                <input type="text" title="Enter mobile" name="mobile" id="mobile" placeholder="Enter mobile" required
                value="<?php if(isset($_SESSION['errors'])) echo $mobile?>" pattern="[0-9]{8}" maxlength="8">
            </div>
            
            <div>
                <label for="password">Password</label>
                <input type="password" name="password" id="password" title="Enter Password" placeholder="Enter Password" required>
            </div>
            <div>
                <label for="confirm_password">Confirm Password</label>
                <input type="password" title="Enter Password again" name="confirm_password" id="confirm_password" placeholder="Enter Password again" required>
            </div>
            
            <div>
                <span>if you have account <a href="<?php echo $cont.'ProfessorController.php?method=showLogin'?>">Make Login</a></span>
            </div>
            <div>
              <input type="submit" name="professor_register" value="Register">
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