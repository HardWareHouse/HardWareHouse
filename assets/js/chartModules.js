import * as echarts from "echarts";
import { processData } from "./dataProcessing.js";

export var paymentsChart;
export var methodsChart;
export var facturesChart;
export var devisChart;
export var mostRecentYear;

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

  // Find the most recent year

  // Remove duplicate years
  years = Array.from(new Set(years));
  mostRecentYear = Math.max(...years);

  // Create dropdown options for years
  var dropdownContainer = document.getElementById("dropdownContainer");
  var yearDropdownHTML =
    '<select id="yearDropdown" class="text-white bg-darkBlue focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center mx-2 dark:focus:ring-blue-800">';
  // Sort the years numerically
  years.sort(function (a, b) {
    return b - a;
  });
  years.forEach(function (year) {
    yearDropdownHTML += '<option value="' + year + '">' + year + "</option>";
  });
  yearDropdownHTML += "</select>";
  dropdownContainer.innerHTML = yearDropdownHTML;

  if (typeof entreprises !== "undefined") {
    var companies = Array.from(new Set(entreprises));
    var companyDropdownHTML =
      '<select id="companyDropdown" class="text-white bg-darkBlue focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center mx-2 dark:focus:ring-blue-800">';

    companies.sort();
    companyDropdownHTML +=
      '<option value="all">Toutes les entreprises </option>';
    companies.forEach(function (company) {
      companyDropdownHTML +=
        '<option value="' + company.id + '">' + company.nom + "</option>";
    });
    companyDropdownHTML += "</select>";
    dropdownContainer.innerHTML += companyDropdownHTML;
  }

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
