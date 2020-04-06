$('#sidebarCollapse').on('click', function () {
    $('#sidebar').toggleClass('active');
});

$('.cityForSuburbSelected').change(function () {
    $('.suburbField').removeAttr('disabled');
});

$('.propertyCity').change(function () { // For add_property.php
    refreshDropdowns('tbl_suburb', 'suburb', 'propertySuburb');
});

$('.by_city').change(function () { // For properties.php
    refreshDropdowns('tbl_suburb', 'suburb', 'by_suburb');
});

$('.propertyCaro').find('div:first').addClass('active');
$('.thumbnailCaro').find('li:first').addClass('active');

$('.resetSearchBtn').click(function () {
    $('.adminSearchForm .form-control').val('all');
    $('.mySearchInput').val('');
    $('.by_suburb').prop('disabled', true);
});

$('.featureCheck').click(function () {
    if ($('.featureCheck').prop("checked") == false) {
        $('.featureCheck').prop("checked", true);
        $('.setFeatured').css("background-color", "#adb6db");
        $('.setFeatured label').text("Featured Listing");
    } else if ($('.featureCheck').prop("checked") == true) {
        $('.featureCheck').prop("checked", false);
        $('.setFeatured').css("background-color", "#c0c5d8");
        $('.setFeatured label').text("Set as Featured Listing");
    }
});

$('.setFeatured').click(function () {
    if ($('.featureCheck').prop("checked") == false) {
        $('.featureCheck').prop("checked", true);
        $('.setFeatured').css("background-color", "#adb6db");
        $('.setFeatured label').text("Featured Listing");

    } else if ($('.featureCheck').prop("checked") == true) {
        $('.featureCheck').prop("checked", false);
        $('.setFeatured').css("background-color", "#c0c5d8");
        $('.setFeatured label').text("Set as Featured Listing");
    }
});

$(".choicesDiv").click(function () {
    $(".choicesDiv.selected").removeClass("selected"); // Remove previously selected divs
    $(this).find("input[type=radio]").prop("checked", true); // Find the radio button within the clicked div
    $(this).addClass("selected"); // Add a background to selected div
    $(".selectAnswerTxt").hide(); // Hide the error message once a choice has been selected
});

function setAsFeatured(propID) {
    $.ajax({
        url: "property_profile.php?property_ID=" + propID,
        method: "POST",
        data: {
            featuredPropID: propID,
            setFeatured: 'yes'
        },
        dataType: "text",
        success: function (data) {
            $('.setAsFeaturedLink' + propID).remove();
            $('.featuredBtn' + propID).append('<a class="btn featuredLink featuredLink' + propID + '" onclick="notFeatured(\'' + propID + '\')"><i class="fas fa-star icon"></i>Featured Listing</a>').addClass('featuredBtn');
        }
    });
}

function refreshDropdowns(tblName, colName, propToChange) {

    if (propToChange == 'by_suburb' || propToChange == 'by_agent') {
        var city_id = $('.by_city').val(); // For Filter in properties.php

    } else {
        var city_id = $('.propertyCity').val(); // For add property.php
    }

    $.ajax({
        url: "fetch_suburbs.php",
        method: "POST",
        data: {
            cityID: city_id,
            table_name: tblName,
            col_name: colName
        },
        dataType: "text",
        success: function (data) {
            // console.log(data);
            $('.' + propToChange).removeAttr('disabled');
            $('.' + propToChange).html(data);
            if (propToChange == 'by_suburb') {
                $('.by_suburb').prepend('<option value="" disabled hidden selected>Suburb</option>');
                $('.by_suburb').prepend('<option value="all" selected>All Suburbs</option>');
            }

            if (propToChange == 'by_agent') {
                $('.by_agent').prepend('<option value="" disabled hidden selected>Agent</option>');
            }
        }
    });
}

function showEditForm(page) {
    $('.editSection_' + page).show();
    $('.profileSection_' + page).hide();
    $('.back2Manage_' + page).hide();
    $('.cancelEditBtn_' + page).show();
}

