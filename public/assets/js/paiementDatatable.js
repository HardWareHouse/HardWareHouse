$(document).ready(function () {
  var language = {
    url: "/assets/json/French.json",
  };

  if (window.location.href.includes("en")) {
    language.url = "/assets/json/English.json";
  }

  // Initialize DataTable with the dynamically set language
  var table = $("#paiementTable").DataTable({
    language: language,
    lengthMenu: [10, 20, 30, 40, 50],
    paging: true,
    info: true,
    searching: true,
    order: [[0, "asc"]],
  });

  // On applique ici le filtre de recherche pour chaque colonne de la table
  $("#paiementTable thead tr:eq(1) th").each(function (i) {
    $("input", this).on("click", function (e) {
      e.stopPropagation();
    });
    
    $("input", this).on("keyup change", function () {
      if (table.column(i).search() !== this.value) {
        table.column(i).search(this.value).draw();
      }
    });
  });
});
