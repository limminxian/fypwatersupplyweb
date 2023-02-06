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
$_SESSION["page"]="viewEquipment";
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

$equipment = new Equipment();
$equipment->getAllEquipment($_SESSION["equiptype"]->id);
if (isset($_POST["add"])){
	header("Location: addEquipmentStock.php");
}

if (isset($_POST["csv"])){
	header("Location: addEquipmentCSV.php");
}

?>
<br>
<table>
<tr bgcolor="#488AC7">
    <th>ID</th>
    <th>serial</th>
    <th>purchasedate</th>
    <th></th>
    <th></th>
  </tr>	
  <form action="" method="post">
  	<button name="add"class="edit"/>Add new stock</button>
  	<button name="csv"class="edit"/>Add new stocks by batch (CSV)</button>
<?php
foreach($equipment->equipmentArray as $c){
	?>
  <tr>
	<?php
		$properties = array('id', 'serial', 'purchasedate');
		foreach ($properties as $prop) {?>
			<td>
				<?=$c->$prop?>
			</td>
		<?php }
	?>
	<td>
		<button  value="<?=base64_encode(serialize($c))?>" name="edit"class="edit"/>edit</button>
	</td>
	<td>
		
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
