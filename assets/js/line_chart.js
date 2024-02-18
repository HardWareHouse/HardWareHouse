import * as echarts from "echarts";

document.addEventListener("DOMContentLoaded", function () {
  // Initialize eCharts instance for line chart
  var paymentsChart = echarts.init(document.getElementById("paiementsAnnee"));

  // Initialize eCharts instance for donut chart
  var methodsChart = echarts.init(document.getElementById("methodes"));

  var facturesChart = echarts.init(document.getElementById("facturesChart"));

  var devisChart = echarts.init(document.getElementById("devisChart"));

  // Extract years from the paiements data
  var years = paiementsData.map(function (paiement) {
    return new Date(paiement.datePaiement).getFullYear();
  });

  // Remove duplicate years
  years = Array.from(new Set(years));

  // Create dropdown options for years
  var dropdownContainer = document.getElementById("dropdownContainer");
  var dropdownHTML =
    '<select id="yearDropdown" class="text-white bg-darkBlue focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:focus:ring-blue-800">';
  // Sort the years numerically
  years.sort(function (a, b) {
    return b - a;
  });
  years.forEach(function (year) {
    dropdownHTML += '<option value="' + year + '">' + year + "</option>";
  });
  dropdownHTML += "</select>";
  dropdownContainer.innerHTML = dropdownHTML;

  var selectedYear = new Date().getFullYear();
  var paymentButton = document.getElementById("csvPayment");
  var factureButton = document.getElementById("csvFacture");
  paymentButton.href = "/csv-methodes/" + selectedYear;
  factureButton.href = "/csv-factures/" + selectedYear;

  // Event listener for dropdown change
  document
    .getElementById("yearDropdown")
    .addEventListener("change", function (event) {
      selectedYear = parseInt(event.target.value);
      updateCharts(selectedYear);
      downloadButton.href = "/csv-methodes/" + selectedYear;
      factureButton.href = "/csv-factures/" + selectedYear;
    });

  // Function to update charts based on selected year
  function updateCharts(selectedYear) {
    var paymentsPerMonth = new Array(12).fill(0); // Initialize array to hold payments per month

    paiementsData.forEach(function (paiement) {
      var paiementYear = new Date(paiement.datePaiement).getFullYear();
      var paiementMonth = new Date(paiement.datePaiement).getMonth();

      // Check if the paiement belongs to the selected year
      if (paiementYear === selectedYear) {
        paymentsPerMonth[paiementMonth] += paiement.montant;
      }
    });

    // Update line chart data
    var paymentsChartOption = {
      tooltip: {
        trigger: "axis",
        formatter: function (params) {
          var tooltipText = params[0].name + "<br/>"; // Assuming the x-axis represents time or categories

          // Iterate over each data point in the tooltip
          params.forEach(function (item) {
            // Format the value as "00 000,00 €" and append it to the tooltip text
            tooltipText +=
              new Intl.NumberFormat("fr-FR").format(item.value) + " €<br/>";
          });

          return tooltipText;
        },
      },
      xAxis: {
        type: "category",
        data: [
          translatedMonths.jan,
          translatedMonths.feb,
          translatedMonths.mar,
          translatedMonths.apr,
          translatedMonths.may,
          translatedMonths.jun,
          translatedMonths.jul,
          translatedMonths.aug,
          translatedMonths.sep,
          translatedMonths.oct,
          translatedMonths.nov,
          translatedMonths.dec,
        ],
      },
      yAxis: {
        type: "value",
        axisLabel: {
          formatter: function (value) {
            // Format the value as "00 000,00 €"
            return new Intl.NumberFormat("fr-FR").format(value) + " €";
          },
        },
      },
      series: [
        {
          data: paymentsPerMonth,
          type: "line",
        },
      ],
    };
    paymentsChart.setOption(paymentsChartOption);

    // Prepare data for donut chart
    var paymentMethods = {};
    paiementsData.forEach(function (paiement) {
      var paiementYear = new Date(paiement.datePaiement).getFullYear();
      if (paiementYear === selectedYear) {
        if (!paymentMethods[paiement.methodePaiement]) {
          paymentMethods[paiement.methodePaiement] = 1;
        } else {
          paymentMethods[paiement.methodePaiement]++;
        }
      }
    });

    // Update donut chart data
    var methodsChartOption = {
      tooltip: {
        trigger: "item",
      },
      legend: {
        orient: "vertical",
        right: 10,
        data: Object.keys(paymentMethods),
      },
      series: [
        {
          label: {
            show: false,
            position: "center",
          },
          type: "pie",
          radius: ["50%", "70%"],
          data: Object.keys(paymentMethods).map(function (method) {
            return { value: paymentMethods[method], name: method };
          }),
        },
      ],
    };
    methodsChart.resize();

    methodsChart.setOption(methodsChartOption);

    var statusData = {
      Payé: Array(12).fill(0),
      "Non-payé": Array(12).fill(0),
      "En retard": Array(12).fill(0),
    };

    // Process Facture data to count status per month
    facturesData.forEach(function (facture) {
      var factureYear = new Date(facture.dateFacturation).getFullYear();
      var factureMonth = new Date(facture.dateFacturation).getMonth();

      // Check if the facture belongs to the selected year
      if (factureYear === selectedYear) {
        // Increment the count for the corresponding status and month
        switch (facture.status) {
          case "Payé":
            statusData["Payé"][factureMonth]++;
            break;
          case "Non-payé":
            statusData["Non-payé"][factureMonth]++;
            break;
          case "En retard":
            statusData["En retard"][factureMonth]++;
            break;
        }
      }
    });

    // Dynamically generate xAxis data array using translated month names
    var xAxisData = [];
    for (var i = 0; i < 12; i++) {
      xAxisData.push(getTranslatedMonthName(i));
    }

    // Stacked bar chart options
    var facturesChartOption = {
      tooltip: {
        trigger: "axis",
        axisPointer: {
          type: "shadow",
        },
      },
      legend: {
        data: ["Payé", "Non-payé", "En retard"],
      },
      xAxis: {
        type: "category",
        data: xAxisData,
      },
      yAxis: {
        type: "value",
      },
      series: [
        {
          name: "Payé",
          type: "bar",
          stack: "status",
          data: statusData["Payé"],
        },
        {
          name: "Non-payé",
          type: "bar",
          stack: "status",
          data: statusData["Non-payé"],
        },
        {
          name: "En retard",
          type: "bar",
          stack: "status",
          data: statusData["En retard"],
        },
      ],
    };

    facturesChart.setOption(facturesChartOption);

    var devisStatusData = {
      "En attente": Array(12).fill(0),
      Approuvé: Array(12).fill(0),
      Refusé: Array(12).fill(0),
    };

    // Process Facture data to count status per month
    devisData.forEach(function (devis) {
      var devisYear = new Date(devis.dateFacturation).getFullYear();
      var devisMonth = new Date(devis.dateFacturation).getMonth();

      // Check if the facture belongs to the selected year
      if (devisYear === selectedYear) {
        // Increment the count for the corresponding status and month
        switch (devis.status) {
          case "En attente":
            devisStatusData["En attente"][devisMonth]++;
            break;
          case "Approuvé":
            devisStatusData["Approuvé"][devisMonth]++;
            break;
          case "Refusé":
            devisStatusData["Refusé"][devisMonth]++;
            break;
        }
      }
    });

    // Dynamically generate xAxis data array using translated month names
    var xAxisData = [];
    for (var i = 0; i < 12; i++) {
      xAxisData.push(getTranslatedMonthName(i));
    }

    // Stacked bar chart options
    var devisChartOption = {
      tooltip: {
        trigger: "axis",
        axisPointer: {
          type: "shadow",
        },
      },
      legend: {
        data: ["En attente", "Approuvé", "Refusé"],
      },
      xAxis: {
        type: "category",
        data: xAxisData,
      },
      yAxis: {
        type: "value",
      },
      series: [
        {
          name: "En attente",
          type: "bar",
          stack: "status",
          data: devisStatusData["En attente"],
        },
        {
          name: "Approuvé",
          type: "bar",
          stack: "status",
          data: devisStatusData["Approuvé"],
        },
        {
          name: "Refusé",
          type: "bar",
          stack: "status",
          data: devisStatusData["Refusé"],
        },
      ],
    };

    devisChart.setOption(devisChartOption);

    function getTranslatedMonthName(monthNumber) {
      switch (monthNumber) {
        case 0:
          return translatedMonths.jan;
        case 1:
          return translatedMonths.feb;
        case 2:
          return translatedMonths.mar;
        case 3:
          return translatedMonths.apr;
        case 4:
          return translatedMonths.may;
        case 5:
          return translatedMonths.jun;
        case 6:
          return translatedMonths.jul;
        case 7:
          return translatedMonths.aug;
        case 8:
          return translatedMonths.sep;
        case 9:
          return translatedMonths.oct;
        case 10:
          return translatedMonths.nov;
        case 11:
          return translatedMonths.dec;
        default:
          return "";
      }
    }
  }

  // Initial update of charts with the current year
  var currentYear = new Date().getFullYear();
  updateCharts(currentYear);
});
