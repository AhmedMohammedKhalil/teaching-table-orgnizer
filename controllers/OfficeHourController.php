<?php 
session_start();

include_once('../layout/functions/functions.php');
$method = $_GET['method'];
if($method != "") {
    $officeHour = new OfficeHourController();
    if($method == 'showAddForm') {
        $officeHour->showAddForm();
    }

    if($method == 'add') {
        $officeHour->add();
    }

    if($method == 'showEditForm') {
        $id = $_GET['id'];
        $officeHour->showEditForm($id);
    }

    if($method == 'edit') {
        $officeHour->edit();
    }

    if($method == 'delete') {
        $id = $_GET['id'];
        $officeHour->delete($id);
    }

    if($method == 'showOfficeHours') {
        $officeHour->showOfficeHours();
    }

}


Class OfficeHourController {

    private $Path = "../professor/officehours/";


    public function showOfficeHours() {
        $join = "days as d ON oh.day_id = d.id";
        $where = "professor_id = {$_SESSION['professor']['id']}";
        $_SESSION['officeHours'] = selectAll('oh.* , d.name as day','office_hours as oh',$where,$join);
        header('location: '.$this->Path);
    }

    public function showAddForm() {
        $_SESSION['days'] = selectAll('*','days');
        header('location: '.$this->Path.'add.php');
    }


    

    public function add() {
        $error=[];
        if($_SERVER['REQUEST_METHOD'] == 'POST') { 
            if(isset($_POST['add_officeHour'])) {
                
                $day_id=trim($_POST['day_id']);
                $professor_id=trim($_POST['professor_id']);
                $start_at=trim($_POST['start_at']);
                $end_at=trim($_POST['end_at']);

                $data = [
                    'day_id'=>$day_id,
                    'professor_id'=>$professor_id,
                    'start_at'=>$start_at,
                    'end_at'=>$end_at
                ];
                $_SESSION['oldData'] = $data;
                $error=[];
                
                if (empty($day_id)) {
                    array_push($error,"day required");
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

                $subjects = selectAll('*','subjects',"professor_id = $professor_id and day_id = $day_id");
                if(!empty($subjects)) {
                    foreach($subjects as $s) {
                        if(strtotime($start_at) >= strtotime($s['start_at']) && strtotime($start_at) < strtotime($s['end_at'])) {
                            array_push($error,"The Office Houre is conflict with subject times");
                            array_push($error,"please change day or times");
                            break;
                        }else if(strtotime($end_at) >= strtotime($s['start_at']) && strtotime($end_at) < strtotime($s['end_at'])) {
                            array_push($error,"The Office Houre is conflict with subject times");
                            array_push($error,"please change day or times");  
                            break;                      
                        }else {
                            if(strtotime($start_at) <= strtotime($s['start_at']) && strtotime($end_at) >= strtotime($s['end_at'])) {
                                array_push($error,"The Office Houre is conflict with subject times");
                                array_push($error,"please change day or times");  
                                break;
                            }
                        }
                    }
                }

                $office_hours = selectAll('*','office_hours',"professor_id = $professor_id and day_id = $day_id");
                if(!empty($office_hours)) {
                    foreach($office_hours as $s) {
                        if(strtotime($start_at) >= strtotime($s['start_at']) && strtotime($start_at) < strtotime($s['end_at'])) {
                            array_push($error,"The Office Houre is conflict with another office hour times");
                            array_push($error,"please change day or times");
                            break;
                        }else if(strtotime($end_at) >= strtotime($s['start_at']) && strtotime($end_at) < strtotime($s['end_at'])) {
                            array_push($error,"The Office Houre is conflict with another office hour times");
                            array_push($error,"please change day or times");  
                            break;                      
                        }else {
                            if(strtotime($start_at) <= strtotime($s['start_at']) && strtotime($end_at) >= strtotime($s['end_at'])) {
                                array_push($error,"The Office Houre is conflict with another office hour times");
                                array_push($error,"please change day or times");  
                                break;
                            }
                        }
                    }
                }
                
                if(!empty($error))
                {
                    $_SESSION['errors'] = $error;
                    header('location: '.$this->Path.'add.php');
                    exit();
                }

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
                $id = insert($keys,'office_hours','?,?,?,?,?,?',$inserted);
                if(!empty($id)) {
                    $_SESSION['msg'] = "office Hour Added Successfuly";
                    $this->showOfficeHours();  
                }
            }
        }
    }

    public function showEditForm($id) {
        $_SESSION['days'] = selectAll('*','days');
        $_SESSION['officeHour'] = selectOne('*','office_hours',"id = {$id}");
        header('location: '.$this->Path.'edit.php');

    }

    public function edit() {
        $error=[];
        if($_SERVER['REQUEST_METHOD'] == 'POST') { 
            if(isset($_POST['edit_officeHour'])) {
                
                $day_id=trim($_POST['day_id']);
                $professor_id=trim($_POST['professor_id']);
                $start_at=trim($_POST['start_at']);
                $end_at=trim($_POST['end_at']);
                $officeHour_id = trim($_POST['id']);


                $data = [
                    'day_id'=>$day_id,
                    'professor_id'=>$professor_id,
                    'start_at'=>$start_at,
                    'end_at'=>$end_at,
                ];
                $_SESSION['oldData'] = $data;
                $error=[];
                
                if (empty($day_id)) {
                    array_push($error,"day required");
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


                $subjects = selectAll('*','subjects',"professor_id = $professor_id and day_id = $day_id");
                if(!empty($subjects)) {
                    foreach($subjects as $s) {
                        if(strtotime($start_at) >= strtotime($s['start_at']) && strtotime($start_at) < strtotime($s['end_at'])) {
                            array_push($error,"The Office Houre is conflict with subject times");
                            array_push($error,"please change day or times");
                            break;
                        }else if(strtotime($end_at) >= strtotime($s['start_at']) && strtotime($end_at) < strtotime($s['end_at'])) {
                            array_push($error,"The Office Houre is conflict with subject times");
                            array_push($error,"please change day or times");  
                            break;                      
                        }else {
                            if(strtotime($start_at) <= strtotime($s['start_at']) && strtotime($end_at) >= strtotime($s['end_at'])) {
                                array_push($error,"The Office Houre is conflict with subject times");
                                array_push($error,"please change day or times");  
                                break;
                            }
                        }
                    }
                }

                $office_hours = selectAll('*','office_hours',"professor_id = $professor_id and day_id = $day_id and id != $officeHour_id");
                if(!empty($office_hours)) {
                    foreach($office_hours as $s) {
                        if(strtotime($start_at) >= strtotime($s['start_at']) && strtotime($start_at) < strtotime($s['end_at'])) {
                            array_push($error,"The Office Houre is conflict with another office hour times");
                            array_push($error,"please change day or times");
                            break;
                        }else if(strtotime($end_at) >= strtotime($s['start_at']) && strtotime($end_at) < strtotime($s['end_at'])) {
                            array_push($error,"The Office Houre is conflict with another office hour times");
                            array_push($error,"please change day or times");  
                            break;                      
                        }else {
                            if(strtotime($start_at) <= strtotime($s['start_at']) && strtotime($end_at) >= strtotime($s['end_at'])) {
                                array_push($error,"The Office Houre is conflict with another office hour times");
                                array_push($error,"please change day or times");  
                                break;
                            }
                        }
                    }
                }
                
                
                if(!empty($error))
                {
                    $_SESSION['errors'] = $error;
                    header('location: '.$this->Path.'edit.php');
                    exit();
                }

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
                $data['id'] = $officeHour_id;
                $success = update('day_id = ? , professor_id = ? , start_at = ? , end_at = ? , slot_start = ? , slot_number = ?','office_hours',array_values($data),'id = ?');
                if($success) {
                    $_SESSION['msg'] = "Office Hour Updated Successfuly";
                    $this->showOfficeHours();  
                }
            }
        }
    }

    public function delete($id) {

        $success = destroy('office_hours','id = ?',[$id]);
        if($success) {
            $_SESSION['msg'] = "Office Hour Deleted Successfuly";
            $this->showOfficeHours();  
        }
    }

    
}