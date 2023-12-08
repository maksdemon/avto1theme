document.addEventListener("DOMContentLoaded", function () {
    const popupLinks = document.querySelectorAll(".popup-link[data-popup]");

    popupLinks.forEach(function (link) {
        link.addEventListener("click", function (e) {
            e.preventDefault();
            const productName = link.getAttribute("data-name");

            // Выполняем запрос к вашему PHP-скрипту
            fetch(`http://avto1theme//your_php_script.php?product=${encodeURIComponent(productName)}`)
                .then(response => response.json())
                .then(data => {
                    // Создаем попап-контейнер и контент (попап-окно)
                    const popupContainer = document.createElement("div");
                    popupContainer.classList.add("popup-container");

                    const popupContent = document.createElement("div");
                    popupContent.classList.add("popup-content");

                    // Добавляем кнопку закрытия
                    const closeButton = document.createElement("button");
                    closeButton.classList.add("popup-close-button");
                    closeButton.setAttribute("aria-label", "Закрыть попап");
                    closeButton.textContent = "×"; // Текст кнопки закрытия
                    popupContent.appendChild(closeButton);

                    // Добавляем заголовок с именем товара
                    const heading = document.createElement("h2");
                    heading.textContent = "Название товара: " + productName;
                    popupContent.appendChild(heading);

                    // Создаем элемент canvas для графика
                    const popupChartCanvas = document.createElement("canvas");
                    popupChartCanvas.id = "popupChart"; // Устанавливаем id для графика

                    // Добавляем canvas в контент попапа
                    popupContent.appendChild(popupChartCanvas);

                    // Добавляем контент в попап и попап в body
                    popupContainer.appendChild(popupContent);
                    document.body.appendChild(popupContainer);

                    // Показываем попап
                    popupContainer.style.display = "block";

                    // Создаем график внутри попапа
                    var popupCanvas = document.getElementById('popupChart');
                    if (popupCanvas) {
                        var ctx = popupCanvas.getContext('2d');

                        if (Array.isArray(data)) {
                            // Если data - массив, создаем график на основе данных массива

                            if (data.length > 0) {
                                const avgDates = data.map(function (priceData) {
                                    return new Date(priceData.date_day);
                                });

                                const avgPricesValues = data.map(function (priceData) {
                                    return parseFloat(priceData.avg_price);
                                });

                                var myPopupChart = new Chart(ctx, {
                                    type: 'bar',
                                    data: {
                                        labels: avgDates,
                                        datasets: [{
                                            label: 'Средняя цена',
                                            data: avgPricesValues,
                                            backgroundColor: 'rgba(75, 192, 192, 0.6)',
                                            borderColor: 'rgba(75, 192, 192, 1)',
                                            borderWidth: 1
                                        }]
                                    },
                                    options: {
                                        legend: {
                                            display: false
                                        },
                                        title: {
                                            display: true,
                                            text: 'Средняя цена товара со временем',
                                            position: 'top',
                                            fontSize: 16,
                                            padding: 20
                                        },
                                        scales: {
                                            xAxes: [{
                                                type: 'time',
                                                time: {
                                                    unit: 'day', // Устанавливаем единицу времени на день
                                                    tooltipFormat: 'DD MMM ',
                                                    displayFormats: {
                                                        day: 'DD MMM ' // Устанавливаем формат отображения даты
                                                    }
                                                }
                                            }],
                                            yAxes: [{
                                                ticks: {
                                                    beginAtZero: true
                                                }
                                            }]
                                        }
                                    }
                                });
                            } else {
                                console.error('data - пустой массив:', data);
                            }
                        } else {
                            console.error('data не является массивом:', data);
                        }
                    }
                    closeButton.addEventListener('click', function () {
                        // Закрываем попап
                        document.body.removeChild(popupContainer);
                    });
                })
                .catch(error => {
                    console.error('Ошибка при выполнении запроса:', error);
                });
        });
    });
});
console.log("Product Name:", productName);