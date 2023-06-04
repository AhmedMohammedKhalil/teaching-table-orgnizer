<?php
	ob_start();
	session_start();
	$pageTitle = 'Edit Office Hour';
	include 'init.php';
	include $tmp.'header.php';
  if(isset($_SESSION['errors'])) {
    $errors = $_SESSION['errors'];
    $oldData = $_SESSION['oldData'];
    extract($oldData);
  }
  
  $days = $_SESSION['days'];
  $officeHour = $_SESSION['officeHour'];
?>
	<div class="section" id="login" style="margin-bottom: 33px;">
      <div class="container">
        <h2 class="special-heading">Edit Office Hour</h2>
        <form action="<?php echo $cont."OfficeHourController.php?method=edit"?>" method="POST" class="form">
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
            
            <input type="hidden" name="professor_id" value="<?php echo $officeHour['professor_id']?>">
            <input type="hidden" name="id" value="<?php echo $officeHour['id']?>">

            
            <div>
                <label for="day_id">Days</label>
                <select name="day_id" id="day_id" title="Choose Day" aria-placeholder="Choose Day">
                    <?php foreach($days as $day) { ?> 
                        <option value="<?php echo $day['id'] ?>" <?php if((isset($_SESSION['errors']) && $day_id == $day['id']) || ($officeHour['day_id'] == $day['id'])) echo 'selected'?>><?php echo $day['name']?></option>
                    <?php } ?>
                </select>
            </div>
            <div>
                <label for="start_at">Start Time</label>
                <input type="text" name="start_at" id="start_at" pattern="^([0-1]?[0-9]):([0-5][0-9])$" title="Enter Start Time with 24 hours as 13:00" placeholder="Enter Start Time with 24 hours as 13:00" required
                value="<?php if(isset($_SESSION['errors'])) { echo $start_at ;} else {echo $officeHour['start_at'] ;} ?>">
            </div>
            <div>
                <label for="end_at">End Time</label>
                <input type="text" name="end_at" id="end_at" pattern="^([0-1]?[0-9]):([0-5][0-9])$" title="Enter End Time with 24 hours as 14:00" placeholder="Enter End Time with 24 hours as 14:00" required
                value="<?php if(isset($_SESSION['errors'])) { echo $end_at ;} else {echo $officeHour['end_at'] ;} ?>">
            </div>

            <div>
                <input type="submit" name="edit_officeHour" value="edit">
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