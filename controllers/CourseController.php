<?php 
session_start();

include_once('../layout/functions/functions.php');
$method = $_GET['method'];
if($method != "") {
    $course = new CourseController();
    if($method == 'showAddForm') {
        $course->showAddForm();
    }

    if($method == 'add') {
        $course->add();
    }

    if($method == 'showEditForm') {
        $id = $_GET['id'];
        $course->showEditForm($id);
    }

    if($method == 'edit') {
        $course->edit();
    }

    if($method == 'delete') {
        $id = $_GET['id'];
        $course->delete($id);
    }

    if($method == 'showCourses') {
        $course->showCourses();
    }

}


Class CourseController {

    private $Path = "../supervisor/courses/";


    public function showCourses() {
        $join = 'departments as d ON c.department_id = d.id';
        $_SESSION['courses'] = selectAll(select:'c.* , d.name as department_name',table:'courses as c',join: $join);
        header('location: '.$this->Path);
    }

    public function showAddForm() {
        $_SESSION['departments'] = selectAll('*','departments');
        header('location: '.$this->Path.'add.php');
    }

    public function add() {
        $error=[];
        if($_SERVER['REQUEST_METHOD'] == 'POST') { 
            if(isset($_POST['add_course'])) {
                $name=trim($_POST['name']);
                $department_id=trim($_POST['department_id']);

                $data = [
                    'name'=>$name,
                    'department_id'=>$department_id
                ];
                $_SESSION['oldData'] = $data;
                $error=[];
                if (empty($name)) {
                    array_push($error,"name required");
                }
                if (empty($department_id)) {
                    array_push($error,"Department required");
                }
                
                if(!empty($error))
                {
                    $_SESSION['errors'] = $error;
                    header('location: '.$this->Path.'add.php');
                    exit();
                }
                $inserted = array_values($data);
                $keys = join(',',array_keys($data));
                $id = insert($keys,'courses','?,?',$inserted);
                if(!empty($id)) {
                    $_SESSION['msg'] = "Course Added Successfuly";
                    $this->showCourses();  
                }
            }
        }
    }

    public function showEditForm($id) {
        $_SESSION['departments'] = selectAll('*','departments');
        $_SESSION['course'] = selectOne('*','courses',"id = {$id}");
        header('location: '.$this->Path.'edit.php');

    }

    public function edit() {
        $error=[];
        if($_SERVER['REQUEST_METHOD'] == 'POST') { 
            if(isset($_POST['edit_course'])) {
                $name=trim($_POST['name']);
                $department_id=trim($_POST['department_id']);
                $course_id = trim($_POST['id']);

                $data = [
                    'name'=>$name,
                    'department_id'=>$department_id,
                    'id'=>$course_id,

                ];

                $error=[];
                if (empty($name)) {
                    array_push($error,"name required");
                }
                if (empty($department_id)) {
                    array_push($error,"Department required");
                }
                

                if(!empty($error))
                {
                    $_SESSION['errors'] = $error;
                    $_SESSION['oldData'] = $data;
                    header('location: '.$this->Path.'edit.php');
                    exit();
                }
                
                $success = update('name = ? , department_id= ?','courses',array_values($data),'id = ?');
                if($success) {
                    $_SESSION['msg'] = "Course Updated Successfuly";
                    $this->showcourses();  
                }


            }
        }
    }

    public function delete($id) {

        $subjects = selectAll('*','subjects','course_id = '.$id);
        if(!empty($subjects)) {
            header('location: ../errors/deleteError.php');
            exit();
        }
        $success = destroy('courses','id = ?',[$id]);
        if($success) {
            $_SESSION['msg'] = "Course Deleted Successfuly";
            $this->showcourses();  
        }
    }

    
}