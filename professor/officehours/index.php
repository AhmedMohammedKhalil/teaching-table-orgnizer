<?php
ob_start();
session_start();
include('init.php');
$pageTitle = "Office Hours";
include($tmp . 'header.php');
$officeHours = $_SESSION['officeHours'];
?>
<?php if (isset($_SESSION['msg'])) { ?>
  <p style="color:black;background:#8bfa8b;padding:20px;margin:0">
    <?php
    echo $_SESSION['msg'];
    unset($_SESSION['msg']);
    ?>
  </p>
<?php } ?>
<div class="section" id="officeHours" style="min-height: 76.3vh;">
  <div style="text-align: center;">
    <a class="button" href="<?php  echo $cont . 'OfficeHourController.php?method=showAddForm' ?>">Add Office Hours</a>
  </div>
  <div class="container" style="margin-top:30px">
    <h2 class="special-heading">All Office Hours</h2>
    
    <table id="table">
      <tr>
        <th>#</th>
        <th>Day</th>
        <th>Start At</th>
        <th>End At</th>
        <th>Control</th>
      </tr>
      <?php
        $i = 1;
        foreach ($officeHours as $oh) {
      ?>
        <tr>
          <td><?php echo $i;
              $i++ ?></td>
          <td><?php echo $oh['day'] ?></td>
          <td><?php echo $oh['start_at'] ?></td>
          <td><?php echo $oh['end_at'] ?></td>
          <td>
            <div class="flex" style="flex-direction: row;margin: 0;justify-content:space-evenly">
              <a href="<?php echo $cont . 'OfficeHourController.php?method=showEditForm&id=' . $oh['id'] ?>">edit</a>
              <a href="<?php echo $cont . 'OfficeHourController.php?method=delete&id=' . $oh['id'] ?>">delete</a>
            </div>
          </td>
        </tr>
      <?php }
      
      if (empty($officeHours)) {
        echo "<tr><td colspan='5'>Office Hours Not Found </td></tr>";
      }
      ?>
    </table>
  </div>
</div>


<?php
include($tmp . 'footer.php');
ob_end_flush();
    //unset($_SESSION['officeHours']);