

(function ($) {
    $(document).ready(function () {
        $('#DataTable2').DataTable({
            dom: 'lBfrtip', // 'l' includes the "length changing" input
            lengthMenu: [[5, 10, 15, 25, 50, 100, -1],[5, 10, 15, 25, 50, 100, "ALL"]],
            pageLength: 15,
            buttons: [
                {
                    extend: 'pdfHtml5',
                    text: '<i class="fas fa-file-pdf"></i> PDF',
                    orientation: 'landscape', // Landscape orientation
                    pageSize: 'A4', // A4 page size
                    title: 'My Table Export', // Optional: Custom title
                    exportOptions: {
                        columns: ':visible' // Export only visible columns
                    }
                },
                {
                    extend: 'copy',
                    text: '<i class="fas fa-copy"></i> Copy',
                },
                {
                    extend:'csv',
                    text: '<i class="fas fa-file-csv"></i> CSV',
                },
                {
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel"></i> Excel',
                },
                {
                    extend: 'print',
                    text: '<i class="fas fa-print"></i> Print',
                },
            ],
            initComplete: function () {
                // Add search inputs
                $('#DataTable2 thead th').each(function() {
                    var title = $('#DataTable2 tfoot th').eq($(this).index()).text();
                    $(this).html('<input type="text" class="form-control" placeholder="Search ' + title + '" />');
                });

                // Apply the search
                var table = $('#DataTable2').DataTable();
                table.columns().eq(0).each(function(colIdx) {
                    $('input', table.column(colIdx).header()).on('keyup change', function() {
                        table.column(colIdx).search(this.value).draw();
                    });
                });
            }
        });
        // Initialize DataTable
        $('#userTable').DataTable({
            dom: 'lBfrtip', // 'l' includes the "length changing" input
            lengthMenu: [[5, 10, 15, 25, 50, 100, -1],[5, 10, 15, 25, 50, 100, "ALL"]],
            pageLength: 15,
            buttons: [
                {
                    extend: 'pdfHtml5',
                    text: '<i class="fas fa-file-pdf"></i> PDF',
                    orientation: 'landscape', // Landscape orientation
                    pageSize: 'A4', // A4 page size
                    title: 'My Table Export', // Optional: Custom title
                    exportOptions: {
                        columns: ':visible' // Export only visible columns
                    }
                },
                {
                    extend: 'copy',
                    text: '<i class="fas fa-copy"></i> Copy',
                },
                {
                    extend:'csv',
                    text: '<i class="fas fa-file-csv"></i> CSV',
                },
                {
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel"></i> Excel',
                },
                {
                    extend: 'print',
                    text: '<i class="fas fa-print"></i> Print',
                },
            ],
            initComplete: function () {
                // Add search inputs
                $('#userTable thead th').each(function() {
                    var title = $('#userTable tfoot th').eq($(this).index()).text();
                    $(this).html('<input type="text" class="form-control" placeholder="Search ' + title + '" />');
                });

                // Apply the search
                var table = $('#userTable').DataTable();
                table.columns().eq(0).each(function(colIdx) {
                    $('input', table.column(colIdx).header()).on('keyup change', function() {
                        table.column(colIdx).search(this.value).draw();
                    });
                });
            }
        });

    })
}(jQuery))
