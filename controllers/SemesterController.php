<?php 
session_start();

include_once('../layout/functions/functions.php');
$method = $_GET['method'];
if($method != "") {
    $semester = new SemesterController();
    if($method == 'showAddForm') {
        $semester->showAddForm();
    }

    if($method == 'add') {
        $semester->add();
    }

    if($method == 'showEditForm') {
        $id = $_GET['id'];
        $semester->showEditForm($id);
    }

    if($method == 'edit') {
        $semester->edit();
    }

    if($method == 'delete') {
        $id = $_GET['id'];
        $semester->delete($id);
    }

    if($method == 'showSemesters') {
        $semester->showSemesters();
    }

}


Class SemesterController {

    private $Path = "../supervisor/semesters/";


    public function showSemesters() {
        $_SESSION['semesters'] = selectAll('*','semesters');
        header('location: '.$this->Path);
    }

    public function showAddForm() {
        header('location: '.$this->Path.'add.php');
    }

    public function add() {
        $error=[];
        if($_SERVER['REQUEST_METHOD'] == 'POST') { 
            if(isset($_POST['add_semester'])) {
                $name=trim($_POST['name']);
                $year=trim($_POST['year']);
                $data = [
                    'name'=>$name,
                    'year'=>$year
                ];
                $_SESSION['oldData'] = $data;
                $error=[];
                if (empty($name)) {
                    array_push($error,"name required");
                }
                if (empty($year)) {
                    array_push($error,"year required");
                } 
                if(!empty($error))
                {
                    $_SESSION['errors'] = $error;
                    header('location: '.$this->Path.'add.php');
                    exit();
                }
                $inserted = array_values($data);
                $keys = join(',',array_keys($data));
                $id = insert($keys,'semesters','?,?',$inserted);
                if(!empty($id)) {
                    $_SESSION['msg'] = "Semester Added Successfuly";
                    $this->showsemesters();  
                }
            }
        }
    }

    public function showEditForm($id) {
        $_SESSION['semester'] = selectOne('*','semesters',"id = {$id}");
        header('location: '.$this->Path.'edit.php');

    }

    public function edit() {
        $error=[];
        if($_SERVER['REQUEST_METHOD'] == 'POST') { 
            if(isset($_POST['edit_semester'])) {
                $name = trim($_POST['name']);
                $year = trim($_POST['year']);
                $semester_id = trim($_POST['id']);

                $data = [
                    'name'=>$name,
                    'year'=>$year,
                    'id'=>$semester_id

                ];
                $error=[];
                
                if (empty($name)) {
                    array_push($error,"name required");
                } 
                if (empty($year)) {
                    array_push($error,"year required");
                } 
                if(!empty($error))
                {
                    $_SESSION['errors'] = $error;
                    $_SESSION['oldData'] = $data;
                    header('location: '.$this->Path.'edit.php');
                    exit();
                }
                
                $success = update('name = ? , year= ?','semesters',array_values($data),'id = ?');
                if($success) {
                    $_SESSION['msg'] = "Semester Updated Successfuly";
                    $this->showsemesters();  
                }


            }
        }
    }

    public function delete($id) {

        $subjects = selectAll('*','subjects','semester_id = '.$id);
        if(!empty($subjects)) {
            header('location: ../errors/deleteError.php');
            exit();
        }
        $success = destroy('semesters','id = ?',[$id]);
        if($success) {
            $_SESSION['msg'] = "Semester Deleted Successfuly";
            $this->showSemesters();  
        }
    }

    
}