//[Dashboard Javascript]

//Project:	Deposito Admin - Responsive Admin Template
//Primary use:   Used only for the main dashboard (index.html)


$(function () {

  'use strict';
	
		var options = {
          series: [{
          data: [48, 31, 42, 17, 41, 25, 54, 20]
        }],
          chart: {
          type: 'line',
          height: 250,
			  toolbar: {
        		show: false,
			  }
        },		
        stroke: {
          curve: 'smooth'
        },
		
		grid: {
			show: true,			
		},
        dataLabels: {
          enabled: false
        },
		colors: ['#6cbbfa'],
        xaxis: {
          categories: ['Mimai', 'New York', 'Washington', 'California', 'Chicago', 'Tampa', 'Orlando', 'Naples'],
			labels: {
          		show: true,
			},
		  axisBorder: {
			  show: true,
		  },
		  axisTicks: {
			  show: true,
		  },
        },
		
        tooltip: {
          y: {
            formatter: function (val) {
              return val + "%"
            }
          },
			marker: {
			  show: false,
		  },
        }
        };

        var chart = new ApexCharts(document.querySelector("#topcities"), options);
        chart.render();
	
	
	
	
		var options = {
          series: [{
          name: 'In',
          data: [76, 85, 101, 98, 87, 45, 96, 55]
        }, {
          name: 'Low',
          data: [54, 45, 67, 76, 31, 32, 67, 45]
        }, {
          name: 'Out',
          data: [30, 25, 47, 56, 21, 22, 31, 41]
        }],
          chart: {
          type: 'bar',
		  foreColor:"#bac0c7",
		  stacked: false,
          height: 273,
			  toolbar: {
        		show: false,
			  }
        },
        plotOptions: {
          bar: {
			borderRadius: 5,
            horizontal: false,
            columnWidth: '80%',
          },
        },
        dataLabels: {
          enabled: false,
        },
		grid: {
			show: false,			
		},
        stroke: {
          show: true,
          width: 2,
          colors: ['transparent']
        },
		colors: ['#5b33c9', '#8865f8', '#6cbbfa'],
        xaxis: {
          categories: ['Amazon', 'Flipkart', 'Alibaba', 'G2pay', 'GoBuy', 'Snapdeal', 'ebay', 'Zamro'],
			
        },
        yaxis: {
          labels: {
          		show: false,
			},
		  axisBorder: {
			  show: false,
		  },
		  axisTicks: {
			  show: false,
		  },
        },
		 legend: {
      		show: true,
			 position: 'top',
      		horizontalAlign: 'center', 
			  fontSize: '18px',
		  fontFamily: 'Rubik, sans-serif',
		  fontWeight: 400,
		 },
        fill: {
          opacity: 1
        },
        tooltip: {
          y: {
            formatter: function (val) {
              return "$ " + val + " thousands"
            }
          },
			marker: {
			  show: false,
		  },
        }
        };

        var chart = new ApexCharts(document.querySelector("#recent_trend"), options);
        chart.render();
	
		var options = {
          series: [{
            name: "Revenue",
            data: [90, 71, 65, 91, 40, 112, 99, 51, 128]
        }],
          chart: {
          height: 165,
          type: 'area',
          zoom: {
            enabled: false
          },			  
		  toolbar: {
			show: false,
		  }
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          curve: 'smooth'
        },
		colors: ['#ee3158'],
        grid: {			
			show: false,
			padding: {
			  top: 0,
			  bottom: -40,
			  right: 6,
			  left: -10
			},
        },
		
		 legend: {
      		show: false,
		 },
        xaxis: {
          categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
			labels: {
          		show: false,
			},
			axisBorder: {
          		show: false,
			},
			axisTicks: {
          		show: false,
			},
        	},
		
        yaxis: {
          labels: {
          		show: false,
			}
        },
			responsive: [{
				breakpoint: 1500,
				options: {					
					  chart: {
					  height: 265,
					},
				},
			},
			{
				breakpoint: 1400,
				options: {					
					  chart: {
					  height: 360,
					},
				},
			},
			{
				breakpoint: 1300,
				options: {					
					  chart: {
					  height: 200,
					},
				},
			}
			],
        };

        var chart = new ApexCharts(document.querySelector("#revenue4"), options);
        chart.render();
	
	
	
	
	
	
	
	$('.inner-user-div3').slimScroll({
		height: '690px'
	  });
	
	
	
	  // Apex  start
	  if($('#apexChart2').length) {
		var options2 = {
		  chart: {
			type: "bar",
			height: 73,
			  width: 100,
			sparkline: {
			  enabled: !0
			}
		  },
		  plotOptions: {
			bar: {
			  columnWidth: "20%"
			}
		  },
		  colors: ["#ffffff"],
		  series: [{
			data: [36, 77, 52, 90, 74, 35, 55, 23]
		  }],
		  xaxis: {
			crosshairs: {
			  width: 5
			}
		  },
		  tooltip: {
			fixed: {
			  enabled: !1
			},
			x: {
			  show: !1
			},
			y: {
			  title: {
				formatter: function(e) {
				  return ""
				}
			  }
			},
			marker: {
			  show: !1
			}
		  }
		};
		new ApexCharts(document.querySelector("#apexChart2"),options2).render();
	  }
	  // Apex  end
		
	
	var options = {
          series: [
          {
            name: "Amazon",
			data: [12, 42, 14, 28, 13, 30, 21]
          },
          {
            name: "Flipkart",            
            data: [28, 10, 23, 40, 25, 15, 41]
          },
          {
            name: "Alibaba",            
            data: [14, 28, 32, 18, 48, 30, 12]
          },
        ],
          chart: {
          height: 305,
          type: 'area',
          dropShadow: {
            enabled: true,
            color: '#000',
            top: 18,
            left: 7,
            blur: 10,
            opacity: 0.01
          },
          toolbar: {
            show: false
          }
        },
        colors: ['#2e72ff','#fb5ea8','#00875a'],
        dataLabels: {
          enabled: false,
        },
        stroke: {
          curve: 'stepline',
		  lineCap: 'butt',
			width: 3,
        },
        grid: {
          borderColor: '#e7e7e7',
        },
        xaxis: {
          categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
        },
        legend: {
          show: false,
        },
		
        tooltip: {
          y: {
            formatter: function (val) {
              return "Total" + val + " Inventory"
            }
          },
			marker: {
			  show: true,
		  },
        }
        };

        var chart = new ApexCharts(document.querySelector("#overview_trend"), options);
        chart.render();
	
	
}); // End of use strict
