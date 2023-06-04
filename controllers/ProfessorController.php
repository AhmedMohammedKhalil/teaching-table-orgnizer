<?php 
session_start();

include_once('../layout/functions/functions.php');
$method = $_GET['method'];
if($method != "") {
    $professor = new ProfessorController();
    if($method == 'showLogin') {
        $professor->showLogin();
    }

    if($method == 'showRegister') {
        $professor->showRegister();
    } 

    if($method == 'register') {
        $professor->Register();
    } 

    if($method == 'login') {
        $professor->login();
    }

    if($method == 'showSettings') {
        $professor->showSettings();
    }

    if($method == 'showChangePassword') {
        $professor->showChangePassword();
    }

    if($method == 'editProfile') {
        $professor->editProfile();
    }

    if($method == 'changePassword') {
        $professor->changePassword();
    }

    if($method == 'showProfile') {
        $professor->showProfile();
    }

    if($method == 'dashboard') {
        $professor->showDashboard();
    }

    if($method == 'showProfessors') {
        $professor->showProfessors();
    }

    if($method == 'logout') {
        $professor->logout();
    }

}

class ProfessorController {
    private $Path = "../professor/";

    public function getAuth() {
        if($_SESSION['professor']['role'] == 'head') {
            $this->Path = '../head/';
        }
    }
    public function showLogin() {
        header('location: '.$this->Path.'login.php');
    }

    public function login() {
        $error=[];
        if($_SERVER['REQUEST_METHOD'] == 'POST') { 
            if(isset($_POST['professor_login'])) {
                $email = trim($_POST['email']);
                $password = trim($_POST['password']);
                $data = [
                    'email'=>$email,
                ];
                $error=[];
                if (empty($email)) {
                    array_push($error,"email required");
                } 
                if (empty($password)) {
                    array_push($error,"password requires");
                } 
                if (strlen($password)>0 && strlen($password)<8) {
                    array_push($error,"this password less than 8 digit");
                }  
                $professor = selectOne('*','professors',"email = '$email'");
                if (empty($professor)) {
                    array_push($error,"this email not exist in Database");
                } 

                if (!empty($professor) ) {
                    if(! password_verify($password,$professor['password'])) {
                            array_push($error,"this password is invalid");
                    }
                }

                if(!empty($error))
                {
                    $_SESSION['oldData'] = $data;
                    $_SESSION['errors'] = $error;
                    header('location: '.$this->Path.'login.php');
                    exit();
                }
                
                $_SESSION['professor'] = $professor;
                $_SESSION['username'] = $professor['name'];
                $_SESSION['msg'] = "Dr".$professor['name']." Login Successfuly";

                if($professor['role'] == 'head') {
                    header('location: ../head/dashboard.php');

                } else {
                    header('location: ../professor/dashboard.php');
                }
               
                


            }
        }
    }


    public function showRegister() {
        $_SESSION['departments'] = selectAll('*','departments');
        header('location: '.$this->Path.'register.php');
    }

