let Obj = {};
let hostname = window.location.hostname;
if(hostname === '127.0.0.1' ||  hostname === 'localhost')
{
    sourceDir = "/chl/public"
}else{
    sourceDir = ""
}
(function ($){
    $(document).ajaxStop(function(){
        $("#ajax_loader").hide();
        $("#ajax_loader2").hide();
    });
    $(document).ajaxStart(function (){
        $("#ajax_loader").show();
        $("#ajax_loader2").show();
    });
    $(document).ready(function(){
        const tags = [];
        const employeeDatas = [];
        const materialsTempList = [];
        $('.select-search').selectize({
            create: false,
            sortField: 'text'
        });
        $('.select-search-with-create').selectize({
            create: true,
            sortField: 'text'
        });
        $('#perAdd').click(function (){
            let per = $("#per").val()
            let dir = $("#dir").val()
            let ref = $("#per").attr('ref')
            // alert(window.location.origin + sourceDir + "/user-per-add")
            // return false
            if (per.length > 0 && dir.length > 0)
            {
                let url = window.location.origin + sourceDir + "/system-operation/user-per-add";
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: url,
                    type: "POST",
                    data: {'per': per, 'dir': dir,'ref':ref},
                    success: function (data) {
                        try {
                            data = JSON.parse(data)
                            alert(data.error.msg)
                        } catch (e) {
                            $("#f-p-list").html(data)
                            alert('Data added successfully!')
                            window.location.reload()
                        }
                    }
                })
            }
        })
        $('.per-delete').click(function (){
            if(!(confirm('Are you sure to delete this data!')))
            {
                return false
            }
            let ref = $(this).attr('ref')
            if (ref.length > 0)
            {
                let url = window.location.origin + sourceDir + "/user-per-delete";
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: url,
                    type: "POST",
                    data: {'ref':ref},
                    success: function (data) {
                        try {
                            data = JSON.parse(data)
                            alert(data.error.msg)
                        } catch (e) {
                            alert('Data delete successfully!')
                            window.location.reload()
                        }
                    }
                })
            }
        })
        $('#file_upload').on('change',function (e){
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader()
                reader.onload = function (e) {
                    const data = e.target.result
                    const workbook = XLSX.read(data, { type: "binary" })
                    const firstSheet = workbook.Sheets[workbook.SheetNames[0]]
                    const jsonData = XLSX.utils.sheet_to_json(firstSheet, { header: 1 })
                    if(jsonData[0].length !== 13)
                    {
                        alert('Invalid input data! Please flowing the prototype of data format!')
                        return false
                    }
                    if (!((jsonData[0][0] === 'Employee ID*' || jsonData[0][0] === 'Employee ID') && (jsonData[0][1] === 'Name') && (jsonData[0][2] === 'Department') && (jsonData[0][3] === 'Financial Year From*' || jsonData[0][3] === 'Financial Year From' ) && (jsonData[0][4] === 'Financial Year To*' || jsonData[0][4] === 'Financial Year To' ) && (jsonData[0][5] === 'Basic*' || jsonData[0][5] === 'Basic' ) && (jsonData[0][6] === 'House Rent*' || jsonData[0][6] === 'House Rent' ) && (jsonData[0][7] === 'Conveyance*' || jsonData[0][7] === 'Conveyance' ) && (jsonData[0][8] === 'Medical Allowance*' || jsonData[0][8] === 'Medical Allowance' ) && (jsonData[0][9] === 'Total' ) && (jsonData[0][10] === 'Festival Bonus*' || jsonData[0][10] === 'Festival Bonus' ) && (jsonData[0][11] === 'Others' ) && (jsonData[0][12] === 'Remarks' ) ))
                    {
                        alert('Invalid input data! Please flowing the prototype of data format!')
                        return false
                    }
                    for (let i = 0; i < jsonData.length; i++) {
                        for (let j = 0; j < jsonData[i].length; j++) {
                            if(typeof jsonData[i][j] === 'undefined')
                            {
                                jsonData[i][j] = 0
                            }
                            if (i > 0)
                            {
                                if (typeof jsonData[0][9] !== 'undefined' && jsonData[0][9] !== null)
                                {
                                    let total = parseInt(jsonData[i][9])
                                    jsonData[i][5] = ((total * 60)/100)
                                    jsonData[i][6] = ((total * 30)/100)
                                    jsonData[i][7] = ((total * 5)/100)
                                    jsonData[i][8] = ((total * 5)/100)
                                }
                                if ( j === 3 || j === 4)
                                {
                                    jsonData[i][j] = ExcelDateToJSFinancialDate(jsonData[i][j])
                                }
                            }
                        }
                    }
                    employeeDatas.push(jsonData)
                    showModal(employeeDatas,file.name);
                };
                reader.readAsBinaryString(file)
            }
        });

        $('#employee_file_upload').on('change',function (e){
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader()
                reader.onload = function (e) {
                    const data = e.target.result
                    const workbook = XLSX.read(data, { type: "binary" })
                    const firstSheet = workbook.Sheets[workbook.SheetNames[0]]
                    const jsonData = XLSX.utils.sheet_to_json(firstSheet, { header: 1 })
                    if(jsonData[0].length !== 10)
                    {
                        alert('Invalid input data! Please flowing the prototype of data format!')
                        return false
                    }
                    if (!((jsonData[0][0] === 'Employee Name*' || jsonData[0][0] === 'Employee Name') && (jsonData[0][1] === 'Department') && (jsonData[0][2] === 'Department Code*') && (jsonData[0][2] === 'Department Code' || jsonData[0][3] === 'Designation*' ) && (jsonData[0][3] === 'Designation' || jsonData[0][4] === 'Branch' ) && (jsonData[0][5] === 'Joining Date*' || jsonData[0][5] === 'Joining Date' ) && (jsonData[0][6] === 'Phone') && (jsonData[0][7] === 'Email') && (jsonData[0][8] === 'Status') && (jsonData[0][9] === 'Blood Group')))
                    {
                        alert('Invalid input data! Please flowing the prototype of data format!')
                        return false
                    }
                    for (let i = 0; i < jsonData.length; i++) {
                        let zero = jsonData[0].length
                        for (let j = 0; j < zero; j++) {
                            if(typeof jsonData[i][j] === 'undefined')
                            {
                                jsonData[i][j] = null
                            }
                            else if (i !== 0 && j === 5)
                            {
                                jsonData[i][j] = ExcelDateToJSDate(jsonData[i][j])
                            }
                        }
                    }
                    employeeDatas.push(jsonData)
                    showModal(employeeDatas,file.name);
                };
                reader.readAsBinaryString(file)
            }
        });
        let fixedDiv = $('#fixedDiv');
        if (typeof (fixedDiv.offset()) !== 'undefined')
        {
            let initialOffset = fixedDiv.offset().top;

            $(window).scroll(function() {
                var scrollPos = $(window).scrollTop();

                if (scrollPos > initialOffset) {
                    fixedDiv.addClass('fixed');
                } else {
                    fixedDiv.removeClass('fixed');
                }
            });
        }

        $('#selected-delete').click(function (){
            if (confirm("Are you sure?"))
            {
                // Create an array to store the checked values
                let checkedValues = [];

                // Use the :checked selector to get all checked checkboxes
                $('input[type="checkbox"]:checked').each(function () {
                    // Add the value of each checked checkbox to the array
                    checkedValues.push($(this).val());
                });

                // Display the result (you can modify this part based on your needs)
                alert('Checked values: ' + checkedValues.join(', '));
            }
        })

        // Select All checkbox
        $('#select_all').change(function() {
            $('.check-box').prop('checked', this.checked);
        });

        // Individual checkboxes
        $('.check-box').change(function() {
            if (!this.checked) {
                $('#select_all').prop('checked', false);
            }
        });
        // Function to display the modal
        function showModal(data,fileName) {
            const modal = document.getElementById("myModal");
            const modelTitle = document.getElementById("userDataModelLabel")
            const dataTable = document.getElementById("data-table");
            while (dataTable.firstChild) {
                dataTable.removeChild(dataTable.firstChild)
            }

            // Create a table from the data
            const table = document.createElement("table")
            table.className = "table"
            let t=0
            let action
            let row_o = 1;
            data[0].forEach(rowData => {
                const row = document.createElement("tr")
                let cell
                if(t===0)
                {
                    action = "Action"
                    cell = document.createElement("td")
                    cell.textContent = "SL"
                    row.appendChild(cell)
                }
                else
                {
                    action = '<a style="cursor: pointer" class="text-danger" onclick="return confirm(`Are you sure?`)? Obj.removeElementOfEmployeeData(this,'+t+',`'+fileName+'`):false"><i class="fa fa-trash" aria-hidden="true"></i></a>'
                    cell = document.createElement("td")
                    cell.textContent = row_o
                    row_o++;
                    row.appendChild(cell)
                }
                rowData.forEach(cellData => {
                    cell = document.createElement("td")
                    cell.textContent = cellData
                    row.appendChild(cell)
                });
                cell = document.createElement("td")
                cell.innerHTML=action
                row.appendChild(cell)
                table.appendChild(row)
                t++
            });

            // Append the table to the modal
            dataTable.appendChild(table)
            modelTitle.innerText = fileName
            $('#myModal').modal('show')
        }
        function ExcelDateToJSDate(serial) {
            const dateToString = d => `${('00' + d.getDate()).slice(-2)}-${('00' + (d.getMonth() + 1)).slice(-2)}-${d.getFullYear()}`
            return dateToString(new Date(Math.round((serial - 25569)*86400*1000)));
        }
        function ExcelDateToJSFinancialDate(serial) {
            if (typeof serial !== 'number')
            {
                return serial
            }
            const dateToString = d => {
                const months = [
                    "January", "February", "March", "April", "May", "June",
                    "July", "August", "September", "October", "November", "December"
                ];
                const monthsShort = [
                    "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                    "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
                ];

                const monthName = monthsShort[d.getMonth()];
                return `${monthName}-${d.getFullYear()}`;
            };

            return dateToString(new Date(Math.round((serial - 25569) * 86400 * 1000)));
        }
        Obj = {
            permissionInput:function (e)
            {
                let is_parent = null
                const permission_parent = $('#permission_parent').val()
                const permission_name = $('#permission_name').val()
                const permission_display_name = $('#permission_display_name').val()
                const description = $('#description').val()
                if($('#is_parent').is(':checked')) is_parent = 1
                if (permission_parent.length === 0 || permission_display_name.length === 0 || permission_name.length === 0)
                {
                    return false
                }
                let url = window.location.origin + sourceDir + "/system-operation/permission-store";
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: url,
                    type: "POST",
                    data: {'permission_parent':permission_parent,'permission_name':permission_name,'permission_display_name':permission_display_name,'is_parent':is_parent,'description':description},
                    success: function(response){
                        if (response.status === 'success')
                        {
                            alert(response.message)
                            $('#permission-list').html(response.data)
                            if (is_parent)window.location.reload()
                        }
                        if (response.status === 'error')
                        {
                            alert("Error:"+response.message)
                            // Handle error
                        }
                    }
                })
            },
            authCompany: function (e){
                const username = $("#email").val()
                const password = $("#password").val()
                if (username.length === 0 || password.length === 0)
                {
                    $("#companySelect").html("<option></option>");
                    $("#login").attr('disabled','disabled')
                    $("#login").hide()
                    return false
                }
                const url = window.location.origin + sourceDir + "/company-check-set"
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: url,
                    type: "POST",
                    data: {'username':username,'password':password},
                    success: function (response) {
                        if (response.status === 'error')
                        {
                            // alert('Error:'+response.message)
                            $("#companySelect").html("<option></option>");
                            $("#login").attr('disabled','disabled')
                            $("#login").hide()
                        }
                        else {
                            // Display the list of companies
                            let companies = response.companies;
                            let companySelect = $('#companySelect');
                            companySelect.empty();
                            $.each(companies, function (index, company) {
                                companySelect.append(new Option(company.company_name, company.id));
                            });
                            $("#login").removeAttr('disabled')
                            $("#login").show()

                        }
                    },
                    error: function (error) {
                        // alert(error.responseJSON.message);
                        $("#companySelect").html("<option></option>");
                        $("#login").attr('disabled','disabled')
                        $("#login").hide()
                    }
                })
            },
            changeUserCompany:function (e){
                let company = $(e).val()
                if (company.length === 0)
                {
                    return false
                }
                let url = window.location.origin + sourceDir + "/change-user-company";
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: url,
                    type: "POST",
                    data: {'company_id':company},
                    success: function (response) {
                        if (response.status === 'error')
                        {
                            return alert(response.message)
                        }
                        if (response.status === 'success')
                        {
                            updateSelectBox(response.data.departments,'dept_menu','id','dept_name')
                            updateSelectBox(response.data.branches,'branch_menu','id','branch_name')
                            updateSelectBox(response.data.designations,'designation_menu','id','title')
                            updateSelectBox(response.data.roles,'role_menu','id','display_name')
                            $("#employee_id").val('')
                            $("#employee_id_hide").val('')
                        }
                    },
                    error: function (error)
                    {
                        return alert(error.responseJSON.message)
                    }
                })
            },
            changeBranchCompany:function (e){
                let company = $(e).val()
                if (company.length === 0)
                {
                    return false
                }
                let url = window.location.origin + sourceDir + "/change-branch-company";
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: url,
                    type: "POST",
                    data: {'company_id':company},
                    success: function (response) {
                        if (response.status === 'error')
                        {
                            return alert(response.message)
                        }
                        if (response.status === 'success')
                        {
                            // console.log(response.data.branchTypes)
                            updateSelectBox(response.data.branchTypes,'branch_type','id','title')
                        }
                    },
                    error: function (error)
                    {
                        return alert(error.responseJSON.message)
                    }
                })
            },
            makeEmployeeID:function (dept_menu,companyID,joining_date){
                let company_id = $("#"+companyID).val()
                let department_id = $("#"+dept_menu).val()
                let joining = $("#"+joining_date).val()
                if (company_id.length === 0)
                {
                    alert("Empty Company ID")
                    return false
                }
                if (department_id.length === 0)
                {
                    alert("Empty Department ID")
                    return false
                }
                if (joining.length === 0)
                {
                    alert("Empty Joining Date")
                    return false
                }
                let url = window.location.origin + sourceDir + "/get-employee-id";
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: url,
                    type: "POST",
                    data: {'department_id':department_id,'company_id':company_id,'joining_date':joining},
                    success: function (response) {
                        if (response.status === 'error')
                        {
                            return alert(response.message)
                        }
                        if (response.status === 'success')
                        {
                            console.log(response)
                            $("#employee_id").val(response.data[1])
                            $("#employee_id_hide").val(response.data[0])
                        }
                    },
                    error: function (error)
                    {
                        return alert(error.responseJSON.message)
                    }

                })
            },
            fiendPermissionChild : function (e,actionID) {
                let id = $(e).val()
                if (id)
                {
                    let url = window.location.origin + sourceDir + "/fiend-permission-child";
                    $.ajax({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url: url,
                        type: "POST",
                        data: {'pid':id},
                        success: function (data) {
                            if (data.error) {
                                let division = "<option></option>";
                                $("#" + actionID).append(division);
                                alert(data.error.msg);
                            } else {
                                let permissions = data.results;
                                updateSelectBox(permissions,actionID,'name','display_name')
                            }
                        }
                    })
                }
            },
            removeElementOfEmployeeData:function (e,index,file){
                employeeDatas[0].splice(index, 1)
                showModal(employeeDatas,file)
            },
            employeeDataSubmit:function (e) {
                if (employeeDatas[0].length <= 1)
                {
                    alert('Empty data set please upload your excel file on the input field!')
                    return false
                }
                let url = window.location.origin + sourceDir + "/salary-certificate-input-excel";
                $.ajax({
                    headers: {'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')},
                    contentType: 'application/json',
                    url: url,
                    type: "POST",
                    data: JSON.stringify({'input': employeeDatas[0]}),
                    success:function (data)
                    {
                        if (data.error) {
                            let alertMessage = data.message + '\nErrors:\n'
                            if (data.errors)
                            {
                                for (let field in data.errors) {
                                    if (data.errors.hasOwnProperty(field)) {
                                        alertMessage += field + ': ' + data.errors[field].join(', ') + '\n'
                                    }
                                }
                            }
                            alert(alertMessage)
                            return false
                        } else {
                            // Handle success
                            let alertMessage = ''
                            if (data.errorMessage)
                            {
                                // Extract and display the Error Message
                                alertMessage += "Error! This Data Are Added not Possible:\n"
                                for (let key in data.errorMessage) {
                                    let employee = data.errorMessage[key]
                                    alertMessage += `Employee ID: ${employee["Employee ID"]}, Name: ${employee.Name}, Department: ${employee.Department}\n`
                                }
                            }
                            if (data.successMessage)
                            {
                                // Extract and display the Success Message
                                alertMessage += "This Data Are Added Successfully:\n"
                                for (let key in data.successMessage) {
                                    let employee = data.successMessage[key]
                                    alertMessage += `Employee ID: ${employee["Employee ID"]}, Name: ${employee.Name}, Department: ${employee.Department}\n`
                                }
                            }
                            if (data.alreadyHasMessage)
                            {
                                // Extract and display the alreadyHasMessage
                                alertMessage += "This Data are Already Exists in DB:\n"
                                for (let key in data.alreadyHasMessage) {
                                    let employee = data.alreadyHasMessage[key]
                                    alertMessage += `Employee ID: ${employee["Employee ID"]}, Name: ${employee.Name}, Department: ${employee.Department}\n`
                                }
                            }
                            alert(alertMessage)
                            window.location.reload()
                            return true
                        }
                    }
                })
            },
            salaryDistribute:function (e){
                let total = $('#total').val()
                if (total.length)
                {
                    total = parseInt(total)
                    let basic = ((total*60) / 100)
                    let house_rent = ((total*30) / 100)
                    let conveyance = ((total*5) / 100)
                    let ma = ((total*5) / 100)
                    $('#basic').val(basic).attr('readonly','readonly')
                    $('#house_rent').val(house_rent).attr('readonly','readonly')
                    $('#conveyance').val(conveyance).attr('readonly','readonly')
                    $('#medical').val(ma).attr('readonly','readonly')
                }
                else {
                    $('#basic').val('').removeAttr('readonly')
                    $('#house_rent').val('').removeAttr('readonly')
                    $('#conveyance').val('').removeAttr('readonly')
                    $('#medical').val('').removeAttr('readonly')
                }
            },
            userExcelFileSubmit:function (e) {
                if (employeeDatas[0].length <= 1)
                {
                    alert('Empty data set please upload your excel file on the input field!')
                    return false
                }
                let url = window.location.origin + sourceDir + "/add-user-excel";
                $.ajax({
                    headers: {'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')},
                    contentType: 'application/json',
                    url: url,
                    type: "POST",
                    data: JSON.stringify({'input': employeeDatas[0]}),
                    success:function (data)
                    {
                        if (data.error) {
                            let alertMessage = data.message + '\nErrors:\n'
                            if (data.errors)
                            {
                                for (let field in data.errors) {
                                    if (data.errors.hasOwnProperty(field)) {
                                        alertMessage += field + ': ' + data.errors[field].join(', ') + '\n'
                                    }
                                }
                            }
                            alert(alertMessage)
                            return false
                        } else {
                            // Handle success
                            let alertMessage = ''
                            if (data.errorMessage)
                            {
                                // Extract and display the Error Message
                                alertMessage += "Error! This Data Are Added not Possible:\n"
                                for (let key in data.errorMessage) {
                                    let employee = data.errorMessage[key]
                                    alertMessage += `Employee name: ${employee["Employee name"]}, Phone: ${employee["phone"]}, Email: ${employee["email"]}\n`
                                }
                            }
                            if (data.successMessage)
                            {
                                // Extract and display the Success Message
                                alertMessage += "This Data Are Added Successfully:\n"
                                for (let key in data.successMessage) {
                                    let employee = data.successMessage[key]
                                    alertMessage += `Employee name: ${employee["Employee name"]}, Phone: ${employee["phone"]}, Email: ${employee["email"]}\n`
                                }
                            }
                            if (data.alreadyHasMessage)
                            {
                                // Extract and display the alreadyHasMessage
                                alertMessage += "This Data are Already Exists in DB:\n"
                                for (let key in data.alreadyHasMessage) {
                                    let employee = data.alreadyHasMessage[key]
                                    alertMessage += `Employee name: ${employee["Employee name"]}, Phone: ${employee["phone"]}, Email: ${employee["email"]}\n`
                                }
                            }
                            alert(alertMessage)
                            window.location.reload()
                            return true
                        }
                    }
                })
            },

            findDocument: function (e,actionID,actionID2,actionID3){
                let path = $(e).attr('path')
                let v_type = $(e).attr('vtype')
                let v_no = $(e).attr('vno')
                if (path)
                {
                    let url = window.location.origin + sourceDir + "/fiend-voucher-document";
                    $.ajax({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url: url,
                        type: "POST",
                        data: {'path':path},
                        success: function(pdfPreviewUrl){
                            // Check if the file exists
                            checkFileExists(pdfPreviewUrl, function(exists) {
                                if (exists) {
                                    const fileExtension = pdfPreviewUrl.split('.').pop().toLowerCase();
                                    const fileName = pdfPreviewUrl.split('/').pop();
                                    $('#v_document_name').html(fileName)
                                    $('#'+actionID2).html(v_type);
                                    $('#'+actionID3).html(v_no);
                                    if (['jpg', 'jpeg', 'png', 'gif','pdf','PDF'].includes(fileExtension)) {
                                        // Preview PDF in iframe
                                        const embedTag = '<embed src="'+pdfPreviewUrl+'#toolbar=0" style="width:100%; height:700px;" />'
                                        $('#'+actionID).html(embedTag);
                                    } else if (['mp4', 'webm', 'ogg'].includes(fileExtension)) {
                                        // Play video
                                        // Modify this to fit your video display logic
                                        const videoTag = `<video controls style="width: 100%"><source src="${pdfPreviewUrl}" type="video/mp4">Your browser does not support the video tag.</video>`;
                                        $('#'+actionID).html(videoTag);
                                        // $('#'+actionID).replaceWith(videoTag);
                                        // $('#staticBackdrop').modal('show');
                                        // $('#pdfPreviewModal').modal('show');
                                    } else {
                                        const btn = '<div class="row">\n' +
                                        '                        <div class="col-md-12 text-center">\n' +
                                        '                            <h1 class="text-center">Sorry! This file type is not supported for preview.</h1>\n' +
                                        '                            <a class="btn btn-success text-center" href="' + pdfPreviewUrl + '" download>\n' +
                                        '                                Click To Download\n' +
                                        '                            </a>\n' +
                                        '                        </div>\n' +
                                        '                    </div>'
                                        // Provide a download link
                                        $('#'+actionID).html(btn);
                                        // window.location.href = pdfPreviewUrl;
                                    }
                                    $('#staticBackdrop').modal('show');
                                } else {
                                    const error = '<div class="text-center text-danger">URL Not Found!</div>'
                                    $('#'+actionID).html(error);
                                    $('#staticBackdrop').modal('show');
                                }
                            });
                            return true
                        }
                    })
                }
                return false
            },
            fileSharingModal:function (e) {
                let id = $(e).attr('ref')
                let url = window.location.origin + sourceDir + "/fiend-voucher-document-info";
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: url,
                    type: "POST",
                    data: {'id':id},
                    success: function(data){
                        if (data.error){
                            alert(data.error.msg)
                        }else{
                            while(tags.length > 0) {
                                tags.pop();
                            }
                            $('#model_dialog').html(data)
                            $('#shareModel').modal('show')
                        }
                    }
                })
                return false
            },
            addVoucherDocumentIndividual:function (e){
                let id = $(e).attr('ref')
                let url = window.location.origin + sourceDir + "/add-voucher-document-individual";
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: url,
                    type: "POST",
                    data: {'id':id},
                    success: function(data){
                        if (data.error){
                            alert(data.error.msg)
                        }else{
                            $('#model_dialog').html(data)
                            $('#shareModel').modal('show')
                        }
                    }
                })
                return false
            },
            closeSharingModel:function (e) {
                while(tags.length > 0) {
                    tags.pop();
                }

            },
            tagInput:function (event)
            {
                const value = $(event).val()
                const regex = /\s|,/;
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                if (regex.test(value))
                {
                    const leanedData = value.replace(/[\s,]/g, '');
                    if (emailRegex.test(leanedData))
                    {
                        const tagValue = leanedData.trim();
                        const tag = $('<div>').addClass('tag').attr('onclick','return Obj.removeTag(this)').text(tagValue+' 🗙');
                        $('#tags').append(tag);
                        $('#tag-input').val('');
                        tags.push(tagValue);
                    }
                }
            },
            removeTag:function (event)
            {

                const tagValue = $(event).text();
                const index = tags.indexOf(tagValue);
                if (index !== -1) {
                    tags.splice(index, 1);
                }
                $(event).remove();
            },
            voucherShareType:function (e)
            {
                let value = $(e).val()
                let refId = $(e).attr('ref');
                if (value.length > 0)
                {
                    let url = window.location.origin + sourceDir + "/voucher-share-type";
                    $.ajax({
                        url: url,
                        method: 'POST',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        data: {value: value, refId: refId},
                        success: function(data) {
                            $('#sharedLink').val(data)
                        }
                    });
                }

            },
            copyDocumentShareLink:function (e)
            {
                let sharedLink = $("#sharedLink")
                if (sharedLink.val().length <= 0)
                {
                    return false
                }
                else {
                    sharedLink.select()
                    try {
                        // Execute the copy command
                        document.execCommand('copy');
                        $(e).html('<i class="fa-solid fa-clipboard"></i> Copied!')
                        // $(e).remove('class','btn-success')
                        $(e).addClass('btn-info')
                    } catch (err) {
                        alert('Unable to copy link:'+err);
                    }
                }
            },
            sendDocumentEmail:function (e)
            {
                const url = window.location.origin + sourceDir + "/share-voucher-document-email";
                const refId = $(e).attr('ref');
                const message = $('#message').val()
                // const data = { tags: tags, refId: refId };
                if (tags.length <= 0) {
                    alert("Error! Empty Field");
                    return false;
                } else {
                    $.ajax({
                        url: url,
                        method: 'POST',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        data: {tags: tags, refId: refId, message: message},
                        success: function(data) {
                            if (data.error){
                                alert(data.error.msg)
                                return false
                            }else {
                                data = JSON.parse(data)
                                alert(data.results)
                                if (data.results)
                                    $('#shareModel').modal('hide')
                                else
                                    return false
                            }
                        },
                        error: function(error) {
                            console.error('Error:', error);
                        }
                    });
                }
            },
            emailLinkStatusChange:function (e) {
                const ref = $(e).attr('ref')
                const status = $(e).attr('status')
                const url = window.location.origin + sourceDir + "/email-link-status-change";
                $.ajax({
                    url: url,
                    method: 'POST',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data: {ref: ref, status: status},
                    success: function(data) {
                        if (data.error){
                            alert(data.error.msg)
                        }else {
                            data = JSON.parse(data)
                            alert(data.results)
                            $('#shareModel').modal('hide')
                        }
                    },
                    error: function(error) {
                        console.error('Error:', error);
                    }
                });
            },
            getFixedAssetSpecification:function (e){
                const value = $(e).val()
                const url = window.location.origin + sourceDir + "/fixed-asset-distribution/get-fixed-asset-spec";
                $.ajax({
                    url:url,
                    method:'POST',
                    headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data:{id:value},
                    success: function (response){
                        if (response.status === 'success')
                        {
                            const fixedAsset = response.data[0].fixed_asset
                            updateSelectBox(response.data,'specification','id','specification')
                            $("#rate").val(fixedAsset.rate)
                            $("#unite").val(fixedAsset.unit)
                            $("#qty").val(1)
                            $("#total").val(fixedAsset.rate)
                        }else if (response.status === 'error')
                        {
                            alert("Error:"+response.message)
                        }
                    },
                    error: function (xhr)
                    {
                        // Handle general AJAX errors
                        console.log('AJAX Error:', xhr.statusText);
                    },
                })
            },
            fixedAssetOpeningAddList:function (e){
                const opdate = $('#date').val()
                const reference = $('#ref_hide').val()
                const r_type = $('#r_type_id_hide').val()
                const project_id = $('#project_id_hide').val()
                const materials_id = $('#materials_id').val()
                const specification = $('#specification').val()
                const rate = $('#rate').val()
                const qty = $('#qty').val()
                const purpose = $('#purpose').val()
                const remarks = $('#remarks').val()
                if (opdate.length === 0 || reference.length === 0 || project_id.length === 0 || materials_id.length === 0 || specification.length === 0 || rate.length === 0 || qty.length === 0 || r_type.length === 0)
                {
                    alert('All field are required')
                    return false
                }
                const url = window.location.origin + sourceDir + "/fixed-asset-distribution/add-fixed-asset-opening";
                $.ajax({
                    url:url,
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    method: "POST",
                    data: {'opening_date':opdate,'reference':reference,'r_type':r_type,'project_id':project_id,'materials_id':materials_id,'specification':specification,'rate':rate,'qty':qty,'purpose':purpose,'remarks':remarks},
                    success(response)
                    {
                        if (response.status === 'success')
                        {
                            $('#opening-materials-list').html(response.data)
                        }
                        else if (response.status === 'warning')
                        {
                            alert("Warning: "+response.message)
                            console.log('Error:', response)
                        }
                        else if (response.status === 'error')
                        {
                            alert("Error: "+response.message)
                            // Handle error
                            console.log('Error:', response)
                        }
                    },
                    error(xhr)
                    {
                        // Handle general AJAX errors
                        console.log('AJAX Error:', xhr.statusText);
                    }
                })
            },
            priceTotal:function (e,inputID,actionID)
            {
                const input = parseFloat($("#"+inputID).val())
                const output = $("#"+actionID)
                const self = parseFloat($(e).val())
                if (input.length === 0 || self.length === 0)
                {
                    output.val('')
                }
                output.val(parseFloat(Number(input*self)))
            },
            fixedAssetOpeningSearch:function (e, outputID)
            {
                const reference = $("#ref-src").val()
                const project = $("#project").val()
                const rt = $("#r_type").val()
                if (reference.length === 0 && project.length === 0 && rt === 0)
                {
                    alert('All Input are Required!')
                    return false
                }
                const url = window.location.origin + sourceDir + "/fixed-asset-distribution/get-fixed-asset-opening"
                $.ajax({
                    url:url,
                    method:'POST',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data:{'reference':reference,'branch_id':project,'r_type_id':rt},
                    success:function(response)
                    {
                        if (response.status === 'success')
                        {
                            $("#"+outputID).html(response.data)
                            // Get the current URL
                            var currentUrl = window.location.href
                            // Construct the new URL by appending the ref value
                            var newUrl = currentUrl.split('?')[0] + '?ref=' + reference + '&project=' + project + '&rt=' + rt
                            // Change the URL without reloading the page
                            history.pushState({ref: reference}, '', newUrl)
                        }
                        else if (response.status === 'warning')
                        {
                            alert("Warning:"+response.message)
                            $("#"+outputID).html('')
                        }
                        else if (response.status === 'error')
                        {
                            alert("Error:"+response.message)
                            // Handle error
                            console.log('Error:', response.message)
                        }
                    },
                    error:function(xhr)
                    {
                        console.log('AJAX Error:', xhr.statusText);
                    }
                })
            },
            fixedAssetOpeningSpecEdit:function (e)
            {
                const id = $(e).attr('ref')
                if (id.length === 0)
                    return false
                const url = window.location.origin + sourceDir + "/fixed-asset-distribution/edit-fixed-asset-opening-spec"
                $.ajax({
                    url: url,
                    method:'POST',
                    headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data:{'id':id},
                    success:function (response)
                    {
                        const view = response.data
                        $('#fixed-asset-spec-edit').html(view)
                        $('#editModal').modal('show')
                    }

                })
            },
            updateFixedAssetOpeningSpec:function (e)
            {
                const opdate = $('#edit-date').val()
                const id = $('#edit-id').val()
                const rate = $('#rate-edit').val()
                const qty = $('#qty-edit').val()
                const purpose = $('#edit-purpose').val()
                const remarks = $('#edit-remarks').val()
                if (opdate.length === 0 || id.length === 0 || rate.length === 0 || qty.length === 0)
                {
                    alert("Error: Empty Field Error!")
                    return false;
                }
                const url = window.location.origin + sourceDir + "/fixed-asset-distribution/update-fixed-asset-opening-spec"
                $.ajax({
                    url: url,
                    method:'POST',
                    headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data:{'id':id,'opening_date':opdate,'rate':rate,'qty':qty,'purpose':purpose,'remarks':remarks},
                    success:function (response)
                    {
                        const view = response.data
                        if (response.status === 'success')
                        {
                            alert(response.message)
                            $('#editModal').modal('hide')
                            $('#opening-materials-list').html(view)
                        }
                        if (response.status === 'error')
                        {
                            alert("Error:"+response.message)
                            // Handle error
                        }
                        return false
                    },
                    error:function(xhr)
                    {
                        console.log('AJAX Error:', xhr.statusText);
                    }

                })
            },
            deleteFixedAssetOpeningSpec:function (e)
            {
                if (confirm('Are you sure delete this data?'))
                {
                    const id = $(e).attr('ref')
                    if (id.length === 0)
                        return false
                    const url = window.location.origin + sourceDir + "/fixed-asset-distribution/delete-fixed-asset-opening-spec"
                    $.ajax({
                        url: url,
                        method:'DELETE',
                        headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        data:{'id':id},
                        success:function (response)
                        {
                            if (response.status === 'success')
                            {
                                alert(response.message)
                                const view = response.data
                                $('#opening-materials-list').html(view)
                            }
                            if (response.status === 'error')
                            {
                                alert("Error:"+response.message)
                                // Handle error
                            }
                            return false
                        }

                    })
                }
            },
            userProjectPermissionSearch:function (e)
            {
                const value = $('#user').val()
                if (value.length === 0)
                {
                    return false;
                }
                const url = window.location.origin + sourceDir + "/control-panel/user-project-permission-search"
                $.ajax({
                    url: url,
                    method: 'POST',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data: {'user':value},
                    success:function (response)
                    {
                        if (response.status === 'success')
                        {
                            // alert(response.message)
                            const view = response.data
                            $('#user-project-permission-add-list').html(view)
                        }
                        if (response.status === 'error')
                        {
                            alert("Error:"+response.message)
                            // Handle error
                        }
                        return false

                    }
                })
            },
            userProjectPermissionAdd:function (e)
            {
                const project_id = $('#project').val()
                const user_id = $('#user_id').val()
                if (project_id.length === 0 || user_id.length === 0)
                {
                    alert('All filed are required')
                    return false;
                }
                const url = window.location.origin + sourceDir + "/control-panel/user-project-permission-add"
                $.ajax({
                    url: url,
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    method: 'POST',
                    data: {'project_id':project_id,'user_id':user_id},
                    success:function (response)
                    {
                        if (response.status === 'success')
                        {
                            alert(response.message)
                            const view = response.data
                            $('#user-permission-list').html(view)
                        }
                        if (response.status === 'error')
                        {
                            alert("Error:"+response.message)
                            // Handle error
                        }
                        return false
                    }
                })
            },
            userProjectPermissionCopy:function (e)
            {
                if(confirm('Are you sure to copy all permissions?'))
                {
                    const copy_user = $('#copy_user').val()
                    const user_id = $('#user_id').val()
                    if (copy_user.length === 0 || user_id.length === 0)
                    {
                        alert('All filed are required')
                        return false;
                    }
                    const url = window.location.origin + sourceDir + "/control-panel/user-project-permission-copy"
                    $.ajax({
                        url: url,
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        method: 'POST',
                        data: {'copy_user_id':copy_user,'user_id':user_id},
                        success:function (response)
                        {
                            if (response.status === 'success')
                            {
                                alert(response.message)
                                const view = response.data
                                $('#user-permission-list').html(view)
                            }
                            if (response.status === 'error')
                            {
                                alert("Error:"+response.message)
                            }
                            return false
                        }
                    })
                }
            },
            userProjectPermissionAddAll:function (e)
            {
                if(confirm('Are you sure add all project permission?'))
                {
                    const user_id = $('#user_id').val()
                    if (user_id.length === 0)
                    {
                        alert('All filed are required')
                        return false;
                    }
                    const url = window.location.origin + sourceDir + "/control-panel/user-project-permission-add-all"
                    $.ajax({
                        url: url,
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        method: 'POST',
                        data: {'user_id':user_id},
                        success:function (response)
                        {
                            if (response.status === 'success')
                            {
                                alert(response.message)
                                const view = response.data
                                $('#user-permission-list').html(view)
                            }
                            if (response.status === 'error')
                            {
                                alert("Error:"+response.message)
                                // Handle error
                            }
                            return false
                        }
                    })
                }
                return false
            },
            userProjectPermissionDelete:function (e)
            {
                if(confirm('Are you sure delete this data?'))
                {
                    const value = $(e).attr('ref')
                    if (value.length === 0)
                    {
                        return false
                    }
                    const url = window.location.origin + sourceDir + "/control-panel/user-project-permission-delete"
                    $.ajax({
                        url: url,
                        headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        method: 'POST',
                        data: {'value':value},
                        success:function (response)
                        {
                            if (response.status === 'success')
                            {
                                alert(response.message)
                                const view = response.data
                                $('#user-permission-list').html(view)
                            }
                            if (response.status === 'error')
                            {
                                alert("Error:"+response.message)
                                // Handle error
                            }
                            return false
                        }
                    })
                }
                return false
            },
            userProjectPermissionDeleteAll:function (e)
            {
                if(confirm('Are you sure to delete all permission for this user?'))
                {
                    const user_id = $('#user_id').val()
                    if (user_id.length === 0)
                    {
                        return false
                    }
                    const url = window.location.origin + sourceDir + "/control-panel/user-project-permission-delete-all"
                    $.ajax({
                        url: url,
                        headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        method: 'POST',
                        data: {'user_id':user_id},
                        success:function (response)
                        {
                            if (response.status === 'success')
                            {
                                alert(response.message)
                                const view = response.data
                                $('#user-permission-list').html(view)
                            }
                            if (response.status === 'error')
                            {
                                alert("Error:"+response.message)
                                // Handle error
                            }
                            return false
                        }
                    })
                }
                return false
            },
            deleteFixedAssetOpening:function (e)
            {
                if (confirm("Are you sure to delete this data?"))
                {
                    const id = $(e).attr('ref')
                    if (id.length === 0)
                    {
                        return false
                    }
                    const url = window.location.origin + sourceDir + "/fixed-asset-distribution/delete-fixed-asset-opening"
                    $.ajax({
                        url: url,
                        headers: {'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')},
                        method: "DELETE",
                        data:{'id':id},
                        success:function (response)
                        {
                            if (response.status === 'success')
                            {
                                alert(response.message)
                                const view = response.data
                                window.location.reload()
                            }
                            if (response.status === 'error')
                            {
                                alert("Error:"+response.message)
                                // Handle error
                            }
                            return false
                        }
                    })
                }
                return false
            },
            deleteFixedAssetWithRefDocument:function (e)
            {
                if (confirm('Are you sure to delete this data?'))
                {
                    const id = $(e).attr('ref')
                    if (id.length === 0)
                    {
                        return false
                    }
                    const url = window.location.origin + sourceDir + "/fixed-asset-distribution/delete-fixed-asset-with-ref-document"
                    $.ajax({
                        url: url,
                        headers: {'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')},
                        method: "DELETE",
                        data:{'id':id},
                        success:function (response)
                        {
                            if (response.status === 'success')
                            {
                                alert(response.message)
                                window.location.reload()
                            }
                            if (response.status === 'error')
                            {
                                alert("Error:"+response.message)
                                // Handle error
                            }
                            return false
                        }
                    })

                }
                return false
            }

        }
        function checkFileExists(url, callback) {
            const xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    callback(xhr.status === 200);
                }
            };
            xhr.open('HEAD', url, true);
            xhr.send();
        }

    })
    function updateSelectBox(data,id,value,value_name) {
        const $select = $('#' + id);
        // Ensure Selectize is initialized
        if ($select[0] && $select[0].selectize) {
            const selectize = $select[0].selectize;

            selectize.clear();
            selectize.clearOptions(); // Clear existing options

            data.forEach(function(item) {
                selectize.addOption({ value: item[value], text: item[value_name] });
            });

            selectize.refreshOptions(true); // Refresh the options in the select box
        } else {
            console.error("Selectize is not initialized for #" + id);
        }
    }
}(jQuery))
