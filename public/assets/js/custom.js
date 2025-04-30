let Obj = {};
let Archive = {};
let Sales = {};
let SalesSetting = {};
// Show loader immediately when the page starts loading
(function ($) {
    //     $(document).ready(function() {
    //         // Show loading message when the document starts loading
    //         $("#ajax_loader").show();
    //         $("#ajax_loader2").show();
    //     });
    //
    // // Hide loading message when the full page (including images) is loaded
    //     $(window).on("load", function() {
    //         $("#ajax_loader").hide();
    //         $("#ajax_loader2").hide();
    //     });
    $(document).ajaxStop(function () {
        $("#ajax_loader").hide();
        $("#ajax_loader2").hide();
    });
    $(document).ajaxStart(function () {
        $("#ajax_loader").show();
        $("#ajax_loader2").show();
    });
    $(document).ready(function () {
        const tags = [];
        const employeeDatas = [];
        const materialsTempList = [];
        var extraMobileNumberCount = 2;
        var extraEmailAddressCount = 2;
        $(".select-search").selectize({
            create: false,
            sortField: "text",
        });
        $(".select-search-with-create").selectize({
            create: true,
            sortField: "text",
        });
        $("#perAdd").click(function () {
            let company = $("#company_id").val();
            let per = $("#per").val();
            let dir = $("#dir").val();
            let ref = $("#per").attr("ref");
            // alert(window.location.origin + sourceDir + "/user-per-add")
            // return false
            if (
                per.length > 0 &&
                dir.length > 0 &&
                company.length > 0 &&
                ref.length > 0
            ) {
                let url =
                    window.location.origin +
                    sourceDir +
                    "/system-operation/user-per-add";
                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    url: url,
                    type: "POST",
                    data: { per: per, dir: dir, ref: ref, company: company },
                    success: function (data) {
                        try {
                            data = JSON.parse(data);
                            alert(data.error.msg);
                        } catch (e) {
                            $("#f-p-list").html(data);
                            alert("Data added successfully!");
                        }
                    },
                });
            }
        });
        $("#file_upload").on("change", function (e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const data = e.target.result;
                    const workbook = XLSX.read(data, { type: "binary" });
                    const firstSheet = workbook.Sheets[workbook.SheetNames[0]];
                    const jsonData = XLSX.utils.sheet_to_json(firstSheet, {
                        header: 1,
                    });
                    if (jsonData[0].length !== 13) {
                        alert(
                            "Invalid input data! Please flowing the prototype of data format!"
                        );
                        return false;
                    }
                    if (
                        !(
                            (jsonData[0][0] === "Employee ID*" ||
                                jsonData[0][0] === "Employee ID") &&
                            jsonData[0][1] === "Name" &&
                            jsonData[0][2] === "Department" &&
                            (jsonData[0][3] === "Financial Year From*" ||
                                jsonData[0][3] === "Financial Year From") &&
                            (jsonData[0][4] === "Financial Year To*" ||
                                jsonData[0][4] === "Financial Year To") &&
                            (jsonData[0][5] === "Basic*" ||
                                jsonData[0][5] === "Basic") &&
                            (jsonData[0][6] === "House Rent*" ||
                                jsonData[0][6] === "House Rent") &&
                            (jsonData[0][7] === "Conveyance*" ||
                                jsonData[0][7] === "Conveyance") &&
                            (jsonData[0][8] === "Medical Allowance*" ||
                                jsonData[0][8] === "Medical Allowance") &&
                            jsonData[0][9] === "Total" &&
                            (jsonData[0][10] === "Festival Bonus*" ||
                                jsonData[0][10] === "Festival Bonus") &&
                            jsonData[0][11] === "Others" &&
                            jsonData[0][12] === "Remarks"
                        )
                    ) {
                        alert(
                            "Invalid input data! Please flowing the prototype of data format!"
                        );
                        return false;
                    }
                    for (let i = 0; i < jsonData.length; i++) {
                        for (let j = 0; j < jsonData[i].length; j++) {
                            if (typeof jsonData[i][j] === "undefined") {
                                jsonData[i][j] = 0;
                            }
                            if (i > 0) {
                                if (
                                    typeof jsonData[0][9] !== "undefined" &&
                                    jsonData[0][9] !== null
                                ) {
                                    let total = parseInt(jsonData[i][9]);
                                    jsonData[i][5] = (total * 60) / 100;
                                    jsonData[i][6] = (total * 30) / 100;
                                    jsonData[i][7] = (total * 5) / 100;
                                    jsonData[i][8] = (total * 5) / 100;
                                }
                                if (j === 3 || j === 4) {
                                    jsonData[i][j] = ExcelDateToJSFinancialDate(
                                        jsonData[i][j]
                                    );
                                }
                            }
                        }
                    }
                    employeeDatas.push(jsonData);
                    showModal(employeeDatas, file.name);
                };
                reader.readAsBinaryString(file);
            }
        });

        $("#employee_file_upload").on("change", function (e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const data = e.target.result;
                    const workbook = XLSX.read(data, { type: "binary" });
                    const firstSheet = workbook.Sheets[workbook.SheetNames[0]];
                    const jsonData = XLSX.utils.sheet_to_json(firstSheet, {
                        header: 1,
                    });
                    if (jsonData[0].length !== 10) {
                        alert(
                            "Invalid input data! Please flowing the prototype of data format!"
                        );
                        return false;
                    }
                    if (
                        !(
                            (jsonData[0][0] === "Employee Name*" ||
                                jsonData[0][0] === "Employee Name") &&
                            jsonData[0][1] === "Department" &&
                            (jsonData[0][2] === "Department Code*" ||
                                jsonData[0][2] === "Department Code") &&
                            (jsonData[0][3] === "Designation*" ||
                                jsonData[0][3] === "Designation") &&
                            jsonData[0][4] === "Branch" &&
                            (jsonData[0][5] === "Joining Date*" ||
                                jsonData[0][5] === "Joining Date") &&
                            jsonData[0][6] === "Phone" &&
                            jsonData[0][7] === "Email" &&
                            jsonData[0][8] === "Status" &&
                            jsonData[0][9] === "Blood Group"
                        )
                    ) {
                        alert(
                            "Invalid input data! Please flowing the prototype of data format!"
                        );
                        return false;
                    }
                    for (let i = 0; i < jsonData.length; i++) {
                        let zero = jsonData[0].length;
                        for (let j = 0; j < zero; j++) {
                            if (typeof jsonData[i][j] === "undefined") {
                                jsonData[i][j] = null;
                            } else if (i !== 0 && j === 5) {
                                jsonData[i][j] = ExcelDateToJSDate(
                                    jsonData[i][j]
                                );
                            }
                        }
                    }
                    employeeDatas.push(jsonData);
                    showModal(employeeDatas, file.name);
                };
                reader.readAsBinaryString(file);
            }
        });
        $("#permission_input_file").on("change", function (e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const data = e.target.result;
                    const workbook = XLSX.read(data, { type: "binary" });
                    const firstSheet = workbook.Sheets[workbook.SheetNames[0]];
                    const jsonData = XLSX.utils.sheet_to_json(firstSheet, {
                        header: 1,
                    });
                    if (jsonData[0].length !== 5) {
                        alert(
                            "Invalid input data! Please flowing the prototype of data format!"
                        );
                        return false;
                    }
                    if (
                        !(
                            (jsonData[0][0] === "Parent*" ||
                                jsonData[0][0] === "Parent") &&
                            (jsonData[0][1] === "Type*" ||
                                jsonData[0][1] === "Type") &&
                            (jsonData[0][2] === "Name*" ||
                                jsonData[0][2] === "Name") &&
                            (jsonData[0][3] === "Display Name*" ||
                                (jsonData[0][3] === "Display Name" &&
                                    jsonData[0][4] === "Details"))
                        )
                    ) {
                        alert(
                            "Invalid input data! Please flowing the prototype of data format!"
                        );
                        return false;
                    }
                    for (let i = 0; i < jsonData.length; i++) {
                        let zero = jsonData[0].length;
                        for (let j = 0; j < zero; j++) {
                            if (typeof jsonData[i][j] === "undefined") {
                                jsonData[i][j] = null;
                            } else if (i !== 0 && j === 5) {
                                jsonData[i][j] = ExcelDateToJSDate(
                                    jsonData[i][j]
                                );
                            }
                        }
                    }
                    employeeDatas.push(jsonData);
                    showModal(employeeDatas, file.name);
                };
                reader.readAsBinaryString(file);
            }
        });
        let fixedDiv = $("#fixedDiv");
        if (typeof fixedDiv.offset() !== "undefined") {
            let initialOffset = fixedDiv.offset().top;

            $(window).scroll(function () {
                var scrollPos = $(window).scrollTop();

                if (scrollPos > initialOffset) {
                    fixedDiv.addClass("fixed");
                } else {
                    fixedDiv.removeClass("fixed");
                }
            });
        }

        $("#selected-delete").click(function () {
            if (confirm("Are you sure?")) {
                // Create an array to store the checked values
                let checkedValues = [];

                // Use the :checked selector to get all checked checkboxes
                $('input[type="checkbox"]:checked').each(function () {
                    // Add the value of each checked checkbox to the array
                    checkedValues.push($(this).val());
                });

                // Display the result (you can modify this part based on your needs)
                alert("Checked values: " + checkedValues.join(", "));
            }
        });

        // Select All checkbox
        $("#select_all").change(function () {
            $(".check-box").prop("checked", this.checked);
        });

        // Individual checkboxes
        $(".check-box").change(function () {
            if (!this.checked) {
                $("#select_all").prop("checked", false);
            }
        });
        // Function to display the modal
        function showModal_old(data, fileName) {
            const modal = document.getElementById("myModal");
            const modelTitle = document.getElementById("userDataModelLabel");
            const dataTable = document.getElementById("data-table");
            while (dataTable.firstChild) {
                dataTable.removeChild(dataTable.firstChild);
            }

            // Create a table from the data
            const table = document.createElement("table");
            table.className = "table";
            let t = 0;
            let action;
            let row_o = 1;
            data[0].forEach((rowData) => {
                const row = document.createElement("tr");
                let cell;
                if (t === 0) {
                    action = "Action";
                    cell = document.createElement("td");
                    cell.textContent = "SL";
                    row.appendChild(cell);
                } else {
                    action =
                        '<a style="cursor: pointer" class="text-danger" onclick="return confirm(`Are you sure?`)? Obj.removeElementOfEmployeeData(this,' +
                        t +
                        ",`" +
                        fileName +
                        '`):false"><i class="fa fa-trash" aria-hidden="true"></i></a>';
                    cell = document.createElement("td");
                    cell.textContent = row_o;
                    row_o++;
                    row.appendChild(cell);
                }
                rowData.forEach((cellData) => {
                    cell = document.createElement("td");
                    cell.textContent = cellData;
                    row.appendChild(cell);
                });
                cell = document.createElement("td");
                cell.innerHTML = action;
                row.appendChild(cell);
                table.appendChild(row);
                t++;
            });

            // Append the table to the modal
            dataTable.appendChild(table);
            modelTitle.innerText = fileName;
            $("#myModal").modal("show");
        }
        function showModal(data, fileName) {
            const modal = document.getElementById("myModal");
            const modelTitle = document.getElementById("userDataModelLabel");
            const dataTable = document.getElementById("data-table");
            while (dataTable.firstChild) {
                dataTable.removeChild(dataTable.firstChild);
            }

            // Create a table from the data
            const table = document.createElement("table");
            table.className = "table";
            let t = 0;
            let action;
            let row_o = 1;

            let col = 0;
            const row = document.createElement("tr");
            cell = document.createElement("td");
            while (col < data[0][0].length + 2) {
                cell = document.createElement("td");
                cell.textContent = col++;
                row.appendChild(cell);
            }
            table.appendChild(row);

            data[0].forEach((rowData) => {
                const row = document.createElement("tr");
                let cell;
                if (t === 0) {
                    action = "Action";
                    cell = document.createElement("th");
                    cell.textContent = "SL";
                    row.appendChild(cell);
                    rowData.forEach((cellData) => {
                        cell = document.createElement("th");
                        cell.textContent = cellData;
                        row.appendChild(cell);
                    });
                    cell = document.createElement("th");
                    cell.innerHTML = action;
                    row.appendChild(cell);
                    table.appendChild(row);
                    t++;
                } else {
                    action =
                        '<a style="cursor: pointer" class="text-danger" onclick="return confirm(`Are you sure?`)? Obj.removeElementOfEmployeeData(this,' +
                        t +
                        ",`" +
                        fileName +
                        '`):false"><i class="fa fa-trash" aria-hidden="true"></i></a>';
                    cell = document.createElement("td");
                    cell.textContent = row_o;
                    row_o++;
                    row.appendChild(cell);
                    rowData.forEach((cellData) => {
                        cell = document.createElement("td");
                        cell.textContent = cellData;
                        row.appendChild(cell);
                    });
                    cell = document.createElement("td");
                    cell.innerHTML = action;
                    row.appendChild(cell);
                    table.appendChild(row);
                    t++;
                }
            });

            // Append the table to the modal
            dataTable.appendChild(table);
            modelTitle.innerText = fileName;
            $("#myModal").modal("show");
        }
        function ExcelDateToJSDate(serial) {
            const dateToString = (d) =>
                `${("00" + d.getDate()).slice(-2)}-${(
                    "00" +
                    (d.getMonth() + 1)
                ).slice(-2)}-${d.getFullYear()}`;
            return dateToString(
                new Date(Math.round((serial - 25569) * 86400 * 1000))
            );
        }
        function ExcelDateToJSFinancialDate(serial) {
            if (typeof serial !== "number") {
                return serial;
            }
            const dateToString = (d) => {
                const months = [
                    "January",
                    "February",
                    "March",
                    "April",
                    "May",
                    "June",
                    "July",
                    "August",
                    "September",
                    "October",
                    "November",
                    "December",
                ];
                const monthsShort = [
                    "Jan",
                    "Feb",
                    "Mar",
                    "Apr",
                    "May",
                    "Jun",
                    "Jul",
                    "Aug",
                    "Sep",
                    "Oct",
                    "Nov",
                    "Dec",
                ];

                const monthName = monthsShort[d.getMonth()];
                return `${monthName}-${d.getFullYear()}`;
            };

            return dateToString(
                new Date(Math.round((serial - 25569) * 86400 * 1000))
            );
        }
        Obj = {
            permissionInput: function (e) {
                let is_parent = null;
                const permission_parent = $("#permission_parent").val();
                const permission_name = $("#permission_name").val();
                const permission_display_name = $(
                    "#permission_display_name"
                ).val();
                const description = $("#description").val();
                if ($("#is_parent").is(":checked")) is_parent = 1;
                if (
                    permission_parent.length === 0 ||
                    permission_display_name.length === 0 ||
                    permission_name.length === 0
                ) {
                    return false;
                }
                let url =
                    window.location.origin +
                    sourceDir +
                    "/system-operation/permission-store";
                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    url: url,
                    type: "POST",
                    data: {
                        permission_parent: permission_parent,
                        permission_name: permission_name,
                        permission_display_name: permission_display_name,
                        is_parent: is_parent,
                        description: description,
                    },
                    success: function (response) {
                        if (response.status === "success") {
                            alert(response.message);
                            $("#permission-list").html(response.data);
                            if (is_parent) window.location.reload();
                        }
                        if (response.status === "error") {
                            alert("Error:" + response.message);
                            // Handle error
                        }
                    },
                });
            },
            permissionUpdate: function (e) {
                const permission_parent = $("#permission_parent").val();
                const permission_name = $("#permission_name").val();
                const permission_display_name = $(
                    "#permission_display_name"
                ).val();
                const description = $("#description").val();
                const permission_id = $("#permission_id").val();
                if (
                    permission_id.length === 0 ||
                    permission_parent.length === 0 ||
                    permission_display_name.length === 0 ||
                    permission_name.length === 0
                ) {
                    return false;
                }
                let url =
                    window.location.origin +
                    sourceDir +
                    "/system-operation/permission-update";
                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    url: url,
                    type: "POST",
                    data: {
                        permission_parent: permission_parent,
                        permission_name: permission_name,
                        permission_display_name: permission_display_name,
                        description: description,
                        permission_id: permission_id,
                    },
                    success: function (response) {
                        if (response.status === "success") {
                            alert(response.message);
                            $("#permission-list").html(response.data);
                        }
                        if (response.status === "error") {
                            alert("Error:" + response.message);
                            // Handle error
                        }
                    },
                });
            },
            authCompany: function (e) {
                const username = $("#email").val();
                const password = $("#password").val();
                if (username.length === 0 || password.length === 0) {
                    $("#companySelect").html("<option></option>");
                    $("#login").attr("disabled", "disabled");
                    $("#login").hide();
                    return false;
                }
                const url =
                    window.location.origin + sourceDir + "/company-check-set";
                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    url: url,
                    type: "POST",
                    data: { username: username, password: password },
                    success: function (response) {
                        if (response.status === "error") {
                            // alert('Error:'+response.message)
                            $("#auth_error_block").attr(
                                "style",
                                "display: block"
                            );
                            $("#auth_error").html(response.message);
                            $("#companySelect").html(
                                "<option value='0'>Not Found</option>"
                            );
                            $("#login").attr("disabled", "disabled");
                            $("#login").hide();
                        } else {
                            // Display the list of companies
                            let companies = response.companies;
                            let companySelect = $("#companySelect");
                            companySelect.empty();
                            $.each(companies, function (index, company) {
                                companySelect.append(
                                    new Option(company.company_name, company.id)
                                );
                            });
                            $("#auth_error_block").attr(
                                "style",
                                "display: none"
                            );
                            $("#auth_error").html("");
                            $("#login").removeAttr("disabled");
                            $("#login").show();
                        }
                    },
                    error: function (error) {
                        $("#auth_error_block").style("display", "block");
                        $("#auth_error").html(response.message);
                        $("#companySelect").html(
                            "<option value='0'>Not Found</option>"
                        );
                        $("#login").attr("disabled", "disabled");
                        $("#login").hide();
                    },
                });
            },
            changeUserCompany: function (e) {
                let company = $(e).val();
                if (company.length === 0) {
                    return false;
                }
                let url =
                    window.location.origin + sourceDir + "/change-user-company";
                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    url: url,
                    type: "POST",
                    data: { company_id: company },
                    success: function (response) {
                        if (response.status === "error") {
                            return alert(response.message);
                        }
                        if (response.status === "success") {
                            updateSelectBoxSingleOption(
                                response.data.departments,
                                "dept_menu",
                                "id",
                                "dept_name"
                            );
                            updateSelectBoxSingleOption(
                                response.data.branches,
                                "branch_menu",
                                "id",
                                "branch_name"
                            );
                            updateSelectBoxSingleOption(
                                response.data.designations,
                                "designation_menu",
                                "id",
                                "title"
                            );
                            updateSelectBoxSingleOption(
                                response.data.roles,
                                "role_menu",
                                "id",
                                "display_name"
                            );
                            $("#employee_id").val("");
                            $("#employee_id_hide").val("");
                        }
                    },
                    error: function (error) {
                        return alert(error.responseJSON.message);
                    },
                });
            },
            changeBranchCompany: function (e) {
                let company = $(e).val();
                if (company.length === 0) {
                    return false;
                }
                let url =
                    window.location.origin +
                    sourceDir +
                    "/change-branch-company";
                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    url: url,
                    type: "POST",
                    data: { company_id: company },
                    success: function (response) {
                        if (response.status === "error") {
                            return alert(response.message);
                        }
                        if (response.status === "success") {
                            // console.log(response.data.branchTypes)
                            updateSelectBoxSingleOption(
                                response.data.branchTypes,
                                "branch_type",
                                "id",
                                "title"
                            );
                        }
                    },
                    error: function (error) {
                        return alert(error.responseJSON.message);
                    },
                });
            },
            makeEmployeeID: function (dept_menu, companyID, joining_date) {
                let company_id = $("#" + companyID).val();
                let department_id = $("#" + dept_menu).val();
                let joining = $("#" + joining_date).val();
                if (company_id.length === 0) {
                    alert("Empty Company ID");
                    return false;
                }
                if (department_id.length === 0) {
                    alert("Empty Department ID");
                    return false;
                }
                if (joining.length === 0) {
                    alert("Empty Joining Date");
                    return false;
                }
                let url =
                    window.location.origin + sourceDir + "/get-employee-id";
                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    url: url,
                    type: "POST",
                    data: {
                        department_id: department_id,
                        company_id: company_id,
                        joining_date: joining,
                    },
                    success: function (response) {
                        if (response.status === "error") {
                            return alert(response.message);
                        }
                        if (response.status === "success") {
                            console.log(response);
                            $("#employee_id").val(response.data[1]);
                            $("#employee_id_hide").val(response.data[0]);
                        }
                    },
                    error: function (error) {
                        return alert(error.responseJSON.message);
                    },
                });
            },
            companyChangeModulePermission: function (e) {
                let cid = $(e).val();
                let uid = $("#user_id").val();
                if (cid.length === 0 || uid.length === 0) {
                    return false;
                }
                let url =
                    window.location.origin +
                    sourceDir +
                    "/company-change-module-permission";
                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    url: url,
                    type: "POST",
                    data: { cid: cid, uid: uid },
                    success: function (responses) {
                        if (responses.status === "error") {
                            alert("Error: " + responses.message);
                            setSelectBoxBlank("parentPermission");
                            setSelectBoxBlank("childPermission");
                        } else {
                            let parents = responses.data;
                            updateSelectBox(
                                parents,
                                "parentPermission",
                                "id",
                                "display_name"
                            );
                            setSelectBoxBlank("childPermission");
                        }
                    },
                });
            },
            fiendPermissionChild: function (e, actionID) {
                let ids = $(e).val();
                let company = $("#company").val();
                if (ids.length !== 0 && company.length !== 0) {
                    let url =
                        window.location.origin +
                        sourceDir +
                        "/fiend-permission-child";
                    $.ajax({
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                                "content"
                            ),
                        },
                        url: url,
                        type: "POST",
                        data: { pids: ids, company_id: company },
                        success: function (response) {
                            if (response.status === "error") {
                                alert("Error: " + response.message);
                                return false;
                            }
                            if (response.status === "success") {
                                let permissions = response.data;
                                updateSelectBox(
                                    permissions,
                                    actionID,
                                    "id",
                                    "display_name"
                                );
                                Obj.selectAllOption(e);
                                return true;
                            }
                        },
                    });
                }
            },
            removeElementOfEmployeeData: function (e, index, file) {
                employeeDatas[0].splice(index, 1);
                showModal(employeeDatas, file);
            },
            employeeDataSubmit: function (e) {
                if (employeeDatas[0].length <= 1) {
                    alert(
                        "Empty data set please upload your excel file on the input field!"
                    );
                    return false;
                }
                let url =
                    window.location.origin +
                    sourceDir +
                    "/salary-certificate-input-excel";
                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    contentType: "application/json",
                    url: url,
                    type: "POST",
                    data: JSON.stringify({ input: employeeDatas[0] }),
                    success: function (data) {
                        if (data.error) {
                            let alertMessage = data.message + "\nErrors:\n";
                            if (data.errors) {
                                for (let field in data.errors) {
                                    if (data.errors.hasOwnProperty(field)) {
                                        alertMessage +=
                                            field +
                                            ": " +
                                            data.errors[field].join(", ") +
                                            "\n";
                                    }
                                }
                            }
                            alert(alertMessage);
                            return false;
                        } else {
                            // Handle success
                            let alertMessage = "";
                            if (data.errorMessage) {
                                // Extract and display the Error Message
                                alertMessage +=
                                    "Error! This Data Are Added not Possible:\n";
                                for (let key in data.errorMessage) {
                                    let employee = data.errorMessage[key];
                                    alertMessage += `Employee ID: ${employee["Employee ID"]}, Name: ${employee.Name}, Department: ${employee.Department}\n`;
                                }
                            }
                            if (data.successMessage) {
                                // Extract and display the Success Message
                                alertMessage +=
                                    "This Data Are Added Successfully:\n";
                                for (let key in data.successMessage) {
                                    let employee = data.successMessage[key];
                                    alertMessage += `Employee ID: ${employee["Employee ID"]}, Name: ${employee.Name}, Department: ${employee.Department}\n`;
                                }
                            }
                            if (data.alreadyHasMessage) {
                                // Extract and display the alreadyHasMessage
                                alertMessage +=
                                    "This Data are Already Exists in DB:\n";
                                for (let key in data.alreadyHasMessage) {
                                    let employee = data.alreadyHasMessage[key];
                                    alertMessage += `Employee ID: ${employee["Employee ID"]}, Name: ${employee.Name}, Department: ${employee.Department}\n`;
                                }
                            }
                            alert(alertMessage);
                            window.location.reload();
                            return true;
                        }
                    },
                });
            },
            salaryDistribute: function (e) {
                let total = $("#total").val();
                if (total.length) {
                    total = parseInt(total);
                    let basic = (total * 60) / 100;
                    let house_rent = (total * 30) / 100;
                    let conveyance = (total * 5) / 100;
                    let ma = (total * 5) / 100;
                    $("#basic").val(basic).attr("readonly", "readonly");
                    $("#house_rent")
                        .val(house_rent)
                        .attr("readonly", "readonly");
                    $("#conveyance")
                        .val(conveyance)
                        .attr("readonly", "readonly");
                    $("#medical").val(ma).attr("readonly", "readonly");
                } else {
                    $("#basic").val("").removeAttr("readonly");
                    $("#house_rent").val("").removeAttr("readonly");
                    $("#conveyance").val("").removeAttr("readonly");
                    $("#medical").val("").removeAttr("readonly");
                }
            },
            userExcelFileSubmit: function (e) {
                if (employeeDatas[0].length <= 1) {
                    alert(
                        "Empty data set please upload your excel file on the input field!"
                    );
                    return false;
                }
                let url =
                    window.location.origin + sourceDir + "/add-user-excel";
                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    contentType: "application/json",
                    url: url,
                    type: "POST",
                    data: JSON.stringify({ input: employeeDatas[0] }),
                    success: function (data) {
                        if (data.error) {
                            let alertMessage = data.message + "\nErrors:\n";
                            if (data.errors) {
                                for (let field in data.errors) {
                                    if (data.errors.hasOwnProperty(field)) {
                                        alertMessage +=
                                            field +
                                            ": " +
                                            data.errors[field].join(", ") +
                                            "\n";
                                    }
                                }
                            }
                            alert(alertMessage);
                            return false;
                        } else {
                            // Handle success
                            let alertMessage = "";
                            if (data.errorMessage) {
                                // Extract and display the Error Message
                                alertMessage +=
                                    "Error! This Data Are Added not Possible:\n";
                                for (let key in data.errorMessage) {
                                    let employee = data.errorMessage[key];
                                    alertMessage += `Employee name: ${employee["Employee name"]}, Phone: ${employee["phone"]}, Email: ${employee["email"]}\n`;
                                }
                            }
                            if (data.successMessage) {
                                // Extract and display the Success Message
                                alertMessage +=
                                    "This Data Are Added Successfully:\n";
                                for (let key in data.successMessage) {
                                    let employee = data.successMessage[key];
                                    alertMessage += `Employee name: ${employee["Employee name"]}, Phone: ${employee["phone"]}, Email: ${employee["email"]}\n`;
                                }
                            }
                            if (data.alreadyHasMessage) {
                                // Extract and display the alreadyHasMessage
                                alertMessage +=
                                    "This Data are Already Exists in DB:\n";
                                for (let key in data.alreadyHasMessage) {
                                    let employee = data.alreadyHasMessage[key];
                                    alertMessage += `Employee name: ${employee["Employee name"]}, Phone: ${employee["phone"]}, Email: ${employee["email"]}\n`;
                                }
                            }
                            alert(alertMessage);
                            window.location.reload();
                            return true;
                        }
                    },
                });
            },
            permissionExcelFileSubmit: function (e) {
                if (employeeDatas[0].length <= 1) {
                    alert(
                        "Empty data set please upload your excel file on the input field!"
                    );
                    return false;
                }
                let url =
                    window.location.origin +
                    sourceDir +
                    "/system-operation/add-permission-excel";
                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    contentType: "application/json",
                    url: url,
                    type: "POST",
                    data: JSON.stringify({ input: employeeDatas[0] }),
                    success: function (data) {
                        if (data.error) {
                            let alertMessage = data.message + "\nErrors:\n";
                            if (data.errors) {
                                for (let field in data.errors) {
                                    if (data.errors.hasOwnProperty(field)) {
                                        alertMessage +=
                                            field +
                                            ": " +
                                            data.errors[field].join(", ") +
                                            "\n";
                                    }
                                }
                            }
                            alert(alertMessage);
                            return false;
                        } else {
                            // Extract and display the Success Message
                            let alertMessage = data.message;
                            alert(alertMessage);
                            window.location.reload();
                            return true;
                        }
                    },
                });
            },
            companyWiseArchiveDashboard: function (e, company_id, output_id) {
                if (company_id.length !== 0) {
                    let url =
                        window.location.origin +
                        sourceDir +
                        "/company-wise-archive-dashboard";
                    $.ajax({
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                                "content"
                            ),
                        },
                        url: url,
                        type: "POST",
                        data: { company_id: company_id },
                        success: function (response) {
                            if (response.status === "error") {
                                alert(response.message);
                                $("#" + output_id).html("");
                                return false;
                            } else {
                                $("#" + output_id).html(response.data);
                            }
                        },
                    });
                }
                return false;
            },
            dateWiseUserUplodedDocuments: function (
                e,
                company_id,
                date_range_name,
                output_id
            ) {
                let url =
                    window.location.origin +
                    sourceDir +
                    "/company-wise-archive-dashboard-date-wise";
                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    url: url,
                    type: "POST",
                    data: {
                        company_id: company_id,
                        date_range_name: date_range_name,
                    },
                    success: function (response) {
                        if (response.status === "error") {
                            alert(response.message);
                            $("#" + output_id).html("");
                            return false;
                        } else {
                            $("#" + output_id).html(response.data);
                        }
                    },
                });
            },
            companyWiseArchiveDashboardLastWeek: function (
                e,
                company_id,
                output_id
            ) {
                let url =
                    window.location.origin +
                    sourceDir +
                    "/company-wise-archive-dashboard-last_week";
                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    url: url,
                    type: "POST",
                    data: { company_id: company_id },
                    success: function (response) {
                        if (response.status === "error") {
                            alert(response.message);
                            $("#" + output_id).html("");
                            return false;
                        } else {
                            $("#" + output_id).html(response.data);
                        }
                    },
                });
            },
            companyWiseArchiveDashboardLastMonth: function (
                e,
                company_id,
                output_id
            ) {
                let url =
                    window.location.origin +
                    sourceDir +
                    "/company-wise-archive-dashboard-last_month";
                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    url: url,
                    type: "POST",
                    data: { company_id: company_id },
                    success: function (response) {
                        if (response.status === "error") {
                            alert(response.message);
                            $("#" + output_id).html("");
                            return false;
                        } else {
                            $("#" + output_id).html(response.data);
                        }
                    },
                });
            },
            companyWiseArchiveDashboardLastYear: function (
                e,
                company_id,
                output_id
            ) {
                let url =
                    window.location.origin +
                    sourceDir +
                    "/company-wise-archive-dashboard-last_year";
                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    url: url,
                    type: "POST",
                    data: { company_id: company_id },
                    success: function (response) {
                        if (response.status === "error") {
                            alert(response.message);
                            $("#" + output_id).html("");
                            return false;
                        } else {
                            $("#" + output_id).html(response.data);
                        }
                    },
                });
            },
            companyWiseArchiveDashboardAll: function (
                e,
                company_id,
                output_id
            ) {
                let url =
                    window.location.origin +
                    sourceDir +
                    "/company-wise-archive-dashboard-all";
                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    url: url,
                    type: "POST",
                    data: { company_id: company_id },
                    success: function (response) {
                        if (response.status === "error") {
                            alert(response.message);
                            $("#" + output_id).html("");
                            return false;
                        } else {
                            $("#" + output_id).html(response.data);
                        }
                    },
                });
            },
            findDocument: function (e, actionID, root_ref) {
                let id = $(e).attr("ref");
                if (id.length !== 0 && root_ref.length !== 0) {
                    let url =
                        window.location.origin +
                        sourceDir +
                        "/fiend-archive-document";
                    $.ajax({
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                                "content"
                            ),
                        },
                        url: url,
                        type: "POST",
                        data: { id: id,ref_id: root_ref },
                        success: function (response) {
                            if (response.status === "error")
                            {
                                alert("Error: "+ response.message)
                            }
                            else if (response.status === "success") {
                                $("#"+actionID).html(response.data)
                                $("#staticBackdrop").modal("show");
                            }
                        },
                    });
                }
                return false;

            },
            fileSharingModal: function (e) {
                let id = $(e).attr("ref");
                let url =
                    window.location.origin +
                    sourceDir +
                    "/fiend-archive-document-info";
                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    url: url,
                    type: "POST",
                    data: { id: id },
                    success: function (data) {
                        if (data.error) {
                            alert(data.error.msg);
                        } else {
                            while (tags.length > 0) {
                                tags.pop();
                            }
                            $("#model_dialog").html(data);
                            $("#shareModel").modal("show");
                        }
                    },
                });
                return false;
            },
            archiveShare: function (e) {
                let id = $(e).attr("ref");
                let url =
                    window.location.origin + sourceDir + "/share-archive-fiend";
                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    url: url,
                    type: "POST",
                    data: { id: id },
                    success: function (response) {
                        if (response.status === "success") {
                            while (tags.length > 0) {
                                tags.pop(); //update global veriable value as null
                            }
                            $("#model_dialog").html(response.data);
                            $("#shareModel").modal("show");
                        } else {
                            alert("Error: " + response.message);
                        }
                        return false;
                    },
                });
                return false;
            },

            shareArchiveMultiple: function (e) {
                let selected = [];
                $(".check-box:checked").each(function () {
                    selected.push($(this).val());
                });
                let url =
                    window.location.origin +
                    sourceDir +
                    "/share-archive-fiend-multiple";
                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    url: url,
                    type: "POST",
                    data: { ids: selected },
                    success: function (response) {
                        if (response.status === "success") {
                            while (tags.length > 0) {
                                tags.pop(); //update global veriable value as null
                            }
                            $("#model_dialog").html(response.data);
                            $("#shareModel").modal("show");
                        } else {
                            alert("Error: " + response.message);
                        }
                        return false;
                    },
                });
                return false;
            },
            voucherDocumentIndividual: function (e) {
                let id = $(e).attr("ref");
                let url =
                    window.location.origin +
                    sourceDir +
                    "/fiend-archive-document-info";
                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    url: url,
                    type: "POST",
                    data: { id: id },
                    success: function (data) {
                        if (data.error) {
                            alert(data.error.msg);
                        } else {
                            while (tags.length > 0) {
                                tags.pop();
                            }
                            $("#model_dialog").html(data);
                            $("#shareModel").modal("show");
                        }
                    },
                });
                return false;
            },
            addArchiveDocumentIndividual: function (e) {
                let id = $(e).attr("ref");
                let url =
                    window.location.origin +
                    sourceDir +
                    "/add-archive-document-individual";
                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    url: url,
                    type: "POST",
                    data: { id: id },
                    success: function (data) {
                        if (data.error) {
                            alert(data.error.msg);
                        } else {
                            $("#model_dialog").html(data);
                            $("#shareModel").modal("show");
                        }
                    },
                });
                return false;
            },
            closeSharingModel: function (e) {
                while (tags.length > 0) {
                    tags.pop();
                }
            },
            tagInput: function (event) {
                const value = $(event).val();
                const regex = /\s|,/;
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                if (regex.test(value)) {
                    const leanedData = value.replace(/[\s,]/g, "");
                    if (emailRegex.test(leanedData)) {
                        const tagValue = leanedData.trim();
                        const tag = $("<div>")
                            .addClass("tag")
                            .attr("onclick", "return Obj.removeTag(this)")
                            .text(tagValue + " 🗙");
                        $("#tags").append(tag);
                        $("#tag-input").val("");
                        tags.push(tagValue);
                    }
                }
            },
            removeTag: function (event) {
                const tagValue = $(event).text();
                const index = tags.indexOf(tagValue);
                if (index !== -1) {
                    tags.splice(index, 1);
                }
                $(event).remove();
            },
            archiveShareType: function (e) {
                let value = $(e).val();
                let refId = $(e).attr("ref");
                if (value.length > 0) {
                    let url =
                        window.location.origin +
                        sourceDir +
                        "/archive-share-type";
                    $.ajax({
                        url: url,
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                                "content"
                            ),
                        },
                        data: { value: value, refId: refId },
                        success: function (data) {
                            $("#sharedLink").val(data);
                        },
                    });
                }
            },
            copyDocumentShareLink: function (e) {
                let sharedLink = $("#sharedLink");
                if (sharedLink.val().length <= 0) {
                    return false;
                } else {
                    sharedLink.select();
                    try {
                        // Execute the copy command
                        document.execCommand("copy");
                        $(e).html(
                            '<i class="fa-solid fa-clipboard"></i> Copied!'
                        );
                        // $(e).remove('class','btn-success')
                        $(e).addClass("btn-info");
                    } catch (err) {
                        alert("Unable to copy link:" + err);
                    }
                }
            },
            sendDocumentEmail: function (e) {
                const url =
                    window.location.origin +
                    sourceDir +
                    "/share-archive-document-email";
                const refId = $(e).attr("ref");
                const message = $("#message").val();
                // const data = { tags: tags, refId: refId };
                if (tags.length <= 0) {
                    alert("Error! Empty Field");
                    return false;
                } else {
                    $.ajax({
                        url: url,
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                                "content"
                            ),
                        },
                        data: { tags: tags, refId: refId, message: message },
                        success: function (data) {
                            if (data.error) {
                                alert(data.error.msg);
                                return false;
                            } else {
                                data = JSON.parse(data);
                                alert(data.results);
                                if (data.results)
                                    $("#shareModel").modal("hide");
                                else return false;
                            }
                        },
                        error: function (error) {
                            console.error("Error:", error);
                        },
                    });
                }
            },
            sendArchiveEmail: function (e) {
                const url =
                    window.location.origin + sourceDir + "/share-archive-email";
                const refId = $(e).attr("ref");
                const message = $("#message").val();
                // const data = { tags: tags, refId: refId };
                if (tags.length <= 0) {
                    alert("Error! Empty Field");
                    return false;
                } else {
                    $.ajax({
                        url: url,
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                                "content"
                            ),
                        },
                        data: { tags: tags, refId: refId, message: message },
                        success: function (data) {
                            if (data.error) {
                                alert(data.error.msg);
                                return false;
                            } else {
                                data = JSON.parse(data);
                                alert(data.results);
                                if (data.results)
                                    $("#shareModel").modal("hide");
                                else return false;
                            }
                        },
                        error: function (error) {
                            console.error("Error:", error);
                        },
                    });
                }
            },
            sendArchiveEmailMultiple: function (e) {
                let ids = $(e).data("ids");
                const url =
                    window.location.origin +
                    sourceDir +
                    "/share-archive-email-multiple";
                const message = $("#message").val();
                // const data = { tags: tags, refId: refId };
                if (tags.length <= 0) {
                    alert("Error! Empty Field");
                    return false;
                } else {
                    $.ajax({
                        url: url,
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                                "content"
                            ),
                        },
                        data: { tags: tags, ids: ids, message: message },
                        success: function (response) {
                            if (response.status === "success") {
                                alert(response.message);
                                $("#shareModel").modal("hide");
                                return true;
                            } else {
                                alert("Error: " + response.message);
                                return false;
                            }
                        },
                        error: function (error) {
                            console.error("Error:", error);
                        },
                    });
                }
            },
            emailLinkStatusChange: function (e) {
                const ref = $(e).attr("ref");
                const status = $(e).attr("status");
                const url =
                    window.location.origin +
                    sourceDir +
                    "/email-link-status-change";
                $.ajax({
                    url: url,
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    data: { ref: ref, status: status },
                    success: function (data) {
                        if (data.error) {
                            alert(data.error.msg);
                        } else {
                            data = JSON.parse(data);
                            alert(data.results);
                            $("#shareModel").modal("hide");
                        }
                    },
                    error: function (error) {
                        console.error("Error:", error);
                    },
                });
            },
            archiveEmailLinkStatusChange: function (e) {
                const ref = $(e).attr("ref");
                const status = $(e).attr("status");
                const url =
                    window.location.origin +
                    sourceDir +
                    "/archive-email-link-status-change";
                $.ajax({
                    url: url,
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    data: { ref: ref, status: status },
                    success: function (data) {
                        if (data.error) {
                            alert(data.error.msg);
                        } else {
                            data = JSON.parse(data);
                            alert(data.results);
                            $("#shareModel").modal("hide");
                        }
                    },
                    error: function (error) {
                        console.error("Error:", error);
                    },
                });
            },
            getFixedAssetSpecification: function (e) {
                const value = $(e).val();
                const url =
                    window.location.origin +
                    sourceDir +
                    "/fixed-asset-distribution/get-fixed-asset-spec";
                $.ajax({
                    url: url,
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    data: { id: value },
                    success: function (response) {
                        if (response.status === "success") {
                            fixedAsset = response.data[0].fixed_asset;
                            updateSelectBoxSingleOption(
                                response.data,
                                "specification",
                                "id",
                                "specification"
                            );
                            $("#rate").val(fixedAsset.rate);
                            $("#unite").val(fixedAsset.unit);
                            $("#qty").val(1);
                            $("#total").val(fixedAsset.rate);
                        } else if (response.status === "error") {
                            alert("Error:" + response.message);
                        }
                    },
                    error: function (xhr) {
                        // Handle general AJAX errors
                        console.log("AJAX Error:", xhr.statusText);
                    },
                });
            },
            fixedAssetOpeningAddList: function (e) {
                const opdate = $("#date").val();
                const company_id = $("#company_id_hide").val();
                const reference = $("#ref_hide").val();
                const r_type = $("#r_type_id_hide").val();
                const project_id = $("#project_id_hide").val();
                const materials_id = $("#materials_id").val();
                const specification = $("#specification").val();
                const rate = $("#rate").val();
                const qty = $("#qty").val();
                const purpose = $("#purpose").val();
                const remarks = $("#remarks").val();
                if (
                    opdate.length === 0 ||
                    company_id.length === 0 ||
                    reference.length === 0 ||
                    project_id.length === 0 ||
                    materials_id.length === 0 ||
                    specification.length === 0 ||
                    rate.length === 0 ||
                    qty.length === 0 ||
                    r_type.length === 0
                ) {
                    alert("All field are required");
                    return false;
                }
                const url =
                    window.location.origin +
                    sourceDir +
                    "/fixed-asset-distribution/add-fixed-asset-opening";
                $.ajax({
                    url: url,
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    method: "POST",
                    data: {
                        opening_date: opdate,
                        company_id: company_id,
                        reference: reference,
                        r_type: r_type,
                        project_id: project_id,
                        materials_id: materials_id,
                        specification: specification,
                        rate: rate,
                        qty: qty,
                        purpose: purpose,
                        remarks: remarks,
                    },
                    success(response) {
                        if (response.status === "success") {
                            // $('#opening-materials-list').html(response.data)
                            $("#load-view").html(response.data);
                        } else if (response.status === "warning") {
                            alert("Warning: " + response.message);
                            console.log("Error:", response);
                        } else if (response.status === "error") {
                            alert("Error: " + response.message);
                            // Handle error
                            console.log("Error:", response);
                        }
                    },
                    error(xhr) {
                        // Handle general AJAX errors
                        console.log("AJAX Error:", xhr.statusText);
                    },
                });
            },
            fixedAssetOpeningEditList: function (e) {
                const opdate = $("#date").val();
                const company_id = $("#company_id_hide").val();
                const reference = $("#ref_hide").val();
                const r_type = $("#r_type_id_hide").val();
                const project_id = $("#project_id_hide").val();
                const materials_id = $("#materials_id").val();
                const specification = $("#specification").val();
                const rate = $("#rate").val();
                const qty = $("#qty").val();
                const purpose = $("#purpose").val();
                const remarks = $("#remarks").val();
                if (
                    opdate.length === 0 ||
                    company_id.length === 0 ||
                    reference.length === 0 ||
                    project_id.length === 0 ||
                    materials_id.length === 0 ||
                    specification.length === 0 ||
                    rate.length === 0 ||
                    qty.length === 0 ||
                    r_type.length === 0
                ) {
                    alert("All field are required");
                    return false;
                }
                const url =
                    window.location.origin +
                    sourceDir +
                    "/fixed-asset-distribution/edit-fixed-asset-opening";
                $.ajax({
                    url: url,
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    method: "POST",
                    data: {
                        opening_date: opdate,
                        company_id: company_id,
                        reference: reference,
                        r_type: r_type,
                        project_id: project_id,
                        materials_id: materials_id,
                        specification: specification,
                        rate: rate,
                        qty: qty,
                        purpose: purpose,
                        remarks: remarks,
                    },
                    success(response) {
                        if (response.status === "success") {
                            // $('#opening-materials-list').html(response.data)
                            $("#opening-materials-list").html(response.data);
                        } else if (response.status === "warning") {
                            alert("Warning: " + response.message);
                            console.log("Error:", response);
                        } else if (response.status === "error") {
                            alert("Error: " + response.message);
                            // Handle error
                            console.log("Error:", response);
                        }
                    },
                    error(xhr) {
                        // Handle general AJAX errors
                        console.log("AJAX Error:", xhr.statusText);
                    },
                });
            },
            fixedAssetGpAddList: function (e) {
                const gp_date = $("#gp_date_hidden").val();
                const from_company_id = $("#from_company_id_hide").val();
                const to_company_id = $("#to_company_id_hide").val();
                const gp_reference = $("#gp_ref_hide").val();
                const from_project_id = $("#from_project_id_hide").val();
                const to_project_id = $("#to_project_id_hide").val();
                const materials_id = $("#materials_id").val();
                const specification = $("#specification").val();
                const rate = $("#rate").val();
                const qty = $("#qty").val();
                const stock = $("#stock").val();
                const purpose = $("#purpose").val();
                const remarks = $("#remarks").val();
                if (
                    gp_date.length === 0 ||
                    from_company_id.length === 0 ||
                    to_company_id.length === 0 ||
                    gp_reference.length === 0 ||
                    from_project_id.length === 0 ||
                    to_project_id.length === 0 ||
                    materials_id.length === 0 ||
                    specification.length === 0 ||
                    rate.length === 0 ||
                    qty.length === 0 ||
                    stock.length === 0
                ) {
                    alert("All field are required");
                    return false;
                }
                if (parseFloat(qty) === 0) {
                    qty.val(0);
                    return false;
                }
                if (parseFloat(stock) <= 0) {
                    qty.val(0);
                    alert("Not within the stock quantity.");
                    return false;
                }
                if (parseFloat(stock) < parseFloat(qty)) {
                    alert("Quantity can't gather then Stock Blanch");
                    return false;
                }
                const url =
                    window.location.origin +
                    sourceDir +
                    "/fixed-asset-distribution/add-to-list-fixed-asset-gp";
                $.ajax({
                    url: url,
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    method: "POST",
                    data: {
                        gp_date: gp_date,
                        from_company_id: from_company_id,
                        to_company_id: to_company_id,
                        reference: gp_reference,
                        from_project_id: from_project_id,
                        to_project_id: to_project_id,
                        materials_id: materials_id,
                        specification: specification,
                        rate: rate,
                        qty: qty,
                        stock: stock,
                        purpose: purpose,
                        remarks: remarks,
                    },
                    success(response) {
                        if (response.status === "success") {
                            // $('#opening-materials-list').html(response.data)
                            $("#materials-list").html(response.data.view);
                        } else if (response.status === "warning") {
                            alert("Warning: " + response.message);
                            console.log("Error:", response);
                        } else if (response.status === "error") {
                            alert("Error: " + response.message);
                            // Handle error
                            console.log("Error:", response);
                        }
                    },
                    error(xhr) {
                        // Handle general AJAX errors
                        console.log("AJAX Error:", xhr.statusText);
                    },
                });
            },
            fixedAssetTransferSpecEdit: function (e) {
                const id = $(e).attr("ref");
                if (id.length === 0) return false;
                const url =
                    window.location.origin +
                    sourceDir +
                    "/fixed-asset-distribution/edit-fixed-asset-transfer-spec";
                $.ajax({
                    url: url,
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    data: { id: id },
                    success: function (response) {
                        if (response.status === "success") {
                            const view = response.data.view;
                            $("#fixed-asset-spec-edit").html(view);
                            $("#editModal").modal("show");
                        } else if (response.status === "warning") {
                            alert("Warning: " + response.message);
                            console.log("Error:", response);
                        } else if (response.status === "error") {
                            alert("Error: " + response.message);
                            // Handle error
                            console.log("Error:", response);
                        }
                    },
                });
            },
            fixedAssetTransferSpecUpdate: function (e) {
                const gp_date = $("#edit-date").val();
                const id = $("#edit-id").val();
                const rate = $("#rate-edit").val();
                const qty = $("#qty-edit").val();
                const purpose = $("#edit-purpose").val();
                const remarks = $("#edit-remarks").val();
                if (
                    id.length === 0 ||
                    gp_date.length === 0 ||
                    rate.length === 0 ||
                    qty.length === 0
                )
                    return false;
                const url =
                    window.location.origin +
                    sourceDir +
                    "/fixed-asset-distribution/update-fixed-asset-transfer-spec";
                $.ajax({
                    url: url,
                    method: "PUT",
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    data: {
                        id: id,
                        gp_date: gp_date,
                        rate: rate,
                        qty: qty,
                        purpose: purpose,
                        remarks: remarks,
                    },
                    success: function (response) {
                        if (response.status === "success") {
                            const view = response.data.view;
                            alert(response.message);
                            $("#editModal").modal("hide");
                            $("#materials-list").html(view);
                        } else if (response.status === "warning") {
                            alert("Warning: " + response.message);
                            console.log("Error:", response);
                        } else if (response.status === "error") {
                            alert("Error: " + response.message);
                            // Handle error
                            console.log("Error:", response);
                        }
                    },
                });
            },
            deleteFixedAssetTransferSpec: function (e) {
                if (confirm("Are you sure delete this data?")) {
                    const id = $(e).attr("ref");
                    if (id.length === 0) return false;
                    const url =
                        window.location.origin +
                        sourceDir +
                        "/fixed-asset-distribution/delete-fixed-asset-transfer-spec";
                    $.ajax({
                        url: url,
                        method: "DELETE",
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                                "content"
                            ),
                        },
                        data: { id: id },
                        success: function (response) {
                            if (response.status === "success") {
                                alert(response.message);
                                const view = response.data.view;
                                $("#materials-list").html(view);
                            }
                            if (response.status === "error") {
                                alert("Error:" + response.message);
                                // Handle error
                            }
                            return false;
                        },
                    });
                }
            },
            gpFinalUpdate: function (e) {
                // Validate required fields
                if ($("#gp_ref_hide").val().length === 0) {
                    alert("Reference is required!");
                    return false;
                }

                // Build FormData object
                const formData = new FormData();
                formData.append("gp_date", $("#gp_date_hidden").val());
                formData.append(
                    "from_company",
                    $("#from_company_id_hide").val()
                );
                formData.append("to_company", $("#to_company_id_hide").val());
                formData.append(
                    "from_project",
                    $("#from_project_id_hide").val()
                );
                formData.append("to_project", $("#to_project_id_hide").val());
                formData.append("reference", $("#gp_ref_hide").val());
                formData.append("narration", $("#narration").val());

                // Append multiple files (if any)
                const files = $("#attachments")[0].files;
                if (files.length > 0) {
                    for (let i = 0; i < files.length; i++) {
                        formData.append(`attachments[${i}]`, files[i]);
                    }
                }

                // Prepare URL
                const url =
                    window.location.origin +
                    sourceDir +
                    "/fixed-asset-distribution/final-update-fixed-asset-transfer";

                // AJAX request
                $.ajax({
                    url: url,
                    method: "post", // Change to POST for better compatibility
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        if (response.status === "success") {
                            alert(response.message);
                            window.location.reload();
                        } else if (response.status === "error") {
                            alert("Error: " + response.message);
                            // Additional error handling
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("AJAX Error:", status, error);
                        alert("An error occurred. Please try again.");
                    },
                });
            },
            deleteFixedAssetTransferDocument: function (e) {
                if (confirm("Are you sure to delete this data?")) {
                    const id = $(e).attr("ref");
                    if (id.length === 0) {
                        return false;
                    }
                    const url =
                        window.location.origin +
                        sourceDir +
                        "/fixed-asset-distribution/delete-fixed-asset-transfer-document";
                    $.ajax({
                        url: url,
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                                "content"
                            ),
                        },
                        method: "DELETE",
                        data: { id: id },
                        success: function (response) {
                            if (response.status === "success") {
                                alert(response.message);
                                window.location.reload();
                            }
                            if (response.status === "error") {
                                alert("Error:" + response.message);
                                // Handle error
                            }
                            return false;
                        },
                    });
                }
                return false;
            },
            priceTotal: function (e, inputID, actionID) {
                const input = parseFloat($("#" + inputID).val());
                const output = $("#" + actionID);
                const self = parseFloat($(e).val());
                if (input.length === 0 || self.length === 0) {
                    output.val("");
                }
                output.val(parseFloat(Number(input * self)));
            },
            priceTotalForTransfer: function (
                e,
                actionID,
                qty_id,
                stock_id,
                rate_id
            ) {
                const output = $("#" + actionID);
                let qty = parseFloat($("#" + qty_id).val());
                const old_stock = parseFloat($("#" + stock_id).attr("ref"));
                const stock = parseFloat($("#" + stock_id).val());
                const rate = parseFloat($("#" + rate_id).val());
                const total_stock = parseFloat(stock + old_stock);
                if (qty.length === 0 || rate.length === 0) {
                    output.val("");
                }
                if (total_stock < qty) {
                    $("#qty").val(total_stock);
                    $("#qty-edit").val(total_stock);
                    qty = total_stock;
                    alert("Quantity can't gather then Stock Balance");
                }
                output.val(parseFloat(Number(qty * rate)));
            },
            fixedAssetOpeningSearch: function (e, outputID) {
                const company_id = $("#company").val();
                const reference = $("#ref-src").val();
                const project = $("#project").val();
                const rt = $("#r_type").val();
                if (
                    company_id.length === 0 &&
                    reference.length === 0 &&
                    project.length === 0 &&
                    rt.length === 0
                ) {
                    alert("All Input are Required!");
                    return false;
                }
                const url =
                    window.location.origin +
                    sourceDir +
                    "/fixed-asset-distribution/get-fixed-asset-opening";
                $.ajax({
                    url: url,
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    data: {
                        company_id: company_id,
                        reference: reference,
                        branch_id: project,
                        r_type_id: rt,
                    },
                    success: function (response) {
                        if (response.status === "success") {
                            $("#" + outputID).html(response.data);
                            // Get the current URL
                            var currentUrl = window.location.href;
                            // Construct the new URL by appending the ref value
                            var newUrl =
                                currentUrl.split("?")[0] +
                                "?ref=" +
                                reference +
                                "&project=" +
                                project +
                                "&rt=" +
                                rt +
                                "&c=" +
                                company_id;
                            // Change the URL without reloading the page
                            history.pushState({ ref: reference }, "", newUrl);
                        } else if (response.status === "warning") {
                            alert("Warning:" + response.message);
                            $("#" + outputID).html("");
                        } else if (response.status === "error") {
                            alert("Error:" + response.message);
                            // Handle error
                            console.log("Error:", response.message);
                        }
                    },
                    error: function (xhr) {
                        console.log("AJAX Error:", xhr.statusText);
                    },
                });
            },
            gpEntrySearch: function (e, outputID) {
                const from_company_id = $("#from_company").val();
                const to_company_id = $("#to_company").val();
                const reference = $("#gp_ref").val();
                const from_project = $("#from_project").val();
                const to_project = $("#to_project").val();
                const gp_date = $("#gp_date").val();
                if (
                    from_company_id.length === 0 &&
                    to_company_id.length === 0 &&
                    reference.length === 0 &&
                    from_project.length === 0 &&
                    to_project.length === 0
                ) {
                    alert("All Input are Empty!");
                    return false;
                }
                const url =
                    window.location.origin +
                    sourceDir +
                    "/fixed-asset-distribution/gp-create";
                $.ajax({
                    url: url,
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    data: {
                        from_company_id: from_company_id,
                        to_company_id: to_company_id,
                        from_branch_id: from_project,
                        to_branch_id: to_project,
                        gp_date: gp_date,
                        gp_reference: reference,
                    },
                    success: function (response) {
                        if (response.status === "success") {
                            let ref = reference;
                            let date = gp_date;
                            let f_p = from_project;
                            let t_p = to_project;
                            let f_c = from_company_id;
                            let t_c = to_company_id;
                            let data = response.data.data;
                            let view = response.data.view;
                            if (typeof data !== "undefined" && data !== null) {
                                ref = data.reference;
                                date = data.date;
                                f_c = data.from_company_id;
                                t_c = data.to_company_id;
                                f_p = data.from_project_id;
                                t_p = data.to_project_id;
                            }
                            if (ref.length === 0) {
                                date = null;
                            }
                            // Get the current URL
                            var currentUrl = window.location.href;
                            // Construct the new URL by appending the ref value
                            var newUrl =
                                currentUrl.split("?")[0] +
                                "?ref=" +
                                ref +
                                "&from_p=" +
                                f_p +
                                "&to_p=" +
                                t_p +
                                "&d=" +
                                date +
                                "&from_c=" +
                                f_c +
                                "&to_c=" +
                                t_c;
                            $("#gp_date").val(date);
                            // Change the URL without reloading the page
                            history.pushState({ ref: reference }, "", newUrl);
                            $("#" + outputID).html(view);
                        } else if (response.status === "warning") {
                            alert("Warning:" + response.message);
                            // $("#"+outputID).html('')
                        } else if (response.status === "error") {
                            alert("Error:" + response.message);
                            $("#" + outputID).html("");
                            // Handle error
                            // console.log('Error:', response.message)
                        }
                    },
                    error: function (xhr) {
                        console.log("AJAX Error:", xhr.statusText);
                    },
                });
            },
            gpMaterialsSpecificationSearch: function (e, outputID) {
                const from_company_id = $("#from_company").val();
                const from_project = $("#from_project").val();
                const materials_id = $(e).val();
                if (
                    from_company_id.length === 0 &&
                    from_project.length === 0 &&
                    materials_id.length === 0
                ) {
                    alert("All Input are Empty!");
                    return false;
                }
                const url =
                    window.location.origin +
                    sourceDir +
                    "/fixed-asset-distribution/material-specification-search";
                $.ajax({
                    url: url,
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    data: {
                        from_company_id: from_company_id,
                        from_branch_id: from_project,
                        materials_id: materials_id,
                    },
                    success: function (response) {
                        if (response.status === "success") {
                            updateSelectBoxSingleOption(
                                response.data,
                                outputID,
                                "id",
                                "specification"
                            );
                            $("#unite").val(response.data[0].fixed_asset.unit);
                        } else if (response.status === "warning") {
                            alert("Warning:" + response.message);
                        } else if (response.status === "error") {
                            alert("Error:" + response.message);
                        }
                    },
                    error: function (xhr) {
                        console.log("AJAX Error:", xhr.statusText);
                    },
                });
            },
            deleteFixedAssetRunningTransfer: function (e) {
                if (confirm("Are you sure to delete this data?")) {
                    const id = $(e).attr("ref");
                    if (id.length === 0) {
                        return false;
                    }
                    const url =
                        window.location.origin +
                        sourceDir +
                        "/fixed-asset-distribution/delete-fixed-asset-transfer";
                    $.ajax({
                        url: url,
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                                "content"
                            ),
                        },
                        method: "DELETE",
                        data: { id: id },
                        success: function (response) {
                            if (response.status === "success") {
                                alert(response.message);
                                const view = response.data;
                                window.location.reload();
                            }
                            if (response.status === "error") {
                                alert("Error:" + response.message);
                                // Handle error
                            }
                            return false;
                        },
                    });
                }
                return false;
            },
            gpMaterialsSpecificationWiseStockAndRateSearch: function (
                e,
                outputID
            ) {
                const from_company_id = $("#from_company").val();
                const from_project = $("#from_project").val();
                const materials_id = $("#materials_id").val();
                const spec_id = $(e).val();
                if (
                    from_company_id.length === 0 &&
                    from_project.length === 0 &&
                    materials_id.length === 0 &&
                    spec_id.length === 0
                ) {
                    alert("All Input are Empty!");
                    return false;
                }
                const url =
                    window.location.origin +
                    sourceDir +
                    "/fixed-asset-distribution/material-specification-wise-stock-rate-search";
                $.ajax({
                    url: url,
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    data: {
                        from_company_id: from_company_id,
                        from_branch_id: from_project,
                        materials_id: materials_id,
                        spec_id: spec_id,
                    },
                    success: function (response) {
                        if (response.status === "success") {
                            $("#rate").val(response.data.rate);
                            $("#stock").val(response.data.stock);
                            $("#qty").val("");
                            $("#total").val("");
                            // console.log(response.data)
                        } else if (response.status === "warning") {
                            alert("Warning:" + response.message);
                            $("#rate").val("");
                            $("#stock").val("");
                            $("#qty").val("");
                            $("#total").val("");
                        } else if (response.status === "error") {
                            alert("Error:" + response.message);
                            $("#rate").val("");
                            $("#stock").val("");
                            $("#qty").val("");
                            $("#total").val("");
                        }
                    },
                    error: function (xhr) {
                        console.log("AJAX Error:", xhr.statusText);
                    },
                });
            },
            fixedAssetOpeningSpecEdit: function (e) {
                const id = $(e).attr("ref");
                if (id.length === 0) return false;
                const url =
                    window.location.origin +
                    sourceDir +
                    "/fixed-asset-distribution/edit-fixed-asset-opening-spec";
                $.ajax({
                    url: url,
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    data: { id: id },
                    success: function (response) {
                        const view = response.data;
                        $("#fixed-asset-spec-edit").html(view);
                        $("#editModal").modal("show");
                    },
                });
            },
            updateFixedAssetOpeningSpec: function (e) {
                const opdate = $("#edit-date").val();
                const id = $("#edit-id").val();
                const rate = $("#rate-edit").val();
                const qty = $("#qty-edit").val();
                const purpose = $("#edit-purpose").val();
                const remarks = $("#edit-remarks").val();
                if (
                    opdate.length === 0 ||
                    id.length === 0 ||
                    rate.length === 0 ||
                    qty.length === 0
                ) {
                    alert("Error: Empty Field Error!");
                    return false;
                }
                const url =
                    window.location.origin +
                    sourceDir +
                    "/fixed-asset-distribution/update-fixed-asset-opening-spec";
                $.ajax({
                    url: url,
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    data: {
                        id: id,
                        opening_date: opdate,
                        rate: rate,
                        qty: qty,
                        purpose: purpose,
                        remarks: remarks,
                    },
                    success: function (response) {
                        const view = response.data;
                        if (response.status === "success") {
                            alert(response.message);
                            $("#editModal").modal("hide");
                            $("#opening-materials-list").html(view);
                        }
                        if (response.status === "error") {
                            alert("Error:" + response.message);
                            // Handle error
                        }
                        return false;
                    },
                    error: function (xhr) {
                        console.log("AJAX Error:", xhr.statusText);
                    },
                });
            },
            deleteFixedAssetOpeningSpec: function (e) {
                if (confirm("Are you sure delete this data?")) {
                    const id = $(e).attr("ref");
                    if (id.length === 0) return false;
                    const url =
                        window.location.origin +
                        sourceDir +
                        "/fixed-asset-distribution/delete-fixed-asset-opening-spec";
                    $.ajax({
                        url: url,
                        method: "DELETE",
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                                "content"
                            ),
                        },
                        data: { id: id },
                        success: function (response) {
                            if (response.status === "success") {
                                alert(response.message);
                                const view = response.data;
                                $("#opening-materials-list").html(view);
                            }
                            if (response.status === "error") {
                                alert("Error:" + response.message);
                                // Handle error
                            }
                            return false;
                        },
                    });
                }
            },
            userProjectPermissionSearch: function (e) {
                const value = $("#user").val();
                const company_id = $("#company").val();
                if (value.length === 0) {
                    return false;
                }
                const url =
                    window.location.origin +
                    sourceDir +
                    "/control-panel/user-project-permission-search";
                $.ajax({
                    url: url,
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    data: { user: value, company_id: company_id },
                    success: function (response) {
                        if (response.status === "success") {
                            // alert(response.message)
                            const view = response.data;
                            $("#user-project-permission-add-list").html(view);
                        }
                        if (response.status === "error") {
                            alert("Error:" + response.message);
                            // Handle error
                        }
                        return false;
                    },
                });
            },
            userProjectPermissionAdd: function (e) {
                const project_id = $("#project").val();
                const user_id = $("#user_id").val();
                const company_id = $("#company_id").val();
                if (project_id.length === 0 || user_id.length === 0) {
                    alert("All filed are required");
                    return false;
                }
                const url =
                    window.location.origin +
                    sourceDir +
                    "/control-panel/user-project-permission-add";
                $.ajax({
                    url: url,
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    method: "POST",
                    data: {
                        project_id: project_id,
                        user_id: user_id,
                        company_id: company_id,
                    },
                    success: function (response) {
                        if (response.status === "success") {
                            alert(response.message);
                            const view = response.data;
                            $("#user-permission-list").html(view);
                        }
                        if (response.status === "error") {
                            alert("Error:" + response.message);
                            // Handle error
                        }
                        return false;
                    },
                });
            },
            userProjectPermissionCopy: function (e) {
                if (confirm("Are you sure to copy all permissions?")) {
                    const copy_user = $("#copy_user").val();
                    const user_id = $("#user_id").val();
                    const company_id = $("#company_id").val();
                    if (copy_user.length === 0 || user_id.length === 0) {
                        alert("All filed are required");
                        return false;
                    }
                    const url =
                        window.location.origin +
                        sourceDir +
                        "/control-panel/user-project-permission-copy";
                    $.ajax({
                        url: url,
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                                "content"
                            ),
                        },
                        method: "POST",
                        data: {
                            copy_user_id: copy_user,
                            user_id: user_id,
                            company_id: company_id,
                        },
                        success: function (response) {
                            if (response.status === "success") {
                                alert(response.message);
                                const view = response.data;
                                $("#user-permission-list").html(view);
                            }
                            if (response.status === "error") {
                                alert("Error:" + response.message);
                            }
                            return false;
                        },
                    });
                }
            },
            userProjectPermissionAddAll: function (e) {
                if (confirm("Are you sure add all project permission?")) {
                    const user_id = $("#user_id").val();
                    const company_id = $("#company_id").val();
                    if (user_id.length === 0 || company_id.length === 0) {
                        alert("All filed are required");
                        return false;
                    }
                    const url =
                        window.location.origin +
                        sourceDir +
                        "/control-panel/user-project-permission-add-all";
                    $.ajax({
                        url: url,
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                                "content"
                            ),
                        },
                        method: "POST",
                        data: { user_id: user_id, company_id: company_id },
                        success: function (response) {
                            if (response.status === "success") {
                                alert(response.message);
                                const view = response.data;
                                $("#user-permission-list").html(view);
                            }
                            if (response.status === "error") {
                                alert("Error:" + response.message);
                                // Handle error
                            }
                            return false;
                        },
                    });
                }
                return false;
            },
            userProjectPermissionDelete: function (e) {
                if (confirm("Are you sure delete this data?")) {
                    const value = $(e).attr("ref");
                    const company_id = $("#company_id").val();
                    if (value.length === 0 || company_id.length === 0) {
                        return false;
                    }
                    const url =
                        window.location.origin +
                        sourceDir +
                        "/control-panel/user-project-permission-delete";
                    $.ajax({
                        url: url,
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                                "content"
                            ),
                        },
                        method: "POST",
                        data: { value: value, company_id: company_id },
                        success: function (response) {
                            if (response.status === "success") {
                                alert(response.message);
                                const view = response.data;
                                $("#user-permission-list").html(view);
                            }
                            if (response.status === "error") {
                                alert("Error:" + response.message);
                                // Handle error
                            }
                            return false;
                        },
                    });
                }
                return false;
            },
            userProjectPermissionDeleteAll: function (e) {
                if (
                    confirm(
                        "Are you sure to delete all permission for this user?"
                    )
                ) {
                    const user_id = $("#user_id").val();
                    const company_id = $("#company_id").val();
                    if (user_id.length === 0 || company_id.length === 0) {
                        return false;
                    }
                    const url =
                        window.location.origin +
                        sourceDir +
                        "/control-panel/user-project-permission-delete-all";
                    $.ajax({
                        url: url,
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                                "content"
                            ),
                        },
                        method: "POST",
                        data: { user_id: user_id, company_id: company_id },
                        success: function (response) {
                            if (response.status === "success") {
                                alert(response.message);
                                const view = response.data;
                                $("#user-permission-list").html(view);
                            }
                            if (response.status === "error") {
                                alert("Error:" + response.message);
                                // Handle error
                            }
                            return false;
                        },
                    });
                }
                return false;
            },
            deleteFixedAssetOpening: function (e) {
                if (confirm("Are you sure to delete this data?")) {
                    const id = $(e).attr("ref");
                    if (id.length === 0) {
                        return false;
                    }
                    const url =
                        window.location.origin +
                        sourceDir +
                        "/fixed-asset-distribution/delete-fixed-asset-opening";
                    $.ajax({
                        url: url,
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                                "content"
                            ),
                        },
                        method: "DELETE",
                        data: { id: id },
                        success: function (response) {
                            if (response.status === "success") {
                                alert(response.message);
                                const view = response.data;
                                window.location.reload();
                            }
                            if (response.status === "error") {
                                alert("Error:" + response.message);
                                // Handle error
                            }
                            return false;
                        },
                    });
                }
                return false;
            },
            deleteFixedAssetWithRefDocument: function (e) {
                if (confirm("Are you sure to delete this data?")) {
                    const id = $(e).attr("ref");
                    if (id.length === 0) {
                        return false;
                    }
                    const url =
                        window.location.origin +
                        sourceDir +
                        "/fixed-asset-distribution/delete-fixed-asset-with-ref-document";
                    $.ajax({
                        url: url,
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                                "content"
                            ),
                        },
                        method: "DELETE",
                        data: { id: id },
                        success: function (response) {
                            if (response.status === "success") {
                                alert(response.message);
                                window.location.reload();
                            }
                            if (response.status === "error") {
                                alert("Error:" + response.message);
                                // Handle error
                            }
                            return false;
                        },
                    });
                }
                return false;
            },
            companyModulePermission: function (e) {
                let id = $(e).val();
                if (id.length === 0) {
                    return false;
                }
                const url =
                    window.location.origin +
                    sourceDir +
                    "/system-operation/parent-wise-module-permission";
                $.ajax({
                    url: url,
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    method: "POST",
                    data: { id: id },
                    success: function (response) {
                        if (response.status === "error") {
                            alert("Error: " + response.message);
                        } else if (response.status === "success") {
                            updateSelectBox(
                                response.data,
                                "permissions",
                                "id",
                                "name"
                            );
                        }
                    },
                });
            },
            companyDirectoryPermission: function (e) {
                let cid = $(e).val();
                let uid = $(e).attr("ref");
                if (cid.length === 0 || uid.length === 0) {
                    return false;
                }
                const url =
                    window.location.origin +
                    sourceDir +
                    "/system-operation/company-wise-directory-permission";
                $.ajax({
                    url: url,
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    method: "POST",
                    data: { cid: cid, uid: uid },
                    success: function (response) {
                        if (response.status === "error") {
                            alert("Error: " + response.message);
                        } else if (response.status === "success") {
                            // console.log(response.data)
                            const $select = $("#dir");
                            // Ensure Selectize is initialized
                            if ($select[0] && $select[0].selectize) {
                                const selectize = $select[0].selectize;

                                selectize.clear();
                                selectize.clearOptions(); // Clear existing options
                                // Loop through the JSON object to add each item as an option
                                Object.entries(response.data).forEach(
                                    ([key, value]) => {
                                        selectize.addOption({
                                            value: value,
                                            text: value,
                                        });
                                    }
                                );

                                selectize.refreshOptions(true); // Refresh the options in the select box
                            } else {
                                console.error(
                                    "Selectize is not initialized for #" + id
                                );
                            }
                        }
                    },
                });
            },
            companyDirectoryPermissionDelete: function (e) {
                if (!confirm("Are you sure to delete this data!")) {
                    return false;
                }
                let ref = $(e).attr("ref");
                if (ref.length > 0) {
                    let url =
                        window.location.origin +
                        sourceDir +
                        "/system-operation/user-per-delete";
                    $.ajax({
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                                "content"
                            ),
                        },
                        url: url,
                        type: "POST",
                        data: { ref: ref },
                        success: function (response) {
                            if (response.status === "error") {
                                alert("Error: " + response.message);
                            } else if (response.status === "success") {
                                $("#f-p-list").html(response.data);
                                alert(response.message);
                            }
                        },
                    });
                }
            },
            companyDirectoryPermissionMultipleDelete: function (e) {
                let selected = [];
                $(".check-box:checked").each(function () {
                    selected.push($(this).val());
                });
                console.log(selected);
                if (selected.length === 0) {
                    alert("Please select at least one record to delete.");
                    return;
                } else {
                    let url =
                        window.location.origin +
                        sourceDir +
                        "/system-operation/user-per-multiple-delete";
                    $.ajax({
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                                "content"
                            ),
                        },
                        url: url,
                        type: "POST",
                        data: { selected: selected },
                        success: function (response) {
                            if (response.status === "error") {
                                alert("Error: " + response.message);
                            } else if (response.status === "success") {
                                $("#f-p-list").html(response.data);
                                alert(response.message);
                            }
                        },
                    });
                }
            },
            companyWiseFixedAssets: function (e, action_id) {
                let id = $(e).val();
                if (id.length === 0) {
                    return false;
                }
                const url =
                    window.location.origin +
                    sourceDir +
                    "/fixed-asset/company-wise-fixed-asset";
                $.ajax({
                    url: url,
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    method: "POST",
                    data: { id: id },
                    success: function (response) {
                        if (response.status === "error") {
                            alert("Error: " + response.message);
                            return false;
                        } else if (response.status === "success") {
                            updateSelectBoxSingleOption(
                                response.data,
                                action_id,
                                "recourse_code",
                                "materials_name"
                            );
                        }
                    },
                });
            },
            companyWiseUsers: function (
                e,
                action_id //only able to control panel permission
            ) {
                let id = $(e).val();
                if (id.length === 0) {
                    return false;
                }
                const url =
                    window.location.origin +
                    sourceDir +
                    "/control-panel/company-wise-user";
                $.ajax({
                    url: url,
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    method: "POST",
                    data: { company_id: id },
                    success: function (response) {
                        if (response.status === "error") {
                            alert("Error: " + response.message);
                            return false;
                        } else if (response.status === "success") {
                            // updateSelectBoxSingleOption(response.data, action_id, 'id', 'name');
                            const $select = $("#" + action_id);
                            // Ensure Selectize is initialized
                            if ($select[0] && $select[0].selectize) {
                                const selectize = $select[0].selectize;

                                selectize.clear();
                                selectize.clearOptions(); // Clear existing options
                                response.data.forEach(function (item) {
                                    const companyName = item.get_company
                                        ? item.get_company.company_name
                                        : "N/A"; // Fallback if get_company is null
                                    const designationTitle = item.designation
                                        ? item.designation.title
                                        : "N/A"; // Fallback if get_designation is null
                                    const optionText = `${item.name} (ID: ${item.employee_id}) - (Designation: ${designationTitle}) - (Company: ${companyName})`;

                                    selectize.addOption({
                                        value: item.id,
                                        text: optionText,
                                    });
                                });
                                selectize.refreshOptions(true); // Refresh the options in the select box
                                $("#user-project-permission-add-list").html("");
                            } else {
                                console.error(
                                    "Selectize is not initialized for #" +
                                        action_id
                                );
                            }
                        }
                    },
                });
            },
            companyWiseUsersForReq: function (
                e,
                action_id //only able to requisition
            ) {
                let id = $(e).val();
                if (id.length === 0) {
                    return false;
                }
                const url =
                    window.location.origin +
                    sourceDir +
                    "/requisition/company-wise-user";
                $.ajax({
                    url: url,
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    method: "POST",
                    data: { company_id: id },
                    success: function (response) {
                        if (response.status === "error") {
                            alert("Error: " + response.message);
                            return false;
                        } else if (response.status === "success") {
                            // updateSelectBoxSingleOption(response.data, action_id, 'id', 'name');
                            const $select = $("#" + action_id);
                            // Ensure Selectize is initialized
                            if ($select[0] && $select[0].selectize) {
                                const selectize = $select[0].selectize;

                                selectize.clear();
                                selectize.clearOptions(); // Clear existing options
                                response.data.forEach(function (item) {
                                    const companyName = item.get_company
                                        ? item.get_company.company_name
                                        : "N/A"; // Fallback if get_company is null
                                    const designationTitle = item.designation
                                        ? item.designation.title
                                        : "N/A"; // Fallback if get_designation is null
                                    const departmentTitle = item.department
                                        ? item.department.dept_name
                                        : "N/A"; // Fallback if get_department is null
                                    const optionText = `${item.name} (${item.employee_id}, ${designationTitle},  ${departmentTitle}, ${companyName})`;

                                    selectize.addOption({
                                        value: item.id,
                                        text: optionText,
                                    });
                                });
                                selectize.refreshOptions(true); // Refresh the options in the select box
                                $("#user-project-permission-add-list").html("");
                            } else {
                                console.error(
                                    "Selectize is not initialized for #" +
                                        action_id
                                );
                            }
                        }
                    },
                });
            },
            companyWiseUsersCompanyPermission: function (
                e,
                action_id // only able to system super admin permission
            ) {
                let id = $(e).val();
                if (id.length === 0) {
                    return false;
                }
                const url =
                    window.location.origin +
                    sourceDir +
                    "/system-operation/company-wise-users-company-permission";
                $.ajax({
                    url: url,
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    method: "POST",
                    data: { company_id: id },
                    success: function (response) {
                        if (response.status === "error") {
                            alert("Error: " + response.message);
                            return false;
                        } else if (response.status === "success") {
                            // updateSelectBoxSingleOption(response.data, action_id, 'id', 'name');
                            const $select = $("#" + action_id);
                            // Ensure Selectize is initialized
                            if ($select[0] && $select[0].selectize) {
                                const selectize = $select[0].selectize;

                                selectize.clear();
                                selectize.clearOptions(); // Clear existing options
                                if (response.data.length > 0) {
                                    selectize.addOption({
                                        value: 0,
                                        text: "@ All",
                                    });
                                }
                                response.data.forEach(function (item) {
                                    const companyName = item.get_company
                                        ? item.get_company.company_name
                                        : "N/A"; // Fallback if get_company is null
                                    const designationTitle = item.designation
                                        ? item.designation.title
                                        : "N/A"; // Fallback if get_company is null
                                    const optionText = `${item.name} (ID: ${item.employee_id}) - (Designation: ${designationTitle}) - (Company: ${companyName})`;

                                    selectize.addOption({
                                        value: item.id,
                                        text: optionText,
                                    });
                                });
                                selectize.refreshOptions(true); // Refresh the options in the select box
                                $("#user-project-permission-add-list").html("");
                            } else {
                                console.error(
                                    "Selectize is not initialized for #" +
                                        action_id
                                );
                            }
                        }
                    },
                });
            },
            companyWiseProjects: function (e, action_id) {
                let id = $(e).val();
                if (id.length === 0) {
                    return false;
                }
                const url =
                    window.location.origin +
                    sourceDir +
                    "/company-wise-projects";
                $.ajax({
                    url: url,
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    method: "POST",
                    data: { company_id: id },
                    success: function (response) {
                        if (response.status === "error") {
                            alert("Error: " + response.message);
                            return false;
                        } else if (response.status === "success") {
                            updateSelectBoxSingleOption(
                                response.data,
                                action_id,
                                "id",
                                "branch_name"
                            );
                        }
                    },
                });
            },
            companyWiseProjectsAndDataTypeArchive: function (
                e,
                action_id_projects,
                action_id_types,
                multiply = null
            ) {
                let id = $(e).val();
                if (id.length === 0) {
                    return false;
                }
                const url =
                    window.location.origin +
                    sourceDir +
                    "/company-wise-projects-and-data-type-archive";
                $.ajax({
                    url: url,
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    method: "POST",
                    data: { company_id: id },
                    success: function (response) {
                        if (response.status === "error") {
                            alert("Error: " + response.message);
                            return false;
                        } else if (response.status === "success") {
                            if (multiply) {
                                if (action_id_projects !== null) {
                                    updateSelectBox(
                                        response.data.projects,
                                        action_id_projects,
                                        "id",
                                        "branch_name"
                                    );
                                }
                                if (action_id_types !== null) {
                                    updateSelectBox(
                                        response.data.types,
                                        action_id_types,
                                        "id",
                                        "voucher_type_title"
                                    );
                                }
                            } else {
                                updateSelectBoxSingleOption(
                                    response.data.projects,
                                    action_id_projects,
                                    "id",
                                    "branch_name"
                                );
                                updateSelectBoxSingleOption(
                                    response.data.types,
                                    action_id_types,
                                    "id",
                                    "voucher_type_title"
                                );
                                if ($("#previous-references").length) {
                                    setSelectBoxBlank("previous-references");
                                }

                                if ($("#previous-files").length) {
                                    setSelectBoxBlank("previous-files");
                                }
                            }
                        }
                    },
                });
            },
            archiveDataQuickSearch: function (e) {
                let company = $("#company").val();
                let projects = $("#projects").val(); //array
                let data_types = $("#data_types").val(); //array
                let from_date = $("#from_date").val();
                let to_date = $("#to_date").val();
                let reference = $("#reference").val();
                if (
                    company.length <= 0 &&
                    projects.length <= 0 &&
                    data_types <= 0 &&
                    from_date <= 0 &&
                    to_date <= 0 &&
                    reference <= 0
                ) {
                    return false;
                }
                const url =
                    window.location.origin +
                    sourceDir +
                    "/archive-data-list-quick";
                $.ajax({
                    url: url,
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    method: "POST",
                    data: {
                        company_id: company,
                        projects: projects,
                        data_types: data_types,
                        to_date: to_date,
                        from_date: from_date,
                        reference: reference,
                    },
                    success: function (response) {
                        if (response.status === "error") {
                            alert("Error: " + response.message);
                            return false;
                        } else if (response.status === "success") {
                            // alert(response.message)
                            $("#quick-list").html(response.data);
                            return true;
                        }
                    },
                });
            },
            companyWiseDepartments: function (e, action_id) {
                let id = $(e).val();
                // console.log(val);
                const url =
                    window.location.origin +
                    sourceDir +
                    "/company-wise-departments";
                $.ajax({
                    url: url,
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    method: "POST",
                    data: { company_id: id },
                    success: function (response) {
                        if (response.status === "error") {
                            alert("Error: " + response.message);
                            return false;
                        } else if (response.status === "success") {
                            updateSelectBox(
                                response.data,
                                action_id,
                                "id",
                                "dept_name"
                            );
                        }
                    },
                });
            },
            userWiseCompanyProjectPermissions: function (
                e,
                user_id,
                action_id
            ) {
                let company_id = $(e).val();
                if (
                    company_id.length === 0 ||
                    user_id.length === 0 ||
                    action_id.length === 0
                ) {
                    return false;
                }
                const url =
                    window.location.origin +
                    sourceDir +
                    "/user-wise-company-project-permissions";
                $.ajax({
                    url: url,
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    method: "POST",
                    data: { company_id: company_id, user_id: user_id },
                    success: function (response) {
                        if (response.status === "error") {
                            alert("Error: " + response.message);
                            return false;
                        } else if (response.status === "success") {
                            updateSelectBox(
                                response.data,
                                action_id,
                                "id",
                                "branch_name"
                            );
                        }
                    },
                });
            },
            companyProjects: function (
                e,
                user_id,
                action_id,
                action_id2 = null
            ) {
                let company_id = $(e).val();
                if (
                    company_id.length === 0 ||
                    user_id.length === 0 ||
                    action_id.length === 0
                ) {
                    return false;
                }
                const url =
                    window.location.origin +
                    sourceDir +
                    "/fixed-asset-distribution/company-projects";
                $.ajax({
                    url: url,
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    method: "POST",
                    data: { company_id: company_id, user_id: user_id },
                    success: function (response) {
                        if (response.status === "error") {
                            alert("Error: " + response.message);
                            return false;
                        } else if (response.status === "success") {
                            updateSelectBoxSingleOption(
                                response.data["project"],
                                action_id,
                                "id",
                                "branch_name"
                            );
                            updateSelectBoxSingleOption(
                                response.data["op_ref_type"],
                                action_id2,
                                "id",
                                "name"
                            );
                        }
                    },
                });
            },
            fixedAssetSpecificationStore: function (e) {
                let cid = $("#company_id").val();
                let fid = $("#fixed_asset_id").val();
                let specification = $("#specification").val();
                let status = $("#status").val();
                if (
                    cid.length === 0 ||
                    fid.length === 0 ||
                    specification.length === 0 ||
                    status.length === 0
                ) {
                    return false;
                }
                const url =
                    window.location.origin +
                    sourceDir +
                    "/fixed-asset/store-specification";
                $.ajax({
                    url: url,
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    method: "POST",
                    data: {
                        cid: cid,
                        fid: fid,
                        specification: specification,
                        status: status,
                    },
                    success: function (response) {
                        if (response.status === "error") {
                            alert("Error: " + response.message);
                            return false;
                        } else if (response.status === "success") {
                            $("#specification_data").html(response.data);
                        }
                    },
                });
            },
            selectAllOption: function (e) {
                const $select = $(e);

                if ($select[0] && $select[0].selectize) {
                    const selectize = $select[0].selectize; // Get the selectize instance
                    const selectedValues = selectize.getValue(); // Get the current selected values as array
                    if (selectedValues.includes("0")) {
                        // Check if "All" (value '0') is selected
                        // Get all option values except the "All" option
                        const allValues = selectize.options;
                        const allKeys = Object.keys(allValues).filter(
                            (key) => key !== "0"
                        ); // Exclude "All"

                        // Set all other options as selected
                        selectize.setValue(allKeys);
                    }
                    return true;
                } else {
                    console.error(
                        "Selectize is not initialized for the provided element."
                    );
                    return false;
                }
            },
            deleteCompanyModulePermissionAll: function (e) {
                if (confirm("Are you sure delete all permission?")) {
                    let cid = $(e).attr("ref");
                    if (cid.length === 0) {
                        return false;
                    }
                    const url =
                        window.location.origin +
                        sourceDir +
                        "/system-operation/company-module-permission-delete-all";
                    $.ajax({
                        url: url,
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                                "content"
                            ),
                        },
                        method: "DELETE",
                        data: { company_id: cid },
                        success: function (response) {
                            if (response.status === "error") {
                                alert("Error: " + response.message);
                            } else if (response.status === "success") {
                                $("#company-permission-list").html(
                                    response.data
                                );
                                alert("Success:" + response.message);
                            }
                        },
                    });
                }
                return false;
            },
            deleteCompanyModulePermissionSingle: function (e) {
                if (confirm("Are you sure delete this permission?")) {
                    let id = $(e).attr("ref");
                    if (id.length === 0) {
                        return false;
                    }
                    const url =
                        window.location.origin +
                        sourceDir +
                        "/system-operation/company-module-permission-delete";
                    $.ajax({
                        url: url,
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                                "content"
                            ),
                        },
                        method: "DELETE",
                        data: { id: id },
                        success: function (response) {
                            if (response.status === "error") {
                                alert("Error: " + response.message);
                            } else if (response.status === "success") {
                                $("#company-permission-list").html(
                                    response.data
                                );
                                alert("Success:" + response.message);
                            }
                        },
                    });
                }
                return false;
            },
            projectWiseMaterials: function (e, company, output) {
                if (this.selectAllOption(e)) {
                    let company_id = $("#" + company).val();
                    let project_ids = $(e).val();
                    if (company_id.length <= 0 || project_ids.length <= 0) {
                        return false;
                    }
                    const url =
                        window.location.origin +
                        sourceDir +
                        "/projects-wise-fixed-assets";
                    $.ajax({
                        url: url,
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                                "content"
                            ),
                        },
                        method: "POST",
                        data: {
                            company_id: company_id,
                            project_ids: project_ids,
                        },
                        success: function (response) {
                            if (response.status === "error") {
                                alert("Error: " + response.message);
                            } else if (response.status === "success") {
                                updateSelectBox(
                                    response.data.data,
                                    output,
                                    "id",
                                    "materials_name"
                                );
                            }
                        },
                    });
                }
            },
            fixedAssetReportSearch: function (e) {
                let company_id = $("#company").val();
                let projects = $("#projects").val();
                let materials = $("#materials").val();
                let from_date = $("#from_date").val();
                let to_date = $("#to_date").val();
                if (company_id.length <= 0) {
                    return false;
                }
                let url =
                    window.location.origin +
                    sourceDir +
                    "/fixed-asset-report/stock-report-search";
                $.ajax({
                    url: url,
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    method: "POST",
                    data: {
                        company_id: company_id,
                        project_ids: projects,
                        material_ids: materials,
                        from_date: from_date,
                        to_date: to_date,
                    },
                    success: function (response) {
                        if (response.status === "error") {
                            alert("Error: " + response.message);
                        } else if (response.status === "success") {
                            $("#fixed-asset-body").html(response.data.view);
                        }
                    },
                });
            },
            document_field_operation: function (e) {
                let number_of_document = parseInt($("#d_count").val(), 10);
                if (number_of_document > 0) {
                    $(e).removeClass("btn-outline-primary"); // Remove the 'btn-primary' class
                    $(e).addClass("btn-outline-danger"); // Add the 'btn-danger' class
                    $(e).html(
                        "<i class='fa-solid fa-clock-rotate-left'></i> Reset"
                    );
                    $("#document_field").empty(); // Clear previous entries

                    for (let i = 1; i <= number_of_document; i++) {
                        $("#document_field").append(`
                        <div class="col">
                            <div class="mb-3">
                                <label for="d_title_${i}">Document ${i} Title</label>
                                <input class="form-control" name="d_title_${i}" id="d_title_${i}" type="text">
                            </div>
                        </div>
                    `);
                    }
                }
                return false;
            },
            requisitionDocumentUsersInfo: function (e) {
                let data = $(e).attr("ref");
                if (data.length <= 0) {
                    return false;
                }
                let url =
                    window.location.origin +
                    sourceDir +
                    "/requisition/req-document-receiver";
                $.ajax({
                    url: url,
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    method: "POST",
                    data: { id: data },
                    success: function (response) {
                        if (response.status === "error") {
                            alert("Error: " + response.message);
                        } else if (response.status === "success") {
                            $("#heading").html("Receiver List");
                            $("#documentPreview").html(response.view);
                            $("#receiverList").modal("show");
                        }
                    },
                });
            },
            dataTypePermissionUsersInfo: function (e) {
                let data = $(e).attr("ref");
                if (data.length <= 0) {
                    return false;
                }
                let url =
                    window.location.origin +
                    sourceDir +
                    "/archive-data-list-permission-users";
                $.ajax({
                    url: url,
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    method: "POST",
                    data: { id: data },
                    success: function (response) {
                        if (response.status === "error") {
                            alert("Error: " + response.message);
                        } else if (response.status === "success") {
                            $("#heading").html("Receiver List");
                            $("#documentPreview").html(response.data);
                            $("#receiverList").modal("show");
                        }
                    },
                });
            },
            deleteTypePermissionFromUser: function (element) {
                if (!confirm("Are you sure you want to delete this item?")) {
                    return false;
                }

                let userId = $(element).data("user-id");
                let dataTypeId = $(element).data("data-type-id");
                let url =
                    window.location.origin +
                    sourceDir +
                    "/delete-data-type-permission-from-user";
                $.ajax({
                    url: url,
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    type: "POST",
                    data: {
                        user_id: userId,
                        data_type_id: dataTypeId,
                    },
                    success: function (response) {
                        if (response.status === "error") {
                            alert("Error: " + response.message);
                            return false;
                        } else if (response.status === "success") {
                            alert(response.message);
                            Obj.updatedPermissionWithUsersList(element);
                        }
                    },
                    error: function (xhr) {
                        console.log(xhr);
                    },
                });
            },
            updatedPermissionWithUsersList: function (element) {
                let voucherId = $(element).data("voucher-id");
                let url =
                    window.location.origin +
                    sourceDir +
                    "/archive-data-list-permission-users";
                $.ajax({
                    url: url,
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    method: "POST",
                    data: {
                        id: voucherId,
                        encrypt_voucher_id: "encrypt_voucher_id",
                    },
                    success: function (response) {
                        if (response.status === "error") {
                            alert("Error: " + response.message);
                        } else if (response.status === "success") {
                            $("#heading").html("Receiver List");
                            $("#documentPreview").html(response.data);
                            $("#receiverList").modal("show");
                        }
                    },
                });
            },
            deleteArchiveMultiple: function (e = null, multiplication = false) {
                let selected = [];
                $(".check-box:checked").each(function () {
                    selected.push($(this).val());
                });
                if (selected.length === 0) {
                    alert("Please select at least one record to delete.");
                    return;
                }

                if (
                    confirm("Are you sure you want to delete selected records?")
                ) {
                    let url =
                        window.location.origin +
                        sourceDir +
                        "/delete-archive-data";
                    $.ajax({
                        url: url,
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                                "content"
                            ),
                        },
                        method: "DELETE",
                        data: {
                            selected: selected,
                            multipleDlt: "multipleDlt",
                        },
                        success: function (response) {
                            if (response.status == "success") {
                                alert(response.message);
                                if (multiplication) {
                                    Obj.archiveDataQuickSearch(e);
                                } else {
                                    location.reload();
                                }
                            } else if (response.status == "error") {
                                alert(response.message);
                            }

                            //location.reload();
                        },
                        error: function (error) {
                            alert("An error occurred while deleting records.");
                        },
                    });
                }
            },
            deleteUserPermissionMultiple: function (userID) {
                let selected = [];
                $(".check-box:checked").each(function () {
                    selected.push($(this).val());
                });
                if (selected.length === 0) {
                    alert("Please select at least one record to delete.");
                    return;
                }

                if (
                    confirm("Are you sure you want to delete selected records?")
                ) {
                    let url =
                        window.location.origin +
                        sourceDir +
                        "/system-operation/delete-multiple-user-permission";
                    $.ajax({
                        url: url,
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                                "content"
                            ),
                        },
                        method: "DELETE",
                        data: {
                            selected: selected,
                            multipleDlt: "multipleDlt",
                            userID: userID,
                        },
                        success: function (response) {
                            if (response.status == "success") {
                                alert(response.message);
                                let url =
                                    window.location.origin +
                                    sourceDir +
                                    "/fetch-user-permissions-after-delete";
                                $.ajax({
                                    url: url,
                                    headers: {
                                        "X-CSRF-TOKEN": $(
                                            'meta[name="csrf-token"]'
                                        ).attr("content"),
                                    },
                                    method: "GET",
                                    data: {
                                        userID: userID,
                                    },
                                    success: function (response) {
                                        if (response.status == "success") {
                                            $(
                                                "#user-permissions-container"
                                            ).html(
                                                response.userPermissionUpdated
                                            );
                                        }
                                    },
                                    error: function (error) {
                                        alert(
                                            "An error occurred while fetching updated record."
                                        );
                                    },
                                });
                            }
                        },
                        error: function (error) {
                            alert("An error occurred while deleting records.");
                        },
                    });
                }
            },
            requisitionDocumentNeed: function (e) {
                let data = $(e).attr("ref");
                if (data.length <= 0) {
                    return false;
                }
                let url =
                    window.location.origin +
                    sourceDir +
                    "/requisition/requested-document";
                $.ajax({
                    url: url,
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    method: "POST",
                    data: { id: data },
                    success: function (response) {
                        if (response.status === "error") {
                            alert("Error: " + response.message);
                        } else if (response.status === "success") {
                            $("#heading").html("Requested Document List");
                            $("#documentPreview").html(response.view);
                            $("#receiverList").modal("show");
                        }
                    },
                });
            },
            searchPreviousDocumentReference: function (
                e,
                company_id,
                target_id
            ) {
                let value = $("#input").val();
                let company = $("#" + company_id).val();
                if (value.length === 0 || company.length === 0) {
                    return false;
                }
                const url =
                    window.location.origin +
                    sourceDir +
                    "/search-previous-document-ref";
                $.ajax({
                    url: url,
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    method: "POST",
                    data: { value: value, company: company },
                    success: function (response) {
                        if (response.status === "error") {
                            alert("Error: " + response.message);
                        } else if (response.status === "success") {
                            // updateSelectBoxSingleOption(response.data,target_id,'id','voucher_number')
                            updateSelectBox(
                                response.data,
                                target_id,
                                "id",
                                "voucher_number"
                            );
                            return true;
                        }
                    },
                });
            },
            searchPreviousDocuments: function (input_id, target_id) {
                let ids = $("#" + input_id).val();
                if (ids.length === 0) {
                    setSelectBoxBlank(target_id);
                    return false;
                }
                const url =
                    window.location.origin +
                    sourceDir +
                    "/search-previous-document";
                $.ajax({
                    url: url,
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    method: "POST",
                    data: { ids: ids },
                    success: function (response) {
                        if (response.status === "error") {
                            alert("Error: " + response.message);
                        } else if (response.status === "success") {
                            updateSelectBox(
                                response.data,
                                target_id,
                                "id",
                                "document"
                            );
                            return true;
                        }
                    },
                });
            },
            searchCompanyDepartmentUsers: function (
                company_id,
                company_wise_departments,
                action_id
            ) {
                let company = $("#" + company_id).val();
                let projects_id = $("#" + company_wise_departments).val();

                const url =
                    window.location.origin +
                    sourceDir +
                    "/search-company-department-users";
                $.ajax({
                    url: url,
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    method: "POST",
                    data: { ids: projects_id, company_id: company },
                    success: function (response) {
                        if (response.status === "error") {
                            alert("Error: " + response.message);
                        } else if (response.status === "success") {
                            updateSelectBox(
                                response.data,
                                action_id,
                                "id",
                                "name"
                            );
                        }
                        return true;
                    },
                });
            },
            searchCompanyAndBranchWiseUsers: function (
                company_id,
                branch_id,
                action_id
            ) {
                let company = $("#" + company_id).val();
                let branches_id = $("#" + branch_id).val();

                const url =
                    window.location.origin +
                    sourceDir +
                    "/search-company-branch-users";
                $.ajax({
                    url: url,
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    method: "POST",
                    data: { branches_id: branches_id, company_id: company },
                    success: function (response) {
                        if (response.status === "error") {
                            alert("Error: " + response.message);
                        } else if (response.status === "success") {
                            updateSelectBox(
                                response.data,
                                action_id,
                                "id",
                                "name"
                            );
                        }
                        return true;
                    },
                });
            },
        };
        Archive = {
            settingSetTypePermission: function (
                e,
                type_company_id,
                types,
                users
            ) {
                let company_id = $("#" + type_company_id).val();
                let data_types = $("#" + types).val();
                let permission_users = $("#" + users).val();
                // Ensure data_types is always an array
                if (!Array.isArray(data_types)) {
                    data_types = [data_types];
                }
                if (
                    data_types.length === 0 ||
                    permission_users.length === 0 ||
                    company_id.length === 0
                ) {
                    return false;
                } else {
                    if (!confirm("Are you sure?")) {
                        return false;
                    }
                    const url =
                        window.location.origin +
                        sourceDir +
                        "/archive-data-type-user-permission-add";
                    $.ajax({
                        url: url,
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                                "content"
                            ),
                        },
                        method: "POST",
                        data: {
                            type_company_id: company_id,
                            data_types: data_types,
                            permission_users: permission_users,
                        },
                        success: function (response) {
                            if (response.status === "error") {
                                alert("Error: " + response.message);
                            } else if (response.status === "success") {
                                alert(response.message);
                            }
                            return true;
                        },
                    });
                }
            },

            typeWiseDataView: function (e, company_id, type_id, output_id) {
                if (company_id.length === 0 || type_id.length === 0) {
                    return false;
                }
                const url =
                    window.location.origin +
                    sourceDir +
                    "/archive-data-type-wise-data-show";
                $.ajax({
                    url: url,
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    method: "POST",
                    data: { company_id: company_id, data_type: type_id },
                    success: function (response) {
                        if (response.status === "error") {
                            alert("Error: " + response.message);
                        } else if (response.status === "success") {
                            $("#" + output_id).html(response.data);
                        }
                        return true;
                    },
                });
            },
            companyPackageEdit:function (e){
                let data = $(e).data('info')
                console.log((data))
                $("#editModalLabel").html("Edit Company Storage Package of"+data.company)
                $("#edit_company_name").html(`<option value="${data.company_id}" selected>${data.company}</option>`)
                $("#edit_company_package").val(data.package_id)
                if (data.status !== '')
                {
                    $("#edit_company_status").val(data.status)
                }
                $("#editModal").modal('show')
                return false
            },
            companyPackageUpdate:function ()
            {
                let company_id = $("#edit_company_name").val();
                let selected_package = parseInt($("#edit_company_package").val());
                let status = parseInt($("#edit_company_status").val());

                selected_package = (isNaN(selected_package) || selected_package === 0) ? null : selected_package;
                status = (isNaN(status) || status === -1) ? 0 : status;
                const url =
                    window.location.origin +
                    sourceDir +
                    "/system-operation/company-storage-package-update";
                $.ajax({
                    url: url,
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    method: "POST",
                    data: { company_id: company_id, selected_package: selected_package, status: status },
                    success: function (response) {
                        if (response.status === "error") {
                            alert("Error: " + response.message);
                        } else if (response.status === "success") {
                            alert(response.message)
                            window.location.reload()
                        }
                        return true;
                    },
                });
            }
        };
        Sales = {
            addEmailPhoneForLead: function (event, displayName, outputId) {
                if (!displayName || !outputId) return false;

                let count = null;
                let inputType = null;

                const maxAllowed = 5;

                if (displayName === "mobile") {
                    if (extraMobileNumberCount > maxAllowed) {
                        alert(
                            `You can add a maximum of ${maxAllowed} alternative mobile numbers.`
                        );
                        return false;
                    }
                    inputType = "number";
                    count = extraMobileNumberCount++;
                } else if (displayName === "email") {
                    if (extraEmailAddressCount > maxAllowed) {
                        alert(
                            `You can add a maximum of ${maxAllowed} alternative email addresses.`
                        );
                        return false;
                    }
                    inputType = "email";
                    count = extraEmailAddressCount++;
                }

                if (inputType !== null && count !== null) {
                    const inputId = `${displayName}_${count}`;
                    const inputPlaceholder = `Add Another ${displayName} ${count}`;
                    const labelText = `Alternative ${displayName} ${count}`;

                    const inputHtml = `
                        <div class="col-md-3">
                            <div class="form-floating mb-2">
                                <input class="form-control" id="${inputId}" type="${inputType}" placeholder="${inputPlaceholder}">
                                <label for="${inputId}">${labelText}</label>
                            </div>
                        </div>
                    `;

                    $(`#${outputId}`).append(inputHtml);
                }

                return false;
            },
        };

        SalesSetting = {
            salesSubTable: function (click_param, desn_var = null) {
                // Clear previous content
                $("#sales_sub_table_content").empty();

                if (desn_var) {
                    if (desn_var === "professionList") {
                        $("#company").attr(
                            "onchange",
                            "return SalesSetting.salesProfessionParentIdDropdown(this)"
                        );
                    } else if (desn_var === "sourceList") {
                        $("#company").attr(
                            "onchange",
                            "return SalesSetting.salesSourceParentIdDropdown(this)"
                        );
                    }
                }

                var html = "";

                switch (click_param) {
                    case "sales_lead_apartment_type":
                        html += SalesSetting.createInput("title", "Title");
                        html += SalesSetting.createHiddenInput(click_param);
                        break;

                    case "sales_lead_apartment_size":
                        html += SalesSetting.createInput("title", "Title");
                        html += SalesSetting.createInput("size", "Size");
                        html += SalesSetting.createHiddenInput(click_param);
                        break;

                    case "sales_lead_source_info":
                        html += SalesSetting.createInput("title", "Title");
                        html += SalesSetting.createSelectInput(
                            "parent_id",
                            "Parent Id"
                        );
                        html += SalesSetting.createCheckboxInput(
                            "is_parent",
                            "Is Parent ?"
                        );
                        html += SalesSetting.createHiddenInput(click_param);
                        break;

                    case "sales_lead_budget":
                        html += SalesSetting.createInput("title", "Title");
                        html += SalesSetting.createHiddenInput(click_param);
                        break;

                    case "sales_lead_view":
                        html += SalesSetting.createInput("title", "Title");
                        html += SalesSetting.createHiddenInput(click_param);
                        break;

                    case "sales_lead_floor":
                        html += SalesSetting.createInput("title", "Title");
                        html += SalesSetting.createHiddenInput(click_param);
                        break;

                    case "sales_lead_location_info":
                        html += SalesSetting.createInput(
                            "location_name",
                            "Location Name"
                        );
                        html += SalesSetting.createHiddenInput(click_param);
                        break;

                    case "sales_lead_profession":
                        html += SalesSetting.createInput("title", "Title");
                        html += SalesSetting.createSelectInput(
                            "parent_id",
                            "Parent Id"
                        );
                        html += SalesSetting.createCheckboxInput(
                            "is_parent",
                            "Is Parent ?"
                        );
                        html += SalesSetting.createHiddenInput(click_param);
                        break;

                    case "sales_lead_facing":
                        html += SalesSetting.createInput("title", "Title");
                        html += SalesSetting.createHiddenInput(click_param);
                        break;

                    case "sales_lead_status_info":
                        html += SalesSetting.createInput("title", "Title");
                        html += SalesSetting.createHiddenInput(click_param);
                        break;

                    default:
                        html += '<p class="text-danger">Unknown parameter.</p>';
                }
                let title = click_param
                    .replace(/^sales_lead_/, "") // remove prefix
                    .replace(/_/g, " "); // replace _ with space

                // Optionally capitalize first letter:
                title = title.charAt(0).toUpperCase() + title.slice(1);

                // Append inputs to modal container
                $("#sales_sub_table_content").append(html);
                $("#generalModalLabel").html(
                    '<i class="fas fa-file-circle-plus"></i>' +
                        " " +
                        "Add" +
                        " " +
                        title
                );

                $(".company_dropdown").val("");

                $(".status_dropdown").val("");
                $("#general_modal").modal("show");
                return false;
            },
            salesSubTableEdit: function (
                click_param,
                desn_var = null,
                record_id = null
            ) {
                $("#sales_sub_table_content").empty();

                // Handle the change event for specific dropdowns based on desn_var
                if (desn_var) {
                    if (desn_var === "professionList") {
                        $("#company").attr(
                            "onchange",
                            "return SalesSetting.salesProfessionParentIdDropdown(this)"
                        );
                    } else if (desn_var === "sourceList") {
                        $("#company").attr(
                            "onchange",
                            "return SalesSetting.salesSourceParentIdDropdown(this)"
                        );
                    }
                }

                var html = "";
                var url = "";

                if (!record_id) {
                    alert("Opeartion Fail");
                }
                // Define the case logic to generate HTML dynamically based on click_param
                switch (click_param) {
                    case "sales_lead_apartment_type":
                        html += SalesSetting.createInput("title", "Title");
                        html += SalesSetting.createHiddenInput(record_id);
                        html +=
                            SalesSetting.createHiddenInputForOutput(
                                click_param
                            );
                        url = "/get-sales-lead-apartment-type-edit";
                        break;
                    case "sales_lead_apartment_size":
                        html += SalesSetting.createInput("title", "Title");
                        html += SalesSetting.createInput("size", "Size");
                        html += SalesSetting.createHiddenInput(record_id);
                        html +=
                            SalesSetting.createHiddenInputForOutput(
                                click_param
                            );
                        url = "/get-sales-lead-apartment-size-edit";
                        break;
                    case "sales_lead_source_info":
                        html += SalesSetting.createInput("title", "Title");
                        html += SalesSetting.createSelectInput(
                            "parent_id",
                            "Parent Id"
                        );
                        html += SalesSetting.createHiddenInput(record_id);
                        html +=
                            SalesSetting.createHiddenInputForOutput(
                                click_param
                            );
                        url = "/get-sales-lead-source-info-edit";
                        break;
                    case "sales_lead_budget":
                        html += SalesSetting.createInput("title", "Title");
                        html += SalesSetting.createHiddenInput(record_id);
                        html +=
                            SalesSetting.createHiddenInputForOutput(
                                click_param
                            );
                        url = "/get-sales-lead-budget-edit";
                        break;
                    case "sales_lead_view":
                        html += SalesSetting.createInput("title", "Title");
                        html += SalesSetting.createHiddenInput(record_id);
                        html +=
                            SalesSetting.createHiddenInputForOutput(
                                click_param
                            );
                        url = "/get-sales-lead-view-edit";
                        break;
                    case "sales_lead_floor":
                        html += SalesSetting.createInput("title", "Title");
                        html += SalesSetting.createHiddenInput(record_id);
                        html +=
                            SalesSetting.createHiddenInputForOutput(
                                click_param
                            );
                        url = "/get-sales-lead-floor-edit";
                        break;
                    case "sales_lead_location_info":
                        html += SalesSetting.createInput(
                            "location_name",
                            "Location Name"
                        );
                        html += SalesSetting.createHiddenInput(record_id);
                        html +=
                            SalesSetting.createHiddenInputForOutput(
                                click_param
                            );
                        url = "/get-sales-lead-location-info-edit";
                        break;
                    case "sales_lead_profession":
                        html += SalesSetting.createInput("title", "Title");
                        html += SalesSetting.createSelectInput(
                            "parent_id",
                            "Parent Id"
                        );
                        html += SalesSetting.createHiddenInput(record_id);
                        html +=
                            SalesSetting.createHiddenInputForOutput(
                                click_param
                            );
                        url = "/get-sales-lead-profession-edit";
                        break;
                    case "sales_lead_facing":
                        html += SalesSetting.createInput("title", "Title");
                        html += SalesSetting.createHiddenInput(record_id);
                        html +=
                            SalesSetting.createHiddenInputForOutput(
                                click_param
                            );
                        url = "/get-sales-lead-facing-edit";
                        break;
                    case "sales_lead_status_info":
                        html += SalesSetting.createInput("title", "Title");
                        html += SalesSetting.createHiddenInput(record_id);
                        html +=
                            SalesSetting.createHiddenInputForOutput(
                                click_param
                            );
                        url = "/get-sales-lead-status-info-edit";
                        break;
                    default:
                        html += '<p class="text-danger">Unknown</p>';
                }

                $("#sales_sub_table_content").html(html);
                const desired_url = window.location.origin + sourceDir + url;
                $.ajax({
                    url: desired_url,
                    method: "GET",
                    data: { record_id: record_id },
                    success: function (response) {
                        if (response && response.data) {
                            if (click_param === "sales_lead_apartment_type") {
                                $("#title").val(response.data.title);
                                $("#company").val(response.data.company_id);
                                $("#status").val(response.data.status);
                            } else if (
                                click_param === "sales_lead_apartment_size"
                            ) {
                                $("#title").val(response.data.title);
                                $("#company").val(response.data.company_id);
                                $("#status").val(response.data.status);
                                $("#size").val(response.data.size);
                            } else if (
                                click_param === "sales_lead_source_info"
                            ) {
                                $("#title").val(response.data.title);
                                $("#company").val(response.data.company_id);
                                $("#status").val(response.data.status);
                                SalesSetting.salesSourceParentIdDropdown(
                                    $("#company"),
                                    response.data.parent_id
                                );
                            } else if (click_param === "sales_lead_budget") {
                                $("#title").val(response.data.title);
                                $("#company").val(response.data.company_id);
                                $("#status").val(response.data.status);
                            } else if (
                                click_param === "sales_lead_location_info"
                            ) {
                                $("#location_name").val(
                                    response.data.location_name
                                );
                                $("#company").val(response.data.company_id);
                                $("#status").val(response.data.status);
                            } else if (click_param === "sales_lead_view") {
                                $("#title").val(response.data.title);
                                $("#company").val(response.data.company_id);
                                $("#status").val(response.data.status);
                            } else if (click_param === "sales_lead_floor") {
                                $("#title").val(response.data.title);
                                $("#company").val(response.data.company_id);
                                $("#status").val(response.data.status);
                            } else if (
                                click_param === "sales_lead_profession"
                            ) {
                                $("#title").val(response.data.title);
                                $("#company").val(response.data.company_id);
                                $("#status").val(response.data.status);
                                SalesSetting.salesProfessionParentIdDropdown(
                                    $("#company"),
                                    response.data.parent_id
                                );
                            } else if (click_param === "sales_lead_facing") {
                                $("#title").val(response.data.title);
                                $("#company").val(response.data.company_id);
                                $("#status").val(response.data.status);
                            } else if (
                                click_param === "sales_lead_status_info"
                            ) {
                                $("#title").val(response.data.title);
                                $("#company").val(response.data.company_id);
                                $("#status").val(response.data.status);
                            }
                        }
                    },
                    error: function (xhr, status, error) {
                        alert("Error fetching data: " + error);
                    },
                });
                $("#perform_store").attr(
                    "onclick",
                    `return SalesSetting.editedDataStore('this','${click_param}')`
                );
                // Show the modal
                $("#general_modal").modal("show");

                return false; // Prevent default action
            },
            editedDataStore: function (e, click_param) {
                let subTableData = {};
                // Get company dropdown value
                let company_id = SalesSetting.companyIdDropdownForEdit();
                subTableData["company_id"] = company_id;
                // Get status dropdown value
                subTableData["status"] = $("#status").val();

                // Get all input fields (including hidden and text)
                $("#sales_sub_table_content input").each(function () {
                    var name = $(this).attr("name");
                    var type = $(this).attr("type");

                    if (type === "checkbox") {
                        subTableData[name] = $(this).is(":checked") ? 1 : 0;
                    } else if (type === "hidden") {
                        subTableData[name] = $(this).val();
                    } else {
                        subTableData[name] = $(this).val();
                    }
                });
                // Get all select fields
                $("#sales_sub_table_content select").each(function () {
                    var name = $(this).attr("name");
                    subTableData[name] = $(this).val();
                });
                let url = "";
                if (click_param === "sales_lead_apartment_type") {
                    url = "/get-sales-lead-apartment-type-edit";
                } else if (click_param === "sales_lead_apartment_size") {
                    url = "/get-sales-lead-apartment-size-edit";
                } else if (click_param === "sales_lead_source_info") {
                    url = "/get-sales-lead-source-info-edit";
                } else if (click_param === "sales_lead_budget") {
                    url = "/get-sales-lead-budget-edit";
                } else if (click_param === "sales_lead_view") {
                    url = "/get-sales-lead-view-edit";
                } else if (click_param === "sales_lead_floor") {
                    url = "/get-sales-lead-floor-edit";
                } else if (click_param === "sales_lead_location_info") {
                    url = "/get-sales-lead-location-info-edit";
                } else if (click_param === "sales_lead_profession") {
                    url = "/get-sales-lead-profession-edit";
                } else if (click_param === "sales_lead_facing") {
                    url = "/get-sales-lead-facing-edit";
                } else if (click_param === "sales_lead_status_info") {
                    url = "/get-sales-lead-status-info-edit";
                }

                const desired_url = window.location.origin + sourceDir + url;
                $.ajax({
                    url: desired_url,
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    method: "POST",
                    data: {
                        subTableData: subTableData,
                    },
                    success: function (response) {
                        if (response.original.status === "error") {
                            alert("Error: " + response.original.message);
                        } else if (response.original.status === "success") {
                            alert(response.original.message);

                            $("#general_modal").modal("hide");
                            $("#" + response.original.output_id).html(
                                response.original.data
                            );
                            $("#company").val("").trigger("change");
                            $("#status").val("").trigger("change");
                        }
                    },
                });
            },

            createInput: function (id, label) {
                let requiredMark =
                    label === "Title"
                        ? '<span class="text-danger">*</span>'
                        : "";
                return `
                    <div class="col-md-4 mb-2 form-group">
                        <label for="${id}" class="form-label">${label}${requiredMark}</label>
                        <input type="text" class="form-control" id="${id}" name="${id}" placeholder="Enter ${label}">
                    </div>
                `;
            },
            createHiddenInput: function (click_param) {
                return `
                    <div class="mb-2">
                        <input type="hidden" class="form-control" name="hidden_click_param" value="${click_param}">
                    </div>
                `;
            },
            createHiddenInputForOutput: function (click_param) {
                return `
                    <div class="mb-2">
                        <input type="hidden" class="form-control" name="hidden_input_for_output" value="${click_param}">
                    </div>
                `;
            },
            createCheckboxInput: function (id, label) {
                return `
                    <div class="col-md-4 mb-2 form-group form-check">
                        <input type="checkbox" class="form-check-input" id="${id}" name="${id}">
                        <label class="form-check-label" for="${id}">${label}</label>
                    </div>
                `;
            },
            createSelectInput: function (id, label) {
                return `
                    <div class="col-md-4 mb-2 form-group">
                        <label for="${id}" class="form-label">${label}</label>
                        <select class="form-select" id="${id}" name="${id}">
                            <option for="pick" class="form-label">--Pick a option--</option>
                            <!-- Options will be added dynamically -->
                        </select>
                    </div>
                `;
            },
            salesSubTableModal: function (element) {
                var subTableData = {};
                // Get company dropdown value
                subTableData["company"] = $("#company").val();

                // Get status dropdown value
                subTableData["status"] = $("#status").val();

                // Get all input fields (including hidden and text)
                $("#sales_sub_table_content input").each(function () {
                    var name = $(this).attr("name");
                    var type = $(this).attr("type");

                    if (type === "checkbox") {
                        subTableData[name] = $(this).is(":checked") ? 1 : 0;
                    } else {
                        subTableData[name] = $(this).val();
                    }
                });
                // Get all select fields
                $("#sales_sub_table_content select").each(function () {
                    var name = $(this).attr("name");
                    subTableData[name] = $(this).val();
                });
                if (subTableData.company === "") {
                    alert("Comapany is required");
                    return false;
                } else if (subTableData.title === "") {
                    alert("Title is required");
                    return false;
                } else {
                    if (!confirm("Are you sure?")) {
                        return false;
                    } else {
                        const url =
                            window.location.origin +
                            sourceDir +
                            "/add_sale_sub_table_data";
                        $.ajax({
                            url: url,
                            headers: {
                                "X-CSRF-TOKEN": $(
                                    'meta[name="csrf-token"]'
                                ).attr("content"),
                            },
                            method: "POST",
                            data: {
                                subTableData: subTableData,
                            },
                            success: function (response) {
                                if (response.status === "error") {
                                    alert("Error: " + response.message);
                                } else if (response.status === "success") {
                                    alert(response.message);

                                    $("#general_modal").modal("hide");
                                    $("#" + response.output_id).html(
                                        response.data
                                    );
                                    $("#company").val("").trigger("change");
                                    $("#status").val("").trigger("change");
                                }
                            },
                        });
                    }
                }
                return false;
            },
            salesProfessionParentIdDropdown: function (e, selected = null) {
                let selectedId = $(e).val();
                const url =
                    window.location.origin +
                    sourceDir +
                    "/get_sale_profession_title_id";
                $.ajax({
                    url: url,
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    method: "GET",
                    data: {
                        selectedId: selectedId,
                    },
                    success: function (response) {
                        if (response.status === "error") {
                            alert("Error: " + response.message);
                            return false;
                        } else if (response.status === "success") {
                            const select = $("#parent_id");
                            select.empty();
                            select.append(
                                '<option value="">--Pick a Option--</option>'
                            );
                            $.each(
                                response.salesProfessionData,
                                function (index, item) {
                                    select.append(
                                        `<option value="${item.id}">${item.title}</option>`
                                    );
                                }
                            );
                            if (selected) {
                                $("#parent_id").val(selected);
                            }
                        }
                    },
                });
            },
            salesSourceParentIdDropdown: function (e, selected = null) {
                let selectedId = $(e).val();
                const url =
                    window.location.origin +
                    sourceDir +
                    "/get_sale_source_title_id";
                $.ajax({
                    url: url,
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    method: "GET",
                    data: {
                        selectedId: selectedId,
                    },
                    success: function (response) {
                        if (response.status === "error") {
                            alert("Error: " + response.message);
                            return false;
                        } else if (response.status === "success") {
                            const select = $("#parent_id");
                            select.empty();
                            select.append(
                                '<option value="">--Pick a Option--</option>'
                            );
                            $.each(
                                response.salesSourceData,
                                function (index, item) {
                                    select.append(
                                        `<option value="${item.id}">${item.title}</option>`
                                    );
                                }
                            );
                            if (selected) {
                                $("#parent_id").val(selected);
                            }
                        }
                    },
                });
            },
            companyIdDropdownForEdit: function () {
                let companyIdDropdownValue = $("#company").val();
                $("#company").on("change", function () {
                    companyIdDropdownValue = $("#company").val();
                });
                return companyIdDropdownValue;
            },
            deleteSalesSettingMultiple: function (get_url) {
                let selected = [];
                $(".check-box:checked").each(function () {
                    selected.push($(this).val());
                });
                if (selected.length === 0) {
                    alert("Please select at least one record to delete.");
                    return;
                }

                if (
                    confirm("Are you sure you want to delete selected records?")
                ) {
                    let url =
                        window.location.origin +
                        sourceDir +
                        get_url;
                    $.ajax({
                        url: url,
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                                "content"
                            ),
                        },
                        method: "DELETE",
                        data: {
                            selected: selected,
                        },
                        success: function (response) {
                            if (response.original.status == "success") {
                                alert(response.original.message);
                                $("#" + response.original.output).html(
                                    response.original.data
                                );
                            } else if (response.original.status == "error") {
                                alert(response.original.message);
                            }

                            //location.reload();
                        },
                        error: function (error) {
                            alert("An error occurred while deleting records.");
                        },
                    });
                }
            },
            AddLead: function () {
                let form = $("#addProfessionForm")[0]; // get the form DOM element
                let formData = new FormData(form); // create FormData from form

                const url =
                    window.location.origin + sourceDir + "/profession/store"; // update API endpoint

                $.ajax({
                    url: url,
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    method: "POST",
                    data: formData,
                    contentType: false, // Important for FormData
                    processData: false, // Important for FormData
                    success: function (response) {
                        console.log(response);

                        if (response.status === "error") {
                            alert("Error: " + response.message);
                        } else if (response.status === "success") {
                            alert(response.message);
                            $("#general_modal").modal("hide"); // close modal if needed

                            // Refresh some area if needed
                            if (response.output_id && response.data) {
                                $("#" + response.output_id).html(response.data);
                            }

                            // Reset the form after success
                            $("#addProfessionForm")[0].reset();
                        }
                    },
                    error: function (xhr) {
                        console.error(xhr.responseText);
                        alert("Something went wrong.");
                    },
                });

                return false; // prevent form default behavior
            },
        };
        AppSetting = {
            addArchivePackage: function (e, output_id) {
                let package_name = $("#package_name").val();
                let package_size = $("#package_size").val();
                let package_status = $("#package_status").val();
                if (
                    package_name.length === 0 ||
                    package_size.length === 0 ||
                    package_status.length === 0
                ) {
                    return false;
                } else {
                    if (!confirm("Are you sure?")) {
                        return false;
                    } else {
                        const url =
                            window.location.origin +
                            sourceDir +
                            "/system-operation/archive-package-add";
                        $.ajax({
                            url: url,
                            headers: {
                                "X-CSRF-TOKEN": $(
                                    'meta[name="csrf-token"]'
                                ).attr("content"),
                            },
                            method: "POST",
                            data: {
                                package_name: package_name,
                                package_size: package_size,
                                package_status: package_status,
                            },
                            success: function (response) {
                                if (response.status === "error") {
                                    alert("Error: " + response.message);
                                } else if (response.status === "success") {
                                    alert(response.message);
                                    $("#" + output_id).html(response.data);
                                }
                                return true;
                            },
                        });
                    }
                }
            },
            editArchivePackage: function (e, output_id) {
                let edit_id = $(e).attr("ref");
                if (edit_id.length === 0) {
                    return false;
                } else {
                    if (!confirm("Are you sure?")) {
                        return false;
                    } else {
                        const url =
                            window.location.origin +
                            sourceDir +
                            "/system-operation/archive-package-edit";
                        $.ajax({
                            url: url,
                            headers: {
                                "X-CSRF-TOKEN": $(
                                    'meta[name="csrf-token"]'
                                ).attr("content"),
                            },
                            method: "POST",
                            data: { edit_id: edit_id },
                            success: function (response) {
                                if (response.status === "error") {
                                    alert("Error: " + response.message);
                                } else if (response.status === "success") {
                                    $("#" + output_id + "_content").html(
                                        response.data
                                    );
                                    $("#" + output_id).modal("show");
                                }
                                return true;
                            },
                        });
                    }
                }
            },
            updateArchivePackage: function (e, output_id) {
                let package_name = $("#edit_package_name").val();
                let package_size = $("#edit_package_size").val();
                let package_status = $("#edit_package_status").val();
                let package_id = $("#edit_package_id").val();
                if (
                    package_name.length === 0 ||
                    package_size.length === 0 ||
                    package_status.length === 0 ||
                    package_id.length === 0
                ) {
                    return false;
                } else {
                    if (!confirm("Are you sure?")) {
                        return false;
                    } else {
                        const url =
                            window.location.origin +
                            sourceDir +
                            "/system-operation/archive-package-update";
                        $.ajax({
                            url: url,
                            headers: {
                                "X-CSRF-TOKEN": $(
                                    'meta[name="csrf-token"]'
                                ).attr("content"),
                            },
                            method: "POST",
                            data: {
                                package_name: package_name,
                                package_size: package_size,
                                package_status: package_status,
                                package_id: package_id,
                            },
                            success: function (response) {
                                if (response.status === "error") {
                                    alert("Error: " + response.message);
                                } else if (response.status === "success") {
                                    alert(response.message);
                                    $("#" + output_id).html(response.data);
                                }
                                return true;
                            },
                        });
                    }
                }
            },
            deleteArchivePackage: function (e, output_id) {
                let delete_id = $(e).attr("ref");
                if (delete_id.length === 0) {
                    return false;
                }
            },
        };
    });
    function checkFileExists(url, callback) {
        const xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                callback(xhr.status === 200);
            }
        };
        xhr.open("HEAD", url, true);
        xhr.send();
    }
    function setSelectBoxBlank(targetID) {
        const $select = $("#" + targetID);
        // Ensure Selectize is initialized
        if ($select[0] && $select[0].selectize) {
            const selectize = $select[0].selectize;
            selectize.clear();
            selectize.clearOptions(); // Clear existing options
            selectize.refreshOptions(true); // Refresh the options in the select box
        } else {
            console.error("Selectize is not initialized for #" + id);
        }
    }
    function updateSelectBox(data, id, value, value_name) {
        // alert('ok');
        const $select = $("#" + id);
        // Ensure Selectize is initialized
        if ($select[0] && $select[0].selectize) {
            const selectize = $select[0].selectize;

            selectize.clear();
            selectize.clearOptions(); // Clear existing options
            if (data.length > 0) {
                selectize.addOption({ value: 0, text: "@ All" });
            }
            data.forEach(function (item) {
                selectize.addOption({
                    value: item[value],
                    text: item[value_name],
                });
            });

            selectize.refreshOptions(true); // Refresh the options in the select box
        } else {
            console.error("Selectize is not initialized for #" + id);
        }
    }
    function updateSelectBoxWithNone(data, id, value, value_name) {
        const $select = $("#" + id);
        // Ensure Selectize is initialized
        if ($select[0] && $select[0].selectize) {
            const selectize = $select[0].selectize;

            selectize.clear();
            selectize.clearOptions(); // Clear existing options
            selectize.addOption({ value: 0, text: "0 - None" });
            data.forEach(function (item) {
                selectize.addOption({
                    value: item[value],
                    text: item[value_name],
                });
            });

            selectize.refreshOptions(true); // Refresh the options in the select box
        } else {
            console.error("Selectize is not initialized for #" + id);
        }
    }
    function updateSelectBoxSingleOption(data, id, value, value_name) {
        try {
            const $select = $("#" + id);
            // Ensure Selectize is initialized
            if ($select[0] && $select[0].selectize) {
                const selectize = $select[0].selectize;

                selectize.clear();
                selectize.clearOptions(); // Clear existing options
                data.forEach(function (item) {
                    selectize.addOption({
                        value: item[value],
                        text: item[value_name],
                    });
                });

                selectize.refreshOptions(true); // Refresh the options in the select box
            } else {
                console.error("Selectize is not initialized for #" + id);
            }
        } catch (error) {
            console.error("Error in updateSelectBoxSingleOption:", error);
        }
    }
})(jQuery);
