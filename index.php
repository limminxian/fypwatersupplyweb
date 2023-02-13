<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">

<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
body,h1,h2,h3,h4,h5 {font-family: "Poppins", sans-serif}
body {font-size: 16px;}
img {margin-bottom: -8px;}
.mySlides {display: none;}
</style>
	
<?php
include_once 'config.php';
include_once 'userClass.php';
createTables();
createSuperadmin();
?>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="stylesheet" href="style.css" type="text/css">
<div id="nav-placeholder">
</div>

<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
<script>
$(function(){
  $("#nav-placeholder").load("navBarIndex.php");
});
</script>
</head>
<body>



<div class="slideshow-container">

<div class="bannerSlides fade">
  <img src="img/homeslider1.PNG" class="slides" style="width:100%">
</div>

<div class="bannerSlides fade">
  <img src="img/homeslider2.PNG" class="slides" style="width:100%">
</div>

<div class="bannerSlides fade">
  <img src="img/homeslider3.PNG" class="slides" style="width:100%">
</div>

<a class="prev" onclick="plusSlides(-1)"><</a>
<a class="next" onclick="plusSlides(1)">></a>

</div>
<br>

<div style="text-align:center">
  <span class="dot" onclick="currentSlide(1)"></span> 
  <span class="dot" onclick="currentSlide(2)"></span> 
  <span class="dot" onclick="currentSlide(3)"></span> 
</div>

<h2>Top rating company</h2>

<div class="row">
<?php
	$company = new DataManager();
	$company->getTopCompany(3);
	foreach($company->topCompanyArray as $c){
?>
		<div class="column">
			<div class="card">
				<img src="img/business<?=$c->id?>.jpg" class="companyphoto" >
				<div class="container">
					<h2><?=$c->compName?></h2>
					<?php
					for($i=1;$i<=5;$i++){
						if($c->noofstar < $i){
							if(is_float($c->noofstar)&& (round($c->noofstar)==$i)){
							?>
								<img src="img/halfstar.png" class="star">
							<?php
							}else{
							?>
								<img src="img/emptystar.png" class="star">
							<?php
							}
						}else{
							?>
								<img src="img/star.png" class="star">
							<?php
						}
					}
					?>
					<p class="rating"><?=$c->noofrate?> subsribers</p>
					<p><?=$c->description?></p>
					<p><button class="button" name="learn">Learn more</button></p>
				</div>
			</div>
		</div>
<?php
	}
?>
</div>
<br>
<br>
<br>
<!-- Clarity Section -->
<div class="w3-padding-64 w3-light-grey">
  <div class="w3-row-padding">
    <div class="w3-col l4 m6">
      <img class="w3-image w3-round-large w3-hide-small w3-grayscale" src="img/waterdroplet.jpg" alt="App" width="335" height="471">
    </div>
    <div class="w3-col l8 m6">
      <h1 class="w3-jumbo"><b>Our Unique Capabilities</b></h1>
      <h1 class="w3-xxxlarge w3-text-red"><b>why join us ?</b></h1>
      <p><span class="w3-xlarge">A Leadership Team.</span> We are a dynamic team with alot of determination. We have experience in collaborating with many companies. We bring out the best abilities in the companies that have joined us and motivate them to work together in achieving a shared goal.Come on board with us and you will never look back!!.</p>
    </div>
  </div>
  <div class="aboutContainer">
  <img src="img/homeAboutBack.jpg" style="width:100%;" class="about">
  <div class="text-block">
    <h1>Who are we</h1>
    <h2>Get to know more about our company!</h2>
    <p>Our company provides a platform for our homeowners and business owners where they can communicate with eachother and home owners can choose to subscribe to business services for water supply.</p>
	<br>
	<div class="centerButton">
		<a>  href="about.php" name="read" class="button">Read more</button </a>
		<br>
	</div>
  </div>
</div>

<br>
<br>

<div class="slideshow-container">
<h2>What they say about us</h2>
<br>
<div class="reviewSlides fade">
	<div class="review">
		<div class="card">
			<div class="container">
				<h2>Jane</h2>
				<h4>subscribe to company4</h4>
				<p>Great software</p>
				<p>Some comments....</p>
			</div>
		</div>
	</div>
</div>

<div class="reviewSlides fade">
	<div class="review">
		<div class="card">
			<div class="container">
				<h2>John</h2>
				<h4>subscribe to company2</h4>
				<p>Best software</p>
				<p>Some comments....</p>
			</div>
		</div>
	</div>
</div>

<div class="reviewSlides fade">
	<div class="review">
		<div class="card">
			<div class="container">
				<h2>Dan</h2>
				<h4>subscribe to company1</h4>
				<p>Cheap</p>
				<p>Some comments....</p>
			</div>
		</div>
	</div>
</div>


</div>

<script>
let bannerSlideIndex = 0;
let reviewSlideIndex = 0;
showBannerSlides(bannerSlideIndex);
showReviewSlides();

function plusSlides(n) {
  showBannerSlides(bannerSlideIndex += n);
}

function currentSlide(n) {
  showBannerSlides(bannerSlideIndex = n);
}


function showBannerSlides(n) {
  let i;
  let slides = document.getElementsByClassName("bannerSlides");
  let dots = document.getElementsByClassName("dot");
  for (i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";  
  }
  bannerSlideIndex++;
  if (bannerSlideIndex > slides.length) {bannerSlideIndex = 1}    
  for (i = 0; i < dots.length; i++) {
    dots[i].className = dots[i].className.replace(" active", "");
  }
  slides[bannerSlideIndex-1].style.display = "block";  
  dots[bannerSlideIndex-1].className += " active";
  setTimeout(showBannerSlides, 5000); // Change image every 2 seconds
}


function showReviewSlides() {
  let i;
  let slides = document.getElementsByClassName("reviewSlides");
  for (i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";  
  }
  reviewSlideIndex++;
  if (reviewSlideIndex > slides.length) {reviewSlideIndex = 1}   
  slides[reviewSlideIndex-1].style.display = "block";  
  setTimeout(showReviewSlides, 5000); // Change image every 2 seconds
}
</script>
</body>
</html>


