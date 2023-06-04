<?php
ob_start();
session_start();
include('init.php');
$pageTitle = "All Supervisors";
include($tmp . 'header.php');
$supervisors = $_SESSION['supervisors'];

?>
<?php if (isset($_SESSION['msg'])) { ?>
  <p style="color:black;background:#8bfa8b;padding:20px;margin:0">
    <?php
    echo $_SESSION['msg'];
    unset($_SESSION['msg']);
    ?>
  </p>
<?php } ?>
<div class="section" id="Supervisors" style="min-height: 76.3vh;">
  <div style="text-align: center;">
    <a class="button" href="<?php  echo $cont . 'SupervisorController.php?method=showAddForm' ?>">Add Supervisor</a>
  </div>
  <div class="container" style="margin-top:30px">
    <h2 class="special-heading">All Supervisors</h2>
    <table id="table">
      <tr>
        <th>#</th>
        <th>Name</th>
        <th>email</th>
        <th>Control</th>
      </tr>
      <?php $i = 1;
      foreach ($supervisors as $s) {
      ?>
        <tr>
          <td><?php echo $i;
              $i++ ?></td>
          <td><?php echo $s['name'] ?></td>
          <td><?php echo $s['email'] ?></td>
          <td>
            <div class="flex" style="flex-direction: row;margin: 0;justify-content:space-evenly">
              <a href="<?php echo $cont . 'SupervisorController.php?method=showEditForm&id=' . $s['id'] ?>">Edit</a>
              <a href="<?php echo $cont . 'SupervisorController.php?method=delete&id=' . $s['id'] ?>">Delete</a>
            </div>
          </td>
        </tr>
      <?php }
      if (empty($supervisors)) {
        echo "<tr><td colspan='4'>Supervisors Not Found </td></tr>";
      }
      ?>

    </table>
  </div>
</div>


<?php
include($tmp . 'footer.php');
ob_end_flush();
    //unset($_SESSION['Supervisors']);