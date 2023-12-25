// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#858796';

function number_format(number, decimals, dec_point, thousands_sep) {
  // *     example: number_format(1234.56, 2, ',', ' ');
  // *     return: '1 234,56'
  number = (number + '').replace(',', '').replace(' ', '');
  var n = !isFinite(+number) ? 0 : +number,
    prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
    sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
    dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
    s = '',
    toFixedFix = function(n, prec) {
      var k = Math.pow(10, prec);
      return '' + Math.round(n * k) / k;
    };
  // Fix for IE parseFloat(0.55).toFixed(0) = 0;
  s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
  if (s[0].length > 3) {
    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
  }
  if ((s[1] || '').length < prec) {
    s[1] = s[1] || '';
    s[1] += new Array(prec - s[1].length + 1).join('0');
  }
  return s.join(dec);
}
console.log(productId);
fetch(`/barsql.php?id=${encodeURIComponent(productId)}`)
    .then(rowssqlbar => {
      if (!rowssqlbar.ok) {
        throw new Error('Network response was not ok');
      }
      return rowssqlbar.json();

    })
    .then(data => {
      const labels = data.map(item => item.day);
      const minPrices = data.map(item => item.min_price);
      const maxPrices = data.map(item => item.max_price);
console.log(data);
      console.log(minPrices);
// Bar Chart Example
var ctx = document.getElementById("myBarChart");
var myBarChart = new Chart(ctx, {
  type: 'bar',
  data: {
    labels: data.map(item => item.day),
    datasets: [{
      label: "Min and Max Prices",
      backgroundColor: "#4e73df",
      hoverBackgroundColor: "#2e59d9",
      borderColor: "#4e73df",
      data: data.map(item => ({
        x: item.day,
        y: Math.max(item.min_price, item.max_price),
        min_price: item.min_price,
        max_price: item.max_price
      })),
    },
      {
        label: "Min Price",
        backgroundColor: "#4e73df",
        hoverBackgroundColor: "#2e59d9",
        borderColor: "#4e73df",
        data: minPrices,
        hidden: true // Hide the dataset to only show the new bar
      },
      {
        label: "Max Price",
        backgroundColor: "#1cc88a",
        hoverBackgroundColor: "#17a673",
        borderColor: "#1cc88a",
        data: maxPrices,
        hidden: true // Hide the dataset to only show the new bar
      }
    ],
  },
  options: {
    maintainAspectRatio: false,
    layout: {
      padding: {
        left: 10,
        right: 25,
        top: 25,
        bottom: 0
      }
    },
    scales: {
      x: {
        time: {
          unit: 'month'
        },
        grid: {
          display: false,
          drawBorder: false
        },
        ticks: {
          maxTicksLimit: 6
        }
      },
      y: {
        ticks: {
          min: 0,
          max: 15000,
          maxTicksLimit: 5,
          padding: 10,
          callback: function(value, index, values) {
            return 'byn' + number_format(value);
          }
        },
        grid: {
          color: "rgb(234, 236, 244)",
          zeroLineColor: "rgb(234, 236, 244)",
          drawBorder: false,
          borderDash: [2],
          zeroLineBorderDash: [2]
        }
      }
    },
    legend: {
      display: true
    },
    tooltips: {
      titleMarginBottom: 10,
      titleFontColor: '#6e707e',
      titleFontSize: 14,
      backgroundColor: "rgb(255,255,255)",
      bodyFontColor: "#858796",
      borderColor: '#dddfeb',
      borderWidth: 1,
      xPadding: 15,
      yPadding: 15,
      displayColors: false,
      caretPadding: 10,
      callbacks: {
        label: function(tooltipItem, chart) {
          var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
          return datasetLabel + ': byn' + number_format(tooltipItem.yLabel);
        }
      }
    },
  }
})})
