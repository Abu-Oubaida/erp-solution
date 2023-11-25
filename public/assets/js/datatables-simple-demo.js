window.addEventListener('DOMContentLoaded', event => {
    // Simple-DataTables
    // https://github.com/fiduswriter/Simple-DataTables/wiki

    const permission = document.getElementById('permissionstable');
    const datatablesSimple = document.getElementById('datatablesSimple');
    const datatablesSimple2 = document.getElementById('datatablesSimple2');
    const dataTableModal = document.getElementById('data-table');
    if (permission) {
        new simpleDatatables.DataTable(permission,{
            perPageSelect: [10, 25, 50,100, ["All", -1]],
            columns: [
                {
                    select: 2,
                    sortSequence: ["desc", "asc"]
                },
                {
                    select: 3,
                    sortSequence: ["desc"]
                },
            ],
            tableRender: (_data, table, type) => {
                if (type === "print") {
                    return table
                }
                const tHead = table.childNodes[0]
                const filterHeaders = {
                    nodeName: "TR",
                    childNodes: tHead.childNodes[0].childNodes.map(
                        (_th, index) => ({nodeName: "TH",
                            childNodes: [
                                {
                                    nodeName: "INPUT",
                                    attributes: {
                                        placeholder:"Search..",
                                        class: "datatable-input",
                                        type: "search",
                                        "data-columns": `[${index}]`
                                    }
                                }
                            ]})
                    )
                }
                tHead.childNodes.push(filterHeaders)
                return table
            }
        });
    }
    if (datatablesSimple) {
        new simpleDatatables.DataTable(datatablesSimple,{
            perPageSelect: [10, 25, 50,100, ["All", -1]],
            columns: [
                {
                    select: 2,
                    sortSequence: ["desc", "asc"]
                },
                {
                    select: 3,
                    sortSequence: ["desc"]
                },
            ]
        });
    }
    // if (dataTableModal) {
    //     new simpleDatatables.DataTable(dataTableModal,{
    //         perPageSelect: [10, 25, 50,100, ["All", -1]],
    //         columns: [
    //             {
    //                 select: 2,
    //                 sortSequence: ["desc", "asc"]
    //             },
    //             {
    //                 select: 3,
    //                 sortSequence: ["desc"]
    //             },
    //         ]
    //     });
    // }
    if (datatablesSimple2) {
        new simpleDatatables.DataTable(datatablesSimple2,{
            perPageSelect: [10, 25, 50,100, ["All", -1]],
            columns: [
                {
                    select: 2,
                    sortSequence: ["desc", "asc"]
                },
                {
                    select: 3,
                    sortSequence: ["desc"]
                },

            ]
        });
    }

});
