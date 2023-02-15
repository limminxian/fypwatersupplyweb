<?php
include_once 'userClass.php';

$c=new Company();
$data = new DataManager();

$sub = [];
$unsub = [];


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

	function getSubscription($company){
		$c=new Company();
		$sub=[];
		$unsub=[];
		$subscribers = $c->getCumulativeSubscribers($company);
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
		$loop = 5000;
		$c0=$data[0][1];
		$c1=$data[0][2];

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
	
	function squaredError (float $c0, float $c1, array $data): float {
	  return array_sum(
		array_map(
		  function ($point) use ($c0, $c1) {
			return ($point[2] - linearFunction($c0, $point[0], $c1, $point[1])) ** 2;
		  },
		  $data
		)
	  ) / count($data);
	}
	
	function linearFunction (float $c0, float $x0, float $c1, float $x1) : float {
		return $c0 * $x0 + $c1 * $x1;
	}
	
	function descent (int $m, float $c0, float $c1, array $data): float {
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

	function adaptC1 (float $c0, float $c1, array $data, float $learningRate): float {
		return $c1 - $learningRate * descent(1, $c0, $c1, $data);
	}
	
	function getCumulativeSubscription($company){
		$c=new Company();
		$cumulative=[];
		$subscribers = $c->getCumulativeSubscribers($company);
		$current = strtotime("-12 month");
		for($i=0;$i<12;$i++){
			$check = false;
			$cu = date("Ym",$current);
			foreach ($subscribers as $s){
				if(strcmp($cu,$s["YEARMONTH"])==0){
					array_push($cumulative,array("label"=> $cu, "y"=>(int)$s["CUMULATIVESUB"]));
					$check = true;
				}
			}
			if(!$check && $i!=0){
				array_push($cumulative,array("label"=> $cu, "y"=>end($cumulative)["y"]));
			}
			else if(!$check){
				array_push($cumulative,array("label"=> $cu, "y"=>0));
			}
			$current = strtotime("+1 month", $current);
		}
		return $cumulative;
	}
	
	function getCumulativeSubscriptionEstimation($company){
		$cumulative = getCumulativeSubscription($company);		
		$data=[];
		$i=0;
		foreach($cumulative as $c){
			$i++;
			array_push($data,array(1,$i,$c["y"]));
		}
		
		$linear = getLinear($data);
		$newcumulative = [];
		$current = time();
		for($i=12;$i<24;$i++){
			$cu = date("Ym",$current);
			$y = $linear[0] + ($linear[1]*$i);
			array_push($newcumulative,array("label"=> $cu, "y"=>(int)$y));
			$current = strtotime("+1 month", $current);
		}
		return $newcumulative;
	}
	
	function overallWaterUsagePerPeople($company){
		$data = new DataManager();
		$waterusage = $data->getAllWaterUse($company);
		$totalwater=0;
		$totalpeople=0;
		foreach($waterusage as $w){
			$totalwater+=$w["WATERUSAGE"];
			$totalpeople+=$w["NOOFPEOPLE"];
		}
		return $totalwater/$totalpeople;
		
	}
	
	function getWaterUsageEstimation($company){
		$overall=overallWaterUsagePerPeople($company);
		$data = new DataManager();
		$waterusage = $data->getAllWaterUse($company);
		$sub = getCumulativeSubscriptionEstimation($company);
		$current = strtotime("-12 month");
		$estimation = time();
		$waterperpeople=[];
		$waterest=[];
		for($i=0;$i<12;$i++){
			$check = false;
			$cu = date("Ym",$current);
			$est = date("Ym",$estimation);
			foreach ($waterusage as $w){
				if(strcmp($cu,$w["RECORD"])==0){
					$wa = $w["WATERUSAGE"]/$w["NOOFPEOPLE"];
					array_push($waterperpeople,array($est,$wa));
					$check = true;
				}
			}
			if(!$check && $i==0){
				array_push($waterperpeople,array($est, $overall));
			}
			else if(!$check){
				array_push($waterperpeople,array($est, end($waterperpeople)[1]));
			}
			$current = strtotime("+1 month", $current);
			$estimation = strtotime("+1 month", $estimation);
		}
		/* print "<pre>"; 
		print_r ($sub);
		print "<pre>"; */
		for($i=0;$i<12;$i++){
			$w=$waterperpeople[$i];
			$s=$sub[$i];
			if (strcmp($s["label"],$w[0]==0)){
				$c = $s["y"]*$w[1];
				array_push($waterest,array("label"=> $w[0], "y"=>(int)$c));
			} 
		}
		return $waterest;
	}
	
	function getChemicalEstimation($c,$id){
		$chemical=[];
		$com = new Staff();
		$company = $com->getCompany($id);
		foreach(getWaterUsageEstimation($company)as $a){
			$est = (FLOAT)$a["y"]*(FLOAT)$c;
			array_push($chemical,array("label"=>$a["label"],"y"=>(FLOAT)$est));
		}
		return $chemical;
	}
?>