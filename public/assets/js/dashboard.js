"use strict";

$(function(){
    // var ctx = document.getElementById('userChart').getContext('2d');

    // var userChart = new Chart(ctx, {
    //     type: 'line',
    //     data: {
    //         labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
    //         datasets: [{
    //             label: $('#userMonthCount').attr('data-label'),
    //             data: JSON.parse($('#userMonthCount').val()),
    //             backgroundColor: [
    //                 'rgba(255, 0, 102, 0.2)',
    //                 'rgba(255, 0, 102, 0.2)',
    //                 'rgba(255, 0, 102, 0.2)',
    //                 'rgba(255, 0, 102, 0.2)',
    //                 'rgba(255, 0, 102, 0.2)',
    //                 'rgba(255, 0, 102, 0.2)'
    //             ],
    //             borderColor: [
    //                 'rgba(255, 99, 132, 1)',
    //                 'rgba(54, 162, 235, 1)',
    //                 'rgba(255, 206, 86, 1)',
    //                 'rgba(75, 192, 192, 1)',
    //                 'rgba(153, 102, 255, 1)',
    //                 'rgba(255, 159, 64, 1)'
    //             ],
    //             borderWidth: 2
    //         }]
    //     },
    //     options: {
    //         scales: {
    //             yAxes: [{
    //                 ticks: {
    //                     beginAtZero: true,
    //                     stepSize: 1
    //                 }
    //             }]
    //         },
    //         aspectRatio:4,
    //         responsive : true, 
    //         maintainAspectRatio : false
    //     }
    // });
    // userChart.canvas.parentNode.style.height = '450px';





     var ctx = document.getElementById('userChart').getContext('2d');

    var userChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            datasets: [{
                label: $('#userMonthCount').attr('data-label'),
                data: JSON.parse($('#userMonthCount').val()),
                backgroundColor: [ 
                    'rgba(255, 0, 102, 0.2)',
                    'rgba(255, 0, 102, 0.2)',
                    'rgba(255, 0, 102, 0.2)',
                    'rgba(255, 0, 102, 0.2)',
                    'rgba(255, 0, 102, 0.2)',
                    'rgba(255, 0, 102, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 2,
                animation: true,
                borderDash: [2, 2],
                fill: {
                    target: 'origin',
                    above: 'rgb(255, 0, 0)',   // Area will be red above the origin
                    below: 'rgb(0, 0, 255)'    // And blue below the origin
                },
                  tension: 0,
            },]
        },
        options: {
            aspectRatio:4,
            responsive : true, 
            maintainAspectRatio : false,
            layout: {
                padding: 10,
            },
            legend: {
                display: false,
                position: 'top',
            },
            title: {
                display: false,
                text: 'Users'
            },
            tooltips: {
                cornerRadius: 3,
                backgroundColor: "#222222"
            },
            scales: {
                yAxes: [{
                    stacked: true,
                    gridLines: {
                        display: false,
                        color: "rgb(217 233 255)"
                    },
                    ticks: {
                        fontColor: "#99abb4",
                        fontSize: 12,
                        beginAtZero: true,
                        stepSize: 1
                    }
                }],
                xAxes: [{
                    gridLines: {
                        display: true,
                        color: "rgb(217 233 255)",
                        type: 'linear',
                    },
                    ticks: {
                        fontColor: "#99abb4",
                        fontSize: 12,
                    },
                    

                }]

            }

        }        


    });
    userChart.canvas.parentNode.style.height = '450px';




    // var ctx1 = document.getElementById('subscriptionChart').getContext('2d');
    // ctx1.height = 500;
    // var myChart = new Chart(ctx1, {
    //     type: 'line',
    //     data: {
    //         labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
    //         datasets: [{
    //             label: $('#subsMonthCount').attr('data-label'), 
    //             data: JSON.parse($('#subsMonthCount').val()),
    //             fill: true,
    //             borderColor: '#ff0066', 
    //             // backgroundColor: 'transparent', 
    //             backgroundColor: [
    //                 'rgba(255, 0, 102, 0.2)',
    //                 'rgba(255, 0, 102, 0.2)',
    //                 'rgba(255, 0, 102, 0.2)',
    //                 'rgba(255, 0, 102, 0.2)',
    //                 'rgba(255, 0, 102, 0.2)',
    //                 'rgba(255, 0, 102, 0.2)'
    //             ],
    //             borderWidth: 2
    //         }]
    //     },


    //     options: {
    //         aspectRatio:4,
    //         responsive: true,
    //         maintainAspectRatio: false,
    //     }
    // });
    // myChart.canvas.parentNode.style.height = '450px';






    var ctx1 = document.getElementById('subscriptionChart').getContext('2d');
    ctx1.height = 500;
    var myChart = new Chart(ctx1, {
        type: 'line',
        data: {
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            datasets: [{
                label: $('#subsMonthCount').attr('data-label'), 
                data: JSON.parse($('#subsMonthCount').val()),
                fill: true,
                borderColor: '#ff0066', 
                // backgroundColor: 'transparent', 
                backgroundColor: [
                    'rgba(255, 0, 102, 0.2)',
                    'rgba(255, 0, 102, 0.2)',
                    'rgba(255, 0, 102, 0.2)',
                    'rgba(255, 0, 102, 0.2)',
                    'rgba(255, 0, 102, 0.2)',
                    'rgba(255, 0, 102, 0.2)'
                ],
                borderWidth: 4
            }]
        },

        // Configuration options
        options: {
            aspectRatio:4,
            responsive: true,
            maintainAspectRatio: false,
            layout: {
                padding: 10,
            },
            legend: {
                display: false,
                position: 'top',
            },
            title: {
                display: false,
                text: 'Users'
            },
            tooltips: {
                cornerRadius: 3,
                backgroundColor: "#222222"
            },
            scales: {
                yAxes: [{
                    stacked: false,
                    gridLines: {
                        display: true,
                        color: "rgb(217 233 255)"
                    },
                    ticks: {
                        fontColor: "#99abb4",
                        fontSize: 12,
                    }
                }],
                xAxes: [{
                    gridLines: {
                        display: true
                    },
                    ticks: {
                        fontColor: "#99abb4",
                        fontSize: 12,
                    }
                }]
            }
        }

        
    });
    myChart.canvas.parentNode.style.height = '450px';



});