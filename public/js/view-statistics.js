// get the canvas element
var ctx = document.getElementById('totalUsersChart').getContext('2d');

// use AJAX or fetch to get the data from your server
$.ajax({
    url: 'ajax',
    type: 'GET',
    dataType: 'json',
    success: function(data) {

        // count the number of registration dates
        var count = data.length;

        // create an object to keep track of the count for each month
        var countsByMonth = {
            'March': 0,
            'April': 0,
            'May': 0,
            'June': 0,
            'July': 0,
            'August': 0,
            'September': 0,
            'October': 0,
            'November': 0,
            'December': 0,
            'January': 0,
            'February': 0
        };

        // loop through the registration dates and count the number of users for each month
        for (var i = 0; i < data.length; i++) {
            var date = new Date(data[i]);
            var month = date.toLocaleString('en-US', { month: 'long' });
            if (month in countsByMonth) {
                countsByMonth[month]++;
            }
        }

        // create arrays for the labels and data based on the countsByMonth object
        var labels = [];
        var data = [];
        for (var month in countsByMonth) {
            if (month === 'March' || labels.length > 0) {
                labels.push(month);
                data.push(countsByMonth[month]);
            }
        }

        // format the data into an object that Chart.js can use
        var chartData = {
            labels: labels,
            datasets: [{
                label: 'Registered Users',
                data: data,
                backgroundColor: 'rgba(54, 162, 235, 1)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2
            }]
        };


        // create the chart using the formatted data
        new Chart(ctx, {
            type: 'line',
            data: chartData
        });
    },
    error: function(xhr, status, error) {
        console.log('Error: ' + error.message);
    }
});
