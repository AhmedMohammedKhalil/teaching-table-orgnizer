<?php 
session_start();

include_once('../layout/functions/functions.php');
$method = $_GET['method'];
if($method != "") {
    $department = new DepartmentController();
    if($method == 'showAddForm') {
        $department->showAddForm();
    }

    if($method == 'add') {
        $department->add();
    }

    if($method == 'showEditForm') {
        $id = $_GET['id'];
        $department->showEditForm($id);
    }

    if($method == 'edit') {
        $department->edit();
    }

    if($method == 'delete') {
        $id = $_GET['id'];
        $department->delete($id);
    }

    if($method == 'showDepartments') {
        $department->showDepartments();
    }

}


Class DepartmentController {

    private $Path = "../supervisor/departments/";


    public function showDepartments() {
        $_SESSION['departments'] = selectAll('*','departments');
        header('location: '.$this->Path);
    }

    public function showAddForm() {
        header('location: '.$this->Path.'add.php');
    }

    public function add() {
        $error=[];
        if($_SERVER['REQUEST_METHOD'] == 'POST') { 
            if(isset($_POST['add_department'])) {
                $name=trim($_POST['name']);
                $code=trim($_POST['code']);
                $data = [
                    'name'=>$name,
                    'code'=>$code

                ];
                $_SESSION['oldData'] = $data;
                $error=[];
                if (empty($name)) {
                    array_push($error,"name required");
                } 
                if (empty($code)) {
                    array_push($error,"code required");
                } 
                if(!empty($error))
                {
                    $_SESSION['errors'] = $error;
                    header('location: '.$this->Path.'add.php');
                    exit();
                }
                $inserted = array_values($data);
                $keys = join(',',array_keys($data));
                $id = insert($keys,'departments','?,?',$inserted);
                if(!empty($id)) {
                    $_SESSION['msg'] = "Department Added Successfuly";
                    $this->showDepartments();  
                }
            }
        }
    }

    public function showEditForm($id) {
        $_SESSION['department'] = selectOne('*','departments',"id = {$id}");
        header('location: '.$this->Path.'edit.php');

    }

    public function edit() {
        $error=[];
        if($_SERVER['REQUEST_METHOD'] == 'POST') { 
            if(isset($_POST['edit_department'])) {
                $name = trim($_POST['name']);
                $code = trim($_POST['code']);

                $department_id = trim($_POST['id']);

                $data = [
                    'name'=>$name,
                    'code'=>$code,
                    'id'=>$department_id

                ];
                $error=[];
                
                if (empty($name)) {
                    array_push($error,"name required");
                } 

                if (empty($code)) {
                    array_push($error,"code required");
                } 
                
                if(!empty($error))
                {
                    $_SESSION['errors'] = $error;
                    $_SESSION['oldData'] = $data;
                    header('location: '.$this->Path.'edit.php');
                    exit();
                }
                
                $success = update('name = ?, code = ?','departments',array_values($data),'id = ?');
                if($success) {
                    $_SESSION['msg'] = "Department Updated Successfuly";
                    $this->showDepartments();  
                }


            }
        }
    }

    public function delete($id) {

        $courses = selectAll('*','courses','department_id = '.$id);
        $professors = selectAll('*','professors','department_id = '.$id);
        if(!empty($courses) || !empty($professors)) {
            header('location: ../errors/deleteError.php');
            exit();
        }
        $success = destroy('departments','id = ?',[$id]);
        if($success) {
            $_SESSION['msg'] = "Department Deleted Successfuly";
            $this->showDepartments();  
        }
    }

    
}