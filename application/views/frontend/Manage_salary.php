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
	
	
	<?php echo validation_errors(); ?>
<form action="<?php echo base_url('employee/search') ?>" method="post" class="form-search my-5">
	<input class="search-bar" id="search_text" type="text" name="search" placeholder="Search employee..">
	<small style="color:red;"><?php echo form_error('search'); ?></small>

	<button type="submit"> search </button>
</form>

<div id="result" class="text-center m-auto w-75"></div>

</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script >
$(document).ready(function() {
    $('#example').DataTable();
} );
	
$(document).ready(function(){

load_data();

function load_data(query)
{

 $.ajax({
  url:"<?php echo base_url(); ?>EmployeeController/fetch",
  method:"POST",
  data:{query:query},
  success:function(data){
   $('#result').html(data);
  }
 })
}

$('#search_text').keyup(function(){
 var search = $(this).val();
 if(search != '')
 {
  load_data(search);
 }
 else
 {
  load_data();
 }
});

});

</script>