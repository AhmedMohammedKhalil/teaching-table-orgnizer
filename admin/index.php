<?php
	ob_start();
    session_start();
	$pageTitle = 'Admin Profile';
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
    <div class="section" id="admin-profile">
      <div class="container">
        <h2 class="special-heading">Admin Profile</h2>
        <div class="kids-info flex">
          <div>
              <img style="width: 300px;height:300px;border-radius:50%" src="<?php if($_SESSION['admin']['photo'] != null) {echo $imgs.'admins/'.$_SESSION['admin']['id']."/".$_SESSION['admin']['photo'];} else {echo $imgs.'profile.png';} ?>" alt="admin image Profile">
          </div>
          <h3>Name : <?php echo $_SESSION['admin']['name'] ?></h3>
          <h3>Email : <?php echo $_SESSION['admin']['email'] ?></h3>
          <?php if ($_SESSION['admin']['role'] == 'admin') { ?>
          <h3>Civil ID : <?php echo $_SESSION['admin']['civil_id'] ?></h3>
          <?php }?>
          <div class="flex" style="flex-direction: row;">
            <a class="button" href="<?php echo $cont.'adminController.php?method=showSettings' ?>" style="margin: 10px;">Settings</a>
          </div>
        </div>
      </div>
  </div>
<?php
	include $tmp . 'footer.php'; 
	ob_end_flush();

?>