// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#858796';
const params = new URLSearchParams(window.location.search);
const productId = params.get('id');
console.log(productId); // Это будет значение "123" в данном случае

fetch(`/availability-days.php?id=${encodeURIComponent(productId)}`)
    .then(response => {
      if (!response.ok) {
        throw new Error('Network response was not ok');
      }
      return response.json();
    })
    .then(jsonData => {
      var difference = ((jsonData.daysWithPrice / jsonData.totalDays) * 100).toFixed(1);

      var ctx = document.getElementById("myPieChart").getContext("2d");
      var myPieChart = new Chart(ctx, {

        type: 'doughnut',
        data: {
          labels: ["Days with Price", "Days without Price"],
          datasets: [{
            data: [jsonData.daysWithPrice, jsonData.daysWithoutPrice],
            backgroundColor: ['#4e73df', '#1cc88a'],
            hoverBackgroundColor: ['#2e59d9', '#17a673'],
            hoverBorderColor: "rgba(234, 236, 244, 1)",
          }],
        },
        options: {
          maintainAspectRatio: false,
          tooltips: {
            backgroundColor: "rgb(255,255,255)",
            bodyFontColor: "#858796",
            borderColor: '#dddfeb',
            borderWidth: 1,
            xPadding: 15,
            yPadding: 15,
            displayColors: false,
            caretPadding: 10,
          },
          legend: {
            display: false
          },
          cutoutPercentage: 80,

        }

      });

      var centerText = document.getElementById("centerText");
      centerText.innerHTML = difference.toString() + "% наличия";
    })
    .catch(error => {
      console.error('Ошибка получения данных:', error);
    });
