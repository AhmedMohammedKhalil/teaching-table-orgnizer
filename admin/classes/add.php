<?php
	ob_start();
	session_start();
	$pageTitle = 'Add Class';
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
        <h2 class="special-heading">Add Class</h2>
        <form action="<?php echo $cont."ClassController.php?method=add"?>" method="POST" class="form">
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
                <label for="building_number">Building Number</label>
                <input type="text" name="building_number" id="building_number" title="Enter Building Number" placeholder="Enter Building Number" required
                value="<?php if(isset($_SESSION['errors'])) { echo $building_number ;} ?>">
            </div>
            <div>
                <label for="capacity">Capacity</label>
                <input type="number" name="capacity" id="capacity" title="Enter Capacity" min="1" placeholder="Enter Capacity" required
                value="<?php if(isset($_SESSION['errors'])) { echo $capacity ;} ?>">
            </div>
            <div>
                <input type="submit" name="add_class" value="Add">
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