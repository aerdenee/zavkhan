var pie_donut_element = document.getElementById('pie_donut');

// Basic donut chart
if (pie_donut_element) {

    // Initialize chart
    var pie_donut = echarts.init(pie_donut_element);


    //
    // Chart config
    //

    // Options
    pie_donut.setOption({
        // Colors
        color: [
            '#2ec7c9', '#b6a2de', '#5ab1ef', '#ffb980', '#d87a80',
            '#8d98b3', '#e5cf0d', '#97b552', '#95706d', '#dc69aa',
            '#07a2a4', '#9a7fd1', '#588dd5', '#f5994e', '#c05050',
            '#59678c', '#c9ab00', '#7eb00a', '#6f5553', '#c14089'
        ],
        // Global text styles
        textStyle: {
            fontFamily: 'Roboto, Arial, Verdana, sans-serif',
            fontSize: 13
        },
        // Add title
        title: {
            left: 'center',
            textStyle: {
                fontSize: 17,
                fontWeight: 500
            },
            subtextStyle: {
                fontSize: 12
            }
        },
        // Add tooltip
        tooltip: {
            trigger: 'item',
            backgroundColor: 'rgba(0,0,0,0.75)',
            padding: [10, 15],
            textStyle: {
                fontSize: 13,
                fontFamily: 'Roboto, sans-serif'
            },
            formatter: "{a} <br/>{b}: {c} ({d}%)"
        },

        // Add series
        series: [{
                name: 'Browsers',
                type: 'pie',
                radius: ['50%', '70%'],
                center: ['50%', '57.5%'],
                itemStyle: {
                    normal: {
                        borderWidth: 1,
                        borderColor: '#fff'
                    }
                },
                data: [
                    {value: 335, name: 'IE'},
                    {value: 310, name: 'Opera'},
                    {value: 234, name: 'Safari'},
                    {value: 135, name: 'Firefox'},
                    {value: 1548, name: 'Chrome'}
                ]
            }]
    });
}

// Resize function
        var triggerChartResize = function() {
            pie_donut_element && pie_donut.resize();
        };