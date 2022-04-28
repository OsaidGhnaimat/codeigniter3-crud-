<!DOCTYPE html>
<html>
<body>
<style>
table, td, th {    
    border: 1px solid #ddd;
    text-align: left;
}

table {
    border-collapse: collapse;
    width: 40%;
}

th, td {
    padding: 13px;
}
</style>
<table class="col-8 m-auto my-5"> 
        <thead>  
            <th>Id</th>  
            <th>Year</th>  
            <th>Month</th>  
            <th>Total Operations</th>
            <th>Net salary</th>
                        
        </thead>  
        <tbody>  
            <?php  
             $sumSalary=0;
                foreach($operations as $row)
                {  
				$monthNum = $row->month;
                $dateObj = DateTime::createFromFormat('!m', $monthNum);
                $monthName = $dateObj->format('F');
                    /*name has to be same as in the database. */
                   ?> <tr> 
                            <td><?php echo $row->emp_id ?></td>
                            <td><?php echo $row->year ?></td>
                            <td><?php echo $monthName ?></td>  
                            <td><?php echo $row->net_operation ?></td> 
                            <td><?php echo $row->base_salary + $row->net_operation  ?></td> 
                    </tr> 
                    
               <?php
                $sumSalary += $row->base_salary;
            }  
               
            ?> 
                    <tr class="bg-secondary ">
                            <td class="text-white" colspan="4">Sum</td>
                            <td class="text-white" colspan="1"><?php echo  $sumSalary?></td>
                    </tr> 
        </tbody> 
    </table> 
</body>
</html>