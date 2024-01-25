//[Dashboard Javascript]

//Project:	Deposito Admin - Responsive Admin Template
//Primary use:   Used only for the main dashboard (index.html)


$(function () {

  'use strict';
	
		  function generateData(count, yrange) {
			var i = 0;
			var series = [];
			while (i < count) {
			  var x = 'w' + (i + 1).toString();
			  var y = Math.floor(Math.random() * (yrange.max - yrange.min + 1)) + yrange.min;

			  series.push({
				x: x,
				y: y
			  });
			  i++;
			}
			return series;
		  }
		var options = {
          series: [{
          name: 'Uint 9',
          data: generateData(7, {
            min: 0,
            max: 90
          })
        },
        {
          name: 'Uint 8',
          data: generateData(7, {
            min: 0,
            max: 90
          })
        },
        {
          name: 'Uint 7',
          data: generateData(7, {
            min: 0,
            max: 90
          })
        },
        {
          name: 'Uint 6',
          data: generateData(7, {
            min: 0,
            max: 90
          })
        },
        {
          name: 'Uint 5',
          data: generateData(7, {
            min: 0,
            max: 90
          })
        },
        {
          name: 'Uint 4',
          data: generateData(7, {
            min: 0,
            max: 90
          })
        },
        {
          name: 'Uint 3',
          data: generateData(7, {
            min: 0,
            max: 90
          })
        },
        {
          name: 'Uint 2',
          data: generateData(7, {
            min: 0,
            max: 90
          })
        },
        {
          name: 'Uint 1',
          data: generateData(7, {
            min: 0,
            max: 90
          })
        }
        ],
          chart: {
          height: 350,
          type: 'heatmap',
        },
        dataLabels: {
          enabled: false
        },
        colors: ["#a45ff8"],
        };

        var chart = new ApexCharts(document.querySelector("#hour-data"), options);
        chart.render();
	
	
	
	
		var options = {
          series: [
          {
            name: 'Desktops',
            data: [
              {
                x: 'Mimai',
                y: 10
              },
              {
                x: 'New York',
                y: 60
              },
              {
                x: 'Washington',
                y: 41
              }
            ]
          },
          {
            name: 'Mobile',
            data: [
              {
                x: 'California',
                y: 10
              },
              {
                x: 'Chicago',
                y: 20
              },
              {
                x: 'Tampa',
                y: 51
              },
              {
                x: 'Orlando',
                y: 30
              },
              {
                x: 'Naples',
                y: 20
              },
              {
                x: 'Destin',
                y: 30
              }
            ]
          }
        ],
		
          legend: {
          show: false
        },
        chart: {
          height: 350,
          type: 'treemap'
        },
			plotOptions: {
			treemap: {
			  distributed: true
			}
		  },
			
        };

        var chart = new ApexCharts(document.querySelector("#city-data"), options);
        chart.render();
	
	
	
	
		var options = {
          series: [{
          data: [48, 31, 42, 17, 41, 25, 54, 20]
        }],
          chart: {
          type: 'area',
          height: 250,
			  toolbar: {
        		show: false,
			  }
        },		
        stroke: {
          curve: 'stepline'
        },
		
		grid: {
			show: true,			
		},
        dataLabels: {
          enabled: false
        },
		colors: ['#a45ff8'],
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
		  stacked: true,
          height: 273,
			  toolbar: {
        		show: false,
			  }
        },
        plotOptions: {
          bar: {
			borderRadius: 5,
            horizontal: true,
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
          		show: true,
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
          name: 'Net Profit',
          data: [44, 55, 57, 56, 61, 58, 63]
        }, {
          name: 'Revenue',
          data: [76, 85, 101, 98, 87, 105, 91]
        }],
          chart: {
          type: 'bar',
          height: 150,
			  toolbar: {
        		show: false,
			  }
        },
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: '30%',
            endingShape: 'rounded'
          },
        },
        dataLabels: {
          enabled: false,
        },
		grid: {
			show: false,
			padding: {
			  top: 0,
			  bottom: 0,
			  right: 30,
			  left: 20
			}
		},
        stroke: {
          show: true,
          width: 2,
          colors: ['transparent']
        },
		colors: ['rgba(255, 255, 255, 0.25)', '#f7f7f7'],
        xaxis: {
          categories: ['Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug'],
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
		 legend: {
      		show: false,
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
        },
		responsive: [{
				breakpoint: 1500,
				options: {					
					  chart: {
					  height: 250,
					},
				},
			},
			{
				breakpoint: 1400,
				options: {					
					  chart: {
					  height: 348,
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

        var chart = new ApexCharts(document.querySelector("#revenue1"), options);
        chart.render();
	
	
	
	
	
	
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
		
	
	
}); // End of use strict
