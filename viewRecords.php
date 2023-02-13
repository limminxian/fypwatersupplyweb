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
	  $("#nav-placeholder").load("navBarComp.php");
	});
	</script>
</head>
<body>
<?php 
include_once 'userClass.php';
$_SESSION["page"]="viewRecords";
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
 ?>
	<select name="type" id="type" onchange="chart()">
		<?php
		$type = array("subscribers","estimation","maintenance","revenue","area");
		foreach($type as $t){
		?>
			<option value=<?=$t?>><?=$t?></option>
		<?php
		}
		?>
	</select>
<?php
$c=new Company();
$sub = [];
$unsub = [];
$data = new DataManager();
$waterusage = $data->getAllWaterUse($_SESSION["loginId"]);
$c->getCumulativeSubscribers();
$current = strtotime("-12 month");
for($i=0;$i<12;$i++){
	$check = false;
	$cu = date("Ym",$current);
	foreach ($c->subscribers as $s){
		if(strcmp($cu,$s["YEARMONTH"])==0){
			array_push($sub,array("label"=> $cu, "y"=>(int)$s["SUBSCRIBER"]));
			$check = true;
		}
	}
	if(!$check){
		array_push($sub,array("label"=> $cu, "y"=>0));
	}
	$current = strtotime("+1 month", $current);
	
	foreach ($c->subscribers as $s){
		if(strcmp($cu,$s["YEARMONTH"])==0){
			array_push($unsub,array("label"=> $cu, "y"=>(int)$s["UNSUBSCRIBER"]));
			$check = true;
		}
	}
	if(!$check){
		array_push($unsub,array("label"=> $cu, "y"=>0));
	}
}

?>
<!DOCTYPE HTML>
<html>
<head>  
<script>
window.onload = function chart() {
	var c = document.getElementById("type").value;
	switch (c){
		case "subscribers":
			var chart = new CanvasJS.Chart("chartContainer", {
				animationEnabled: true,
				exportEnabled: true,
				theme: "light1", // "light1", "light2", "dark1", "dark2"
				title:{
					text: "Subscribe count data in past 12 months"
				},
				legend:{
					cursor: "pointer",
					verticalAlign: "center",
					horizontalAlign: "right",
					itemclick: toggleDataSeries
				},
				data: [{
					type: "column", //change type to bar, line, area, pie, etc
					name: "Subscribe count",
					indexLabel: "{y}", //Shows y value on all Data Points
					showInLegend: true,
					indexLabelFontColor: "#5A5757",
					indexLabelPlacement: "outside",   
					dataPoints: <?php echo json_encode($sub); ?>
				},{
					type: "column",
					name: "Unsubscribe count",
					indexLabel: "{y}",
					showInLegend: true,
					indexLabelFontColor: "#5A5757",
					indexLabelPlacement: "outside",  
					dataPoints: <?php echo json_encode($unsub); ?>
				}]
			});
			chart.render();
			  
			function toggleDataSeries(e){
				if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
					e.dataSeries.visible = false;
				}
				else{
					e.dataSeries.visible = true;
				}
				chart.render();
			}
			break;
		
		case "estimation":

	}
 

}
</script>

<div id="chartContainer" style="height: 370px; width: 100%;"></div>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
         <?PHP
}
?>
   
</body>
</html> 
