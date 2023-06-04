<?php 
session_start();

include_once('../layout/functions/functions.php');
$method = $_GET['method'];
if($method != "") {
    $class = new ClassController();
    if($method == 'showAddForm') {
        $class->showAddForm();
    }

    if($method == 'add') {
        $class->add();
    }

    if($method == 'showEditForm') {
        $id = $_GET['id'];
        $class->showEditForm($id);
    }

    if($method == 'edit') {
        $class->edit();
    }

    if($method == 'delete') {
        $id = $_GET['id'];
        $class->delete($id);
    }

    if($method == 'showClasses') {
        $class->showClasses();
    }

}


Class ClassController {

    private $Path = "../admin/classes/";


    public function showClasses() {
        $_SESSION['classes'] = selectAll('*','classes');
        header('location: '.$this->Path);
    }

    public function showAddForm() {
        header('location: '.$this->Path.'add.php');
    }

    public function add() {
        $error=[];
        if($_SERVER['REQUEST_METHOD'] == 'POST') { 
            if(isset($_POST['add_class'])) {
                $name=trim($_POST['name']);
                $building_number=trim($_POST['building_number']);
                $capacity=trim($_POST['capacity']);

                $data = [
                    'name'=>$name,
                    'building_number'=>$building_number,
                    'capacity'=>$capacity
                ];
                $_SESSION['oldData'] = $data;
                $error=[];
                if (empty($name)) {
                    array_push($error,"name required");
                }
                if (empty($capacity)) {
                    array_push($error,"capacity required");
                }
                if (empty($building_number)) {
                    array_push($error,"Building Number required");
                }
                if (!empty($capacity) && $capacity <= 0) {
                    array_push($error,"capacity must to be greater than 0 ");
                }
                if(!empty($error))
                {
                    $_SESSION['errors'] = $error;
                    header('location: '.$this->Path.'add.php');
                    exit();
                }
                $inserted = array_values($data);
                $keys = join(',',array_keys($data));
                $id = insert($keys,'classes','?,?,?',$inserted);
                if(!empty($id)) {
                    $_SESSION['msg'] = "Class Added Successfuly";
                    $this->showClasses();  
                }
            }
        }
    }

    public function showEditForm($id) {
        $_SESSION['class'] = selectOne('*','classes',"id = {$id}");
        header('location: '.$this->Path.'edit.php');

    }

    public function edit() {
        $error=[];
        if($_SERVER['REQUEST_METHOD'] == 'POST') { 
            if(isset($_POST['edit_class'])) {
                $name=trim($_POST['name']);
                $building_number=trim($_POST['building_number']);
                $capacity=trim($_POST['capacity']);
                $class_id = trim($_POST['id']);

                $data = [
                    'name'=>$name,
                    'building_number'=>$building_number,
                    'capacity'=>$capacity,
                    'id'=>$class_id,

                ];

                $error=[];
                if (empty($name)) {
                    array_push($error,"name required");
                }
                if (empty($capacity)) {
                    array_push($error,"capacity required");
                }
                if (empty($building_number)) {
                    array_push($error,"Building Number required");
                }
                if (!empty($capacity) && $capacity <= 0) {
                    array_push($error,"capacity must to be greater than 0 ");
                }

                if(!empty($error))
                {
                    $_SESSION['errors'] = $error;
                    $_SESSION['oldData'] = $data;
                    header('location: '.$this->Path.'edit.php');
                    exit();
                }
                
                $success = update('name = ? , building_number= ? , capacity = ?','classes',array_values($data),'id = ?');
                if($success) {
                    $_SESSION['msg'] = "Class Updated Successfuly";
                    $this->showClasses();  
                }


            }
        }
    }

    public function delete($id) {

        $subjects = selectAll('*','subjects','class_id = '.$id);
        if(!empty($subjects)) {
            header('location: ../errors/deleteError.php');
            exit();
        }
        $success = destroy('classes','id = ?',[$id]);
        if($success) {
            $_SESSION['msg'] = "Class Deleted Successfuly";
            $this->showClasses();  
        }
    }

    
}