    public function register() {
        $error=[];
        if($_SERVER['REQUEST_METHOD'] == 'POST') { 
            if(isset($_POST['professor_register'])) {
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
                    'email'=>$email,'name'=>$name,'password'=> $hashpassword,'mobile' => $mobile,'role' => 'professor',
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
                    header('location: '.$this->Path.'register.php');
                    exit();
                }
                $inserted = array_values($data);
                $keys = join(',',array_keys($data));
                $id = insert($keys,'professors','?,?,?,?,?,?,?,?',$inserted);
                if(!empty($id)) {
                    $result = selectOne('*','professors','id = '.$id);
                    $_SESSION['professor'] = $result;
                    $_SESSION['username'] = $result['name'];
                    $_SESSION['msg'] = "Dr".$result['name']." Register Successfuly";
                    $this->showDashboard();  
                }
            }
        }
    }


    public function changePassword() {
        $this->getAuth();
        $error=[];
        if($_SERVER['REQUEST_METHOD'] == 'POST') { 
            if(isset($_POST['change_password'])) {
                $confirm_password = trim($_POST['confirm_password']);
                $password = trim($_POST['password']);
                $hashpassword = password_hash($password, PASSWORD_BCRYPT);
                $professor_id = $_SESSION['professor']['id'];
                $data = [
                    'password'=>$hashpassword,
                    'id'=>$professor_id
                ];
                $error=[];
                if (empty($password)) {
                    array_push($error,"password required");
                } 
                if (strlen($password)>0 && strlen($password)<8) {
                    array_push($error,"this password less than 8 digit");
                } 
                if (empty($confirm_password)) {
                    array_push($error,"confirm_password required");
                } 
                if ($password!=$confirm_password) {
                    array_push($error,"passwords not matched");
                }

                if(!empty($error))
                {
                    $_SESSION['errors'] = $error;
                    header('location: '.$this->Path.'changePassword.php');
                    exit();
                }

                $success = update('password = ?','professors',array_values($data),'id = ?');
                if($success) {
                    $_SESSION['professor']['password'] = $hashpassword;
                    $_SESSION['msg'] = "Change Password Successfuly";

                    header('location: '.$this->Path);
                }

            }
        }
    }

    public function editProfile() {
        $this->getAuth();
        $error=[];
        if($_SERVER['REQUEST_METHOD'] == 'POST') { 
            if(isset($_POST['edit_profile'])) {
                $email = trim($_POST['email']);
                $name = trim($_POST['name']);
                $mobile = trim($_POST['mobile']);
                $office_location = trim($_POST['office_location']);
                $office_phone = trim($_POST['office_phone']);
                $professor_id = $_SESSION['professor']['id'];
                $photoName = $_FILES['photo']['name'];
                $current_password = trim($_POST['current_password']);
                $password = trim($_POST['new_password']);
                $confirm_password = $password ? trim($_POST['confirm_password']) : '';
                $hashpassword = $password ? password_hash($password, PASSWORD_BCRYPT) : '';
                if(!empty($photoName)) {
                    $photoSize = $_FILES['photo']['size'];
                    $photoTmp	= $_FILES['photo']['tmp_name'];
                    $photoAllowedExtension = array("jpeg", "jpg", "png");
                    $explode = explode('.', $photoName);
                    $photoExtension = strtolower(end($explode));
                }
                $data = [
                    'email'=>$email,
                    'name'=>$name,
                    'office_location' => $office_location,
                    'office_phone' => $office_phone,
                    'mobile' => $mobile,
                    'password' => $password ? $hashpassword : $_SESSION['professor']['password'],

                    

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
                if($email != $_SESSION['professor']['email']) {
                    $professor = selectOne('*','professors',"email = '$email'");
                    if (!empty($professor) ) {
                        array_push($error,"this email exist in Database");
                    } 
                }
                if (! empty($photoName) && ! in_array($photoExtension, $photoAllowedExtension)) {
                    $error[] = 'This Extension Is Not <strong>Allowed</strong>';
                }

                if (!empty($password)) {
                    if (empty($current_password)) {
                        array_push($error,"Old Password required");
                    }
                    if (strlen($current_password)>0 && strlen($current_password)<8) {
                        array_push($error,"This Cuttent password less than 8 digit");
                    } 
                    if(!password_verify($current_password,$_SESSION['professor']['password'])) {
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

                if (! empty($photoName) && $photoSize > 4194304) {
                    $error[] = 'photo Cant Be Larger Than <strong>4MB</strong>';
                }
                if(!empty($error))
                {
                    $_SESSION['errors'] = $error;
                    $_SESSION['oldData'] = $data;
                    header('location: '.$this->Path.'settings.php');
                    exit();
                }

                $oldphoto = $_SESSION['professor']['photo'];
                if(!empty($photoName)) {
                    $path = '../assets/images/professors/'.$professor_id;
                    if(!is_dir($path)) {
                        mkdir($path);
                    } 
                    if($oldphoto != null) {
                        unlink($path.'/'.$oldphoto);
                    }
                    move_uploaded_file($photoTmp, $path.'/'. $photoName);
                }
                $photo = !empty($photoName) ? $photoName : $oldphoto;
                $data['photo'] = $photo;
                $data['id'] = $professor_id;
                $success = update('email = ? , name = ? , office_location = ? , office_phone = ? , mobile = ?,password = ?, photo = ?','professors',array_values($data),'id = ?');
                if($success) {
                    $_SESSION['username'] = $name;
                    $_SESSION['professor']['email'] = $email; 
                    $_SESSION['professor']['name'] = $name; 
                    $_SESSION['professor']['photo'] = $photo; 
                    $_SESSION['professor']['office_location'] = $office_location; 
                    $_SESSION['professor']['office_phone'] = $office_phone; 
                    $_SESSION['professor']['mobile'] = $mobile; 
                    $_SESSION['professor']['password'] = $password ? $hashpassword : $_SESSION['professor']['password']; 


                    $_SESSION['msg'] = "Edit Profile Successfuly";

                    header('location: '.$this->Path);

                }


            }
        }
    }

    public function showSettings() {
        $this->getAuth();
        header('location: '.$this->Path.'settings.php');
    }

    public function showChangePassword() {
        $this->getAuth();
        header('location: '.$this->Path.'change-password.php');

    }

    public function showProfile() {
        $this->getAuth();
        $department = selectOne('name','departments', "id = {$_SESSION['professor']['department_id']}");
        $_SESSION['professor']['department'] = $department['name'];
        header('location: '.$this->Path);
    }

    public function showDashboard() {
        $this->getAuth();
        header('location: '.$this->Path.'dashboard.php');
    }


    public function showProfessors() {
        $_SESSION['professors'] = selectAll('*','professors','role = "professor" and department_id ='.$_SESSION['professor']['department_id']);
        header('location: ../head/professors/');

    }

    
    public function logout(){
        unsetAllSession();
        unset($_SESSION['professor']);
        unset($_SESSION['username']);
        header('location: ../');

    }
    


}