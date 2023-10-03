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
        <div class="card-header p-5 border-b">
          <h2 class="text-xl text-gray-700 font-medium tracking-wide">dasdasd sator</h2>
        </div>
        <div class="card-content p-5">
          <div class="grid grid-cols-3 gap-4 text-center">
          	<h2 class="text-xl text-gray-700 font-medium tracking-wide">QA Week Trend</h2>
          		  <div style="width: 70%; height: 100%;">
        <canvas id="canvas"></canvas>
    </div>
    <button id="randomizeData">Randomize Data</button>


    		<style type="text/css">
    			  canvas {
        -moz-user-select: none;
        -webkit-user-select: none;
        -ms-user-select: none;
    }
    		</style>

    			<script type="text/javascript">
    				'use strict';

window.chartColors = {
	red: 'rgb(255, 99, 132)',
	orange: 'rgb(255, 159, 64)',
	yellow: 'rgb(255, 205, 86)',
	green: 'rgb(75, 192, 192)',
	blue: 'rgb(54, 162, 235)',
	purple: 'rgb(153, 102, 255)',
	grey: 'rgb(201, 203, 207)'
};

(function(global) {
	var Months = [
		'January',
		'February',
		'March',
		'April',
		'May',
		'June',
		'July',
		'August',
		'September',
		'October',
		'November',
		'December'
	];

	var COLORS = [
		'#4dc9f6',
		'#f67019',
		'#f53794',
		'#537bc4',
		'#acc236',
		'#166a8f',
		'#00a950',
		'#58595b',
		'#8549ba'
	];

	var Samples = global.Samples || (global.Samples = {});
	var Color = global.Color;

	Samples.utils = {
		// Adapted from http://indiegamr.com/generate-repeatable-random-numbers-in-js/
		srand: function(seed) {
			this._seed = seed;
		},

		rand: function(min, max) {
			var seed = this._seed;
			min = min === undefined ? 0 : min;
			max = max === undefined ? 1 : max;
			this._seed = (seed * 9301 + 49297) % 233280;
			return min + (this._seed / 233280) * (max - min);
		},

		numbers: function(config) {
			var cfg = config || {};
			var min = cfg.min || 0;
			var max = cfg.max || 1;
			var from = cfg.from || [];
			var count = cfg.count || 8;
			var decimals = cfg.decimals || 8;
			var continuity = cfg.continuity || 1;
			var dfactor = Math.pow(10, decimals) || 0;
			var data = [];
			var i, value;

			for (i = 0; i < count; ++i) {
				value = (from[i] || 0) + this.rand(min, max);
				if (this.rand() <= continuity) {
					data.push(Math.round(dfactor * value) / dfactor);
				} else {
					data.push(null);
				}
			}

			return data;
		},

		labels: function(config) {
			var cfg = config || {};
			var min = cfg.min || 0;
			var max = cfg.max || 100;
			var count = cfg.count || 8;
			var step = (max - min) / count;
			var decimals = cfg.decimals || 8;
			var dfactor = Math.pow(10, decimals) || 0;
			var prefix = cfg.prefix || '';
			var values = [];
			var i;

			for (i = min; i < max; i += step) {
				values.push(prefix + Math.round(dfactor * i) / dfactor);
			}

			return values;
		},

		months: function(config) {
			var cfg = config || {};
			var count = cfg.count || 12;
			var section = cfg.section;
			var values = [];
			var i, value;

			for (i = 0; i < count; ++i) {
				value = Months[Math.ceil(i) % 12];
				values.push(value.substring(0, section));
			}

			return values;
		},

		color: function(index) {
			return COLORS[index % COLORS.length];
		},

		transparentize: function(color, opacity) {
			var alpha = opacity === undefined ? 0.5 : 1 - opacity;
			return Color(color).alpha(alpha).rgbString();
		}
	};

	// DEPRECATED
	window.randomScalingFactor = function() {
		return Math.round(Samples.utils.rand(-100, 100));
	};

	// INITIALIZATION

	Samples.utils.srand(Date.now());

	// Google Analytics
	/* eslint-disable */
	if (document.location.hostname.match(/^(www\.)?chartjs\.org$/)) {
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
		ga('create', 'UA-28909194-3', 'auto');
		ga('send', 'pageview');
	}
	/* eslint-enable */

}(this));        
        var chartData = {
            labels: ["January", "February", "March", "April", "May", "June", "July"],
            datasets: [{
                type: 'line',
                label: 'Dataset 1',
                backgroundColor: window.chartColors.yellow,
                borderColor: window.chartColors.yellow,
                borderWidth: 2,
                fill: false,
                data: [
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor()
                ]
            }, 
              {
                type: 'bar',
                label: 'Dataset 2',
                backgroundColor: window.chartColors.blue,
                data: [
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor()
                ],
                borderColor: 'white',
                borderWidth: 2
            }, {
                type: 'bar',
                label: 'Dataset 3',
                backgroundColor: window.chartColors.green,
                data: [
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor()
                ]
            }]

        };
        window.onload = function() {
            var ctx = document.getElementById("canvas").getContext("2d");
            window.myMixedChart = new Chart(ctx, {
                type: 'bar',
                data: chartData,
                options: {
                    responsive: true,
                    title: {
                        display: true,
                        text: 'QA Week Trend'
                    },
                    tooltips: {
                        mode: 'index',
                        intersect: true
                    }
                }
            });
        };

        document.getElementById('randomizeData').addEventListener('click', 
          function() {
            chartData.datasets.forEach(function(dataset) {
                dataset.data = dataset.data.map(function() {
                    return randomScalingFactor();
                });
            });
            window.myMixedChart.update();
        });
    			</script>

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
          
          <h2 class="text-xl text-gray-700 font-medium tracking-wide">QA Monthly Trend</h2> 
        </div>
        <div class="card-content p-5">
          <div class="grid grid-cols-3 gap-4 text-center">
          		
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
          <h2 class="text-xl text-gray-700 font-medium tracking-wide">Department</h2>
        </div>
        <div class="container">
  
</div>
        <div class="card-content p-5">
          <div class="grid grid-cols-3 gap-4 text-center">
          		
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
          <h2 class="text-xl text-gray-700 font-medium tracking-wide">Over All</h2>
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