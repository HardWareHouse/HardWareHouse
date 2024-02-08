import * as echarts from "echarts";

// METHODES DE PAIEMENT
var methodes = echarts.init(document.getElementById("methodes"));
var uniquePaymentMethods = [
  ...new Set(paiementsData.map((paiement) => paiement.methodePaiement)),
];

methodes.setOption({
  title: {
    text: "Methodes de paiement",
    left: "center",
  },
  tooltip: {},
  legend: { orient: "vertical", top: "center" },
  series: [
    {
      name: "Nombre de paiements",
      type: "pie", // Change the chart type to pie
      radius: ["50%", "70%"], // Set inner and outer radius for the donut chart
      avoidLabelOverlap: false,
      label: {
        show: false,
        // position: "center",
      },
      //   emphasis: {
      //     label: {
      //       show: true,
      //       fontSize: "30",
      //       fontWeight: "medium",
      //     },
      //   },
      labelLine: {
        show: false,
      },
      data: uniquePaymentMethods.map((method) => ({
        name: method,
        value: paiementsData.filter((p) => p.methodePaiement === method).length,
      })),
    },
  ],
});

// PAIEMENTS PAR MOIS/ANNEE
var revenue = echarts.init(document.getElementById("paiementsAnnee"));

// Initialize monthlyData object with all months set to 0 for all years
var monthlyData = {};

// Extract the unique years from the data
var uniqueYears = [
  ...new Set(
    paiementsData.map((paiement) =>
      new Date(paiement.datePaiement).getFullYear()
    )
  ),
];

// Initialize monthly data for each year
uniqueYears.forEach((year) => {
  for (var i = 1; i <= 12; i++) {
    var monthKey = year + "-" + (i < 10 ? "0" + i : i);
    monthlyData[monthKey] = 0;
  }
});

// Iterate through payments and update monthly data
paiementsData.forEach(function (paiement) {
  var date = new Date(paiement.datePaiement);
  var year = date.getFullYear();
  var month = date.getMonth() + 1; // Months are zero-based, so add 1
  var key = year + "-" + (month < 10 ? "0" + month : month); // Ensure month format is 'MM'
  monthlyData[key] += paiement.montant;
});

// Extract the keys (year-month) and corresponding montant totals
var xAxisData = Object.keys(monthlyData);
var montantData = Object.values(monthlyData);

// Filter out future months from xAxisData and montantData
var currentDate = new Date();
var currentYear = currentDate.getFullYear();
var currentMonth = currentDate.getMonth() + 1; // Add 1 because months are zero-based

var filteredXAxisData = xAxisData.filter(function (yearMonth) {
  var [year, month] = yearMonth.split("-");
  return year < currentYear || (year == currentYear && month <= currentMonth);
});

var filteredMontantData = filteredXAxisData.map(function (yearMonth) {
  return monthlyData[yearMonth];
});

revenue.setOption({
  title: {
    text: "Montant des paiements par mois",
    left: "center", // Center the title
    top: 20, // Adjust top margin
  },
  tooltip: {
    trigger: "axis", // Show tooltip when hovering over data points
    formatter: function (params) {
      var value = params[0].value;
      var yearMonth = params[0].axisValue;
      return yearMonth + ": " + value.toFixed(2); // Format value as needed
    },
  },
  xAxis: {
    type: "category",
    data: filteredXAxisData,
  },
  yAxis: {
    type: "value",
  },
  series: [
    {
      type: "line",
      data: filteredMontantData,
    },
  ],
});

// Extract years from the dataset
var years = [];
xAxisData.forEach(function (yearMonth) {
  var year = yearMonth.split("-")[0];
  if (!years.includes(year)) {
    years.push(year);
  }
});
// Sort the years array in ascending order
years.sort();

if (years.length > 1) {
  var dropdownContainer = document.getElementById("dropdownContainer");
  var dropdown = document.createElement("select");
  dropdown.setAttribute("id", "yearDropdown");

  years.forEach(function (year) {
    var option = document.createElement("option");
    option.setAttribute("value", year);
    option.textContent = year;
    dropdown.appendChild(option);
  });

  dropdown.addEventListener("change", function () {
    var selectedYear = dropdown.value;
    var filteredData = xAxisData.filter(function (yearMonth) {
      return yearMonth.startsWith(selectedYear);
    });
    var filteredMontantData = filteredData.map(function (yearMonth) {
      return monthlyData[yearMonth];
    });
    revenue.setOption({
      xAxis: {
        data: filteredData,
      },
      series: [
        {
          data: filteredMontantData,
        },
      ],
    });
  });

  // Append the dropdown to the container
  dropdownContainer.appendChild(dropdown);

  // Set the default value of the dropdown to the current year
  var currentYear = new Date().getFullYear().toString();
  dropdown.value = currentYear;
}

// Initially display only the data for the current year
var initialFilteredData = filteredXAxisData.filter(function (yearMonth) {
  return yearMonth.startsWith(currentYear);
});
var initialFilteredMontantData = initialFilteredData.map(function (yearMonth) {
  return monthlyData[yearMonth];
});
revenue.setOption({
  xAxis: {
    data: initialFilteredData,
  },
  series: [
    {
      data: initialFilteredMontantData,
    },
  ],
});

// Sort the xAxisData array based on year and month
xAxisData.sort(function (a, b) {
  // Split the year and month parts
  var [yearA, monthA] = a.split("-");
  var [yearB, monthB] = b.split("-");

  // Compare years
  if (yearA !== yearB) {
    return yearA - yearB;
  } else {
    // If years are equal, compare months
    return monthA - monthB;
  }
});
