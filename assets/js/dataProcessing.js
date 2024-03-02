import {
  paymentsChart,
  methodsChart,
  facturesChart,
  devisChart,
  mostRecentYear,
} from "./chartModules.js";

export function processData(
  paiementsData,
  facturesData,
  devisData,
  translatedMonths,
  translatedMethods,
  translatedStatusFacture,
  translatedStatusDevis,
  filteredDevisData,
  filteredFacturesData,
  filteredPaiementsData
) {
  var selectedYear = new Date().getFullYear();
  // updateMethodsChart(selectedYear);

  var selectedCompanyId;
  var filteredPaiementsData;
  var filteredFacturesData;
  var filteredDevisData;
  // Event listener for dropdown change
  document
    .getElementById("yearDropdown")
    .addEventListener("change", function (event) {
      selectedYear = parseInt(event.target.value);
      paymentMethods = {};
      updateCharts(selectedYear, selectedCompanyId);
    });

  document
    .getElementById("companyDropdown")
    .addEventListener("change", function (event) {
      selectedCompanyId = event.target.value;
      updateCharts(selectedYear, selectedCompanyId);
      paymentMethods = {};
    });

  function updateCharts(selectedYear, selectedCompanyId) {
    updateCsvDownloadLinks(selectedYear, selectedCompanyId);
    updatePaymentsChart(selectedYear, selectedCompanyId);
    updateMethodsChart(selectedYear, selectedCompanyId);
    updateFacturesChart(selectedYear, selectedCompanyId);
    updateDevisChart(selectedYear, selectedCompanyId);

    filteredPaiementsData = paiementsData.filter(function (paiement) {
      return paiement.entrepriseId == selectedCompanyId;
    });

    filteredFacturesData = facturesData.filter(function (facture) {
      return facture.entrepriseId == selectedCompanyId;
    });

    filteredDevisData = devisData.filter(function (devis) {
      return devis.entrepriseId == selectedCompanyId;
    });
  }

  function updateCsvDownloadLinks(selectedYear, selectedCompanyId) {
    var currentLocale = document.documentElement.lang;
    var csvMethodsLink = `/${currentLocale}/admin/csv-methodes/${selectedYear}`;
    var csvFactureLink = `/${currentLocale}/admin/csv-factures/${selectedYear}`;
    var csvDevisLink = `/${currentLocale}/admin/csv-devis/${selectedYear}`;
    var csvRevenueLink = `/${currentLocale}/admin/csv-revenue/${selectedYear}`;

    document.getElementById("csvMethods").href = csvMethodsLink;
    document.getElementById("csvFacture").href = csvFactureLink;
    document.getElementById("csvRevenue").href = csvRevenueLink;
    document.getElementById("csvDevis").href = csvDevisLink;
  }
  var paymentsPerMonth;

  function updatePaymentsChart(selectedYear, selectedCompanyId) {
    if (selectedCompanyId && selectedCompanyId != "all") {
      paymentsPerMonth = processCompanyPaymentsData(
        paiementsData,
        selectedYear
      );
    } else {
      paymentsPerMonth = processPaymentsData(paiementsData, selectedYear);
    }
    var paymentsChartOption = generatePaymentsChartOption(paymentsPerMonth);
  }
  var paymentMethods = {};
  function updateMethodsChart(selectedYear, selectedCompanyId) {
    if (selectedCompanyId && selectedCompanyId != "all") {
      paymentMethods = processCompanyPaymentMethodsData(
        filteredPaiementsData,
        selectedYear
      );
    } else {
      paymentMethods = processPaymentMethodsData(paiementsData, selectedYear);
    }
    var methodsChartOption = generateMethodsChartOption(paymentMethods);
    methodsChart.resize();
  }

  function updateFacturesChart(selectedYear, selectedCompanyId) {
    xAxisData = [];

    var statusData = processFacturesData(facturesData, selectedYear);
    var facturesChartOption = generateFacturesChartOption(statusData);
  }

  function updateDevisChart(selectedYear, selectedCompanyId) {
    var devisStatusData = processDevisData(devisData, selectedYear);
    var devisChartOption = generateDevisChartOption(devisStatusData);
  }

  // Utility Functions
  function processPaymentsData(paiementsData, selectedYear) {
    var paymentsPerMonth = new Array(12).fill(0); // Initialize array to hold payments per month

    paiementsData.forEach(function (paiement) {
      var paiementYear = new Date(paiement.datePaiement).getFullYear();
      var paiementMonth = new Date(paiement.datePaiement).getMonth();

      // Check if the paiement belongs to the selected year
      if (paiementYear === selectedYear) {
        paymentsPerMonth[paiementMonth] += paiement.montant;
      }
    });
    return paymentsPerMonth;
  }

  function processCompanyPaymentsData(paiementsData, selectedYear) {
    var paymentsPerMonth = new Array(12).fill(0); // Initialize array to hold payments per month

    filteredPaiementsData.forEach(function (paiement) {
      var paiementYear = new Date(paiement.datePaiement).getFullYear();
      var paiementMonth = new Date(paiement.datePaiement).getMonth();

      // Check if the paiement belongs to the selected year
      if (paiementYear === selectedYear) {
        paymentsPerMonth[paiementMonth] += paiement.montant;
      }
    });
    return paymentsPerMonth;
  }

  function processPaymentMethodsData(paiementsData, selectedYear) {
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
    return paymentMethods; // Return the paymentMethods object
  }

  function processCompanyPaymentMethodsData(
    filteredPaiementsData,
    selectedYear
  ) {
    filteredPaiementsData.forEach(function (paiement) {
      var paiementYear = new Date(paiement.datePaiement).getFullYear();
      if (paiementYear === selectedYear) {
        if (!paymentMethods[paiement.methodePaiement]) {
          paymentMethods[paiement.methodePaiement] = 1;
        } else {
          paymentMethods[paiement.methodePaiement]++;
        }
      }
    });

    return paymentMethods; // Return the paymentMethods object
  }
  var xAxisData = [];

  function processFacturesData(facturesData, selectedYear) {
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

    // Return the statusData object

    // Dynamically generate xAxis data array using translated month names
    for (var i = 0; i < 12; i++) {
      xAxisData.push(getTranslatedMonthName(i));
    }
    return statusData;
  }

  function processDevisData(devisData, selectedYear) {
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
    return devisStatusData;
  }

  function generatePaymentsChartOption(paymentsPerMonth) {
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
  }
  console.log(paymentMethods);
  // Translate legend labels
  var translatedLegend = [];
  Object.keys(paymentMethods).forEach(function (method) {
    translatedLegend.push(translatedMethods[method]);
  });
  var methodsChartOption = generateMethodsChartOption(
    paymentMethods,
    translatedLegend
  );

  function generateMethodsChartOption(paymentMethods, translatedLegend) {
    methodsChartOption = {
      tooltip: {
        trigger: "item",
      },
      legend: {
        orient: "vertical",
        right: 10,
        data: translatedLegend, // Use translated legend labels
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
            return {
              value: paymentMethods[method],
              name: translatedMethods[method],
            }; // Use translated labels for pie chart
          }),
        },
      ],
    };
    methodsChart.resize();

    methodsChart.setOption(methodsChartOption);
  }

  function generateFacturesChartOption(statusData) {
    var facturesChartOption = {
      tooltip: {
        trigger: "axis",
        axisPointer: {
          type: "shadow",
        },
      },
      legend: {
        data: [
          translatedStatusFacture["Payé"],
          translatedStatusFacture["Non-payé"],
          translatedStatusFacture["En retard"],
        ],
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
          name: translatedStatusFacture["Payé"],
          type: "bar",
          stack: "status",
          data: statusData["Payé"],
        },
        {
          name: translatedStatusFacture["Non-payé"],
          type: "bar",
          stack: "status",
          data: statusData["Non-payé"],
        },
        {
          name: translatedStatusFacture["En retard"],
          type: "bar",
          stack: "status",
          data: statusData["En retard"],
        },
      ],
    };

    facturesChart.setOption(facturesChartOption);
  }

  function generateDevisChartOption(devisStatusData) {
    var devisChartOption = {
      tooltip: {
        trigger: "axis",
        axisPointer: {
          type: "shadow",
        },
      },
      legend: {
        data: [
          translatedStatusDevis["En attente"],
          translatedStatusDevis["Approuvé"],
          translatedStatusDevis["Refusé"],
        ],
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
          name: translatedStatusDevis["En attente"],
          type: "bar",
          stack: "status",
          data: devisStatusData["En attente"],
        },
        {
          name: translatedStatusDevis["Approuvé"],
          type: "bar",
          stack: "status",
          data: devisStatusData["Approuvé"],
        },
        {
          name: translatedStatusDevis["Refusé"],
          type: "bar",
          stack: "status",
          data: devisStatusData["Refusé"],
        },
      ],
    };

    devisChart.setOption(devisChartOption);
  }
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
  updateCharts(mostRecentYear);
}
