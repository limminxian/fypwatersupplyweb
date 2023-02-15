<?php
include_once 'userClass.php';

$c=new Company();
$data = new DataManager();

$sub = [];
$unsub = [];

$waterusage = $data->getAllWaterUse($_SESSION["loginId"]);


$uniquenoofpeople = $data->getUniqueNoofpeople($_SESSION["loginId"]);
$noofpeople = array("less than 3"=>0,"3 to 5"=>0,"6 to 9"=>0,"more than 10"=>0);
$revenue = $data->getRevenue($_SESSION["loginId"]);
foreach($uniquenoofpeople as $a){
	if($a<=2){
		$noofpeople["less than 3"]+=1;
	}
	else if($a>2 and $a<=5){
		$noofpeople["3 to 5"]+=1;
	}
	else if($a>5 and $a<=9){
		$noofpeople["6 to 9"]+=1;
	}
	else{
		$noofpeople["more than 10"]+=1;
	}
}
$areahomeowner = $data->getAreaHomeowner($_SESSION["loginId"]);

	function getSubscription(){
		$c=new Company();
		$sub=[];
		$unsub=[];
		$subscribers = $c->getCumulativeSubscribers();
		$current = strtotime("-12 month");
		for($i=0;$i<12;$i++){
			$check = false;
			$cu = date("Ym",$current);
			foreach ($subscribers as $s){
				if(strcmp($cu,$s["YEARMONTH"])==0){
					array_push($sub,array("label"=> $cu, "y"=>(int)$s["SUBSCRIBER"]));
					$check = true;
				}
			}
			if(!$check){
				array_push($sub,array("label"=> $cu, "y"=>0));
			}
			$current = strtotime("+1 month", $current);
			
			foreach ($subscribers as $s){
				if(strcmp($cu,$s["YEARMONTH"])==0){
					array_push($unsub,array("label"=> $cu, "y"=>(int)$s["UNSUBSCRIBER"]));
					$check = true;
				}
			}
			if(!$check){
				array_push($unsub,array("label"=> $cu, "y"=>0));
			}
		}
		return array($sub,$unsub);
	}
	
	function getLinear($data){
		$learningRate=0.001;
		$loop = 3000;
		$c0=$data[0][1];
		$c1=$data[0][2];

		function linearFunction (float $c0, float $x0, float $c1, float $x1) : float {
			return $c0 * $x0 + $c1 * $x1;
		}

		function squaredError(float $c0, float $c1, array $data): float {
		  return array_sum(
			array_map(
			  function ($point) use ($c0, $c1) {
				return ($point[2] - linearFunction($c0, $point[0], $c1, $point[1])) ** 2;
			  },
			  $data
			)
		  ) / count($data);
		}

		var_dump(squaredError($c0, $c1, $data));

		function descent(int $m, float $c0, float $c1, array $data): float {
		  return (-2 / count($data)) * array_sum(
			array_map(
			  function ($point) use ($c0, $c1, $m) {
				return ($point[2] - linearFunction($c0, $point[0], $c1, $point[1])) * $point[$m];
			  },
			  $data
			)
		  );
		}
		function adaptC0(float $c0, float $c1, array $data, float $learningRate): float {
			return $c0 - $learningRate * descent(0, $c0, $c1, $data);
		}

		function adaptC1(float $c0, float $c1, array $data, float $learningRate): float {
			return $c1 - $learningRate * descent(1, $c0, $c1, $data);
		}

		$errors = [];
		for ($i = 0; $i < $loop; $i++) {
			$errors[] = squaredError($c0, $c1, $data);
			$newC0 = adaptC0($c0, $c1, $data, $learningRate);
			$newC1 = adaptC1($c0, $c1, $data, $learningRate);

			$c0 = $newC0;
			$c1 = $newC1;
		} 
		return array($c0 ,$c1);
	}
?>