function cancelEdit(page) {
    $('.editSection_' + page).hide();
    $('.profileSection_' + page).show();
    $('.back2Manage_' + page).show();
    $('.cancelEditBtn_' + page).hide();
}

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('.thumbnailSize').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}

function imagesPreview(input, placetoInsertPreview) {
    //if the photos have already been picked previously
    if ($('.placeHolderThumbnailImg').length == 0) {
        $('.' + placetoInsertPreview).children().remove();
    }

    if (input.files) {
        var filesAmount = input.files.length;

        for (i = 0; i < filesAmount; i++) {
            var reader = new FileReader();

            reader.onload = function (event) {
                $('.placeHolderThumbnailImg').remove();
                $($.parseHTML('<img>')).attr({ 'src': event.target.result, 'class': 'thumbnailSize' }).appendTo('.' + placetoInsertPreview);


            }

            reader.readAsDataURL(input.files[i]);
        }
    }
}

function deletePropImage(propImageName, propID) {
    $("#confirmDelete").modal('show');

    modalConfirm(function (confirm) {
        if (confirm) {
        var numOfPhotos = 'one';

        if (propImageName == 'all') {
            numOfPhotos = 'all';
        }

        $.ajax({
            url: "property_profile.php",
            method: "POST",
            data: {
                deletePropPhoto: numOfPhotos,
                delete_propertyImageName: propImageName,
                delete_propertyID: propID
            },
            dataType: "text",
            success: function (data) {
                refreshPropImgTable(propID);
            }
        });
    }
});
}

function updateListingStat(propID) {
    var newListingStat = $('.newPropertyListingStatus').val();
    console.log(newListingStat);
    $.ajax({
        url: "property_profile.php",
        method: "POST",
        data: {
            editListingStatus: newListingStat,
            editPropID: propID
        },
        dataType: "text",
        success: function (data) {
            console.log("success");
        }
    });
}

function refreshPropImgTable(propID) {
    $.ajax({
        url: "refresh_property_tables.php",
        method: "POST",
        data: {
            refresh: 'yes',
            propertyID: propID
        },
        dataType: "text",
        success: function (data) {
            console.log(data);
            $('.propertyImagesTable').html(data);
        }
    });
}

function hideOriginal(tdClass, tdSuburbCity) {

    if (tdSuburbCity == undefined) {
        $('.' + tdClass).hide();
        $('.editFields' + tdClass).show();
        $('.icon' + tdClass).hide();
        $('.cancelLink' + tdClass).show();
    } else if (tdClass == 'user') {
        $('.ogValue' + tdSuburbCity).hide();
        $('.editDiv' + tdSuburbCity).show();
        $('.userIcon' + tdSuburbCity).hide();
        $('.editUserBtn' + tdSuburbCity).show();
        $('.userCancelLink' + tdSuburbCity).show();
    } else {
        $('.' + tdClass).hide();
        $('.' + tdSuburbCity).hide();
        $('.editFields' + tdClass).show();
        $('.editFields' + tdSuburbCity).show();
        $('.icon' + tdClass).hide();
        $('.cancelLink' + tdClass).show();
    }

}

function cancelChange(tdClass, tdSuburbCity) {

    if (tdSuburbCity == undefined) {
        $('.' + tdClass).show();
        $('.editFields' + tdClass).hide();
        $('.icon' + tdClass).show();
        $('.cancelLink' + tdClass).hide();
    } else if (tdClass == 'user') {
        $('.ogValue' + tdSuburbCity).show();
        $('.editDiv' + tdSuburbCity).hide();
        $('.userIcon' + tdSuburbCity).show();
        $('.editUserBtn' + tdSuburbCity).hide();
        $('.userCancelLink' + tdSuburbCity).hide();
    } else {
        $('.' + tdClass).show();
        $('.' + tdSuburbCity).show();
        $('.editFields' + tdClass).hide();
        $('.editFields' + tdSuburbCity).hide();
        $('.icon' + tdClass).show();
        $('.cancelLink' + tdClass).hide();
    }

}

