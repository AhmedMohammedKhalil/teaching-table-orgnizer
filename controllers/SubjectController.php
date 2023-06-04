<?php 
session_start();

include_once('../layout/functions/functions.php');
$method = $_GET['method'];
if($method != "") {
    $subject = new SubjectController();
    if($method == 'showAddForm') {
        $subject->showAddForm();
    }

    if($method == 'add') {
        $subject->add();
    }

    if($method == 'showEditForm') {
        $id = $_GET['id'];
        $subject->showEditForm($id);
    }

    if($method == 'edit') {
        $subject->edit();
    }

    if($method == 'delete') {
        $id = $_GET['id'];
        $subject->delete($id);
    }

    if($method == 'showSubjects') {
        $_SESSION['department_id'] = $_GET['id'];
        $subject->showSubjects($_SESSION['department_id']);
    }

    if($method == 'getSubjects') {
        $id = $_SESSION['professor']['role'] == 'head' ? $_GET['id'] : $_SESSION['professor']['id'];
        $subject->getSubjects($id);
    }

}


Class SubjectController {

    private $Path = "../supervisor/subjects/";


    public function getSubjects($id) {
        $this->Path =  "../professor/subjects/";
        $_SESSION['days'] = selectAll('*','days');
        $group_by = "sub.code,sub.section_number,sub.start_at,sub.end_at,sub.class_id,sub.semester_id,sub.professor_id,sub.name";
        $select = 'sub.* , sem.name as sem_name, d.code as dep_code, cl.name as class_name, cl.building_number as building, p.name as professor_name';
        $join = 
        'semesters as sem ON sub.semester_id = sem.id 
        JOIN classes as cl ON sub.class_id = cl.id
        JOIN courses as c ON sub.course_id = c.id
        JOIN departments as d ON sub.department_id = d.id
        Join professors as p ON sub.professor_id = p.id';
        $where = "sub.professor_id = {$id} Group By {$group_by}";
        $all_subjects = selectAll($select,'subjects as sub',$where,$join);
        $subjects = [];
        foreach($all_subjects as $subject) {
            $subject['days'] = $this->getDays($subject);
            $subjects[] = $subject; 
        }
        $_SESSION['subjects'] = $subjects;
        if($_SESSION['professor']['role'] == 'head') {
            header('location: ../head/professors/show.php');
            exit();
        }
        header('location: '.$this->Path);
    }

    public function getDays($s) {
        $days = [];
        $where = "code = '{$s['code']}' and section_number = '{$s['section_number']}' and start_at= '{$s['start_at']}' and end_at= '{$s['end_at']}' and class_id = {$s['class_id']} and semester_id = {$s['semester_id']} and professor_id = {$s['professor_id']} and name = '{$s['name']}' ";
        $subjects = selectAll('*','subjects',$where);
        foreach($subjects as $sub) {
            $days[] = $sub['day_id'];
        }
        return join(",",$days);
    }

    public function getSubjectIds($s) {
        $ids = [];
        $where = "code = '{$s['code']}' and section_number = '{$s['section_number']}' and start_at= '{$s['start_at']}' and end_at= '{$s['end_at']}' and class_id = {$s['class_id']} and semester_id = {$s['semester_id']} and professor_id = {$s['professor_id']} and name = '{$s['name']}' ";
        $subjects = selectAll('*','subjects',$where);
        foreach($subjects as $sub) {
            $ids[] = $sub['id'];
        }
        return $ids;
    }
    public function showSubjects($id) {
        $_SESSION['days'] = selectAll('*','days');
        $group_by = "sub.code,sub.section_number,sub.start_at,sub.end_at,sub.class_id,sub.semester_id,sub.professor_id,sub.name";
        $select = 'sub.* , sem.name as sem_name, d.code as dep_code, cl.name as class_name, cl.building_number as building , p.name as professor_name';
        $join = 
        'semesters as sem ON sub.semester_id = sem.id 
        JOIN classes as cl ON sub.class_id = cl.id
        JOIN courses as c ON sub.course_id = c.id
        JOIN departments as d ON sub.department_id = d.id
        Join professors as p ON sub.professor_id = p.id';
        $where = "sub.department_id = {$id} Group By {$group_by}";
        $all_subjects = selectAll($select,'subjects as sub',$where,$join);
        $subjects = [];
        foreach($all_subjects as $subject) {
            $subject['days'] = $this->getDays($subject);
            $subjects[] = $subject; 
        }
        $_SESSION['subjects'] = $subjects;
        header('location: '.$this->Path);
    }

    public function showAddForm() {
        $_SESSION['courses'] = selectAll('*','courses',"department_id = {$_SESSION['department_id']}");
        $_SESSION['professors'] = selectAll('*','professors',"department_id = {$_SESSION['department_id']} and role = 'professor'");
        $_SESSION['semesters'] = selectAll('*','semesters');
        $_SESSION['classes'] = selectAll('*','classes');
        $_SESSION['days'] = selectAll('*','days');
        if(empty( $_SESSION['courses']) || empty( $_SESSION['professors']) ||empty( $_SESSION['semesters']) ||empty( $_SESSION['classes']) ||empty( $_SESSION['days'])) {
            $_SESSION['msg'] = "please check courses, professors, semesters, classes or days found in database";
            $this->showSubjects($_SESSION['department_id']); 
            exit();
        }
        header('location: '.$this->Path.'add.php');
    }

    public function add() {
        $error=[];
        if($_SERVER['REQUEST_METHOD'] == 'POST') { 
            if(isset($_POST['add_subject'])) {
                $days = $_POST['days'];
                $name=trim($_POST['name']);
                $code=trim($_POST['code']);
                $section_number=trim($_POST['section_number']);
                $type=trim($_POST['type']);
                $course_id=trim($_POST['course_id']);
                $semester_id=trim($_POST['semester_id']);
                $class_id=trim($_POST['class_id']);
                $professor_id=trim($_POST['professor_id']);
                $department_id=trim($_POST['department_id']);
                $capacity=trim($_POST['capacity']);
                $start_at=trim($_POST['start_at']);
                $end_at=trim($_POST['end_at']);
                $error=[];
                if (empty($name)){
                    array_push($error,"name required");
                }
                if (empty($code)) {
                    array_push($error,"code required");
                }
                if (empty($section_number)) {
                    array_push($error,"Section Number required");
                }
                if (empty($type)) {
                    array_push($error,"Type required");
                }
                if (empty($course_id)) {
                    array_push($error,"course required");
                }
                if (empty($semester_id)) {
                    array_push($error,"semester required");
                }
                if (empty($class_id)) {
                    array_push($error,"class required");
                }
                if (empty($capacity)) {
                    array_push($error,"capacity required");
                }
                if (!empty($capacity) && $capacity <= 0) {
                    array_push($error,"capacity must to be greater than 0 ");
                }
                if (empty($start_at)) {
                    array_push($error,"Start Time required");
                }
                if (empty($end_at)) {
                    array_push($error,"End Time required");
                }
                if (!empty($start_at) && strtotime($start_at) < strtotime("8:00")) {
                    array_push($error,"Start Time must be equal or greater than 8:00");
                }

                if (!empty($start_at) && strtotime($start_at) > strtotime("18:30")) {
                    array_push($error,"Start Time must be equal or less than 18:30");
                }

                if (!empty($end_at) && strtotime($end_at) < strtotime("8:30")) {
                    array_push($error,"End Time must be equal or greater than 8:30");
                }

                if (!empty($end_at) && strtotime($end_at) > strtotime("19:00")) {
                    array_push($error,"End Time must be equal or less than 19:00");
                }

                if (!empty($start_at) && !empty($end_at) && strtotime($start_at) > strtotime($end_at)) {
                    array_push($error,"End Time must be greater than Start Time");
                }
                foreach($days as $day_id) {
                    
                    $data = [
                        'name'=>$name,
                        'code'=>$code,
                        'section_number'=>$section_number,
                        'type'=>$type,
                        'course_id'=>$course_id,
                        'semester_id'=>$semester_id,
                        'class_id'=>$class_id,
                        'professor_id'=>$professor_id,
                        'department_id'=>$department_id,
                        'capacity'=>$capacity,
                        'start_at'=>$start_at,
                        'end_at'=>$end_at,
                        'day_id' => $day_id,
                    ];
                    $_SESSION['oldData'] = $data;
                    
                    $office_hours = selectAll('*','office_hours',"professor_id = $professor_id and day_id = $day_id");
                    if(!empty($office_hours)) {
                        foreach($office_hours as $s) {
                            $err1 = "The subject is conflict with office hour times";
                            if(strtotime($start_at) >= strtotime($s['start_at']) && strtotime($start_at) < strtotime($s['end_at'])) {
                                if(! in_array($err1, $error)) {
                                    array_push($error,"The subject is conflict with office hour times");
                                    array_push($error,"please change day or times");
                                    break;
                                }
                            }else if(strtotime($end_at) >= strtotime($s['start_at']) && strtotime($end_at) < strtotime($s['end_at'])) {
                                if(! in_array($err1, $error)) {
                                    array_push($error,"The subject is conflict with office hour times");
                                    array_push($error,"please change day or times");
                                    break;

                                }
                            }else {
                                if(strtotime($start_at) <= strtotime($s['start_at']) && strtotime($end_at) >= strtotime($s['end_at'])) {
                                    if(! in_array($err1, $error)) {
                                        array_push($error,"The subject is conflict with office hour times");
                                        array_push($error,"please change day or times");
                                        break;
                                    }  
                                }
                            }
                        }
                    }

                    $departments_subjects = selectAll('*','subjects',"department_id = $department_id and semester_id = $semester_id and day_id = $day_id");
                    if(!empty($departments_subjects)) {
                        foreach($departments_subjects as $s) {
                            $err1 = "The subject is conflict with office hour times";
                            if(! in_array($err1, $error)) {
                                if(strtotime($start_at) >= strtotime($s['start_at']) && strtotime($start_at) < strtotime($s['end_at'])) {
                                    array_push($error,"The subject is conflict with another subject times");
                                    array_push($error,"please change day or times");
                                    break;
                                }else if(strtotime($end_at) >= strtotime($s['start_at']) && strtotime($end_at) < strtotime($s['end_at'])) {
                                    array_push($error,"The subject is conflict with another subject times");
                                    array_push($error,"please change day or times");  
                                    break;                      
                                }else {
                                    if(strtotime($start_at) <= strtotime($s['start_at']) && strtotime($end_at) >= strtotime($s['end_at'])) {
                                        array_push($error,"The subject is conflict with another subject times");
                                        array_push($error,"please change day or times");  
                                        break;
                                    }
                                }
                            }
                            
                        }
                    }


                    $classes_subjects = selectAll('*','subjects',"class_id = $class_id and semester_id = $semester_id and day_id = $day_id");
                    if(!empty($classes_subjects)) {
                        foreach($classes_subjects as $s) {
                            $err1 = "The subject is conflict with another subject times and class";
                            if(! in_array($err1, $error)) {
                                if(strtotime($start_at) >= strtotime($s['start_at']) && strtotime($start_at) < strtotime($s['end_at'])) {
                                    array_push($error,"The subject is conflict with another subject times and class");
                                    array_push($error,"please change day or times or class");
                                    break;
                                }else if(strtotime($end_at) >= strtotime($s['start_at']) && strtotime($end_at) < strtotime($s['end_at'])) {
                                    array_push($error,"The subject is conflict with another subject times and class");
                                    array_push($error,"please change day or times or class");  
                                    break;                      
                                }else {
                                    if(strtotime($start_at) <= strtotime($s['start_at']) && strtotime($end_at) >= strtotime($s['end_at'])) {
                                        array_push($error,"The subject is conflict with another subject times and class");
                                        array_push($error,"please change day or times or class");  
                                        break;
                                    }
                                }
                            }
                        }
                    }
                    
                }
                if(!empty($error))
                {
                    $_SESSION['oldData']['days'] = $days;
                    $_SESSION['errors'] = $error;
                    header('location: '.$this->Path.'add.php');
                    exit();
                }

                foreach($days as $day_id) {   
                    $data = [
                        'name'=>$name,
                        'code'=>$code,
                        'section_number'=>$section_number,
                        'type'=>$type,
                        'course_id'=>$course_id,
                        'semester_id'=>$semester_id,
                        'class_id'=>$class_id,
                        'professor_id'=>$professor_id,
                        'department_id'=>$department_id,
                        'capacity'=>$capacity,
                        'start_at'=>$start_at,
                        'end_at'=>$end_at,
                        'day_id' => $day_id,
                    ]; 
                    $slot_start = 0;
                    $arr = explode(':',$start_at);
                    $arr[0]= (int) $arr[0];
                    $arr[1]= (int) $arr[1];
                    if($arr[0] >= 8 && $arr[1] != 0) {
                        $slot_start += $arr[0]+1-5;
                        $slot_start += $arr[0]-8;
                    }else if ($arr[0] >= 8 && $arr[1] == 0) {
                        $slot_start += $arr[0]-5;
                        $slot_start += $arr[0]-8;
                    }
                    $slot_number = round(abs(strtotime($end_at) - strtotime($start_at)) / 60,2)/30;
                    $data['slot_start'] = $slot_start;
                    $data['slot_number'] = $slot_number;
                    
                    $inserted = array_values($data);
                    $keys = join(',',array_keys($data));
                    $id = insert($keys,'subjects','?,?,?,?,?,?,?,?,?,?,?,?,?,?,?',$inserted);
                }
                
                if(!empty($id)) {
                    $_SESSION['msg'] = "subject Added Successfuly";
                    $this->showSubjects($_SESSION['department_id']);  
                }
            }
        }
    }

    public function showEditForm($id) {
        $_SESSION['subject'] = selectOne('*','subjects',"id = {$id}");
        $_SESSION['subject']['days'] = explode(',',$this->getDays( $_SESSION['subject'])) ;
        $_SESSION['courses'] = selectAll('*','courses',"department_id = {$_SESSION['subject']['department_id']}");
        $_SESSION['semesters'] = selectAll('*','semesters');
        $_SESSION['classes'] = selectAll('*','classes');
        $_SESSION['days'] = selectAll('*','days');
        header('location: '.$this->Path.'edit.php');

    }

    public function edit() {
        $error=[];
        if($_SERVER['REQUEST_METHOD'] == 'POST') { 
            if(isset($_POST['edit_subject'])) {

                $days = $_POST['days'];
                $name=trim($_POST['name']);
                $code=trim($_POST['code']);
                $section_number=trim($_POST['section_number']);
                $type=trim($_POST['type']);
                $course_id=trim($_POST['course_id']);
                $semester_id=trim($_POST['semester_id']);
                $class_id=trim($_POST['class_id']);
                $professor_id=trim($_POST['professor_id']);
                $department_id=trim($_POST['department_id']);
                $capacity=trim($_POST['capacity']);
                $start_at=trim($_POST['start_at']);
                $end_at=trim($_POST['end_at']);
                $subject_id = trim($_POST['id']);

                $subject = selectOne('*','subjects',"id = {$subject_id}");
                $subjectIds = $this->getSubjectIds($subject);

                $error=[];
                if (empty($name)){
                    array_push($error,"name required");
                }
                if (empty($code)) {
                    array_push($error,"code required");
                }
                if (empty($section_number)) {
                    array_push($error,"Section Number required");
                }
                if (empty($type)) {
                    array_push($error,"Type required");
                }
                if (empty($course_id)) {
                    array_push($error,"course required");
                }
                if (empty($semester_id)) {
                    array_push($error,"semester required");
                }
                if (empty($class_id)) {
                    array_push($error,"class required");
                }
                if (empty($capacity)) {
                    array_push($error,"capacity required");
                }
                if (!empty($capacity) && $capacity <= 0) {
                    array_push($error,"capacity must to be greater than 0 ");
                }
                if (empty($start_at)) {
                    array_push($error,"Start Time required");
                }
                if (empty($end_at)) {
                    array_push($error,"End Time required");
                }
                if (!empty($start_at) && strtotime($start_at) < strtotime("8:00")) {
                    array_push($error,"Start Time must be equal or greater than 8:00");
                }

                if (!empty($start_at) && strtotime($start_at) > strtotime("18:30")) {
                    array_push($error,"Start Time must be equal or less than 18:30");
                }

                if (!empty($end_at) && strtotime($end_at) < strtotime("8:30")) {
                    array_push($error,"End Time must be equal or greater than 8:30");
                }

                if (!empty($end_at) && strtotime($end_at) > strtotime("19:00")) {
                    array_push($error,"End Time must be equal or less than 19:00");
                }

                if (!empty($start_at) && !empty($end_at) && strtotime($start_at) > strtotime($end_at)) {
                    array_push($error,"End Time must be greater than Start Time");
                }

                foreach($days as $day_id) {
                    
                    $data = [
                        'name'=>$name,
                        'code'=>$code,
                        'section_number'=>$section_number,
                        'type'=>$type,
                        'course_id'=>$course_id,
                        'semester_id'=>$semester_id,
                        'class_id'=>$class_id,
                        'professor_id'=>$professor_id,
                        'department_id'=>$department_id,
                        'capacity'=>$capacity,
                        'start_at'=>$start_at,
                        'end_at'=>$end_at,
                        'day_id' => $day_id,
                    ];
                    $_SESSION['oldData'] = $data;
                    
                    $office_hours = selectAll('*','office_hours',"professor_id = $professor_id and day_id = $day_id");
                    if(!empty($office_hours)) {
                        foreach($office_hours as $s) {
                            $err1 = "The subject is conflict with office hour times";
                            if(strtotime($start_at) >= strtotime($s['start_at']) && strtotime($start_at) < strtotime($s['end_at'])) {
                                if(! in_array($err1, $error)) {
                                    array_push($error,"The subject is conflict with office hour times");
                                    array_push($error,"please change day or times");
                                    break;
                                }
                            }else if(strtotime($end_at) >= strtotime($s['start_at']) && strtotime($end_at) < strtotime($s['end_at'])) {
                                if(! in_array($err1, $error)) {
                                    array_push($error,"The subject is conflict with office hour times");
                                    array_push($error,"please change day or times");
                                    break;

                                }
                            }else {
                                if(strtotime($start_at) <= strtotime($s['start_at']) && strtotime($end_at) >= strtotime($s['end_at'])) {
                                    if(! in_array($err1, $error)) {
                                        array_push($error,"The subject is conflict with office hour times");
                                        array_push($error,"please change day or times");
                                        break;
                                    }  
                                }
                            }
                        }
                    }

                    $departments_subjects = selectAll('*','subjects',"department_id = $department_id and semester_id = $semester_id and day_id = $day_id and id not in (".implode(',',$subjectIds).")");
                    if(!empty($departments_subjects)) {
                        foreach($departments_subjects as $s) {
                            $err1 = "The subject is conflict with office hour times";
                            if(! in_array($err1, $error)) {
                                if(strtotime($start_at) >= strtotime($s['start_at']) && strtotime($start_at) < strtotime($s['end_at'])) {
                                    array_push($error,"The subject is conflict with another subject times");
                                    array_push($error,"please change day or times");
                                    break;
                                }else if(strtotime($end_at) >= strtotime($s['start_at']) && strtotime($end_at) < strtotime($s['end_at'])) {
                                    array_push($error,"The subject is conflict with another subject times");
                                    array_push($error,"please change day or times");  
                                    break;                      
                                }else {
                                    if(strtotime($start_at) <= strtotime($s['start_at']) && strtotime($end_at) >= strtotime($s['end_at'])) {
                                        array_push($error,"The subject is conflict with another subject times");
                                        array_push($error,"please change day or times");  
                                        break;
                                    }
                                }
                            }
                            
                        }
                    }


                    $classes_subjects = selectAll('*','subjects',"class_id = $class_id and semester_id = $semester_id and day_id = $day_id and id not in (".implode(',',$subjectIds).")");
                    if(!empty($classes_subjects)) {
                        foreach($classes_subjects as $s) {
                            $err1 = "The subject is conflict with another subject times and class";
                            if(! in_array($err1, $error)) {
                                if(strtotime($start_at) >= strtotime($s['start_at']) && strtotime($start_at) < strtotime($s['end_at'])) {
                                    array_push($error,"The subject is conflict with another subject times and class");
                                    array_push($error,"please change day or times or class");
                                    break;
                                }else if(strtotime($end_at) >= strtotime($s['start_at']) && strtotime($end_at) < strtotime($s['end_at'])) {
                                    array_push($error,"The subject is conflict with another subject times and class");
                                    array_push($error,"please change day or times or class");  
                                    break;                      
                                }else {
                                    if(strtotime($start_at) <= strtotime($s['start_at']) && strtotime($end_at) >= strtotime($s['end_at'])) {
                                        array_push($error,"The subject is conflict with another subject times and class");
                                        array_push($error,"please change day or times or class");  
                                        break;
                                    }
                                }
                            }
                        }
                    }
                    
                }
                if(!empty($error))
                {
                    $_SESSION['oldData']['days'] = $days;
                    $_SESSION['errors'] = $error;
                    header('location: '.$this->Path.'edit.php');
                    exit();
                }

                foreach($subjectIds as $subId) {
                    destroy('subjects','id = ?',[$subId]);
                }

                foreach($days as $day_id) {   
                    $data = [
                        'name'=>$name,
                        'code'=>$code,
                        'section_number'=>$section_number,
                        'type'=>$type,
                        'course_id'=>$course_id,
                        'semester_id'=>$semester_id,
                        'class_id'=>$class_id,
                        'professor_id'=>$professor_id,
                        'department_id'=>$department_id,
                        'capacity'=>$capacity,
                        'start_at'=>$start_at,
                        'end_at'=>$end_at,
                        'day_id' => $day_id,
                    ]; 
                    $slot_start = 0;
                    $arr = explode(':',$start_at);
                    $arr[0]= (int) $arr[0];
                    $arr[1]= (int) $arr[1];
                    if($arr[0] >= 8 && $arr[1] != 0) {
                        $slot_start += $arr[0]+1-5;
                        $slot_start += $arr[0]-8;
                    }else if ($arr[0] >= 8 && $arr[1] == 0) {
                        $slot_start += $arr[0]-5;
                        $slot_start += $arr[0]-8;
                    }
                    $slot_number = round(abs(strtotime($end_at) - strtotime($start_at)) / 60,2)/30;
                    $data['slot_start'] = $slot_start;
                    $data['slot_number'] = $slot_number;
                    
                    $inserted = array_values($data);
                    $keys = join(',',array_keys($data));
                    $id = insert($keys,'subjects','?,?,?,?,?,?,?,?,?,?,?,?,?,?,?',$inserted);

                }
                if(!empty($id)) {
                    $_SESSION['msg'] = "Subject Updated Successfuly";
                    $this->showSubjects($_SESSION['department_id']);  
                }

            }
        }
    }

    public function delete($id) {
        $subject = selectOne('*','subjects',"id = {$id}");
        $subjectIds = $this->getSubjectIds($subject);
        foreach($subjectIds as $subId) {
            $success = destroy('subjects','id = ?',[$subId]);
        }
        if($success) {
            $_SESSION['msg'] = "Subject Deleted Successfuly";
            $this->showSubjects($_SESSION['department_id']);  
        }
    }

    
}