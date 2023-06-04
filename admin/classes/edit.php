<?php
	ob_start();
	session_start();
	$pageTitle = 'Edit Class';
	include 'init.php';
	include $tmp.'header.php';
  if(isset($_SESSION['errors'])) {
    $errors = $_SESSION['errors'];
    $oldData = $_SESSION['oldData'];
    extract($oldData);
  }
  $class = $_SESSION['class'];
?>
	<div class="section" id="login" style="margin-bottom: 33px;">
      <div class="container">
        <h2 class="special-heading">Edit Class</h2>
        <form action="<?php echo $cont."ClassController.php?method=edit"?>" method="POST" class="form">
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
            <input type="hidden" name="id" value="<?php echo $class['id']?>">
            </div>
            <div>
                <label for="name">Name</label>
                <input type="text" name="name" id="name" title="Enter name" placeholder="Enter name" required
                value="<?php if(isset($_SESSION['errors'])) { echo $name ;} else { echo $class['name'];} ?>">
            </div>
            <div>
                <label for="building_number">Building Number</label>
                <input type="text" name="building_number" id="building_number" title="Enter Building Number" placeholder="Enter Building Number" required
                value="<?php if(isset($_SESSION['errors'])) { echo $building_number ;} else { echo $class['building_number'];} ?>">
            </div>
            <div>
                <label for="capacity">Capacity</label>
                <input type="number" name="capacity" id="capacity" title="Enter Capacity" min="1" placeholder="Enter Capacity" required
                value="<?php if(isset($_SESSION['errors'])) { echo $capacity ;} else { echo $class['capacity'];} ?>">
            </div>
            <div>
                <input type="submit" name="edit_class" value="Save Changes">
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