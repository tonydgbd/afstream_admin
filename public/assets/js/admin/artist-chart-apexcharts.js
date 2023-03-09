    'use strict';
    $(function() {
        chartAM();       
    });


    /* Basic Line Chart */

    function chartAM() {
    
        var options = {
                  series: [{
                    name: $('#artistStreamCount').attr('data-label'),            
                    data: JSON.parse($('#artistStreamCount').val()) 
                }],
                  chart: {
                  height: 350,
                  type: 'line',
                  zoom: {
                    enabled: false
                  },
                  toolbar: {
                        show: false
                    },
                },
                dataLabels: {
                     enabled: true,
                    formatter: function(val) {
                        return val + "%";
                    },
                    offsetY: -15,
                    style: {
                        fontSize: '12px',
                        colors: ['var(--graph)'],
                        fontFamily: 'Poppins, sans-serif',
                    }
                },
                stroke: {
                  curve: 'smooth'
                },
                title: {          
                  align: 'left'
                },
                grid: {
                  row: {
                    colors: ['var(--graph)', 'transparent'], 
                    opacity: 0.5
                  },
                },
                xaxis: {
                  categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                },
                colors: ['var(--primary)', 'var(--primary)']
        };
    
            var chart = new ApexCharts(document.querySelector("#chartAM"), options);
            chart.render();
    
    }

