<?php
defined('BASEPATH') or exit('No direct script access allowed');

class EmployeeController extends CI_Controller
{

    public function dd()
    {
        foreach (func_get_args() as $x) {
            echo '<pre>';
            var_dump($x);
            echo '</pre>';
        }
        die;
    }
    public function index()
    {

        $this->load->view('template/header');

        $this->load->model("EmployeeModel");
        $data['employee'] = $this->EmployeeModel->getEmployee();
        $this->load->view('frontend/employee', $data);
        $this->load->view('template/footer');
    }
    
    public function create()
    {

        $this->load->view('template/header');
        $this->load->view('frontend/create');
        $this->load->view('template/footer');
    }

    public function store()
    {
        $this->form_validation->set_rules('first_name', 'First Name', 'required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required');
        $this->form_validation->set_rules('phone', 'Phone', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required');
        $this->form_validation->set_rules('salary', 'salary', 'required');

        // $this->form_validation->set_rules('prod_image', 'image', 'required');


        if ($this->form_validation->run()) {

            $ori_filename = $_FILES['image']['name'];
            $new_name  = time() . "" . str_replace(' ', '-', $ori_filename);
            $config = [
                'upload_path' => './asset/img/',
                'allowed_types' => 'gif|jpg|png',
                'file_name'   => $new_name,
            ];
            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('image')) {
                $imageError = array('imageError' => $this->upload->display_errors());
                $this->load->view('template/header');
                $this->load->view('frontend/create', $imageError);
                $this->load->view('template/footer');
            } else {
                $prod_filename = $this->upload->data('file_name');
                $data = [
                    'first_name' => $this->input->post('first_name'),
                    'last_name'  => $this->input->post('last_name'),
                    'phone'      => $this->input->post('phone'),
                    'email'      => $this->input->post('email'),
                    'salary'      => $this->input->post('salary'),
                    'img'        => $prod_filename
                ];
                $this->load->model('EmployeeModel', 'emp');
                $this->emp->insertEmployee($data);
                redirect(base_url('employee'));
            }
        } else {
            $this->create();
            // or
            // redirect(base_url('employee/add'));
        }
    }

    public function storeOperation(){
        
            $data = [
                'operation_name' => $this->input->post('operation_name'),
                'amount'         => $this->input->post('amount'),
                'operation_type' => $this->input->post('operation_type'),
                'operation'      => $this->input->post('operation'),
                'emp_id'         => $this->input->post('emp_id'),
                'net'            => $this->input->post('net'),
                'base_salary'    => $this->input->post('base'),
            ];
            
            $this->load->model("EmployeeModel");
            $this->EmployeeModel->insertOperation($data);
             
            $dataSalary= [
                'salary' => $this->input->post('salary')
            ];
            // print_r($_POST);
            // die;
            $id = $this->input->post('emp_id');
            $this->EmployeeModel->updateEmployee($dataSalary, $id);

            $this->load->view('template/header');
            redirect(base_url('employee/manageSalary'));

             // $data = file_get_contents("php://input");
            // if (!$this->input->is_ajax_request()) {
            //     exit('No direct script access allowed');
            // }
            //  $data=[
            //     'salary' => $this->input->post('valSalary')
            //  ];
            //  $this->dd($data);
        
    }

    public function edit($id)
    {
        $this->load->view('template/header');
        $this->load->model("EmployeeModel");
        $data['employee'] = $this->EmployeeModel->editEmployee($id);
        $this->load->view('frontend/edit', $data);
        $this->load->view('template/footer');
    }

