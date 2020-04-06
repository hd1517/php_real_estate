<?php
include "include/config.php";

$thisPage = 'Property Profile';

// check if user is logged in
if (isset($_SESSION['user_id'])) {
    $userID = $_SESSION['user_id'];
} else {
    $userID = 'none';
}

// retreive from URL
$propertyID = mysqli_real_escape_string($link, $_GET['property_ID']);

// return page
$_SESSION['return_page'] = $_SERVER['REQUEST_URI'];

// locate the property record from database
$query = "SELECT tbl_properties.property_id as property_id, 
tbl_properties.title as propertyTitle, 
tbl_city.city as propertyCity, 
tbl_suburb.suburb as propertySuburb, 
tbl_properties.address as propertyAddress, 
tbl_bedrooms.bedrooms as propertyBedrooms, 
tbl_bathrooms.bathrooms as propertyBathrooms, 
tbl_parking.parking as propertyParking,
tbl_agents.agent_id as propertyAgentID,
tbl_agents.fname as propertyAgentFName,
tbl_agents.lname as propertyAgentLnName,
tbl_agents.email as propertyAgentEmail,
tbl_agents.picture as propertyAgentPicture,
tbl_agents.phone as propertyAgentPhone,
tbl_sale_type.sale_type as propertySaleType,
tbl_properties.sale_details as propertySaleDetails,
tbl_properties.description as propertyDescription,
tbl_listing_status.listing_status as propertyListingStatus
FROM tbl_properties 
INNER JOIN tbl_city ON tbl_properties.city_id_f=tbl_city.city_id 
INNER JOIN tbl_suburb ON tbl_properties.suburb_id_f=tbl_suburb.suburb_id
INNER JOIN tbl_bedrooms ON tbl_properties.bedrooms_id_f=tbl_bedrooms.bedrooms_id
INNER JOIN tbl_bathrooms ON tbl_properties.bathrooms_id_f=tbl_bathrooms.bathrooms_id
INNER JOIN tbl_parking ON tbl_properties.parking_id_f=tbl_parking.parking_id
INNER JOIN tbl_agents ON tbl_properties.agent_id_f=tbl_agents.agent_id
INNER JOIN tbl_sale_type ON tbl_properties.sale_type_id_f=tbl_sale_type.sale_type_id
INNER JOIN tbl_listing_status ON tbl_properties.listing_status_id_f=tbl_listing_status.listing_status_id
WHERE property_id='$propertyID'";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_array($result);
extract($row);
$propID = $property_id;
$propTitle = $propertyTitle;
$propCity = $propertyCity;
$propSuburb = $propertySuburb;
$propAddress = $propertyAddress;
$propBedrooms = $propertyBedrooms;
$propBathrooms = $propertyBathrooms;
$propParking = $propertyParking;
$propAgentID = $propertyAgentID;
$propAgentFName = $propertyAgentFName;
$propAgentLName = $propertyAgentLnName;
$propAgentEmail = $propertyAgentEmail;
$propAgentPhone = $propertyAgentPhone;
$propAgentPicture = $propertyAgentPicture;
$propSaleType = $propertySaleType;
$propSaleDetails = $propertySaleDetails;
$propDescription = $propertyDescription;
$propListingStatus = $propertyListingStatus;

// ADD TO WATCHLIST
if (isset($_POST['addToWatchlist']) || isset($_GET['add'])) {
    // check if user is logged in
    if ($userID != 'none') {
        $property_ID = mysqli_real_escape_string($link, $_GET['property_ID']);
        $userID = $_SESSION['user_id'];

        //check for duplication before insert
        $duplication_query = "SELECT * FROM tbl_watchlist WHERE property_id_f=$propertyID AND user_id_f=$userID";
        $duplication_result = mysqli_query($link, $duplication_query);
        if (mysqli_num_rows($duplication_result) == 0) {
            $query = "INSERT INTO tbl_watchlist (property_id_f, user_id_f) VALUES ('$property_ID', '$userID') ";
            mysqli_query($link, $query); // execute the SQL 
        }
        // header("Location: property_profile.php?property_ID=" . $property_ID);
    }
}

// REMOVE FROM WATCHLIST
if (isset($_POST['removeWatch'])) {
    // check if user is logged in
    if ($userID != 'none') {
        $property_ID = mysqli_real_escape_string($link, $_GET['property_ID']);
        $userID = $_SESSION['user_id'];

        $query = "DELETE FROM tbl_watchlist WHERE property_id_f=$property_ID AND user_id_f=$userID";
        mysqli_query($link, $query); // execute the SQL 
        header("Location: property_profile.php?property_ID=" . $property_ID);
    }
}

