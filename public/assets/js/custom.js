let Obj = {};
if(window.location.port)
{
    sourceDir = "";
}else{
    // sourceDir = "/chl/public";
    sourceDir = "";
}
(function ($){
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
        // Obj = {
        //     receivedComplainAction : function (e,actionID) {
        //         // $('#'+actionID).addClass('show')
        //         // $('#'+actionID).removeAttr("aria-hidden")
        //         // $('#'+actionID).attr("aria-modal",'true')
        //         // $('#'+actionID).attr("role",'dialog')
        //         // $('#'+actionID).show()
        //         $('.modal-body').html("<h1>Hello</h1>")
        //     }
        // }

    })
}(jQuery))
