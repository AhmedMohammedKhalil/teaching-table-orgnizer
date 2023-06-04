
<?php
    ob_start();
    session_start();
    include('init.php');
    $pageTitle = "About Us";
    include($tmp.'header.php');
    include_once('layout/functions/functions.php');
?>

<!-- Start section -->
<div class="section" id="about">
        <div class="container">
            <h2 class="special-heading">About Us</h2>
            <div class="section-content" style="justify-content:center">
                <div class="text" style="text-align:center">
                    <img style="height:200px" src="<?php echo $imgs ?>about.jpg" alt="about image" />
                    <p>
                        we made website for computer project subject - ENC 307-5 <br>
                        Second Smester - 2022/2023
                    </p>
                    <hr />
                    <p>
                    Public Authority for Applied Education and Training
                    </p>
                    <hr />
                    <p>
                    Collage of Technological Studies <br>
                    Electronics Engineering Department
                    </p>
                    <hr />
                    <p>
                    Instructor<br>
                    Dr. Mohammad ALFARES
                    </p>
                    <hr />
                    <p>
                        Teaching Table Organizer <br>
                        Group E
                    </p>
                    <hr />
                    <p>
                    Teams <br>
                    Zahraa abdulhussain alkhalifi <br>
                    Almas abdullah alsahli <br>
                    Hawraa Ibrahim Ali <br>
                    </p>

                    
                </div>
            </div>
        </div>
    </div>
    <!-- End section -->
    <?php
    include($tmp.'footer.php');
    ob_end_flush();