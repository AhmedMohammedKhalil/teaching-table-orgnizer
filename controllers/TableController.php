<?php 
session_start();

include_once('../layout/functions/functions.php');
$method = $_GET['method'];
if($method != "") {
    $table = new TableController();
    if($method == 'showMyTable') {
        $table->showMyTable();
    }

    if($method == 'getMyTable') {
        $table->getMyTable();
    }


    if($method == 'showProfessorTable') {
        $table->showProfessorTable();
    }

    if($method == 'getProfessorTable') {
        $table->getProfessorTable();
    }

}

Class TableController {
    public function showMyTable() {
        unset($_SESSION['table'],$_SESSION['semester']);
        $_SESSION['semesters'] = selectAll('*','semesters');
        header('location: ../professor/my-table.php');
    }


    public function getSubjects($professor_id,$day_id,$semester_id) {
        $select = 'sub.* , sem.name as sem_name , d.code as dep_code, sem.year as sem_year , cl.name as class_name, cl.building_number as building';
        $join = 
        'semesters as sem ON sub.semester_id = sem.id 
        JOIN classes as cl ON sub.class_id = cl.id
        JOIN departments as d ON sub.department_id = d.id';
        $where = "professor_id = $professor_id and semester_id = $semester_id and day_id = $day_id";
        return selectAll($select,'subjects as sub',$where,$join);
    }


    public function getOfficeHours($professor_id,$day_id) {
        $where = "professor_id = $professor_id and day_id = $day_id";
        return selectAll("*",'office_hours',$where);
    }


    public function getOfficeHoursSlot($professor_id,$day_id,$slot = null) {
        $where = "professor_id = $professor_id and day_id = $day_id $slot";
        return selectOne('*','office_hours',$where);
    }

    public function getSubjectSlot($professor_id,$day_id,$semester_id,$slot=null) {
        $select = 'sub.* , sem.name as sem_name ,d.code as dep_code, sem.year as sem_year , cl.name as class_name , cl.building_number as building';
        $join = 
        'semesters as sem ON sub.semester_id = sem.id 
        JOIN classes as cl ON sub.class_id = cl.id
        JOIN departments as d ON sub.department_id = d.id';
        $where = "professor_id = $professor_id and semester_id = $semester_id and day_id = $day_id $slot";
        return selectOne($select,'subjects as sub',$where,$join);
    }

    public function getMyTable() {
        $error=[];
        if($_SERVER['REQUEST_METHOD'] == 'POST') { 
            if(isset($_POST['get_my_table'])) {
                $semester_id = $_POST['semester_id'];
                $professor_id = $_SESSION['professor']['id'];
                $days = selectAll('*','days');
                $table = "";
                foreach($days as $day) {
                    $table .= "<tr>";
                    $table .= "<td colspan='2'>".$day['name']."</td>";
                    $subjects = $this->getSubjects($professor_id,$day['id'],$semester_id);
                    $officeHours = $this->getOfficeHours($professor_id,$day['id']);
                    if(!empty($subjects) || !empty($officeHours)) {
                        for($i=3;$i<24;) {
                            $subjectBySlot = $this->getSubjectSlot($professor_id,$day['id'],$semester_id,"and slot_start = $i");
                            $officeBySlot = $this->getOfficeHoursSlot($professor_id,$day['id'],"and slot_start = $i");
                            if(!empty($subjectBySlot)) {
                                $table .="<td colspan='".$subjectBySlot['slot_number']."'";
                                if($subjectBySlot['type'] == 'ولاد') {
                                    $table .= " style='background:#acbef5'>";
                                } else {
                                    $table .= " style='background:#9eb5a2'>";
                                }
                                $table .="<h4>".$subjectBySlot['name']."</h4>";
                                $table .="<p>".$subjectBySlot['dep_code'].$subjectBySlot['code']."/".$subjectBySlot['section_number']."</p>";
                                $table .="<p>".$subjectBySlot['building']."/".$subjectBySlot['class_name']."</p>";
                                $table .="</td>";
                                $i += $subjectBySlot['slot_number'];
                            }else if(!empty($officeBySlot)) {
                                $table .="<td colspan='".$officeBySlot['slot_number']."'";
                                $table .= " style='background:#f5b2b2'>";
                                $table .="<h4>Office Hours</h4>";
                                $table .="</td>";
                                $i += $officeBySlot['slot_number'];
                            }
                            else if($i%2 == 0) {
                                $table .="<td></td>";
                                $i++;
                            }else{
                                $j = $i+1;
                                $subjectBySlot = $this->getSubjectSlot($professor_id,$day['id'],$semester_id,"and slot_start = $j");
                                $officeBySlot = $this->getOfficeHoursSlot($professor_id,$day['id'],"and slot_start = $j");
                                if(!empty($subjectBySlot)) {
                                    $table .="<td></td>";
                                    $i++;
                                }else if(!empty($officeBySlot)) {
                                    $table .="<td></td>";
                                    $i++; 
                                }else {
                                    $table .="<td colspan='2'></td>";
                                    $i += 2;
                                }
                            }
                        }
                    }else {
                        for($i=3;$i<25; $i += 2 ){
                            $table .= "<td colspan='2'></td>";
                        }
                    }
                    $table .= "</tr>";
                }

                $_SESSION['semesters'] = selectAll('*','semesters');
                $_SESSION['semester'] = selectOne('*','semesters',"id = $semester_id");
                $_SESSION['table'] = $table;
                header('location: ../professor/my-table.php');
            }
        }



    }
    
    public function showProfessorTable() {
        $whr = "";
        if(isset($_SESSION['professor']) && $_SESSION['professor']['role'] == 'head') {
            $whr = " and department_id = ".$_SESSION['professor']['department_id'];
        }
        unset($_SESSION['table'],$_SESSION['semester'],$_SESSION['profs']);
        $_SESSION['semesters'] = selectAll('*','semesters');
        $_SESSION['professors'] = selectAll('*','professors','role = "professor"' .$whr);
        if(isset($_SESSION['admin']) && $_SESSION['admin']['role'] == 'supervisor')
            header('location: ../supervisor/all-tables.php');

        else if(isset($_SESSION['professor']) && $_SESSION['professor']['role'] == 'head')
            header('location: ../head/all-tables.php');
        else
            header('location: ../admin/all-tables.php');

    }


    public function getProfessorTable() {
        $error=[];
        if($_SERVER['REQUEST_METHOD'] == 'POST') { 
            if(isset($_POST['get_professor_table'])) {

                $semester_id = $_POST['semester_id'];
                $professor_id = $_POST['professor_id'];
                $days = selectAll('*','days');
                $table = "";
                foreach($days as $day) {
                    $table .= "<tr>";
                    $table .= "<td colspan='2'>".$day['name']."</td>";
                    $subjects = $this->getSubjects($professor_id,$day['id'],$semester_id);
                    $officeHours = $this->getOfficeHours($professor_id,$day['id']);
                    if(!empty($subjects) || !empty($officeHours)) {
                        for($i=3;$i<24;) {
                            $subjectBySlot = $this->getSubjectSlot($professor_id,$day['id'],$semester_id,"and slot_start = $i");
                            $officeBySlot = $this->getOfficeHoursSlot($professor_id,$day['id'],"and slot_start = $i");
                            if(!empty($subjectBySlot)) {
                                $table .="<td colspan='".$subjectBySlot['slot_number']."'";
                                if($subjectBySlot['type'] == 'ولاد') {
                                    $table .= " style='background:#acbef5'>";
                                } else {
                                    $table .= " style='background:#9eb5a2'>";
                                }
                                $table .="<h4>".$subjectBySlot['name']."</h4>";
                                $table .="<p>".$subjectBySlot['dep_code'].$subjectBySlot['code']."/".$subjectBySlot['section_number']."</p>";
                                $table .="<p>".$subjectBySlot['building']."/".$subjectBySlot['class_name']."</p>";
                                $table .="</td>";
                                $i += $subjectBySlot['slot_number'];
                            }else if(!empty($officeBySlot)) {
                                $table .="<td colspan='".$officeBySlot['slot_number']."'";
                                $table .= " style='background:#f5b2b2'>";
                                $table .="<h4>Office Hours</h4>";
                                $table .="</td>";
                                $i += $officeBySlot['slot_number'];
                            }
                            else if($i%2 == 0) {
                                $table .="<td></td>";
                                $i++;
                            }else{
                                $j = $i+1;
                                $subjectBySlot = $this->getSubjectSlot($professor_id,$day['id'],$semester_id,"and slot_start = $j");
                                $officeBySlot = $this->getOfficeHoursSlot($professor_id,$day['id'],"and slot_start = $j");
                                if(!empty($subjectBySlot)) {
                                    $table .="<td></td>";
                                    $i++;
                                }else if(!empty($officeBySlot)) {
                                    $table .="<td></td>";
                                    $i++; 
                                }else {
                                    $table .="<td colspan='2'></td>";
                                    $i += 2;
                                }
                            }
                        }
                    }else {
                        for($i=3;$i<25; $i += 2 ){
                            $table .= "<td colspan='2'></td>";
                        }
                    }
                    $table .= "</tr>";
                }


                $_SESSION['semesters'] = selectAll('*','semesters');
                $_SESSION['semester'] = selectOne('*','semesters',"id = $semester_id");
                $_SESSION['professors'] = selectAll('*','professors','role = "professor"');
                $_SESSION['profs'] = selectOne('*','professors',"id = $professor_id");
                $_SESSION['table'] = $table;
                if(isset($_SESSION['admin']) && $_SESSION['admin']['role'] == 'supervisor')
                    header('location: ../supervisor/all-tables.php');
                else if(isset($_SESSION['professor']) && $_SESSION['professor']['role'] == 'head')
                    header('location: ../head/all-tables.php');
                else
                    header('location: ../admin/all-tables.php');
            }
        }
    }
}