<?php
ob_start();
session_start();
include('init.php');
$pageTitle = "Subjects";
include($tmp . 'header.php');
$subjects = $_SESSION['subjects'];
?>
<?php if (isset($_SESSION['msg'])) { ?>
  <p style="color:black;background:#8bfa8b;padding:20px;margin:0">
    <?php
    echo $_SESSION['msg'];
    unset($_SESSION['msg']);
    ?>
  </p>
<?php } ?>
<div class="section" id="subjects" style="min-height: 76.3vh;">
  <div class="container" style="margin-top:30px">
    <h2 class="special-heading">All Subjects</h2>
    
    <table id="table">
      <tr>
        <th>#</th>
        <th>Subject_name</th>
        <th>subject_number</th>
        <th>Group</th>
        <th>Building</th>
        <th>Class</th>
        <th>Capacity</th>
        <th>Day</th>
        <th>Time</th>
      </tr>
      <?php
        $i = 1;
        foreach ($subjects as $s) {
      ?>
        <tr>
          <td><?php echo $i;
              $i++ ?></td>
          <td><?php echo $s['name'] ?></td>
          <td><?php echo $s['dep_code']." ".$s['code']?></td>
          <td><?php echo $s['section_number'] ?></td>
          <td><?php echo $s['building'] ?></td>
          <td><?php echo $s['class_name'] ?></td>
          <td><?php echo $s['capacity'] ?></td>
          <td><?php echo $s['days'] ?></td>
          <td><?php echo $s['start_at']."-".$s['end_at'] ?></td>
        </tr>
      <?php }
      
      if (empty($subjects)) {
        echo "<tr><td colspan='9'>Subjects Not Found </td></tr>";
      }
      ?>
    </table>

    <h2 class="special-heading">All Days</h2>
    
    <table id="table">
      <tr>
        <th>#</th>
        <th>Day</th>
      </tr>
      <?php
        $i = 1;
        foreach ($_SESSION['days'] as $d) {
      ?>
        <tr>
          <td><?php echo $i;
              $i++ ?></td>
          <td><?php echo $d['name'] ?></td>
        </tr>
      <?php } ?>
    </table>
  </div>
</div>


<?php
include($tmp . 'footer.php');
ob_end_flush();
    //unset($_SESSION['subjects']);