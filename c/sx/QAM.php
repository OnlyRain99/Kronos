<?php
    include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';
	
	$title = "Quality Assurance Management"
	
?>









<!DOCTYPE html>
<html lang="en">
<?php include 'head.php'; ?>
<script
  type="text/javascript"
  src="../node_modules/tw-elements/dist/js/tw-elements.umd.min.js"></script>
  <style type="text/css">

  </style>
<body>
	<div class="page-wrapper">
	<?php include 'header-m.php'; ?>
    <?php include 'sidebar.php'; ?>
	<div class="page-container">
		<div class="main-content" style="padding: 20px;">
			<div class="col-lg-12">
                    <h2 class="title-1 m-b-25">QAM(Quality Assurance Management)<i class="fa fa-check"></i></h2>
                    
                </div>
                	<content class="main box-border my-10">
      <div class="card my-2 bg-white shadow-lg rounded">
        <div class="card-header p-5 border-b" >
         <center> <h2 class="text-xl text-gray-700 font-medium tracking-wide" >Weekly</h2></center>
                 </div>
              <div class="card-content p-5">
              <div class="grid grid-cols-3 gap-4 text-center">
              <!DOCTYPE HTML>
<html>
<head>
<script>
window.onload = function () {

var chart = new CanvasJS.Chart("chartContainer", {
	title:{
		text: "QA Week Trend"
	},
	axisY: {
		title: "Number of Locations",
		lineColor: "#4F81BC",
		tickColor: "#4F81BC",
		labelFontColor: "#4F81BC"
	},
	axisY2: {
		title: "Percent",
		suffix: "%",
		lineColor: "#C0504E",
		tickColor: "#C0504E",
		labelFontColor: "#C0504E"
	},
  
	data: [{
		type: "column",
    name: "Audit Count",
    showInLegend: true,
		dataPoints: [
			{ label: "Week 33", y: 44853 },
			{ label: "Week 34", y: 36525 },
			{ label: "Week 35", y: 23768 },
			{ label: "Week 36", y: 19420 },
			{ label: "Week 37", y: 13528 },
			{ label: "Week 38", y: 11906 },
      { label: "Week 39", y: 54330 }
		]
	}]
  
});
chart.render();
createPareto();	

function createPareto(){
	var dps = [];
	var yValue, yTotal = 0, yPercent = 0;

	for(var i = 0; i < chart.data[0].dataPoints.length; i++)
		yTotal += chart.data[0].dataPoints[i].y;

	for(var i = 0; i < chart.data[0].dataPoints.length; i++){
		yValue = chart.data[0].dataPoints[i].y;
		yPercent += (yValue / yTotal * 100);
		dps.push({label: chart.data[0].dataPoints[i].label, y: yPercent});
	}
	
	chart.addTo("data",{type:"line", yValueFormatString: "0.##\"%\"", dataPoints: dps});
	chart.data[1].set("axisYType", "secondary", false);
	chart.axisY[0].set("maximum", yTotal);
	chart.axisY2[0].set("maximum", 100);
}

}
</script>
</head>
<body>
<div id="chartContainer" style="height: 300px; width: 100%;"></div>
<script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
</body>
</html>
          </div>
        </div>
        <div class="card-footer border-t p-5">
          <a class="inline-block" href="#"><i class="mr-2 mdi mdi-newspaper-variant"></i>Something happened yesterday...</a>
        </div>
        <div class="card-footer border-t p-5">
          <a class="inline-block" href="#"><i class="mr-2 mdi mdi-newspaper-variant-multiple-outline"></i>Something happened last year...</a>
        </div>
      </div>
       <div class="card mt-5 bg-white shadow-lg">
        <div class="card-header p-5 border-b">
          
        <center> <h2 class="text-xl text-gray-700 font-medium tracking-wide">QA Monthly Trend</h2> </center> 
        </div>
        <div class="card-content p-5">
          <div class="grid grid-cols-3 gap-4 text-center">
          		-----------------------
          </div>
        </div>
        <div class="card-footer border-t p-5">
          <a class="inline-block" href="#"><i class="mr-2 mdi mdi-newspaper-variant"></i>Something happened yesterday...</a>
        </div>
        <div class="card-footer border-t p-5">
          <a class="inline-block" href="#"><i class="mr-2 mdi mdi-newspaper-variant-multiple-outline"></i>Something happened last year...</a>
        </div>
      </div>
      <div class="card mt-5 bg-white shadow-lg">
        <div class="card-header p-5 border-b">
        <center> <h2 class="text-xl text-gray-700 font-medium tracking-wide">Department</h2></center> 
        </div>
        <div class="container">
      </div>
        <div class="card-content p-5">
          <div class="grid grid-cols-3 gap-4 text-center">
          		-----------------------
          </div>
        </div>
        <div class="card-footer border-t p-5">
          <a class="inline-block" href="#"><i class="mr-2 mdi mdi-newspaper-variant"></i>Something happened yesterday...</a>
        </div>
        <div class="card-footer border-t p-5">
          <a class="inline-block" href="#"><i class="mr-2 mdi mdi-newspaper-variant-multiple-outline"></i>Something happened last year...</a>
        </div>
      </div>
      <div class="card mt-5 bg-white shadow-lg">
        <div class="card-header p-5 border-b">
        <center>  <h2 class="text-xl text-gray-700 font-medium tracking-wide">Over All</h2></center> 
        </div>
        <div class="card-content p-5">
          <div class="grid grid-cols-3 gap-4 text-center">
           ----------------
          </div>
        </div>
        <div class="card-footer border-t p-5">
          <a class="inline-block" href="#"><i class="mr-2 mdi mdi-newspaper-variant"></i>Something happened yesterday...</a>
        </div>
        <div class="card-footer border-t p-5">
          <a class="inline-block" href="#"><i class="mr-2 mdi mdi-newspaper-variant-multiple-outline"></i>Something happened last year...</a>
        </div>
      </div>
    </content>	


	<div class="card-deck">
			<body>
  <div class="container">
   
    <div class="card">
    	<h2 class="text">Quality Performance</h2>
      <div class="box">
        <div class="percent">
          <svg>
            <circle cx="70" cy="70" r="70"></circle>
            <circle cx="70" cy="70" r="70"></circle>
          </svg>
          <div class="number">
            <h2>85<span>%</span></h2>
          </div>
        </div>
        <h2 class="text">QA Score</h2>
      </div>
    </div>
 
  </div>
