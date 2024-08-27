

(function ($) {
    $(document).ready(function () {
        $('#simpleDataTable2').DataTable({
            dom: 'lfrtip',
            lengthMenu: [[5, 10, 15, 25, 50, 100, -1],[5, 10, 15, 25, 50, 100, "ALL"]],
            pageLength: 15,
        })
        $('#dataTableSmall').DataTable({
            dom: 'lfrtip',
            lengthMenu: [[2, 10, 15, 25, 50, 100, -1],[2, 10, 15, 25, 50, 100, "ALL"]],
            pageLength: 2,
        })
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
                        columns: ':visible', // Export only visible columns
                        format: {
                            header: function (data, columnIdx) {
                                // Extract the header text from the <th> element, ignoring the input field
                                return $('#DataTable2 tfoot th').eq(columnIdx).text();
                            }
                        }
                    }
                },
                {
                    extend: 'copy',
                    text: '<i class="fas fa-copy"></i> Copy',
                },
                {
                    extend: 'csv',
                    text: '<i class="fas fa-file-csv"></i> CSV',
                    exportOptions: {
                        columns: ':visible',
                        format: {
                            header: function (data, columnIdx) {
                                return $('#DataTable2 tfoot th').eq(columnIdx).text();
                            }
                        }
                    }
                },
                {
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel"></i> Excel',
                    exportOptions: {
                        columns: ':visible',
                        format: {
                            header: function (data, columnIdx) {
                                return $('#DataTable2 tfoot th').eq(columnIdx).text();
                            }
                        }
                    }
                },
                {
                    extend: 'print',
                    text: '<i class="fas fa-print"></i> Print',
                    exportOptions: {
                        columns: ':visible',
                        format: {
                            header: function (data, columnIdx) {
                                return $('#DataTable2 tfoot th').eq(columnIdx).text();
                            }
                        }
                    }
                }
            ],
            initComplete: function () {
                // Add search inputs to header
                $('#DataTable2 thead th').each(function() {
                    var title = $(this).text(); // Use the text content of the header cells
                    $(this).html('<input type="text" class="form-control" placeholder="' + title + '..." />');
                });

                // Apply the search
                var table = this.api(); // Use the DataTables API instance
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
                        columns: ':visible' ,// Export only visible columns
                        format: {
                            header: function (data, columnIdx) {
                                return $('#userTable tfoot th').eq(columnIdx).text();
                            }
                        }
                    }
                },
                {
                    extend: 'copy',
                    text: '<i class="fas fa-copy"></i> Copy',
                    exportOptions: {
                        columns: ':visible' ,// Export only visible columns
                        format: {
                            header: function (data, columnIdx) {
                                return $('#userTable tfoot th').eq(columnIdx).text();
                            }
                        }
                    },
                },
                {
                    extend:'csv',
                    text: '<i class="fas fa-file-csv"></i> CSV',
                    exportOptions: {
                        columns: ':visible' ,// Export only visible columns
                        format: {
                            header: function (data, columnIdx) {
                                return $('#userTable tfoot th').eq(columnIdx).text();
                            }
                        }
                    },
                },
                {
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel"></i> Excel',
                    exportOptions: {
                        columns: ':visible' ,// Export only visible columns
                        format: {
                            header: function (data, columnIdx) {
                                return $('#userTable tfoot th').eq(columnIdx).text();
                            }
                        }
                    },
                },
                {
                    extend: 'print',
                    text: '<i class="fas fa-print"></i> Print',
                    exportOptions: {
                        columns: ':visible' ,// Export only visible columns
                        format: {
                            header: function (data, columnIdx) {
                                return $('#userTable tfoot th').eq(columnIdx).text();
                            }
                        }
                    },
                },
            ],
            initComplete: function () {
                // Add search inputs
                $('#userTable thead th').each(function() {
                    var title = $('#userTable tfoot th').eq($(this).index()).text();
                    $(this).html('<input type="text" class="form-control" placeholder="' + title + '..." />');
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
