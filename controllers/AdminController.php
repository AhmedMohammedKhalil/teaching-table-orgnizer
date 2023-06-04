<?php 
session_start();

include_once('../layout/functions/functions.php');
$method = $_GET['method'];
if($method != "") {
    $admin = new AdminController();
    if($method == 'showLogin') {
        $admin->showLogin();
    }

    if($method == 'login') {
        $admin->login();
    }

    if($method == 'showSettings') {
        $admin->showSettings();
    }

    if($method == 'showChangePassword') {
        $admin->showChangePassword();
    }

    if($method == 'editProfile') {
        $admin->editProfile();
    }

    if($method == 'changePassword') {
        $admin->changePassword();
    }

    if($method == 'showProfile') {
        $admin->showProfile();
    }

    if($method == 'dashboard') {
        $admin->showDashboard();
    }

    if($method == 'logout') {
        $admin->logout();
    }

}

class AdminController {
    private $Path = "../admin/";

    public function getAuth() {
        if($_SESSION['admin']['role'] == 'supervisor') {
            $this->Path = '../supervisor/';
        }
    }
    
    public function showLogin() {
        header('location: '.$this->Path.'login.php');
    }

    public function login() {
        $error=[];
        if($_SERVER['REQUEST_METHOD'] == 'POST') { 
            if(isset($_POST['admin_login'])) {
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
                $admin = selectOne('*','admins',"email = '$email'");
                if (empty($admin)) {
                    array_push($error,"this email not exist in Database");
                } 

                if (!empty($admin) ) {
                    if(! password_verify($password,$admin['password'])) {
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

                $_SESSION['admin'] = $admin;
                $_SESSION['username'] = $admin['name'];
                $_SESSION['msg'] = $admin['name']." Login Successfuly";
                if($admin['role'] == 'supervisor') {
                    header('location: ../supervisor/dashboard.php');

                } else {
                    header('location: ../admin/dashboard.php');
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
                $admin_id = $_SESSION['admin']['id'];
                $data = [
                    'password'=>$hashpassword,
                    'id'=>$admin_id
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
                    header('location: '.$this->Path.'change-password.php');
                    exit();
                }

                $success = update('password = ?','admins',array_values($data),'id = ?');
                if($success) {
                    $_SESSION['admin']['password'] = $hashpassword;
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
                $civil_id = trim($_POST['civil_id']);
                $admin_id = $_SESSION['admin']['id'];
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
                    'civil_id'=>$civil_id ? $civil_id : null,
                    'password' => $password ? $hashpassword : $_SESSION['admin']['password'],


                ];
                $error=[];
                if (empty($email)) {
                    array_push($error,"email required");
                } 
                if (empty($name)) {
                    array_push($error,"name required");
                } 
                 
                if($email != $_SESSION['admin']['email']) {
                    $admin = selectOne('*','admins',"email = '$email'");
                    if (!empty($admin) ) {
                        array_push($error,"this email exist in Database");
                    } 
                }

                if (! empty($photoName) && ! in_array($photoExtension, $photoAllowedExtension)) {
                    $error[] = 'This Extension Is Not <strong>Allowed</strong>';
                }
                if (! empty($photoName) && $photoSize > 4194304) {
                    $error[] = 'photo Cant Be Larger Than <strong>4MB</strong>';
                }

                if (!empty($password)) {
                    if (empty($current_password)) {
                        array_push($error,"Old Password required");
                    }
                    if (strlen($current_password)>0 && strlen($current_password)<8) {
                        array_push($error,"This Cuttent password less than 8 digit");
                    } 
                    if(!password_verify($current_password,$_SESSION['admin']['password'])) {
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

                if(!empty($error))
                {
                    $_SESSION['errors'] = $error;
                    $_SESSION['oldData'] = $data;
                    header('location: '.$this->Path.'settings.php');
                    exit();
                }

                $oldphoto = $_SESSION['admin']['photo'];
                if(!empty($photoName)) {
                    $path = '../assets/images/admins/'.$admin_id;
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
                $data['id'] = $admin_id;
                $success = update('email = ? , name = ?, civil_id = ?, password = ?, photo = ?','admins',array_values($data),'id = ?');
                if($success) {
                    $_SESSION['username'] = $name;
                    $_SESSION['admin']['email'] = $email; 
                    $_SESSION['admin']['photo'] = $photo; 
                    $_SESSION['admin']['name'] = $name; 
                    $_SESSION['admin']['civil_id'] = $civil_id ? $civil_id : null; 
                    $_SESSION['admin']['password'] = $password ? $hashpassword : $_SESSION['admin']['password']; 

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
        header('location: '.$this->Path);
    }

    public function showDashboard() {
        $this->getAuth();
        header('location: '.$this->Path.'dashboard.php');
    }

    
    public function logout(){
        unsetAllSession();
        unset($_SESSION['admin']);
        unset($_SESSION['username']);
        header('location: ../');

    }
    


}