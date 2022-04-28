<?php

class EmployeeModel extends CI_Model
{
    public function getEmployee()
    {
        $query = $this->db->get('employee');
        return $query->result();
    }
    // get operation for delete

    public function getOperation($id)
    {
        $query = $this->db->get_where('manage_salary', ['id' => $id]);
        return $query->row();
    }
    // get salary for delete
    public function getSalary($id)
    {
        $query = $this->db->get_where('employee', ['id' => $id]);
        return $query->row();
    }

    public function joinSalary($key){
        $this->db->SELECT('employee.salary, employee.id');    
        $this->db->FROM('employee');
        $this->db->JOIN('manage_salary', 'manage_salary.emp_id = employee.id');
        $this->db->WHERE('manage_salary.id', $key);
        return $this->db->get()->result();
        // $query = $this->db->get_where('manage_salary', ['emp_id' => $key]);
        // return $query->row();
    }

    public function insertEmployee($data)
    {
        return $this->db->insert('employee', $data);
    }

    public function insertOperation($data)
    {
        return $this->db->insert('manage_salary', $data);
    }

    public function editEmployee($id)
    {
        $query = $this->db->get_where('employee', ['id' => $id]);
        return $query->row();
    }

    public function updateEmployee($data, $id)
    {
       return $this->db->update('employee', $data, ['id' => $id]);
    }

    public function deleteEmployee($id)
    {
       return $this->db->delete('employee',['id' => $id]);
    }
    public function deletOperation($id)
    {
       return $this->db->delete('manage_salary',['id' => $id]);
    }

    public function getManageSalary()
    {
        $query = $this->db->get('manage_salary');
        return $query->result();

    }

    public function search($key){
        $this->db->select('*');
        $this->db->from('employee');
        $this->db->where('id', $key);
        return $this->db->get()->row();

        // $query = $this->db->get_where('manage_salary', ['emp_id' => $key]);
        // return $query->row();
    }

    // public function searchAjax($query){
    //     $this->db->select("*");
    //     $this->db->FROM('employee');
    //     if($query != ''){
    //         $this->db->like('id', $query);
    //         $this->db->or_like('first_name', $query);
    //         $this->db->or_like('last_name', $query);
    //     }
    //     $this->db->order_by('id', 'DESC');
    //     return $this->db->get();

    // }

    function fetch_data($query)
    {
    $this->db->select("*");
    $this->db->from("employee");
    if($query != '')
    {
        $this->db->like('id', $query);
        $this->db->or_like('first_name', $query);
        $this->db->or_like('last_name', $query);
    }
    $this->db->order_by('id', 'DESC');
    return $this->db->get();
    }

    public function join($key){
        $this->db->SELECT('*');    
        $this->db->FROM('employee');
        $this->db->JOIN('manage_salary', 'manage_salary.emp_id = employee.id');
        $this->db->WHERE('emp_id', $key);
        return $this->db->get()->result();
        // $query = $this->db->get_where('manage_salary', ['emp_id' => $key]);
        // return $query->row();
    }
    public function joinAll(){
        $this->db->SELECT('*');    
        $this->db->FROM('employee');
        $this->db->JOIN('manage_salary', 'manage_salary.emp_id = employee.id');
        return $this->db->get()->result();
        // $query = $this->db->get_where('manage_salary', ['emp_id' => $key]);
        // return $query->row();
    }

    public function joinMonth($key){
        $query = $this->db->query('select year(created_at) as year, month(created_at) as month, sum(net) as net_operation, base_salary from manage_salary group by year(created_at), month(created_at)');   
        return $query->result();  

    }

    public function joinMainReport(){
        $query = $this->db->query('select emp_id ,First_name,last_name , year(created_at) as year, month(created_at) as month, sum(net) as net_operation, base_salary from employee
        INNER JOIN manage_salary ON employee.id = manage_salary.emp_id
        group by year(created_at), month(created_at), emp_id');  
        return $query->result();  
    }
    //////// neeeeeeww ////////

    public function insertAllEmpIncomeFixed($operation_name, $amount, $operation_type, $operation){
        $query = $this->db->query("
        Insert into manage_salary(emp_id, operation_name, operation_type, operation, amount, base_salary, for_all)
        
            Select id AS emp_id,
                    '$operation_name' AS operation_name,
                    '$operation_type' AS operation_type,
                    '$operation' AS operation,
                    '$amount' AS amount,
                    
                     (Case '$operation_type' = 'fixed'
                         When '$operation' = 'income'    Then salary + '$amount'
                         When '$operation' = 'deduction' Then salary - '$amount'
                    End)  as base_salary,

                    1 AS for_all

        from employee ;"); 
        

        
    }

    public function insertAllEmpIncomePercentage($operation_name, $amount, $operation_type, $operation){
        $query = $this->db->query("
        Insert into manage_salary(emp_id, operation_name, operation_type, operation, amount, base_salary, for_all)
        
            Select id AS emp_id,
                    '$operation_name' AS operation_name,
                    '$operation_type' AS operation_type,
                    '$operation' AS operation,
                    '$amount' AS amount,
                    
                    (Case '$operation_type' = 'percentage'
                    When '$operation' = 'income'    Then salary + (salary * '$amount' * 0.01)
                    When '$operation' = 'deduction' Then salary - (salary * '$amount' * 0.01)
               End)  as base_salary,

               1 AS for_all

        from employee ;");  
        
    }


    public function getSalartOperations($id){
        $this->db->SELECT('base_salary as newsalary , max(created_at)');    
        $this->db->FROM('manage_salary');
        $this->db->WHERE('emp_id', $id);
        return $this->db->get()->row();
    }
    

    public function forAll(){
        $this->db->SELECT('*');    
        $this->db->FROM('manage_salary');
        $this->db->WHERE('for_all', 1);
        return $this->db->get()->result();
    }

    public function empIds(){
        $this->db->SELECT('id');    
        $this->db->FROM('employee');
        return $this->db->get()->result();
    }

    public function salaryEmp($id){
        $this->db->select('salary');
        $this->db->from('employee');
        $this->db->where('id', $id);
        return $this->db->get()->row();
    }

}