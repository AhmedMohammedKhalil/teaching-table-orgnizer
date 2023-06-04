<?php
ob_start();
session_start();
include('init.php');
$pageTitle = "Semesters";
include($tmp . 'header.php');
$semesters = $_SESSION['semesters'];

?>
<?php if (isset($_SESSION['msg'])) { ?>
  <p style="color:black;background:#8bfa8b;padding:20px;margin:0">
    <?php
    echo $_SESSION['msg'];
    unset($_SESSION['msg']);
    ?>
  </p>
<?php } ?>
<div class="section" id="semesters" style="min-height: 76.3vh;">
  <div style="text-align: center;">
    <a class="button" href="<?php  echo $cont . 'SemesterController.php?method=showAddForm' ?>">Add Semester</a>
  </div>
  <div class="container" style="margin-top:30px">
    <h2 class="special-heading">All Semesters</h2>
    <table id="table">
      <tr>
        <th>#</th>
        <th>Semester</th>
        <th>Year</th>
        <th>Control</th>
      </tr>
      <?php $i = 1;
      foreach ($semesters as $s) {
      ?>
        <tr>
          <td><?php echo $i;
              $i++ ?></td>
          <td><?php echo $s['name'] ?></td>
          <td><?php echo $s['year'] ?></td>
          <td>
            <div class="flex" style="flex-direction: row;margin: 0;justify-content:space-evenly">
              <a href="<?php echo $cont . 'SemesterController.php?method=showEditForm&id=' . $s['id'] ?>">edit</a>
              <a href="<?php echo $cont . 'SemesterController.php?method=delete&id=' . $s['id'] ?>">delete</a>
            </div>
          </td>
        </tr>
      <?php }
      if (empty($semesters)) {
        echo "<tr><td colspan='4'>Semesters Not Found </td></tr>";
      }
      ?>

    </table>
  </div>
</div>


<?php
include($tmp . 'footer.php');
ob_end_flush();
    //unset($_SESSION['semesters']);