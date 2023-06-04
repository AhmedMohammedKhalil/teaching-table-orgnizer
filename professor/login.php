<?php
	ob_start();
	session_start();
	$pageTitle = 'Professor Login';
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
        <h2 class="special-heading">Professor Login</h2>
        <form action="<?php echo $cont."ProfessorController.php?method=login"?>" method="POST" class="form">
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
                value="<?php if(isset($_SESSION['errors'])) echo $email?>">
            </div>
            <div>
                <label for="password">Password</label>
                <input type="password" name="password" id="password" title="Enter Password" placeholder="Enter Password" required>
            </div>
            <div>
                <span>if don't have account <a href="<?php echo $cont.'ProfessorController.php?method=showRegister'?>">Make Register</a></span>
            </div>
            <div>
              <input type="submit" name="professor_login" value="Login">
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