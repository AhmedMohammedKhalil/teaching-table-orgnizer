<?php
ob_start();
session_start();
include('init.php');
$pageTitle = "Departments";
include($tmp . 'header.php');
$departments = $_SESSION['departments'];

?>
<?php if (isset($_SESSION['msg'])) { ?>
  <p style="color:black;background:#8bfa8b;padding:20px;margin:0">
    <?php
    echo $_SESSION['msg'];
    unset($_SESSION['msg']);
    ?>
  </p>
<?php } ?>
<div class="section" id="departments" style="min-height: 76.3vh;">
  <div style="text-align: center;">
    <a class="button" href="<?php  echo $cont . 'DepartmentController.php?method=showAddForm' ?>">Add Department</a>
  </div>
  <div class="container" style="margin-top:30px">
    <h2 class="special-heading">All Departments</h2>
    <table id="table">
      <tr>
        <th>#</th>
        <th>Name</th>
        <th>All Subjects</th>
        <th>Control</th>
      </tr>
      <?php $i = 1;
      foreach ($departments as $d) {
      ?>
        <tr>
          <td><?php echo $i;
              $i++ ?></td>
          <td><?php echo $d['name'] ?></td>
          <td> <div class="flex" style="flex-direction: row;margin: 0;justify-content:space-evenly">
              <a href="<?php echo $cont . 'SubjectController.php?method=showSubjects&id=' . $d['id'] ?>">Show Supjects</a>
            </div>
          </td>
          <td>
            <div class="flex" style="flex-direction: row;margin: 0;justify-content:space-evenly">
              <a href="<?php echo $cont . 'DepartmentController.php?method=showEditForm&id=' . $d['id'] ?>">edit</a>
              <a href="<?php echo $cont . 'DepartmentController.php?method=delete&id=' . $d['id'] ?>">delete</a>
            </div>
          </td>
        </tr>
      <?php }
      if (empty($departments)) {
        echo "<tr><td colspan='3'>Departments Not Found </td></tr>";
      }
      ?>

    </table>
  </div>
</div>


<?php
include($tmp . 'footer.php');
ob_end_flush();
    //unset($_SESSION['departments']);