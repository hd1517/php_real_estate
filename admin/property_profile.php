<?php
include "include/user_check.php";
include "../include/image_creation.php";

// if admin role has logged in, set this page as 'Manage Agents'
if ($role == 'admin') {
    $thisPage = "Manage Properties";
} else { // if normal agent has logged in
    // if the agent is looking at other profiles   
    if (!isset($_GET['properties'])) {
        $thisPage = "My Properties";
    } else {
        $thisPage = "All Properties";
    }
}

// retreive from URL
$propertyID = mysqli_real_escape_string($link, $_GET['property_ID']);

// EDIT LISTING STATUS
if (isset($_POST['editListingStatus'])) {
    $newListingStatus = mysqli_real_escape_string($link, $_POST['editListingStatus']);
    $editPropID = mysqli_real_escape_string($link, $_POST['editPropID']);

    $query = "UPDATE tbl_properties SET listing_status_id_f='$newListingStatus' WHERE property_id=$editPropID";
    mysqli_query($link, $query); // execute the SQL 
    header("Location: edit_property.php?property_ID=" . $propertyID);
}


// SET AS FEATURED PROPERTY
if (isset($_POST['setFeatured'])) {
    $featuredPropID = mysqli_real_escape_string($link, $_POST['featuredPropID']);

    // find the previous featured property and unset as featured
    $featured_query = "UPDATE `tbl_properties` SET `featured`= 'n' WHERE `featured`= 'y'";
    mysqli_query($link, $featured_query); // execute the SQL 
    // update property record to be the featured property
    $query = "UPDATE `tbl_properties` SET `featured`= 'y' WHERE `property_id`= $featuredPropID";
    mysqli_query($link, $query); // execute the SQL 
    header("Location: property_profile.php?property_ID=" . $propertyID);
}

// EDIT PROPERTY DETAILS
if (isset($_POST['editProperty'])) {

    $newPropertyTitle = mysqli_real_escape_string($link, $_POST['newPropertyTitle']);
    $newPropertyCity = mysqli_real_escape_string($link, $_POST['newPropertyCity']);
    $newPropertySuburb = mysqli_real_escape_string($link, $_POST['newPropertySuburb']);
    $newPropertyAddress = mysqli_real_escape_string($link, $_POST['newPropertyAddress']);
    $newPropertyBedrooms = mysqli_real_escape_string($link, $_POST['newPropertyBedrooms']);
    $newPropertyBathrooms = mysqli_real_escape_string($link, $_POST['newPropertyBathrooms']);
    $newPropertyParking = mysqli_real_escape_string($link, $_POST['newPropertyParking']);
    $newPropertySaleType = mysqli_real_escape_string($link, $_POST['newPropertySaleType']);
    $newPropertySaleDetails = mysqli_real_escape_string($link, $_POST['newPropertySaleDetails']);
    $newPropertyDescription = mysqli_real_escape_string($link, $_POST['newPropertyDescription']);

    if ($role == 'admin') {
        $newPropertyAgent = mysqli_real_escape_string($link, $_POST['newPropertyAgent']);
    } else {
        $newPropertyAgent = $userID;
    }

    // Checks if user didn't choose any photos
    if (!empty(array_filter($_FILES['files']['name']))) {

        // Loop through each file in files[] array 
        foreach ($_FILES['files']['tmp_name'] as $key => $val) {

            //image file
            $imgName = $_FILES['files']['name'][$key];
            $tmpName = $_FILES['files']['tmp_name'][$key];
            $ext = strtolower(strrchr($imgName, ".")); // strtolower -- change to lowercase
            $newName = md5(rand() * time()) . $ext;
            $imgPath = PRODUCT_IMG_DIR . $newName;
            $width = 1000;
            $height = 667;
            createThumbnail($tmpName, $imgPath, $width, $height);

            $query2 = "INSERT INTO tbl_images (property_id_f, file_name) VALUES ('$propertyID', '$newName')";
            mysqli_query($link, $query2); // execute the SQL 
            //header("Location: edit_property.php?property_ID=".$propertyID );
        }
    }

    // Update query 
    $query1 = "UPDATE tbl_properties SET title='$newPropertyTitle', city_id_f='$newPropertyCity', suburb_id_f='$newPropertySuburb', address='$newPropertyAddress', bedrooms_id_f='$newPropertyBedrooms', bathrooms_id_f='$newPropertyBathrooms', parking_id_f='$newPropertyParking', agent_id_f='$newPropertyAgent', sale_type_id_f='$newPropertySaleType', sale_details='$newPropertySaleDetails', description='$newPropertyDescription' WHERE property_id='$propertyID'";

    mysqli_query($link, $query1); // execute the SQL 
    header("Location: property_profile.php?property_ID=" . $propertyID);
} // end of if statement


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

