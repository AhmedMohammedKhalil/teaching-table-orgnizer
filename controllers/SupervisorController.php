<?php 
session_start();

include_once('../layout/functions/functions.php');
$method = $_GET['method'];
if($method != "") {
    $supervisor = new SupervisorController();
    if($method == 'showAddForm') {
        $supervisor->showAddForm();
    }

    if($method == 'add') {
        $supervisor->add();
    }

    if($method == 'showEditForm') {
        $id = $_GET['id'];
        $supervisor->showEditForm($id);
    }

    if($method == 'edit') {
        $supervisor->edit();
    }

    if($method == 'delete') {
        $id = $_GET['id'];
        $supervisor->delete($id);
    }

    if($method == 'showSupervisors') {
        $supervisor->showSupervisors();
    }

}


Class SupervisorController {

    private $Path = "../admin/supervisors/";


    public function showSupervisors() {
        $_SESSION['supervisors'] = selectAll('*','admins','role = "supervisor"');

        header('location: '.$this->Path);
    }

    public function showAddForm() {
        header('location: '.$this->Path.'add.php');
    }

    public function add() {
        $error=[];
        if($_SERVER['REQUEST_METHOD'] == 'POST') { 
            if(isset($_POST['add_supervisor'])) {
                $name=trim($_POST['name']);
                $email = trim($_POST['email']);
                $password = trim($_POST['password']);
                $confirm_password = trim($_POST['confirm_password']);
                $hashpassword = password_hash($password, PASSWORD_BCRYPT);
                $data = [
                    'email'=>$email,'name'=>$name,'password'=> $hashpassword,'role' => 'supervisor'
                ];
                $_SESSION['oldData'] = $data;
                $error=[];
                if (empty($name)) {
                    array_push($error,"name required");
                } if (empty($email)) {
                    array_push($error,"email required");
                } if (empty($password)) {
                    array_push($error,"password required");
                } if (strlen($password)>0 && strlen($password)<8) {
                    array_push($error,"this password less than 8 digit");
                } if (empty($confirm_password)) {
                    array_push($error,"confirm_password required");
                } if ($password!=$confirm_password) {
                    array_push($error,"passwords not matched");
                } 
                $supervisor = selectOne('*','admins',"email = '$email'");
                if (!empty($supervisor)) {
                    array_push($error,"This email exist in Database");
                } if(!empty($error))
                {
                    $_SESSION['errors'] = $error;
                    header('location: '.$this->Path.'add.php');
                    exit();
                }
                $inserted = array_values($data);
                $keys = join(',',array_keys($data));
                $id = insert($keys,'admins','?,?,?,?',$inserted);
                if(!empty($id)) {
                    $_SESSION['msg'] = "Dr".$name." Added Successfuly";
                    $this->showSupervisors();  
                }
            }
        }
    }

    public function showEditForm($id) {
        $_SESSION['supervisor'] = selectOne('*','admins',"id = {$id}");
        header('location: '.$this->Path.'edit.php');
    }

    public function edit() {
        $error=[];
        if($_SERVER['REQUEST_METHOD'] == 'POST') { 
            if(isset($_POST['edit_supervisor'])) {
                $email = trim($_POST['email']);
                $name = trim($_POST['name']);
                $current_password = trim($_POST['current_password']);
                $password = trim($_POST['new_password']);
                $confirm_password = $password ? trim($_POST['confirm_password']) : '';
                $hashpassword = $password ? password_hash($password, PASSWORD_BCRYPT) : '';
                $supervisor_id = $_SESSION['supervisor']['id'];


                $data = [
                    'email'=>$email,
                    'name'=>$name,
                    'password' => $password ? $hashpassword : $_SESSION['supervisor']['password'],
                    'id'=>$supervisor_id

                ];
                $error=[];
                if (empty($email)) {
                    array_push($error,"email required");
                } 
                if (empty($name)) {
                    array_push($error,"name required");
                }  
                if (!empty($password)) {
                    if (empty($current_password)) {
                        array_push($error,"Old Password required");
                    }
                    if (strlen($current_password)>0 && strlen($current_password)<8) {
                        array_push($error,"This Cuttent password less than 8 digit");
                    } 
                    if(!password_verify($current_password,$_SESSION['supervisor']['password'])) {
                        array_push($error,"This Current password invalid");
                    }
                    if (strlen($password)>0 && strlen($password)<8) {
                        array_push($error,"This password less than 8 digit");
                    } 
                    if (empty($confirm_password)) {
                        array_push($error,"Confirm Password required");
                    } 
                    if ($password!=$confirm_password) {
                        array_push($error,"passwords not matched");
                    }
                } 

                if($email != $_SESSION['supervisor']['email']) {
                    $supervisor = selectOne('*','admins',"email = '$email'");
                    if (!empty($supervisor) ) {
                        array_push($error,"This email exist in Database");
                    } 
                }
                if(!empty($error))
                {
                    $_SESSION['errors'] = $error;
                    $_SESSION['oldData'] = $data;
                    header('location: '.$this->Path.'edit.php');
                    exit();
                }
                
                $success = update('email = ? , name = ? , password = ?','admins',array_values($data),'id = ?');
                if($success) {
                    $_SESSION['msg'] = "Dr".$_SESSION['supervisor']['name']." Updated Successfuly";
                    $this->showSupervisors();  
                }


            }
        }
    }

    public function delete($id) {
        $head = selectOne('name','admins','id = '.$id);
        $success = destroy('admins','id = ?',[$id]);
        if($success) {
            $_SESSION['msg'] = "Dr.".$head['name']." Deleted Successfuly";
            $this->showSupervisors();  
        }
    }

    
}