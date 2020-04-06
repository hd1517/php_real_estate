$('.propertyCaro').find('div:first').addClass('active');
$('.thumbnailCaro').find('li:first').addClass('active');


$('.by_city').change(function () { // For properties.php
    refreshDropdowns('tbl_suburb', 'suburb', 'by_suburb');
});

$('.resetBtn').click(function () {
    $('.form-control').val('all');
    $('.by_suburb').prop('disabled', true);
});

function refreshDropdowns(tblName, colName, propToChange) {

    if (propToChange == 'by_suburb' || propToChange == 'by_agent') {
        var city_id = $('.by_city').val(); // For Filter in properties.php

    } else {
        var city_id = $('.propertyCity').val(); // For add property.php
    }

    $.ajax({
        url: "admin/fetch_suburbs.php",
        method: "POST",
        data: {
            cityID: city_id,
            table_name: tblName,
            col_name: colName
        },
        dataType: "text",
        success: function (data) {
            // console.log(city_id);
            $('.' + propToChange).removeAttr('disabled');
            $('.' + propToChange).html(data);
            if (propToChange == 'by_suburb') {
                $('.by_suburb').prepend('<option value="all" selected>All Suburbs</option>');
                if (city_id == 'all') {
                    $('.by_suburb').prop('disabled', true);
                }
            }
        }
    });
}


function addWatchlist(userID, propID) {
    // if user is not logged in, bring up the login modal
    if (userID == 'none') {
        $('#loginModal').modal('show');
    }


    $.ajax({
        url: "property_profile.php?property_ID=" + propID + "&add",
        method: "POST",
        data: {
            property_ID: propID,
            addToWatchlist: 'yes'
        },
        dataType: "text",
        success: function (data) {
            if (userID != 'none') {
                $('.addWatchBtn' + propID).remove();
                $('.btnRow' + propID).append('<a class="btn btn-secondary text-light btn-sm text-center removeWatchBtn' + propID + '" onclick="removeWatchlist(\'' + userID + '\',\'' + propID + '\', \'properties\')"><i class="fas fa-star icon"></i> Watched</a>');

                $('.watchlistBtn').html('<a class="btn" onclick="removeWatchlist(\'' + userID + '\',\'' + propID + '\')"><i class="fas fa-star icon"></i> Remove from Watchlist</a>').addClass('removeWatch');
            }
        }
    });

}

function removeWatchlist(userID, propID) {

    $.ajax({
        url: "property_profile.php?property_ID=" + propID + "&add",
        method: "POST",
        data: {
            removeWatch: 'yes'
        },
        dataType: "text",
        success: function (data) {
            $('.watchlistBtn').html('<a class="btn" onclick="addWatchlist(\'' + userID + '\',\'' + propID + '\')"><i class="far fa-star icon"></i> Add to Watchlist</a>').removeClass('removeWatch');
            $('.removeWatchBtn' + propID).remove();
            $('.btnRow' + propID).append('<a class="btn btn-secondary text-light btn-sm text-center addWatchBtn' + propID + '" onclick="addWatchlist(\'' + userID + '\',\'' + propID + '\', \'properties\')"><i class="far fa-star icon"></i> Add to Watchlist</a>');

        }
    });

}


console.log('test');
