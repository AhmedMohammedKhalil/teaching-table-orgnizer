<?php
    ob_start();
    session_start();
    include('init.php');
    $pageTitle = "Home";
    include($tmp.'header.php');
    include_once('layout/functions/functions.php');
?>




<!-- Start Landing Section -->

<!-- Start Landing -->
<div class="landing">
      <div class="container">
        <div class="text">
            <h1>Teaching Table Organizer</h1>
            <p>Best Website for Manage Tables for Courses in Colleges</p>
            <p>Let the computer do the work for you</p>
        </div>
        <div class="image">
          <img src="<?php echo $imgs."hero.png" ?>" alt="" />
        </div>
      </div>
      <a href="#articles" class="go-down">
        <i class="fas fa-angle-double-down fa-2x"></i>
      </a>
    </div>
    <!-- End Landing -->

    <!-- End Landing Section -->
    <!-- Start section -->
    <div class="section" id="articles">
        <div class="container">
            <h2 class="special-heading">Services</h2>
            <div class="section-content">
                <div class="text">
                    <div>
                        <p>
                            In few minutes, the website generates a complete timetable that fulfills all your requirements
                        </p>
                    </div>
                    <hr />
                    <div>
                        <p>
                            It is quick and easy to enter all subjects (Physics, Maths), classes/forms (C1, C2, C3..), Departments(Science, Math), Professors and their office Hours. 
                        </p>
                    </div>
                    <hr />
                    <div>
                        <p>
                            Our algorithm quickly checks the schedule for any conflicts
                        </p>
                    </div>

                </div>
                <div class="image">
                    <img src="<?php echo $imgs?>events.png" alt="event image" />
                </div>
            </div>
        </div>
    </div>
    <!-- End section -->


    <?php
    include($tmp.'footer.php');
    ob_end_flush();