</body>
<style type="text/css">
	@import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap');


.container {
  position: relative;
  width: 400px;
  display: flex;
  justify-content: space-around;
}

.container .card {
  position: relative;
  width: 250px;
  background: linear-gradient(0deg, #1b1b1b, #222, #1b1b1b);
  display: flex;
  justify-content: center;
  align-items: center;
  height: 300px;
  border-radius: 4px;
  text-align: center;
  overflow: hidden;
  transition: 0.5s;
}

.container .card:hover {
  transform: translateY(-10px);
  box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5);
}

.container .card:before {
  content: '';
  position: absolute;
  top: 0;
  left: -50%;
  width: 100%;
  height: 100%;
  background: rgba(255, 255, 255, 0.03);
  pointer-events: none;
  z-index: 1;
}

.percent {
  position: relative;
  width: 150px;
  height: 150px;
  border-radius: 50%;
  box-shadow: inset 0 0 50px #000;
  background: #222;
  z-index: 1000;
}

.percent .number {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
  border-radius: 50%;
}

.percent .number h2 {
  color: #777;
  font-weight: 700;
  font-size: 40px;
  transition: 0.5s;
}

.card:hover .percent .number h2 {
  color: #fff;
  font-size: 60px;
}

.percent .number h2 span {
  font-size: 24px;
  color: #777;
}

.card:hover .percent .number h2 span {
  color: #fff;
  transition: 0.5s;
}

.text {
  position: relative;
  color: #777;
  margin-top: 20px;
  font-weight: 700;
  font-size: 18px;
  letter-spacing: 1px;
  text-transform: uppercase;
  transition: 0.5s;
}

.card:hover .text {
  color: #fff;
}

svg {
  position: relative;
  width: 150px;
  height: 150px;
  z-index: 1000;
}

svg circle {
  width: 100%;
  height: 100%;
  fill: none;
  stroke: #191919;
  stroke-width: 10;
  stroke-linecap: round;
  transform: translate(5px, 5px);
}

svg circle:nth-child(2) {
  stroke-dasharray: 440;
  stroke-dashoffset: 440;
}

.card:nth-child(1) svg circle:nth-child(2) {
  stroke-dashoffset: calc(440 - (440 * 90) / 100);
  stroke: #00ff43;
}

.card:nth-child(2) svg circle:nth-child(2) {
  stroke-dashoffset: calc(440 - (440 * 85) / 100);
  stroke: #00a1ff;
}
.card:nth-child(3) svg circle:nth-child(2) {
  stroke-dashoffset: calc(440 - (440 * 60) / 100);
  stroke: #ff04f7;
}

@media (max-width: 991px) {
  .container {
    width: 100%;
    flex-direction: column;
  }

  .container .card {
    margin: 20px auto;
  }
}
.container{
  width: 85%;
  margin: 0 auto;
  display: flex;
  justify-content: center;
  flex-direction: column;
  align-items: center;
}
 

.btn-dark-mode{
  width: 75px;
  height: 35px;
  background-color: #CCC;
  border-radius: 25px;
  padding: 1px;
  display:flex;
  justify-content: flex-start;
  align-items: center;
  cursor: pointer;
  transition: 1s;
}

.slider {
  width: 30px;
  height: 30px;
  background-color: #FFF;
  border-radius: 50%;
  margin:2px;
  transition: 1s;
}


</style>
			</div>
			<?php include 'footer.php'; ?>
		</div>
</div>
       
	<?php include 'scripts.php'; ?>

</body>
</html>