    public function update($id)
    {

        $this->form_validation->set_rules('first_name', 'First Name', 'required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required');
        $this->form_validation->set_rules('phone', 'Phone', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required');
        $this->form_validation->set_rules('salary', 'salary', 'required');

        if ($this->form_validation->run()) :

            $data = [
                'first_name' => $this->input->post('first_name'),
                'last_name'  => $this->input->post('last_name'),
                'phone'      => $this->input->post('phone'),
                'email'      => $this->input->post('email'),
                'salary'      => $this->input->post('salary'),

            ];
            $this->load->model("EmployeeModel");
            $this->EmployeeModel->updateEmployee($data, $id);
            redirect(base_url('employee'));
        else :
            $this->edit($id);
        endif;
    }

    public function manageSalary()
    {
        $this->load->view('template/header');
        $this->load->model("EmployeeModel");
        $data['manageSalary'] = $this->EmployeeModel->joinAll();
        // $this->dd($data['manageSalary']);
        $this->load->view('frontend/manage_salary', $data);
        $this->load->view('template/footer');
    }
    public function allOperations()
    {
        $this->load->view('template/header');
        $this->load->model("EmployeeModel");
        $data['manageSalary'] = $this->EmployeeModel->joinAll();
        // $this->dd($data['manageSalary']);
        $this->load->view('frontend/operations', $data);
        $this->load->view('template/footer');
    }
    


    public function delete($id)
    {
        $this->load->model('EmployeeModel');
        $this->EmployeeModel->deleteEmployee($id);
        redirect(base_url('employee'));
    }

    public function deleteOperation($id)
    {
        $this->load->model('EmployeeModel');

        $data = $this->EmployeeModel->getOperation($id);
        $salary = $this->EmployeeModel->joinSalary($id);
        $test = json_decode(json_encode($salary), true);
        $oldSalary=$test[0]['salary'];
        $userId=$test[0]['id'];
         
        // $this->dd($salary);
        // print_r($netSalary);
        // die;
        if($data->operation_type == 'fixed'){
            if($data->operation == 'income'){
                $newSalary = $oldSalary - $data->amount;
            }else if($data->operation == 'deduction'){
                $newSalary = $oldSalary + $data->amount;
            }
        }else if($data->operation_type == 'percentage'){
            if($data->operation == 'income'){
                $newSalary  = $oldSalary - ($oldSalary*($data->amount*0.01));
            }else if($data->operation == 'deduction'){
                $newSalary = $oldSalary + ($oldSalary*($data->amount*0.01));
            }
        }
        $data= [
            'salary' => $newSalary
        ];
        //  print_r($test);
        // die;
        $this->EmployeeModel->updateEmployee($data, $userId);
        $this->EmployeeModel->deletOperation($id);
        redirect(base_url('employee/manageSalary'));
    }

    public function search()
    {
            if ($this->input->post('search')) {
                // $this->dd();
                $key = $this->input->post('search');
                $this->load->model('EmployeeModel');
                $detail = $this->EmployeeModel->search($key);
                $data['results'] = $detail;
                $data['join_table'] = $this->EmployeeModel->join($key);
                $this->load->view('template/header');
                $this->load->view('frontend/searchResult', $data);
            }
    }

    function fetch()
    {
    $output = '';
    $query = '';
    $this->load->model('EmployeeModel');
    if($this->input->post('query'))
    {
    $query = $this->input->post('query');
    }
    $data = $this->EmployeeModel->fetch_data($query);
    $output .= '
    <div class="table-responsive " >
        <table class="table table-bordered table-striped " id="example">
        <tr>
        <th>id</th>
        <th>employee Name</th>
        <th>Email</th>
        <th>Salary</th>
        
        </tr>
    ';
    if($data->num_rows() > 0)
    {
    foreach($data->result() as $row)
    {
        $output .= '
        <tr>
            <td>'.$row->id.'</td>
            <td>'.$row->first_name.' '.$row->last_name.'</td>
            <td>'.$row->email.'</td>
            <td>'.$row->salary.'</td>
        </tr>
        ';
    }
    }
    else
    {
    $output .= '<tr>
        <td colspan="5">No Data Found</td>
        </tr>';
    }
    $output .= '</table>';
    echo $output;
    }
    
        public function report($id){
            $this->load->model('EmployeeModel');
            $data['employee']   = $this->EmployeeModel->search($id);
            $data['operations'] = $this->EmployeeModel->joinMonth($id);
            $this->load->view('template/header');
            $this->load->view('frontend/report', $data);

        }

        ///////////////// for all emp /////////////

        public function showForAllEmp() {
            $this->load->model('EmployeeModel');
            $data['forAll'] = $this->EmployeeModel->forAll();
            $this->load->view('template/header');
            $this->load->view('frontend/forAllEmployee', $data);
        }

        public function storeOperationForAll() {
            if(empty($this->input->post('emp_id'))){

                $operation_name = $this->input->post('operation_name');
                $amount         = $this->input->post('amount');
                $operation_type = $this->input->post('operation_type');
                $operation      = $this->input->post('operation');

                $this->load->model("EmployeeModel");
                if($operation_type == "fixed"){
                    $this->EmployeeModel->insertAllEmpIncomeFixed($operation_name, $amount, $operation_type, $operation);
                }else {
                    $this->EmployeeModel->insertAllEmpIncomePercentage($operation_name, $amount, $operation_type, $operation);
                }
                
                $this->load->view('template/header');
                redirect(base_url('employee/showForAllEmp'));




                // $this->load->model("EmployeeModel");
                // $ids = $this->EmployeeModel->empIds();
                // //$this->dd($ids);
                // foreach ($ids as $id) {
                //     if (($this->input->post('operation_type') != 'null') && ($this->input->post('operation') != 'null')) {
                //         $salary = $this->EmployeeModel->salaryEmp($id->id)->salary;
                //         $base_salary = $salary;
                //         $x=$base_salary;
                //         $net = 0;
                //         if ($this->input->post('operation_type') != '') {
                //             if ($this->input->post('operation_type') == 'fixed') {
                //                 if ($this->input->post('operation') == 'income') {
                //                     $x = $x + $this->input->post('amount');        
            
                //                 } else if ($this->input->post('operation') == 'deduction') {
                //                     $x = $x - $this->input->post('amount');
                //                 }
                //             } else if ($this->input->post('operation_type') == 'percentage') {
                //                 if ($this->input->post('operation') == 'income') {
                //                     $x = $x + ($x * ($this->input->post('amount') * 0.01));
                //                 } else if ($this->input->post('operation') == 'deduction') {
                //                     $x = $x - ($x * ($this->input->post('amount') * 0.01));
                //                 }
                //             }
                //         }
                //         $net = $x - $salary;

                //     }
                //     $data = [
                //         'operation_name' => $this->input->post('operation_name'),
                //         'amount'         => $this->input->post('amount'),
                //         'operation_type' => $this->input->post('operation_type'),
                //         'operation'      => $this->input->post('operation'),
                //         'emp_id'         => $id->id,
                //         'net'            => $net  ,
                //         'base_salary'    => $base_salary,
                //         'all'            => 1,
                //     ];
                //     $this->load->model("EmployeeModel");
                //     $this->EmployeeModel->insertOperation($data);
                    
                //     if($id->id == $ids[count($ids)-1]->id){
                //         $this->load->view('template/header');
                //         redirect(base_url('employee/showForAllEmp'));
                //     }
                    
                // }
                

            }else {
                $this->load->model('EmployeeModel');
                $id = $this->input->post('emp_id');
                // $this->dd($this->input->post('emp_id'));
                $salary = $this->EmployeeModel->salaryEmp($id)->salary;
                $newSalary = $this->EmployeeModel->getSalartOperations($id);
                // print($newSalary->newsalary);
                // // $sumNet->sumnet
                // // print_r($netSalary->netsalary);
                // die;
                $base_salary = $salary;
                $x=$base_salary;
                $net = 0;
                if ($this->input->post('operation_type') != '') {
                    if ($this->input->post('operation_type') == 'fixed') {
                        if ($this->input->post('operation') == 'income') {
                            $x = $x + $this->input->post('amount');      
    
                        } else if ($this->input->post('operation') == 'deduction') {
                            $x = $x - $this->input->post('amount');
                        }
                    } else if ($this->input->post('operation_type') == 'percentage') {
                        if ($this->input->post('operation') == 'income') {
                            $x = $x + ($x * ($this->input->post('amount') * 0.01));
                        } else if ($this->input->post('operation') == 'deduction') {
                            $x = $x - ($x * ($this->input->post('amount') * 0.01));
                        }
                    }
                    $net = $x - $salary ;
                }
                
                $data = [
                    'operation_name' => $this->input->post('operation_name'),
                    'amount'         => $this->input->post('amount'),
                    'operation_type' => $this->input->post('operation_type'),
                    'operation'      => $this->input->post('operation'),
                    'emp_id'         => $this->input->post('emp_id'),
                    'net'            => $net,
                    'base_salary'    => $base_salary ,
                    'all'            => 0,
                ];
                $this->load->model("EmployeeModel");
                $this->EmployeeModel->insertOperation($data);

                $this->load->view('template/header');
                redirect(base_url('employee/showForAllEmp'));
            }
            
            

        }

        public function showMainReport(){
            $this->load->model('EmployeeModel');
            $data['operations'] = $this->EmployeeModel->joinMainReport();
            $this->load->view('template/header');
            $this->load->view('frontend/mainReport', $data);
        }

        public function savePhoto(){
            die;
            $api_url = 'https://jsonplaceholder.typicode.com/photos';
            $json_data = file_get_contents($api_url);
            $response_data = json_decode($json_data);
            $user_data = $response_data;die;

            foreach ($user_data as $user) {

                $url_to_image = $user->url.".jpg";
                $my_save_dir  = "./asset/img/api/";
                $filename     = basename($url_to_image);
                $complete_save_loc = $my_save_dir.$filename;
                $url_to_image=base64_decode($url_to_image);
                
                $ch = curl_init();
                echo "http://localhost:8000/employee/photoDownload/".$url_to_image;die;
                curl_setopt($ch, CURLOPT_URL,"http://localhost:8000/employee/photoDownload/".$url_to_image);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_TIMEOUT, 10);

                curl_setopt($ch, CURLOPT_POSTFIELDS,
                            "postvar1=value1&postvar2=value2&postvar3=value3");
                

                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                
                $server_output = curl_exec($ch);
die;    
                // file_put_contents($complete_save_loc, file_get_contents("http://localhost:8000/employee/photoDownload/".$url_to_image));
               
            }

            



            // $this->load->view('template/header');
            // redirect(base_url('photo.php'));
            
        }

        public function photoDownload($url){
        $url=base64_decode($url);

            // $url_to_image = 'https://jsonplaceholder.typicode.com/photos';
            // $my_save_dir  = "./asset/img/api/";
            // $filename     = basename($url_to_image);               
            // $complete_save_loc = $my_save_dir.$filename;

            file_put_contents($complete_save_loc, file_get_contents($url));
                

        }
    }
