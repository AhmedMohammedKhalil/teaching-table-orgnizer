<?php
	ob_start();
    session_start();
	$pageTitle = 'Department Head Profile';
	include 'init.php';
  include $tmp.'header.php';
?>
    <?php if(isset($_SESSION['msg'])) { ?>
        <p style="color:black;background:#8bfa8b;padding:20px;margin:0">
            <?php 
                echo $_SESSION['msg'] ;
                unset($_SESSION['msg']);
            ?>
        </p>
    <?php } ?>
    <div class="section" id="professor-profile">
      <div class="container">
        <h2 class="special-heading">Department Head Profile</h2>
        <div class="kids-info flex">
          <div>
          <img style="width: 300px;height:300px;border-radius:50%" src="<?php if($_SESSION['professor']['photo'] != null) {echo $imgs.'professors/'.$_SESSION['professor']['id']."/".$_SESSION['professor']['photo'];} else {echo $imgs.'profile.png';} ?>" alt="Professor image Profile">
          </div>
          <h3>Name : <?php echo $_SESSION['professor']['name'] ?></h3>
          <h3>Email : <?php echo $_SESSION['professor']['email'] ?></h3>
          <h3>Department : <?php echo $_SESSION['professor']['department'] ?></h3>
          <h3>Mobile : <?php echo $_SESSION['professor']['mobile'] ?></h3>
          <h3>Office Phone : <?php echo $_SESSION['professor']['office_phone'] ?></h3>
          <h3>Office Location : <?php echo $_SESSION['professor']['office_location'] ?></h3>

          <div class="flex" style="flex-direction: row;">
            <a class="button" href="<?php echo $cont.'ProfessorController.php?method=showSettings' ?>" style="margin: 10px;">Settings</a>

          </div>
        </div>
      </div>
  </div>
<?php
	include $tmp . 'footer.php'; 
	ob_end_flush();

?>