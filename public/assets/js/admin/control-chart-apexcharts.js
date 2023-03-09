    'use strict';
    $(function() {
        chartG();
        chartGG();
    });



    /* Basic Line Chart */

    function chartG() {
    
        var options = {
                  series: [{
                    name: $('#userMonthCount').attr('data-label'),            
                    data: JSON.parse($('#userMonthCount').val()) 
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
    
            var chart = new ApexCharts(document.querySelector("#chartG"), options);
            chart.render();
    
    }



    function chartGG() {
    
        var options = {
                  series: [{
                    name: $('#subsMonthCount').attr('data-label'),
                    data: JSON.parse($('#subsMonthCount').val()),
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
                    shadow: {
                        enabled: true,
                        top: 18,
                        left: 7,
                        blur: 10,
                        opacity: 1
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
                  }              
                },
                xaxis: {
                  categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                },
                colors: ['var(--primary)', 'var(--primary)'],
                fill: {
                  colors: ['var(--primary)', 'var(--primary)']
                }
        };
    
            var chart = new ApexCharts(document.querySelector("#chartGG"), options);
            chart.render();
    
    }





