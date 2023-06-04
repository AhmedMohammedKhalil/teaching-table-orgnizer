<?php
ob_start();
session_start();
include('init.php');
$pageTitle = "Department Heads";
include($tmp . 'header.php');
$heads = $_SESSION['heads'];

?>
<?php if (isset($_SESSION['msg'])) { ?>
  <p style="color:black;background:#8bfa8b;padding:20px;margin:0">
    <?php
    echo $_SESSION['msg'];
    unset($_SESSION['msg']);
    ?>
  </p>
<?php } ?>
<div class="section" id="heads" style="min-height: 76.3vh;">
  <div style="text-align: center;">
    <a class="button" href="<?php  echo $cont . 'HeadController.php?method=showAddForm' ?>">Add Department Head</a>
  </div>
  <div class="container" style="margin-top:30px">
    <h2 class="special-heading">All Department Heads</h2>
    <table id="table">
      <tr>
        <th>#</th>
        <th>Name</th>
        <th>Department Name</th>
        <th>Mobile</th>
        <th>Control</th>
      </tr>
      <?php $i = 1;
      foreach ($heads as $h) {
      ?>
        <tr>
          <td><?php echo $i;
              $i++ ?></td>
          <td><?php echo $h['name'] ?></td>
          <td><?php echo $h['department'] ?></td>
          <td><?php echo $h['mobile'] ?></td>

          <td>
            <div class="flex" style="flex-direction: row;margin: 0;justify-content:space-evenly">
              <a href="<?php echo $cont . 'HeadController.php?method=showEditForm&id=' . $h['id'] ?>">Edit</a>
              <a href="<?php echo $cont . 'HeadController.php?method=delete&id=' . $h['id'] ?>">Delete</a>
            </div>
          </td>
        </tr>
      <?php }
      if (empty($heads)) {
        echo "<tr><td colspan='5'>Department Heads Not Found </td></tr>";
      }
      ?>

    </table>
  </div>
</div>


<?php
include($tmp . 'footer.php');
ob_end_flush();
    //unset($_SESSION['heads']);