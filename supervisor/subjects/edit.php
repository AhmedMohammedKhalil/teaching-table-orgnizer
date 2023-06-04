<?php
	ob_start();
	session_start();
	$pageTitle = 'Edit Subject';
	include 'init.php';
	include $tmp.'header.php';
  if(isset($_SESSION['errors'])) {
    $errors = $_SESSION['errors'];
    $oldData = $_SESSION['oldData'];
    extract($oldData);
  }
  $courses = $_SESSION['courses'];
  $semesters = $_SESSION['semesters'];
  $classes = $_SESSION['classes'];
  $professors = $_SESSION['professors'];
  $subject = $_SESSION['subject'];
?>
	<div class="section" id="login" style="margin-bottom: 33px;">
      <div class="container">
        <h2 class="special-heading">Edit Subject</h2>
        <form action="<?php echo $cont."SubjectController.php?method=edit"?>" method="POST" class="form">
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
            
            <input type="hidden" name="department_id" value="<?php echo $_SESSION['department_id']?>">
            <input type="hidden" name="id" value="<?php echo $subject['id']?>">

            
            <div>
                <label for="name">Name</label>
                <input type="text" name="name" id="name" title="Enter name" placeholder="Enter name" required
                value="<?php if(isset($_SESSION['errors'])) { echo $name ;} else {echo $subject['name'];} ?>">
            </div>

            <div>
                <label for="code">Code</label>
                <input type="text" name="code" id="code" title="Enter code" placeholder="Enter code" required
                value="<?php if(isset($_SESSION['errors'])) { echo $code ;} else {echo $subject['code'];} ?>">
            </div>
            <div>
                <label for="section_number">Section Number</label>
                <input type="text" name="section_number" id="section_number" title="Enter section number" placeholder="Enter section number" required
                value="<?php if(isset($_SESSION['errors'])) { echo $section_number ;} else {echo $subject['section_number'];}?>">
            </div>
            <div>
                <label for="type">Type</label>
                <select name="type" id="type" title="Choose Type" aria-placeholder="Choose Type">
                    <option value="ولاد" <?php if((isset($_SESSION['errors']) && $type == 'ولاد') || $subject['type'] == 'ولاد') echo 'selected'?>>ولاد</option>
                    <option value="بنات" <?php if((isset($_SESSION['errors']) && $type == 'بنات') || $subject['type'] == 'بنات') echo 'selected'?>>بنات</option>
                </select>
            </div>
            
            <div>
                <label for="course_id">Courses</label>
                <select name="course_id" id="course_id" title="Choose Course" aria-placeholder="Choose Course">
                    <?php foreach($courses as $course) { ?> 
                        <option value="<?php echo $course['id'] ?>" <?php if((isset($_SESSION['errors']) && $course_id == $course['id']) || $subject['course_id'] == $course['id']) echo 'selected'?>><?php echo $course['name']?></option>
                    <?php } ?>
                </select>
            </div>
            <div>
                <label for="professor_id">professors</label>
                <select name="professor_id" id="professor_id" title="Choose professor" aria-placeholder="Choose professor">
                    <?php foreach($professors as $professor) { ?> 
                        <option value="<?php echo $professor['id'] ?>" <?php if((isset($_SESSION['errors']) && $professor_id == $professor['id']) || $subject['professor_id'] == $professor['id']) echo 'selected'?>><?php echo $professor['name']?></option>
                    <?php } ?>
                </select>
            </div>
            <div>
                <label for="semester_id">Semesters</label>
                <select name="semester_id" id="semester_id" title="Choose Semester" aria-placeholder="Choose Semester">
                    <?php foreach($semesters as $semester) { ?> 
                        <option value="<?php echo $semester['id'] ?>" <?php if((isset($_SESSION['errors']) && $semester_id == $semester['id']) || $subject['semester_id'] == $semester['id']) echo 'selected'?>><?php echo $semester['name']." ".$semester['year']?></option>
                    <?php } ?>
                </select>
            </div>
            <div>
                <label for="class_id">Classes</label>
                <select name="class_id" id="class_id" title="Choose Class" aria-placeholder="Choose Class">
                    <?php foreach($classes as $class) { ?> 
                        <option value="<?php echo $class['id'] ?>" <?php if((isset($_SESSION['errors']) && $class_id == $class['id']) || $subject['class_id'] == $class['id']) echo 'selected'?>><?php echo $class['building_number']."/".$class['name']?></option>
                    <?php } ?>
                </select>
            </div>
            <div>
                <label for="days[]">Days</label>
                <select name="days[]" id="days[]" title="Choose Days" aria-placeholder="Choose Days" multiple required>
                    <?php foreach($_SESSION['days'] as $day) { ?> 
                        <option value="<?php echo $day['id'] ?>" <?php if((isset($_SESSION['errors']) && in_array($day['id'],$days)) || in_array($day['id'],$subject['days'])) echo 'selected'?>><?php echo $day['name']?></option>
                    <?php } ?>
                </select>
            </div>
            <div>
                <label for="start_at">Start Time</label>
                <input type="text" name="start_at" id="start_at" pattern="^([0-1]?[0-9]):([0-5][0-9])$" title="Enter Start Time with 24 hours as 13:00" placeholder="Enter Start Time with 24 hours as 13:00" required
                value="<?php if(isset($_SESSION['errors'])) { echo $start_at ;} else {echo $subject['start_at'];} ?>">
            </div>
            <div>
                <label for="end_at">End Time</label>
                <input type="text" name="end_at" id="end_at" pattern="^([0-1]?[0-9]):([0-5][0-9])$" title="Enter End Time with 24 hours as 14:00" placeholder="Enter End Time with 24 hours as 14:00" required
                value="<?php if(isset($_SESSION['errors'])) { echo $end_at ;} else {echo $subject['end_at'];} ?>">
            </div>
            <div>
                <label for="capacity">Capacity</label>
                <input type="number" min="1" name="capacity" id="capacity" title="Enter capacity" placeholder="Enter capacity" required
                value="<?php if(isset($_SESSION['errors'])) { echo $capacity ;} else {echo $subject['capacity'];} ?>">
            </div>
            <div>
                <input type="submit" name="edit_subject" value="Save Changes">
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