function addRecord(colName) {

    var tblName = "tbl_" + colName;

    //console.log(col_Name);

    if (colName == 'city' || colName == 'sale_type' || colName == 'listing_status') {
        var newRecordValue = $('.addInput_' + colName).val();
    } else if (colName == 'suburb') {
        var city_forSuburb = $('.cityForSuburbSelected').val();
        var newRecordValue = $('.addInput_' + colName).val();
    }
    //console.log(tblName + colName);
    $.ajax({
        url: "manage_fields.php",
        method: "POST",
        data: {
            table_name: tblName,
            col_name: colName,
            newValue: newRecordValue,
            cityForSuburb: city_forSuburb
        },
        dataType: "text",
        success: function (data) {
            // console.log(colName + tblName)
            // console.log(data);
            refreshTable(colName, tblName);
            if (colName == 'city') {
                refreshTable('suburb', 'tbl_suburb');
                refreshTable('citySuburb');
            }
            $('.addInput_' + colName).val('');
            $(".cityForSuburbSelected").val('Choose...');
        }
    });
}

function updateTable(value_id, column_name) {

    if (value_id == 'viewSuburbByCity') {
        var viewSuburbBy_City = $('.viewSuburbByCity_dropdown').val();
        //   console.log(viewSuburbBy_City);
    }


    if (column_name == 'suburb') {
        var cityForSuburb = $('.dropdown_suburbCity' + value_id).val();
        //  console.log(cityForSuburb);
    }

    var newVal = $('.editInput' + column_name + value_id).val(),
        value_ID = value_id,
        col_Name = column_name,
        fieldClass = col_Name + value_ID;


    //console.log(newVal + " " + value_ID + " " + col_Name);
    $.ajax({
        url: "manage_fields.php",
        method: "POST",
        data: {
            newValue: newVal,
            colName: col_Name,
            valueID: value_ID,
            citySuburb: cityForSuburb,
            viewSuburbByCity: viewSuburbBy_City
        },
        dataType: "text",
        success: function (data) {
            //console.log(viewSuburbBy_City);
            $('.editFields' + fieldClass).hide();
            $('.cancelLink' + fieldClass).hide();
            $('.icon' + fieldClass).show();
            $('.' + fieldClass).show().html(newVal);
            if (column_name == 'suburb') {
                var showCity = $(".dropdown_suburbCity" + value_id + " option:selected").text();
                $('.suburbCity' + value_id).show().html(showCity);
                $('.editFieldssuburbCity' + value_id).hide();
            }

            if (column_name == 'city' || column_name == 'suburb') {
                refreshTable('suburb', 'tbl_suburb');
                refreshTable('citySuburb');
            }

            if (value_id == 'viewSuburbByCity') {
                refreshTable('suburb', 'tbl_suburb', viewSuburbBy_City);
            }


        }
    });

}

var modalConfirm = function (callback) {
    $("#yesDeleteBtn").on("click", function () {
        callback(true);
        $("#confirmDelete").modal('hide');
    });

    $("#noDeleteBtn").on("click", function () {
        callback(false);
        $("#confirmDelete").modal('hide');
    });
};

