import * as echarts from "echarts";
import { processData } from "./dataProcessing.js";

export var paymentsChart;
export var methodsChart;
export var facturesChart;
export var devisChart;

export function initializeCharts(
  paiementsData,
  facturesData,
  devisData,
  translatedMonths,
  translatedMethods,
  translatedStatusFacture,
  translatedStatusDevis
) {
  //Initialize charts
  paymentsChart = echarts.init(document.getElementById("paiementsAnnee"));

  methodsChart = echarts.init(document.getElementById("methodes"));

  facturesChart = echarts.init(document.getElementById("facturesChart"));

  devisChart = echarts.init(document.getElementById("devisChart"));

  //Get available years from Symfony to create dropdown
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
  processData(
    paiementsData,
    facturesData,
    devisData,
    translatedMonths,
    translatedMethods,
    translatedStatusFacture,
    translatedStatusDevis
  );
}