// DELETE PHOTOS
if (isset($_POST['deletePropPhoto'])) {
    $oneOrAll = mysqli_real_escape_string($link, $_POST['deletePropPhoto']);
    $prop_id = mysqli_real_escape_string($link, $_POST['delete_propertyID']);

    if ($oneOrAll == 'one') {
        // grab the file name
        $deletePropImage = mysqli_real_escape_string($link, $_POST['delete_propertyImageName']);
        // delete image from image folder
        unlink('images/'.$deletePropImage);
        // delete from image table
        $query = "DELETE FROM tbl_images WHERE file_name= '$deletePropImage' ";
        mysqli_query($link, $query);
    } else {
        // find image titles and delete images from image folder
        $query1 = "SELECT file_name FROM tbl_images WHERE property_id_f = '$prop_id'";
        $result = mysqli_query($link, $query1);
        while ($row = mysqli_fetch_array($result)) {
            unlink('images/' . $row['file_name']);
        }
        // delete from image table
        $query = "DELETE FROM tbl_images WHERE property_id_f= $prop_id ";
        mysqli_query($link, $query);
    }
}

// DELETE PROPERTY
if (isset($_POST['delete'])) {
    $prop_ID = mysqli_real_escape_string($link, $_POST['delete_entryID']);
    //delete property from property table
    $query = "DELETE FROM tbl_properties WHERE property_id = '$prop_ID' ";
    mysqli_query($link, $query);

    //delete images from image folder
    $query1 = "SELECT file_name FROM tbl_images WHERE property_id_f = '$prop_ID'";
    $result = mysqli_query($link, $query1);
    while ($row = mysqli_fetch_array($result)) {
        unlink('images/' . $row['file_name']);
    }

    //delete property images from image table
    $query2 = "DELETE FROM tbl_images WHERE property_id_f = '$prop_ID' ";
    mysqli_query($link, $query2);
    header("Location: properties.php");
}
// end of if statement for Delete

