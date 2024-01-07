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


var dates = [];
var avgPrices = [];
var minPrices = [];
var usd = [];
const urlParams = new URLSearchParams(window.location.search);
const id = urlParams.get('id');
console.log(id); // Это будет значение "123" в данном случае
fetch(`grafdemosql.php?id=${encodeURIComponent(id)}`)
    .then(response => response.json())
    .then(data => {
      const paramd = data.paramd; // Получаем значение paramd из полученных данных
      const resultData = data.data; // Получаем данные результата SQL-запроса
      // Используйте переменную paramd и данные resultData в вашем коде
    //  console.log(paramd+" пришло"); // Пример вывода переменной paramd в консоль
      //console.log(resultData);
      // Проход по каждому элементу массива data (полученного из AJAX-запроса)
      resultData.forEach(function(row) {
        dates.push(row['start_of_week']);
        avgPrices.push(parseFloat(row['max_price']));
        minPrices.push(parseFloat(row['min_price']))
        usd.push(parseFloat(row['usd']));
      });

// Area Chart Example
      var ctx = document.getElementById("myAreaChart1");
      var myLineChart = new Chart(ctx, {
        type: 'line',
        data: {
          labels: dates,
          datasets: [{
            label: "максимальная цена",
            lineTension: 0.3,
            backgroundColor: "rgba(78, 115, 223, 0.05)",
            borderColor: "rgb(105,223,78)",
            pointRadius: 3,
            pointBackgroundColor: "rgba(78, 115, 223, 1)",
            pointBorderColor: "rgba(78, 115, 223, 1)",
            pointHoverRadius: 3,
            pointHoverBackgroundColor: "rgb(105,223,78)",
            pointHoverBorderColor: "rgba(78, 115, 223, 1)",
            pointHitRadius: 10,
            pointBorderWidth: 2,
            data: avgPrices,
          },
            {
              label: "минимальная цена",
              lineTension: 0.3,
              backgroundColor: "rgba(78, 115, 223, 0.05)",
              borderColor: "rgba(78, 115, 223, 1)",
              pointRadius: 3,
              pointBackgroundColor: "rgba(78, 115, 223, 1)",
              pointBorderColor: "rgba(78, 115, 223, 1)",
              pointHoverRadius: 3,
              pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
              pointHoverBorderColor: "rgba(78, 115, 223, 1)",
              pointHitRadius: 10,
              pointBorderWidth: 2,
              data: minPrices,
            },
            {
              label: "usd",
              yAxisID: 'usd-axis',
              lineTension: 0.3,
              backgroundColor: "rgba(78, 115, 223, 0.05)",
              borderColor: "rgb(223,78,100)",
              pointRadius: 3,
              pointBackgroundColor: "rgb(250,8,45)",
              pointBorderColor: "rgb(223,78,100)",
              pointHoverRadius: 3,
              pointHoverBackgroundColor: "rgb(250,8,45)",
              pointHoverBorderColor: "rgb(250,8,45)",
              pointHitRadius: 10,
              pointBorderWidth: 2,
              data: usd,
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
            xAxes: [{
              time: {
                unit: 'date'
              },
              gridLines: {
                display: false,
                drawBorder: false
              },
              ticks: {
                maxTicksLimit: 7
              }
            }],
            yAxes: [{
              ticks: {
                maxTicksLimit: 5,
                padding: 10,
                // Include a dollar sign in the ticks
                callback: function(value, index, values) {
                  return 'BYN' + number_format(value);
                }
              },
              gridLines: {
                color: "rgb(234, 236, 244)",
                zeroLineColor: "rgb(234, 236, 244)",
                drawBorder: false,
                borderDash: [2],
                zeroLineBorderDash: [2]
              }
            },
              {
                id: 'usd-axis',
                type: 'linear',
                position: 'right',
                ticks: {
                  // настройки для оси Y с учетом данных usd
                  suggestedMin: Math.min(...usd),
                  suggestedMax: Math.max(...usd),
                  callback: function(value, index, values) {
                    return 'USD ' + value.toFixed(2); // формат для отображения значения usd
                  }
                },
                gridLines: {
                  drawOnChartArea: false // чтобы избежать наложения на основную область графика
                }
              }],
          },
          legend: {
            display: true
          },
          tooltips: {
            backgroundColor: "rgb(255,255,255)",
            bodyFontColor: "#858796",
            titleMarginBottom: 10,
            titleFontColor: '#6e707e',
            titleFontSize: 14,
            borderColor: '#dddfeb',
            borderWidth: 1,
            xPadding: 15,
            yPadding: 15,
            displayColors: false,
            intersect: false,
            mode: 'index',
            caretPadding: 10,
            callbacks: {
              label: function(tooltipItem, chart) {
                var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                return datasetLabel + ': byn' + number_format(tooltipItem.yLabel);
              }
            }
          }
        }
      })
    })
    .catch(error => {
      console.error('Ошибка получения данных:', error);

    });