function deleteRecord(colName, entry_id) {
    var deleteTblName = "tbl_" + colName;
    var URL = window.location.pathname;
    var textWithNumber = $('.listingNumber').text();
    var numberOfListings = (textWithNumber.slice(15, -1));

    $("#confirmDelete").modal('show');

    modalConfirm(function (confirm) {
        if (confirm) {

            if ($('.featureBtn').hasClass('featuredBtn') == true) {
                $('#errorModal').modal('show');
                // alert('This property cannot be deleted as it has been set as the Featured Listing. Please choose another property to feature before deleting this one.');
            } else if (colName == 'agents' && numberOfListings > 0) {
                // if the agent has a listing, show error
                $('#errorAgentModal').modal('show');
            } else {
                $.ajax({
                    url: URL,
                    method: "POST",
                    data: {
                        delete: 'yes',
                        delete_table_name: deleteTblName,
                        delete_col_name: colName,
                        delete_entryID: entry_id,
                    },
                    dataType: "text",
                    success: function () {
                        //console.log(colName);

                        if (colName == 'agents') {
                            window.location = "agents.php";
                        } else if (colName == 'properties') {
                            window.location = "properties.php";
                        } else {
                            refreshTable(colName, deleteTblName);
                            if (colName == 'city') {
                                refreshTable('suburb', 'tbl_suburb');
                                refreshTable('citySuburb');
                            }
                        }


                    },
                    error: function (req, textStatus, errorThrown) {
                        // alert('Ooops, something happened: ' + textStatus + ' ' +errorThrown);
                        if (colName == 'agents') {
                            window.location = "agents.php";
                        } else if (colName == 'properties') {
                            window.location = "properties.php";
                        } else {
                            refreshTable(colName, deleteTblName);
                            if (colName == 'city') {
                                refreshTable('suburb', 'tbl_suburb');
                                refreshTable('citySuburb');
                            }
                        }
                    }

                });
            }


        } else {
            console.log('not deleted');
        }
    });


}


function refreshTable(colName, tblName, viewSuburbByCity) {

    if (viewSuburbByCity == undefined) {
        viewSuburbByCity = 'all';
    }

    $.ajax({
        url: "refresh_listing_tables.php",
        method: "POST",
        data: {
            table_name: tblName,
            col_name: colName,
            viewSuburbByCity: viewSuburbByCity
        },
        dataType: "text",
        success: function (data) {
            //console.log(data);
            $('.table_' + colName).html(data);

            if (tblName == undefined) {
                $('.cityForSuburbSelected').html(data);
                //  console.log(data);
                $('.cityForSuburbSelected').prepend('<option selected disabled>Choose...</option>');
                $('.viewSuburbByCity_dropdown').html(data);
                $('.viewSuburbByCity_dropdown').prepend('<option value="all">All Cities</option>').val('all');
            }

            if (viewSuburbByCity == undefined) {
                $('.viewSuburbByCity_dropdown').val('all');
            }


        }
    });

}


function editUser(user_ID, size) {

    if (size == 'mob') {
        var firstName = $('.editMob_userFName' + user_ID).val(),
            lastName = $('.editMob_userLName' + user_ID).val(),
            new_email = $('.editMob_userEmail' + user_ID).val(),
            new_phone = $('.editMob_userPhone' + user_ID).val();
    } else {
        var firstName = $('.edit_userFName' + user_ID).val(),
            lastName = $('.edit_userLName' + user_ID).val(),
            new_email = $('.edit_userEmail' + user_ID).val(),
            new_phone = $('.edit_userPhone' + user_ID).val();
    }

    $.ajax({
        url: "manage_users.php",
        method: "POST",
        data: {
            userID: user_ID,
            newFName: firstName,
            newLName: lastName,
            newEmail: new_email,
            newPhone: new_phone,
            update: 'yes'
        },
        dataType: "text",
        success: function (data) {
            $('.userFName' + user_ID).html(firstName);
            $('.userLName' + user_ID).html(lastName);
            $('.userEmail' + user_ID).html(new_email);
            $('.userPhone' + user_ID).html(new_phone);
            cancelChange('user', user_ID);
        }
    });

}

function deleteUser(user_ID) {
    $("#confirmDelete").modal('show');

    modalConfirm(function (confirm) {
        if (confirm) {
            $.ajax({
                url: "manage_users.php",
                method: "POST",
                data: {
                    userID: user_ID,
                    delete: 'yes'
                },
                dataType: "text",
                success: function (data) {
                    $('.userRow' + user_ID).remove();
                },
                error: function (req, textStatus, errorThrown) {
                    // alert('Ooops, something happened: ' + textStatus + ' ' +errorThrown);
                    $('.userRow' + user_ID).remove();
                }
            });
        }
    });
    }