include "include/login_modal.php";
include "include/header.php";
?>

<section class="propertyProfileSection container mb-5">

    <div class="row ml-3 ml-md-5 mb-2">
        <h2><?php echo $propTitle; ?></h2>
    </div>

    <div class="row mr-3 mb-3">
        <div id='carousel-custom' class='carousel slide ml-3 ml-md-5' data-ride='carousel'>
            <!-- Wrapper for slides -->
            <div class='carousel-inner propertyCaro'>
                <?php

                $img_query = "SELECT * FROM tbl_images WHERE property_id_f='$propertyID'";
                $image_result = mysqli_query($link, $img_query);
                if (!$image_result) {
                    printf("Error: %s\n", mysqli_error($link));
                    exit();
                }
                while ($image_row = mysqli_fetch_array($image_result)) {
                ?>
                    <div class="carousel-item ">
                        <img src=<?php echo 'admin/images/' . $image_row['file_name']; ?> class="d-block w-100" alt="...">
                    </div>
                <?php } //end of while 
                ?>

                <!-- Controls -->
                <a class="carousel-control-prev" href="#carousel-custom" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carousel-custom" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>


            <!-- Indicators -->
            <ol class='carousel-indicators thumbnailCaro'>
                <?php

                $img_query = "SELECT * FROM tbl_images WHERE property_id_f='$propertyID' ORDER BY image_id";
                $image_result = mysqli_query($link, $img_query);
                $start = 0;

                while ($image_row = mysqli_fetch_array($image_result)) {
                    $next = $start++;
                ?>
                    <li data-target='#carousel-custom' data-slide-to='<?php echo $next; ?>' class=''><img src='<?php echo 'admin/images/' . $image_row['file_name']; ?>' alt=''></li>
                <?php } //end of while 
                ?>
            </ol>
        </div>
        <div class="col col-md-3 d-none d-md-block noPadding" style="margin-right: -20px;">
            <div class="row">

                <?php
                if ($userID == 'none') {
                    echo '<div class="card watchlistBtn"><a class="btn" onclick="addWatchlist(\'' . $userID . '\',\'' . $propID . '\')"><i class="far fa-star icon"></i> Add to Watchlist</a></div>';
                } else {
                    $watchlist_query = "SELECT * FROM tbl_watchlist WHERE user_id_f=$userID AND property_id_f=$propID";
                    $watchlist_result = mysqli_query($link, $watchlist_query);
                    if (mysqli_num_rows($watchlist_result) == 0) {
                        echo '<div class="card watchlistBtn"><a class="btn" onclick="addWatchlist(\'' . $userID . '\',\'' . $propID . '\')"><i class="far fa-star icon"></i> Add to Watchlist</a></div>';
                    } else {
                        echo '<div class="card watchlistBtn removeWatch"><a class="btn" onclick="removeWatchlist(\'' . $userID . '\',\'' . $propID . '\')"><i class="fas fa-star icon"></i> Remove from Watchlist</a></div>';
                    }
                }
                ?>


            </div>
            <div class="row">
                <div class="card center propertyAgentCard">
                    <img src="<?php echo 'admin/images/' . $propertyAgentPicture; ?>" class="card-img-top" alt="Photo" title="photo" />
                    <div class="card-body">
                        <h5 class="card-title "><?php echo $propAgentFName; ?> <?php echo $propAgentLName; ?></h5>
                        <p class="card-text"><i class="fas fa-envelope icon"></i> <?php echo $propAgentEmail; ?></p>
                        <p class="card-text"><i class="fas fa-phone icon"></i> <?php echo $propAgentPhone; ?></p>
                        <?php

                        $agentProp_query = "SELECT tbl_city.city as city FROM tbl_city INNER JOIN tbl_agents ON tbl_city.city_id=tbl_agents.city_id_f WHERE tbl_agents.agent_id='$propAgentID'";
                        $agentProp_result = mysqli_query($link, $agentProp_query);
                        while ($agentProp_row = mysqli_fetch_array($agentProp_result)) {
                        ?>
                            <p class="card-text"><i class="fas fa-map-marker-alt icon1"></i> <?php echo $agentProp_row['city']; ?></p>
                        <?php
                        } //end of while
                        ?>
                        <a class="btn btn-secondary btn-sm text-center myBtn" href="agent_profile.php?agentID=<?php echo $propAgentID; ?>">Agent Profile</a>
                        <a class="btn btn-secondary btn-sm text-center myBtn" href="properties.php?agentID=<?php echo $propAgentID; ?>">View Listings</a>
                    </div>
                </div>
            </div>



        </div>
    </div>

    <div class="row">

        <?php
        if ($userID == 'none') {
            echo '<div class="card d-md-none watchlistBtn ml-4 ml-md-5 mr-4 mb-2"><a class="btn" onclick="addWatchlist(\'' . $userID . '\',\'' . $propID . '\')"><i class="far fa-star icon"></i> Add to Watchlist</a></div>';
        } else {
            $watchlist_query = "SELECT * FROM tbl_watchlist WHERE user_id_f=$userID AND property_id_f=$propID";
            $watchlist_result = mysqli_query($link, $watchlist_query);
            if (mysqli_num_rows($watchlist_result) == 0) {
                echo '<div class="card d-md-none watchlistBtn ml-4 ml-md-5 mr-4 mb-2"><a class="btn" onclick="addWatchlist(\'' . $userID . '\',\'' . $propID . '\')"><i class="far fa-star icon"></i> Add to Watchlist</a></div>';
            } else {
                echo '<div class="card d-md-none watchlistBtn removeWatch ml-4 ml-md-5 mr-4 mb-2"><a class="btn" onclick="removeWatchlist(\'' . $userID . '\',\'' . $propID . '\')"><i class="fas fa-star icon"></i> Remove from Watchlist</a></div>';
            }
        }
        ?>

    </div>

    <div class="row">
        <div class="col-md-8 ml-4 ml-md-5 noPadding mr-4">
            <div class="card bg-light mb-4 propDetails">
                <div class="card-body propertyDetailsCard">
                    <table class="table table-borderless propertyDetailsTable">
                        <thead>
                            <tr>
                                <th class="col-4 center" style="width: 30%"><i class="fas fa-bed fa-2x"></th>
                                <th class="col-4 center" style="width: 30%"><i class="fas fa-bath fa-2x"></th>
                                <th class="col-4 center" style="width: 30%"><i class="fas fa-car fa-2x"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="center"><?php echo $propBedrooms; ?> bedrooms</td>
                                <td class="center"><?php echo $propBathrooms; ?> bathrooms</td>
                                <td class="center"><?php echo $propParking; ?> garage parking</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer center" style="padding: 0.5rem;">
                    <small class="text-muted "><?php echo $propSaleDetails; ?></small>
                </div>
            </div>

        </div>
    </div>

    <div class="row">
        <div class="col-md-8 ml-4 ml-md-5 mr-4">
            <div class="row mb-3 mt-2">
                <h6><i class="fas fa-map-marker-alt icon1"></i><?php echo $propAddress; ?></h6>
            </div>
            <div class="row aboutProperty">
                <p><?php echo $propDescription; ?></p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="ml-3 mr-3 d-md-none mt-3 ">
            <div class="card mb-3">
                <div class="row no-gutters">
                    <div class="col-sm-5">
                        <img src="<?php echo 'admin/images/' . $propertyAgentPicture; ?>" class="card-img-top" alt="Photo" title="photo" />
                    </div>
                    <div class="col-sm-7">
                        <div class="card-body center">
                            <h5 class="card-title "><?php echo $propAgentFName; ?> <?php echo $propAgentLName; ?></h5>
                            <p class="card-text"><i class="fas fa-envelope icon"></i> <?php echo $propAgentEmail; ?></p>
                            <p class="card-text"><i class="fas fa-phone icon"></i> <?php echo $propAgentPhone; ?></p>
                            <?php

                            $agentProp_query = "SELECT tbl_city.city as city FROM tbl_city INNER JOIN tbl_agents ON tbl_city.city_id=tbl_agents.city_id_f WHERE tbl_agents.agent_id='$propAgentID'";
                            $agentProp_result = mysqli_query($link, $agentProp_query);
                            while ($agentProp_row = mysqli_fetch_array($agentProp_result)) {
                            ?>
                                <p class="card-text"><i class="fas fa-map-marker-alt icon1"></i> <?php echo $agentProp_row['city']; ?></p>
                            <?php
                            } //end of while
                            ?>
                            <a class="btn btn-secondary btn-sm text-center myBtn" href="agent_profile.php?agentID=<?php echo $propAgentID; ?>">View Agent</a>
                            <a class="btn btn-secondary btn-sm text-center myBtn" href="properties.php?agentID=<?php echo $propAgentID; ?>">View Listings</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div> <!-- end of property profile -->


</section>

<!-- Footer -->
<?php include "include/footer.php"; ?>