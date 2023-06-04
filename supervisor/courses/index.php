<?php
ob_start();
session_start();
include('init.php');
$pageTitle = "Courses";
include($tmp . 'header.php');
$courses = $_SESSION['courses'];

?>
<?php if (isset($_SESSION['msg'])) { ?>
  <p style="color:black;background:#8bfa8b;padding:20px;margin:0">
    <?php
    echo $_SESSION['msg'];
    unset($_SESSION['msg']);
    ?>
  </p>
<?php } ?>
<div class="section" id="courses" style="min-height: 76.3vh;">
  <div style="text-align: center;">
    <a class="button" href="<?php  echo $cont . 'CourseController.php?method=showAddForm' ?>">Add Course</a>
  </div>
  <div class="container" style="margin-top:30px">
    <h2 class="special-heading">All Courses</h2>
    <table id="table">
      <tr>
        <th>#</th>
        <th>Name</th>
        <th>Department</th>
        <th>Control</th>
      </tr>
      <?php $i = 1;
      foreach ($courses as $c) {
      ?>
        <tr>
          <td><?php echo $i;
              $i++ ?></td>
          <td><?php echo $c['name'] ?></td>
          <td><?php echo $c['department_name'] ?></td>

          <td>
            <div class="flex" style="flex-direction: row;margin: 0;justify-content:space-evenly">
              <a href="<?php echo $cont . 'CourseController.php?method=showEditForm&id=' . $c['id'] ?>">edit</a>
              <a href="<?php echo $cont . 'CourseController.php?method=delete&id=' . $c['id'] ?>">delete</a>
            </div>
          </td>
        </tr>
      <?php }
      if (empty($courses)) {
        echo "<tr><td colspan='4'>Courses Not Found </td></tr>";
      }
      ?>

    </table>
  </div>
</div>


<?php
include($tmp . 'footer.php');
ob_end_flush();
    //unset($_SESSION['courses']);