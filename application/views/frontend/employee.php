<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
</head>
<body>
    

</html>
        <div class="container">
            <div class="row">
                <div class="col-md-12 mt-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>how to insert data into database</h5>
                        </div>
                        <div class="card-body">
                            <a href="<?php echo base_url('employee/add'); ?>" class="btn btn-primary float-right">Add Employee</a>
                            <a href="<?php echo base_url('employee/manageSalary'); ?>" class="btn btn-warning float-right"> Manage Salary </a> 
                            <a href="<?php echo base_url('employee/allOperations'); ?>" class="btn btn-warning float-right"> All Operations </a>
                            <a href="<?php echo base_url('employee/showForAllEmp'); ?>" class="btn btn-warning float-right"> For All Employee </a>
                            <a href="<?php echo base_url('employee/showMainReport'); ?>" class="btn btn-danger float-right"> Main Report </a>
                        </div>

                        <div class="card-body">
                            <table class="table table table-borderd" id="example">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>image</th>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Salary</th>
                                        <th>Phone No </th>
                                        <th>Email ID</th>
                                        <th>Edit</th>
                                        <th>Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        foreach ($employee as $row): ?>
                                            <tr>
                                                <td><?php echo $row->id ?></td>
                                                <td><img class="emp_img" src="<?php echo './asset/img/'.$row->img ?>" class="rounded-circle" alt="img"></td>
                                                <td><?php echo $row->first_name ?></td>
                                                <td><?php echo $row->last_name ?></td>
                                                <td><?php echo $row->salary ?></td>
                                                <td><?php echo $row->phone ?></td>
                                                <td><?php echo $row->email ?></td>
                                                <td>
                                                    <a href="<?php  echo base_url('employee/edit/'.$row->id) ?>" class="btn btn-success btn-sm">Edit</a>
                                                </td>
                                                <td>
                                                <a href="<?php  echo base_url('employee/delete/'.$row->id) ?>" class="btn btn-danger btn-sm">Delete</a>
                                                </td>
                                            </tr>   
                                    <?php    endforeach; ?>

                                    
                                  
                                </tbody>
                            </table>
                            <!-- <p><?php echo $links; ?></p> -->
                        </div>

                    </div>
                </div>
            </div>
        </div>
        </body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script >
$(document).ready(function() {
    $('#example').DataTable();
} );
</script>



