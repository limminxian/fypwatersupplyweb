<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="style.css">
	<div id="nav-placeholder">
	</div>

	<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
	<script>
	$(function(){
	  $("#nav-placeholder").load("navBarTech.php");
	});
	</script>
</head>
<body>
<?php 
include_once 'userClass.php';
$_SESSION["page"]="technician";
if(!isset($_SESSION['loginId'])){
	echo "Not allowed! Please login!";
	?>
	
<br><br><input type="button" onclick="window.location.href='login.php';" value="Login" />

<?php

} 
else{
	if(isset($_POST["logout"])){
	unset($_SESSION["loginId"]);
	header("Location: login.php");
}

if (isset($_POST["addNew"])){
	header("Location: addChemical.php");
}

$chemical = new Company();
$chemical->getAllChemical();
?>
<br>
<table>
  <tr>
    <th>ID</th>
    <th>Name</th>
    <th>Amount</th>
    <th></th>
    <th></th>
    <th></th>
	<th></th>
  </tr>	
  <form action="" method="post">
  	<a class="rightButton" href="addChemical.php">Add new chemical</a>
<?php
foreach($chemical->chemicalArray as $c){
	?>
  <tr>
	<?php
		$properties = array('id', 'name', 'amount');
		foreach ($properties as $prop) {?>
			<td>
				<?=$c->$prop?>
			</td>
		<?php }
	?>
	<td>
		<button  value="<?=base64_encode(serialize($c))?>" name="add"/>Add amount used</button>
	</td>
	<td>
		<button  value="<?=base64_encode(serialize($c))?>" name="view"/>View amount Used</button>
	</td>
	<td>
		<button  value="<?=base64_encode(serialize($c))?>" name="edit"/>edit</button>
	</td>
	<td>
		<button  value="<?=base64_encode(serialize($c))?>" name="delete"/>delete</button>
	</td>
	</tr>
  <?php
}
?>

</form>
</div>

<?php 
}
?>
   
</body>
</html> 
