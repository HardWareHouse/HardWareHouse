import { initializeCharts } from "./chartModules.js";

document.addEventListener("DOMContentLoaded", function () {
  initializeCharts(
    paiementsData,
    facturesData,
    devisData,
    translatedMonths,
    translatedMethods,
    translatedStatusFacture,
    translatedStatusDevis
  );
});
