<?php
ob_start();
session_start();
include('init.php');
$pageTitle = "Classes";
include($tmp . 'header.php');
$classes = $_SESSION['classes'];

?>
<?php if (isset($_SESSION['msg'])) { ?>
  <p style="color:black;background:#8bfa8b;padding:20px;margin:0">
    <?php
    echo $_SESSION['msg'];
    unset($_SESSION['msg']);
    ?>
  </p>
<?php } ?>
<div class="section" id="classes" style="min-height: 76.3vh;">
  <div style="text-align: center;">
    <a class="button" href="<?php  echo $cont . 'ClassController.php?method=showAddForm' ?>">Add Class</a>
  </div>
  <div class="container" style="margin-top:30px">
    <h2 class="special-heading">All Classes</h2>
    <table id="table">
      <tr>
        <th>#</th>
        <th>Name</th>
        <th>Building Number</th>
        <th>Capacity</th>
        <th>Control</th>
      </tr>
      <?php $i = 1;
      foreach ($classes as $c) {
      ?>
        <tr>
          <td><?php echo $i;
              $i++ ?></td>
          <td><?php echo $c['name'] ?></td>
          <td><?php echo $c['building_number'] ?></td>
          <td><?php echo $c['capacity'] ?></td>

          <td>
            <div class="flex" style="flex-direction: row;margin: 0;justify-content:space-evenly">
              <a href="<?php echo $cont . 'ClassController.php?method=showEditForm&id=' . $c['id'] ?>">Edit</a>
              <a href="<?php echo $cont . 'ClassController.php?method=delete&id=' . $c['id'] ?>">Delete</a>
            </div>
          </td>
        </tr>
      <?php }
      if (empty($classes)) {
        echo "<tr><td colspan='5'>Classes Not Found </td></tr>";
      }
      ?>

    </table>
  </div>
</div>


<?php
include($tmp . 'footer.php');
ob_end_flush();
    //unset($_SESSION['classes']);