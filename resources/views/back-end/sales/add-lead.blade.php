@extends('layouts.back-end.main')
@section('mainContent')
    <div class="container-fluid px-4">
        <div class="row">
            <div class="col-md-10">
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="text-capitalize text-chl">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a style="text-decoration: none;" href="#"
                            class="text-capitalize">{{ str_replace('.', ' ', \Route::currentRouteName()) }}</a>
                    </li>
                </ol>
            </div>
            <div class="col-md-2">
                <a href="{{ \Illuminate\Support\Facades\URL::previous() }}" class="btn btn-danger btn-sm float-end"><i
                        class="fas fa-chevron-left"></i> Go Back</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 card">
                <div class="card-header mt-2">
                    <div class="row">
                        <h3 class="text-capitalize"><i class="fas fa-leaf"></i> Add Lead Information</h3>
                    </div>
                </div>
                @include('back-end.sales.__add-lead-form')
            </div>
        </div>
    </div>
    <script>
        function addLeadStep1(operation, lead_id) {
            let add_lead_step1_data = {
                company_id: $("#company_id").val(),
                associate_id:$("#associate_id").val(),
                full_name: $("#full_name").val(),
                spouse: $("#spouse").val(),
                primary_mobile: $("#primary_mobile").val(),
                primary_email: $("#primary_email").val(),
                notes: $("#notes").val(),
            };
            let alternate_mobiles = {};
            if (
                $("#mobile_1").length &&
                $("#mobile_1").val().trim() !== ""
            ) {
                alternate_mobiles.mobile_1 = $("#mobile_1").val().trim();
            }
            if (
                $("#mobile_2").length &&
                $("#mobile_2").val().trim() !== ""
            ) {
                alternate_mobiles.mobile_2 = $("#mobile_2").val().trim();
            }
            if (
                $("#mobile_3").length &&
                $("#mobile_3").val().trim() !== ""
            ) {
                alternate_mobiles.mobile_3 = $("#mobile_3").val().trim();
            }
            if (
                $("#mobile_4").length &&
                $("#mobile_4").val().trim() !== ""
            ) {
                alternate_mobiles.mobile_4 = $("#mobile_4").val().trim();
            }
            if (
                $("#mobile_5").length &&
                $("#mobile_5").val().trim() !== ""
            ) {
                alternate_mobiles.mobile_5 = $("#mobile_5").val().trim();
            }
            let alternate_emails = {};
            if ($("#email_1").length && $("#email_1").val().trim() !== "") {
                alternate_emails.email_1 = $("#email_1").val().trim();
            }
            if ($("#email_2").length && $("#email_2").val().trim() !== "") {
                alternate_emails.email_2 = $("#email_2").val().trim();
            }
            if ($("#email_3").length && $("#email_3").val().trim() !== "") {
                alternate_emails.email_3 = $("#email_3").val().trim();
            }
            if ($("#email_4").length && $("#email_4").val().trim() !== "") {
                alternate_emails.email_4 = $("#email_4").val().trim();
            }
            if ($("#email_5").length && $("#email_5").val().trim() !== "") {
                alternate_emails.email_5 = $("#email_5").val().trim();
            }
            const url =
                window.location.origin + sourceDir + "/add-lead-step1";

            let alternate_mobiles_value = Object.values(alternate_mobiles);
            let alternate_emails_value = Object.values(alternate_emails);
            $.ajax({
                url: url,
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                method: "POST",
                data: {
                    add_lead_step1_data,
                    alternate_mobiles_value,
                    alternate_emails_value,
                    op_name: operation,
                    lead_id: lead_id
                },
                success: function(response) {
                    if (response.status === "error") {
                        if (typeof response.message === "object") {
                            let errorMessages = "";
                            for (const field in response.message) {
                                response.message[field].forEach((msg) => {
                                    errorMessages += msg + "\n";
                                });
                            }
                            alert(errorMessages);
                        } else {
                            alert(response.message);
                        }
                    } else if (response.status === "success") {
                        $("#commonSlot_for_multiple_step").html(response.data.view);
                    }
                },
            });

            return false;
        }
    </script>
@stop
