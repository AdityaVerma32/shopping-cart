$(function () {

    var messages = {
        'required': 'This field is required.',
        'email': 'This field is required & must be a valid email address.',
        'firstname': 'This field is required & length should be between 2 to 60.',
        'lastname': 'This field is required & length should be between 2 to 60.',
        'passwd': 'This field is required with length between 5 - 10 & must contain at least one Capital, Special character and Number.',
        'conf_pass': 'This field is required and must be same as password',
        'dob': 'Enter valid DOB (DD-MM-YYYY)',
        'mobile': 'This field is required & must be having 10 digits only.',
        'zipcode': 'This field is required & must be having 6 digits only.',
        'address': 'This field is required & length should be between 2 to 255.',
        'city': 'This field is required',
        'state': 'This field is required',
        'country': 'Please select a country',
        'image': 'This file should not exceed 2 MB in size.',
        'valid_yop': "Please enter a valid Year of Passing"
    }


    // validation for first name - must be albets and not empty
    jQuery.validator.addMethod("first_name_", function (value, element) {
        if ($.trim(value) == "" || !/^[a-zA-Z]+$/.test(value)) {
            return false;
        } else {
            return true;
        }
    }, messages.firstname);

    // validation for DOB - format (DD-MM-YYYY)
    jQuery.validator.addMethod("dob", function (value, element) {
        if ($.trim(value) == "" || !/^(0[1-9]|[12][0-9]|3[01])-(0[1-9]|1[0-2])-(\d{4})$/.test(value)) {
            return false;
        } else {
            return true;
        }
    }, messages.dob);

    //Add New Method for - Email - Mandatory + Email Validation
    jQuery.validator.addMethod("email", function (value, element) {
        if ($.trim(value) == "" || !/^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/.test(value)) {
            return false;
        } else {
            return true;
        }
    }, messages.email);

    //Add New Method for - Password - Mandatory + Minimum Length 5 + Maximum Length 10 + At least one Capital Letter + At least one Special Character + At least one Number
    jQuery.validator.addMethod("passwd", function (value, element) {
        if ($.trim(value) == "" || $.trim(value).length < 5 || $.trim(value).length > 10 || !/^(?=.*[a-zA-Z])(?=.*\d)(?=.*[!@#$%^&*()_+])[A-Za-z\d!@#$%^&*()_+]{5,10}/.test(value)) {
            return false;
        } else {
            return true;
        }
    }, messages.passwd);

    // validaiton for confirm password
    jQuery.validator.addMethod("confirm_pass", function (value, element) {
        var password = $("#password").val();  // accessing the password field with id

        return value === password;
    })

    //Add New Method for - Mobile No. - Mandatory + Number + Total Length 10
    jQuery.validator.addMethod("mobile", function (value, element) {
        if ($.trim(value) == "" || $.trim(value).length != 10 || !/^\d{10}?$/.test(value)) {
            return false;
        } else {
            return true;
        }
    }, messages.mobile);

    //Add New Method for - Address- Mandatory + Minimum Length 2 + Maximum Length 255
    jQuery.validator.addMethod("address_", function (value, element) {
        if ($.trim(value) == "" || $.trim(value).length < 2 || $.trim(value).length > 255) {
            return false;
        } else {
            return true;
        }
    }, messages.address);

    // validation for city 
    jQuery.validator.addMethod("city_", function (value, element) {
        if ($.trim(value) == "" || $.trim(value).length < 2 || $.trim(value).length > 255) {
            return false;
        } else {
            return true;
        }
    }, messages.city);

    // validation for Zip Code
    jQuery.validator.addMethod("zipcode_", function (value, element) {
        if ($.trim(value) == "" || $.trim(value).length != 6 || !/^[1-9][0-9]{5}$/.test(value)) {
            return false;
        } else {
            return true;
        }
    }, messages.zipcode);

    // validaiton for state
    jQuery.validator.addMethod("state_", function (value, element) {
        if ($.trim(value) == "" || $.trim(value).length < 2 || $.trim(value).length > 30) {
            return false;
        } else {
            return true;
        }
    }, messages.state);

    // valition for country
    jQuery.validator.addMethod("country_", function (value, element) {
        if ($.trim(value) == "" || $.trim(value).length < 2 || $.trim(value).length > 30) {
            return false;
        } else {
            return true;
        }
    }, messages.country);


    jQuery.validator.addMethod("academic_year", function (value) {
        var X_yop = $("#X-yop").val();  // accessing the password field with id

        var XII_yop = $("#XII-yop").val(); // accessing the password field with id
        return X_yop < XII_yop;;
    })



    //================================================================//
    $("#sample").validate({

        rules: {
            first_name: {
                first_name_: true,
                required: true
            },
            last_name: {
                required: true,
                first_name_: true
            },
            date_of_birth: {
                required: true,
                dob: true
            },

            mobile_number: {
                required: true,
                mobile: true
            },
            conf_pass: {
                required: true,
                confirm_pass: true
            },
            address: {
                required: true,
                address_: true
            },
            city: {
                required: true,
                city_: true
            },
            zipcode: {
                required: true,
                zipcode_: true
            },
            state: {
                required: true,
                state_: true
            },
            country: {
                required: true,
                country_: true
            },
            XII_Year_of_Passing: {
                academic_year: true
            }
        },
        messages: {
            first_name: {
                required: messages.required,
                first_name_: messages.firstname
            },
            last_name: {
                required: messages.required,
                first_name_: messages.firstname
            },
            date_of_birth: { 
                required: messages.required,
                dob: messages.dob
            },
            mobile_number: {
                required: messages.required,
                mobile: messages.mobile
            },
            conf_pass: {
                required: messages.required,
                confirm_pass: messages.conf_pass
            },
            address: {
                required: messages.required,
                address_: messages.address
            },
            city: {
                required: messages.required,
                city_: messages.city
            },
            zipcode: {
                required: messages.required,
                zipcode_: messages.zipcode
            },
            state: {
                required: messages.required,
                state_: messages.state
            },
            country: {
                required: messages.required,
                country_: messages.country
            },
            XII_Year_of_Passing: {
                academic_year: messages.valid_yop
            }

        },
        highlight: function (label) {
            $(label).closest('.form-group').removeClass('has-success').addClass('has-error');
        },
        success: function (label) {
            $(label).closest('.form-group').removeClass('has-error');
            label.remove();
        }
    });
})