<?php
	ob_start();
	session_start();
	$pageTitle = 'All Tables';
	include 'init.php';
	include $tmp.'header.php';
  if(isset($_SESSION['errors'])) {
    $errors = $_SESSION['errors'];
    $oldData = $_SESSION['oldData'];
    extract($oldData);
  }
  $semesters = $_SESSION['semesters'];
  $professors = $_SESSION['professors'];

  if(isset($_SESSION['table'])) {
    $table = $_SESSION['table'];
    $sem_id = $_SESSION['semester']['id'];
    $sem = $_SESSION['semester'];

    $prof_id = $_SESSION['profs']['id'];
    $prof = $_SESSION['profs'];

  }
?>

<div class="section" id="filters" style="min-height: calc(100vh - 172px);">
        <div class="container">
            <div id="semseters">
                <h2 class="special-heading">Choose Semseter and Professor</h2>
                <div class="container">
                <form action="<?php echo $cont."TableController.php?method=getProfessorTable"?>" method="POST" class="form">
                        <div>
                            <div>
                                <label for="semester_id">Semesters</label>
                                <select name="semester_id" id="semester_id" title="choose semester" aria-placeholder="choose semester">
                                    <?php foreach($semesters as $semester) { ?> 
                                        <option value="<?php echo $semester['id'] ?>" <?php if((isset($_SESSION['errors']) && $semester_id == $semester['id']) || isset($_SESSION['table']) && $sem_id == $semester['id']) echo 'selected'?>><?php echo $semester['name']?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div>
                                <label for="professor_id">professors</label>
                                <select name="professor_id" id="professor_id" title="choose Professor" aria-placeholder="choose Professor">
                                    <?php foreach($professors as $professor) { ?> 
                                        <option value="<?php echo $professor['id'] ?>" <?php if((isset($_SESSION['errors']) && $professor_id == $professor['id']) || isset($_SESSION['table']) && $prof_id == $professor['id']) echo 'selected'?>><?php echo $professor['name']?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div>
                                <input type="submit" name="get_professor_table"  value="Show Table">
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    
<?php
    if(isset($_SESSION['table'])) {
?>


    <div class="spikes"></div>
    <div style="background-color: #80808038;padding: 50px 0">
    <div class="container" id="table-print" >
        <h2 class="special-heading">Table</h2>
        <h2 style="display:flex;justify-content:space-evenly"><span><?php echo $sem['name']." ".$sem['year']?></span><span><?php echo $prof['name']?></span></h2>
        <table id="table">
            
        <tr style="border:1px solid transparent !important">
                <td style="border:1px solid transparent !important"></td>
                <td style="border:1px solid transparent !important"></td>
                <td style="border:1px solid transparent !important"></td>
                <td style="border:1px solid transparent !important"></td>
                <td style="border:1px solid transparent !important"></td>
                <td style="border:1px solid transparent !important"></td>
                <td style="border:1px solid transparent !important"></td>
                <td style="border:1px solid transparent !important"></td>
                <td style="border:1px solid transparent !important"></td>
                <td style="border:1px solid transparent !important"></td>
                <td style="border:1px solid transparent !important"></td>
                <td style="border:1px solid transparent !important"></td>
                <td style="border:1px solid transparent !important"></td>
                <td style="border:1px solid transparent !important"></td>
                <td style="border:1px solid transparent !important"></td>
                <td style="border:1px solid transparent !important"></td>
                <td style="border:1px solid transparent !important"></td>
                <td style="border:1px solid transparent !important"></td>
                <td style="border:1px solid transparent !important"></td>
                <td style="border:1px solid transparent !important"></td>
                <td style="border:1px solid transparent !important"></td>
                <td style="border:1px solid transparent !important"></td>
                <td style="border:1px solid transparent !important"></td>
                <td style="border:1px solid transparent !important"></td>
            </tr>
            <tr>
                <th colspan="2"> Days </th>
                <th colspan="2"> 8 - 9 </th>
                <th colspan="2"> 9 - 10 </th>
                <th colspan="2"> 10 - 11 </th>
                <th colspan="2"> 11 - 12 </th>
                <th colspan="2"> 12 - 1 </th>
                <th colspan="2"> 1 - 2 </th>
                <th colspan="2"> 2 - 3 </th>
                <th colspan="2"> 3 - 4 </th>
                <th colspan="2"> 4 - 5 </th>
                <th colspan="2"> 5 - 6 </th>
                <th colspan="2"> 6 - 7 </th>
            </tr>
            <?php echo $table?>
        </table>
    </div>
    <a class="button" style="display:block; width:fit-content; margin: 20px auto" onclick="printTable('table-print')" href="#">Print Table</a>
    </div>
<?php
    }?><?php
	include $tmp . 'footer.php';
	ob_end_flush();
  unset($_SESSION['oldData']);
  unset($_SESSION['errors']);
?>