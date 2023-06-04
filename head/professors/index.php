<?php
ob_start();
session_start();
include('init.php');
$pageTitle = "All Professors";
include($tmp . 'header.php');
$professors = $_SESSION['professors'];
?>
<?php if (isset($_SESSION['msg'])) { ?>
  <p style="color:black;background:#8bfa8b;padding:20px;margin:0">
    <?php
    echo $_SESSION['msg'];
    unset($_SESSION['msg']);
    ?>
  </p>
<?php } ?>
<div class="section" id="professors" style="min-height: 76.3vh;">
  <div class="container" style="margin-top:30px">
    <h2 class="special-heading">All Professors</h2>
    
    <table id="table">
      <tr>
        <th>#</th>
        <th>Name</th>
        <th>Email</th>
        <th>Mobile</th>
        <th>Subjects</th>
      </tr>
      <?php
        $i = 1;
        foreach ($professors as $p) {
      ?>
        <tr>
          <td><?php echo $i;
              $i++ ?></td>
          <td><?php echo $p['name'] ?></td>
          <td><?php echo $p['email'] ?></td>
          <td><?php echo $p['mobile'] ?></td>
          <td> <div class="flex" style="flex-direction: row;margin: 0;justify-content:space-evenly">
              <a href="<?php echo $cont . 'SubjectController.php?method=getSubjects&id=' . $p['id'] ?>">Show Subjects</a>
            </div>
          </td>
        </tr>
      <?php }
      
      if (empty($professors)) {
        echo "<tr><td colspan='5'>professors Not Found </td></tr>";
      }
      ?>
    </table>
  </div>
</div>


<?php
include($tmp . 'footer.php');
ob_end_flush();
    //unset($_SESSION['professors']);