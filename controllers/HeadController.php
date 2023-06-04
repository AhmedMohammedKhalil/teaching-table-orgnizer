<?php 
session_start();

include_once('../layout/functions/functions.php');
$method = $_GET['method'];
if($method != "") {
    $head = new HeadController();
    if($method == 'showAddForm') {
        $head->showAddForm();
    }

    if($method == 'add') {
        $head->add();
    }

    if($method == 'showEditForm') {
        $id = $_GET['id'];
        $head->showEditForm($id);
    }

    if($method == 'edit') {
        $head->edit();
    }

    if($method == 'delete') {
        $id = $_GET['id'];
        $head->delete($id);
    }

    if($method == 'showHeads') {
        $head->showHeads();
    }

}


Class HeadController {

    private $Path = "../admin/heads/";


    public function showHeads() {
        $_SESSION['heads'] = selectAll('p.* , d.name as department','professors as p','role = "head"','departments as d ON p.department_id = d.id');

        header('location: '.$this->Path);
    }

    public function showAddForm() {
        $_SESSION['departments'] = selectAll('*','departments');
        header('location: '.$this->Path.'add.php');
    }

    public function add() {
        $error=[];
        if($_SERVER['REQUEST_METHOD'] == 'POST') { 
            if(isset($_POST['add_head'])) {
                $name=trim($_POST['name']);
                $email = trim($_POST['email']);
                $mobile = trim($_POST['mobile']);
                $office_location = trim($_POST['office_location']);
                $office_phone = trim($_POST['office_phone']);
                $department_id = trim($_POST['department_id']);
                $password = trim($_POST['password']);
                $confirm_password = trim($_POST['confirm_password']);
                $hashpassword = password_hash($password, PASSWORD_BCRYPT);
                $data = [
                    'email'=>$email,'name'=>$name,'password'=> $hashpassword,'mobile' => $mobile,'role' => 'head',
                    'office_location' => $office_location,'office_phone' => $office_phone,'department_id' => $department_id
                ];
                $_SESSION['oldData'] = $data;
                $error=[];
                if (empty($name)) {
                    array_push($error,"name required");
                } if (empty($email)) {
                    array_push($error,"email required");
                } if (empty($mobile)) {
                    array_push($error,"Mobile required");
                } if (empty($office_phone)) {
                    array_push($error,"Office Phone required");
                }if (empty($department_id)) {
                    array_push($error,"Department Id required");
                }if (empty($office_location)) {
                    array_push($error,"Office Location required");
                } if (empty($password)) {
                    array_push($error,"password required");
                } if (strlen($password)>0 && strlen($password)<8) {
                    array_push($error,"this password less than 8 digit");
                } if (empty($confirm_password)) {
                    array_push($error,"confirm_password required");
                } if ($password!=$confirm_password) {
                    array_push($error,"passwords not matched");
                } 
                $professor = selectOne('*','professors',"email = '$email'");
                if (!empty($professor)) {
                    array_push($error,"this email exist in Database");
                } if(!empty($error))
                {
                    $_SESSION['errors'] = $error;
                    header('location: '.$this->Path.'add.php');
                    exit();
                }
                $inserted = array_values($data);
                $keys = join(',',array_keys($data));
                $id = insert($keys,'professors','?,?,?,?,?,?,?,?',$inserted);
                if(!empty($id)) {
                    $_SESSION['msg'] = "Dr.".$name." Added Successfuly";
                    $this->showHeads();  
                }
            }
        }
    }

    public function showEditForm($id) {
        $_SESSION['head'] = selectOne('*','professors',"id = {$id}");
        $_SESSION['departments'] = selectAll('*','departments');
        header('location: '.$this->Path.'edit.php');
    }

    public function edit() {
        $error=[];
        if($_SERVER['REQUEST_METHOD'] == 'POST') { 
            if(isset($_POST['edit_head'])) {
                $email = trim($_POST['email']);
                $name = trim($_POST['name']);
                $mobile = trim($_POST['mobile']);
                $office_location = trim($_POST['office_location']);
                $office_phone = trim($_POST['office_phone']);
                $department_id = trim($_POST['department_id']);
                $current_password = trim($_POST['current_password']);
                $password = trim($_POST['new_password']);
                $confirm_password = $password ? trim($_POST['confirm_password']) : '';
                $hashpassword = $password ? password_hash($password, PASSWORD_BCRYPT) : '';
                $head_id = $_SESSION['head']['id'];


                $data = [
                    'email'=>$email,
                    'name'=>$name,
                    'office_location' => $office_location,
                    'office_phone' => $office_phone,
                    'mobile' => $mobile,
                    'department_id' => $department_id,
                    'password' => $password ? $hashpassword : $_SESSION['head']['password'],
                    'id'=>$head_id

                ];
                $error=[];
                if (empty($email)) {
                    array_push($error,"email required");
                } 
                if (empty($name)) {
                    array_push($error,"name required");
                } 
                if (empty($mobile)) {
                    array_push($error,"Mobile required");
                }
                if (empty($office_phone)) {
                    array_push($error,"Office Phone required");
                }
                if (empty($office_location)) {
                    array_push($error,"Office Location required");
                } 

                if (empty($department_id)) {
                    array_push($error,"Department Id required");
                }
                if (empty($office_location)) {
                    array_push($error,"Office Location required");
                } 
                if (!empty($password)) {
                    if (empty($current_password)) {
                        array_push($error,"Old Password required");
                    }
                    if (strlen($current_password)>0 && strlen($current_password)<8) {
                        array_push($error,"This Cuttent password less than 8 digit");
                    } 
                    if(!password_verify($current_password,$_SESSION['head']['password'])) {
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
                 

                if($email != $_SESSION['head']['email']) {
                    $professor = selectOne('*','professors',"email = '$email'");
                    if (!empty($professor) ) {
                        array_push($error,"this email exist in Database");
                    } 
                }
                if(!empty($error))
                {
                    $_SESSION['errors'] = $error;
                    $_SESSION['oldData'] = $data;
                    header('location: '.$this->Path.'edit.php');
                    exit();
                }
                
                $success = update('email = ? , name = ? , office_location = ? , office_phone = ? , mobile = ? , department_id = ?, password = ?','professors',array_values($data),'id = ?');
                if($success) {
                    $_SESSION['msg'] = "Dr.".$name." Updated Successfuly";
                    $this->showHeads();  
                }


            }
        }
    }

    public function delete($id) {
        $head = selectOne('name','professors','id = '.$id);
        $success = destroy('professors','id = ?',[$id]);
        if($success) {
            $_SESSION['msg'] = "Dr.".$head['name']." Deleted Successfuly";
            $this->showHeads();  
        }
    }

    
}