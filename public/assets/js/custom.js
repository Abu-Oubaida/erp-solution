let Obj = {};
(function ($){
    $(document).ready(function(){

        Obj = {
            receivedComplainAction : function (e,actionID) {
                // $('#'+actionID).addClass('show')
                // $('#'+actionID).removeAttr("aria-hidden")
                // $('#'+actionID).attr("aria-modal",'true')
                // $('#'+actionID).attr("role",'dialog')
                // $('#'+actionID).show()
                $('.modal-body').html("<h1>Hello</h1>")
            }
        }

    })
}(jQuery))
