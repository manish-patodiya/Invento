//[Dashboard Javascript]

//Project:	Deposito Admin - Responsive Admin Template
//Primary use:   Used only for the main dashboard (index.html)


$(function () {

  'use strict';
	
		var options = {
          series: [44, 55, 41, 17, 15],
          chart: {
          type: 'donut',
        },
		colors: ['#3246D3', '#00D0FF', '#ee3158', '#ffa800', '#1dbfc1'],
		legend: {
		  position: 'bottom'
		},	
			
		  plotOptions: {
			  pie: {
				  donut: {
					size: '45%',
				  }
			  }
		  },
		labels: ["Operation", "Theraphy", "Mediation", "Colestrol", "Heart Beat"],
        responsive: [{
          breakpoint: 480,
          options: {
            chart: {
            },
            legend: {
              position: 'bottom'
            }
          }
        }]
        };

        var chart = new ApexCharts(document.querySelector("#chart123"), options);
        chart.render();
	
	
	
	var options = {
				series: [{
				name: 'Heart',
				data: [4, 3, 10, 9, 50, 19, 22, 9, 17, 2, 7, 15]
			}],
				chart: {
					width: 200,
				toolbar: {
					show: false,
				},
				height: 120,
				type: 'line',
			},
			stroke: {
				width: 4,
				curve: 'smooth',
				colors: ['#1dbfc1']
			},
			
			legend: {
				show: false
			},
			tooltip: {
				enabled: true,
			},
			
			grid: {
		show: false,
		},

			xaxis: {
				show: false,
				lines: {
					show: false,
				},
				labels: {
					show: false,
				},
				axisBorder: {
				  show: false,
				},
				
			},
			yaxis: {
				show: false,
			},
		};

		var chart = new ApexCharts(document.querySelector("#chart"), options);
		chart.render();
	
	
	
	
	
	// Slim scrolling
  	$('.inner-user-div').slimScroll({
		height: '350px'
	  });
  
	  $('.inner-user-div4').slimScroll({
		height: '350px'
	  });
	
	var datepaginator = function() {
		return {
			init: function() {
				$("#paginator1").datepaginator()
			}
		}
	}();
	jQuery(document).ready(function() {
		datepaginator.init()
	}); 
	
	
	
	
}); // End of use strict
