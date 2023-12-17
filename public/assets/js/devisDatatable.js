$(document).ready(function () {
    $('#devisTable').DataTable({
        "language": {
            url: "/assets/json/French.json"
        },
        "lengthMenu": [10, 20, 30, 40, 50],
        "paging": true,
        "info": true,
        "searching": true,
        "order": [[0, 'asc']],
    });
});
