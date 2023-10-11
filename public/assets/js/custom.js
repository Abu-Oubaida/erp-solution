let Obj = {};
if(window.location.port)
{
    sourceDir = "";
}else{
    // sourceDir = "/chl/public";
    sourceDir = "";
}
(function ($){
    $(document).ajaxStop(function(){
        $("#ajax_loader").hide();
    });
    $(document).ajaxStart(function (){
        $("#ajax_loader").show();
    });
    $(document).ready(function(){
        $('#perAdd').click(function (){
            let per = $("#per").val()
            let dir = $("#dir").val()
            let ref = $("#per").attr('ref')
            // alert(window.location.origin + sourceDir + "/user-per-add")
            // return false
            if (per.length > 0 && dir.length > 0)
            {
                let url = window.location.origin + sourceDir + "/user-per-add";
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: url,
                    type: "POST",
                    data: {'per': per, 'dir': dir,'ref':ref},
                    success: function (data) {
                        console.log(data)
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
                        console.log(data)
                        try {
                            data = JSON.parse(data)
                            alert(data.error.msg)
                        } catch (e) {
                            alert('Data added successfully!')
                            window.location.reload()
                        }
                    }
                })
            }
        })
        Obj = {
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
                            if (data.error){
                                // throw data.error.msg;
                                let division = "<option></option>";
                                $("#"+actionID).append(division);
                                alert(data.error.msg)
                            }else{
                                // Parse the JSON string into an object
                                let permissions = JSON.parse(data).results;
                                // console.log(permissions)
                                // return false
                                if ( permissions.length == 0 ) {
                                    alert("No data found!")
                                    let response = "<option value=\"none\">1. None</option>";
                                    $("#"+actionID).html(response);
                                }
                                let counter = 2
                                let response = "<option value=\"none\">1. None</option>";
                                permissions.forEach(function(permission) {
                                    response += "<option value=\"" + permission.name + "\">"+ counter++ +". " + permission.display_name +"</option>";
                                    $("#"+actionID).html(response);
                                });
                            }
                        }
                    })
                }
            }
        }

    })
}(jQuery))