include "include/header.php";
?>
<!-- Page Content -->
<section class="content agentTableSection ">

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <button type="button" id="sidebarCollapse" class="btn btn-info sticky">
                <i class="fas fa-align-left"></i>
                <!-- <span>Toggle Sidebar</span> -->
            </button>
            <?php
            if ($role == 'admin') {
                echo "<h5 class='mt-2 admin'>ADMIN</h5>";
            }
            ?>
            <h5 class='mr-3 mt-2'>Hello, <?php echo $_SESSION['fname']; ?></h5>
        </div>
    </nav>


    <div class="row pageTitle marginLeft mb-3">
        <div class="col center">
            <h1>Property ID: <?php echo $propID ?></h1>
        </div>
    </div>

    <div class="profileSection_property">
        <!-- if user is admin or if the property is by the agent user, then show edit buttons -->
        <?php if ($role == 'admin' || $propAgentID == $userID) {
            include "../admin/include/change_prop_btns.php";
        }
        ?>

        <div class="row mr-2 mb-3">
            <div id='carousel-custom' class='carousel slide ml-4 ml-md-5' data-ride='carousel'>
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
                            <img src=<?php echo '../admin/images/' . $image_row['file_name']; ?> class="d-block w-100" alt="...">
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
                        <li data-target='#carousel-custom' data-slide-to='<?php echo $next; ?>' class=''><img src='<?php echo '../admin/images/' . $image_row['file_name']; ?>' alt=''></li>
                    <?php } //end of while 
                    ?>
                </ol>
            </div>
            <div class="col col-md-3 d-none d-md-block noPadding card-deck" style="margin-right: 20px;">
                <div class="row mb-3">

                    <?php
                    // Find if property is last record in tbl_feature
                    $featuredQuery = "SELECT property_id FROM tbl_properties WHERE featured = 'y'";
                    $featuredResult = mysqli_query($link, $featuredQuery);
                    while ($featured_row = mysqli_fetch_array($featuredResult)) {
                        if ($role == 'admin' || $featured_row['property_id'] == $propID) {

                            if ($featured_row['property_id'] == $propID) {
                                echo '<div class="card featuredBtn featureBtn featuredBtn' . $propID . '"><a class="btn featuredLink' . $propID . '"><i class="fas fa-star icon"></i>Featured Listing</a></div>';
                            } else {
                                echo '<div class="card featureBtn featuredBtn' . $propID . '"><a class="btn setAsFeaturedLink' . $propID . '" onclick="setAsFeatured(\'' . $propID . '\')"><i class="far fa-star icon"></i>Set as Featured Listing</a></div>';
                            }
                        }
                    }
                    ?>

                </div>
                <div class="row">
                    <div class="card center propertyAgentCard">
                        <img src="<?php echo '../admin/images/' . $propertyAgentPicture; ?>" class="card-img-top" alt="Photo" title="photo" />
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
                            <a class="btn btn-secondary btn-sm text-center" href="agent_profile.php?agentID=<?php echo $propAgentID; ?>">View Agent</a>
                            <a class="btn btn-secondary btn-sm text-center" href="properties.php?agentID=<?php echo $propAgentID; ?>">View Listings</a>
                        </div>
                    </div>
                </div>



            </div>
        </div>

        <div class="row mb-3 d-md-none">

            <?php
            // Find if property is last record in tbl_feature
            $featuredQuery = "SELECT property_id FROM tbl_properties WHERE featured = 'y'";
            $featuredResult = mysqli_query($link, $featuredQuery);
            while ($featured_row = mysqli_fetch_array($featuredResult)) {
                if ($role == 'admin' || $featured_row['property_id'] == $propID) {

                    if ($featured_row['property_id'] == $propID) {
                        echo '<div class="card col-md-8 ml-4 ml-md-5 mr-4 featuredBtn featureBtn featuredBtn' . $propID . '"><a class="btn featuredLink' . $propID . '"><i class="fas fa-star icon"></i>Featured Listing</a></div>';
                    } else {
                        echo '<div class="card col-md-8 ml-4 ml-md-5 mr-4 featureBtn featuredBtn' . $propID . '"><a class="btn setAsFeaturedLink' . $propID . '" onclick="setAsFeatured(\'' . $propID . '\')"><i class="far fa-star icon"></i>Set as Featured Listing</a></div>';
                    }
                }
            }
            ?>
        </div>

        <div class="row">
            <div class="col-md-8 ml-4 ml-md-5 noPadding mr-4">
                <div class="card bg-light mb-4 ">
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
                <div class="row mb-3">
                    <h3><?php echo $propTitle; ?></h3>
                </div>
                <div class="row mb-3">
                    <h6><?php echo $propAddress; ?></h6>
                </div>
                <div class="row aboutProperty">
                    <p><?php echo $propDescription; ?></p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="ml-5 mr-5 d-md-none mt-3 ">
                <div class="card mb-3">
                    <div class="row no-gutters">
                        <div class="col-sm-5">
                            <img src="<?php echo '../admin/images/' . $propertyAgentPicture; ?>" class="card-img-top" alt="Photo" title="photo" />
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
                                <a class="btn btn-secondary btn-sm text-center" href="agent_profile.php?agentID=<?php echo $propAgentID; ?>">View Agent</a>
                                <a class="btn btn-secondary btn-sm text-center" href="properties.php?agentID=<?php echo $propAgentID; ?>">View Listings</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div> <!-- end of property profile -->

    <?php if ($role == 'admin' || $propAgentID == $userID) {
        include "../admin/include/change_property.php";
    }
    ?>

</section> <!-- End of wrapper div -->

<?php
include "include/close_modal.php";
include "include/footer.php"; ?>