//import ".vendor\jquery\jquery.min.js";
//import ".vendor\bootstrap\js\bootstrap.min.js";
//import ".vendor\jquery\jquery.easing.min.js";
//import ".vendor\scrollreveal\scrollreveal.min.js";
//import ".vendor\magnific-popup\jquery.magnific-popup.min.js";
//import ".vendor\js\jquery.validate.min.js";
//import ".vendor\js\creative.min.js";


var new_complainant = false;
var complainant = 0;
var complainant_id = 0;
var complainant_name = 0;

function reset() {

    complainant_id = 0;
    complainant_name = 0;
    $('#formReset').click();
    $('#loginCancel').click()
    $('#complainCancel').click()

}

function collectName(state) {
    if (state) {
        $('[name="complainantTitle"]').removeClass('hidden');
        $('[name="complainantFirstName"]').removeClass('hidden');
        $('[name="complainantLastName"]').removeClass('hidden');
    } else {
        $('[name="complainantTitle"]').addClass('hidden');
        $('[name="complainantFirstName"]').addClass('hidden');
        $('[name="complainantLastName"]').addClass('hidden');
    }
    new_complainant = state;
}

//------------------

function identifyComplainant(pend, _alert) {
    $.ajax("./actions/get.php",
            {
                type: 'POST',
                async: false,
                data: {
                    email1: $("[name='complainantEmail" + pend + "']").val(),
                    phone_mobile: $("[name='complainantPhone" + pend + "']").val(),
                    mobile: $("[name='complainantPhone" + pend + "']").val(),
//                    name_request: _alert?'yes':'no',
                    module: 'Contacts'
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Sorry a sever error was experienced.');
                },
                success: function (data, textStatus, jqXHR) {
                    if (data == 1) {
                        if (_alert)
                            alert("Sorry These Details have not been used to lodge a comlpiant yet..!\n\n");
                        return false;
                    }
                    if (data == 2) {
                        if (_alert)
                            alert("Wrong Email Address..!\n\n");
                        return false;
                    }
                    if (data == 3) {
                        if (_alert)
                            alert("Wrong Phone Number..!\n\n");
                        return false;
                    }
                    complainant_id = data.id;
                    complainant_name = data.name;
//                    alert(data.name);
                    return true;
                }
            });
}

function registerComplainant() {
    $.ajax("./actions/set.php",
            {
                type: 'POST',
                async: false,
                data: {
                    salutation: $("[name='complainantTitle']").val(),
                    title: $("[name='complainantTitle']").val(),
                    first_name: $("[name='complainantFirstName']").val(),
                    last_name: $("[name='complainantLastName']").val(),
                    email1: $("[name='complainantEmail']").val(),
                    phone_mobile: $("[name='complainantPhone']").val(),
                    mobile: $("[name='complainantPhone']").val(),
                    module: 'Contacts'
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Sorry a sever error was experienced.');
                },
                success: function (data, textStatus, jqXHR) {
                    if (data != '') {
//                        alert(data);
                        $('#complainantClose').click();
                        alert("Thank You. You have been registered.\n\n Your Comlpaint Is Being Submitted");
                    }
                    complainant_id = data.id;
                    complainant_name = data.name;
                }
            });
}

function submitComplaint() {

    $.ajax("./actions/set.php",
            {
                type: 'POST',
                async: false,
                data: {
                    contact_id_c: complainant_id,
                    external_agency_c: complainant_name,
                    name: $("[name='name']").val(),
                    complainant_interest_c: $("[name='complainant_interest_c']").val(),
                    request_prayer_c: $("[name='request_prayer_c']").val(),
                    in_date_c: $("[name='in_date_c']").val(),
                    issue_cat_c: $("[name='issue_cat_c']").val(),
                    new_status_c: 'New',
                    incident_state_c: $("[name='incident_state_c']").val(),
                    area_incidence_c: $("[name='incidence_address_c']").val(),
                    law_case_c: $("[name='law_case_c']").prop('checked')?'Yes':'No',
                    complaint_range_c: $("[name='complaint_range_c']").prop('checked')?'Yes':'No',
                    complaint_occurance_c: $("[name='complaint_occurance_c']").prop('checked')?'Yes':'No',
                    complaint_lodge_c: $("[name='complaint_lodge_c']").prop('checked')?'Yes':'No',
                    legal_add_c: $("[name='legal_add_c']").prop('checked')?'Yes':'No',
                    complaint_description_c: $("[name='complaint_description_c']").val(),
                    resolution_steps_c: $("[name='resolution_steps_c']").val(),
                    module: 'Cases'
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Sorry a sever error was experienced.');
                },
                success: function (data, textStatus, jqXHR) {
                    alert('Your Complaint Has Been Submitted');
                    alert(data);
                    $('#formReset').click();
                    $('#loginCancel').click()
                    $('#complainCancel').click()
                }
            });
}

function getStatus(complaintID) {
    $.ajax("./actions/get.php",
            {
                type: 'POST',
                async: false,
                data: {
                    complaint_id: $("[name='comlplaintID']").val(),
                    complainant_id: complainant_id,
                    module: 'Cases',
                    action: 'status'
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Sorry a sever error was experienced.');
                },
                success: function (data, textStatus, jqXHR) {
                    if (data == 0) {
                        alert('Please check the complaint ID');
                    }
                    alert("Complaint:\t\t" + data.name
                            + "\nComplaint ID:\t\t" + data.case_number
                            + "\nComplaint Date:\t\t" + data.date_entered
                            + "\nComplaint State:\t\t" + data.incident_state_c
                            + "\nComplaint Stage:\t\t" + data.new_status_c
                            + "\nComplaint Is " + data.state);
//                    $('#alertText').html(data);
//                    $('#alertModalBtn').click();
                }
            });
//    'complaintId': complainant_id
}



$.validate({
    lang: 'es'
});
$("[name=issue_date_c]").datetimepicker({
    format: "dd MM yyyy",
    autoclose: true,
//        todayBtn: true,
    time: false,
    pickerPosition: "bottom-right"
});

$("#complainForm").submit(function (evt) {
    evt.preventDefault();

    console.log($('input').attr("disabled", true));
    console.log($('button').attr("disabled", true));
//    console.log($("[name='legal_add_c']"));


    identifyComplainant('', !new_complainant);
    var identified = complainant_id != 0;

//    alert(identified);

    if (!new_complainant && identified) {
        submitComplaint();
    }

    if (new_complainant && !identified) {
        registerComplainant();
        submitComplaint();
    }

    if (!new_complainant && !identified) {
        alert('These details cannot be identified\n\nPlease try again as a new complainant');
    }

    if (new_complainant && identified) {
        alert("These Details Have been Identified\n\nPlease try again as an old complainant");
    }
    console.log($('input').removeAttr("disabled"));
    console.log($('button').removeAttr("disabled"));

    reset();

//    submitComplaint();
});

$("#statusForm").submit(function (evt) {
    evt.preventDefault();

//    alert('pop');

    console.log($('input').attr("disabled", true));
    console.log($('button').attr("disabled", true));

    identifyComplainant("Update", true);
    var identified = complainant_id != 0;
    if (identified) {
//        alert(complainant_id + "\n\n" + complainant_name);
        getStatus($('[name="complain"]').val());
    }

    reset();

    console.log($('input').removeAttr("disabled"));
    console.log($('button').removeAttr("disabled"));
});





