$(document).ready(function () {
    // Initialisation DataTable
    var table = $('#clientTable').DataTable({
        "language": {
            "url": "/assets/json/French.json"
        },
        "lengthMenu": [10, 20, 30, 40, 50],
        "paging": true,
        "info": true,
        "searching": true,
        "order": [[0, 'asc']]
    });

    // On applique ici le filtre de recherche pour chaque colonne de la table
    $('#clientTable thead tr:eq(1) th').each(function (i) {
        $('input', this).on('keyup change', function () {
            if (table.column(i).search() !== this.value) {
                table
                    .column(i)
                    .search(this.value)
                    .draw();
            }
        });
    });
});
