<?php
include "include/config.php";
include "include/pagination.php";

$thisPage = 'Agent Profile';

// retreive from URL
$agentID = mysqli_real_escape_string($link, $_GET['agentID']);

// locate the agent record from database
$query = "SELECT tbl_agents.fname as fname,
tbl_agents.lname as lname,
tbl_agents.phone as phone,
tbl_agents.email as email,
tbl_agents.about as about,
tbl_agents.picture as picture,
tbl_agents.password as pass,
tbl_city.city as city,
tbl_user_type.user_type as userType
FROM tbl_agents 
INNER JOIN tbl_city ON tbl_agents.city_id_f=tbl_city.city_id
INNER JOIN tbl_user_type ON tbl_agents.user_type_id_f=tbl_user_type.user_type_id
WHERE agent_id='$agentID'";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_array($result);
extract($row);
$fName = $fname;
$lName = $lname;
$Phone = $phone;
$Email = $email;
$City = $city;
$About = $about;
$Picture = $picture;

include "include/login_modal.php";
include "include/header.php";
?>

<section class="header">
    <div class="container-fluid noPadding">
        <img src="images/header03.jpg" alt="" class="headerImg">
    </div>
</section>

<section class="agentProfileSection container mb-4">
    <div class="row mb-5">
        <div class="col-sm-6 col-md-4">
            <div class="card agentCard profileCard center">
                <img src="<?php echo 'admin/images/' . $Picture; ?>" class="card-img-top" alt="Photo" title="photo" />
                <div class="card-body">
                    <h5 class="card-title "><?php echo $fName; ?> <?php echo $lName; ?></h5>
                    <p class="card-text"><i class="fas fa-envelope icon"></i> <?php echo $Email; ?></p>
                    <p class="card-text"><i class="fas fa-phone icon"></i> <?php echo $Phone; ?></p>
                    <p class="card-text"><i class="fas fa-map-marker-alt icon1"></i> <?php echo $City; ?></p>
                    <a class="btn btn-secondary btn-sm" href="properties.php?agentID=<?php echo $agentID; ?>">View Listings</a>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-8 aboutProfile">
            <div class="row">
                <h3>About <?php echo $fName; ?></h3>
                <hr>
                <p><?php echo $About; ?></p>
            </div>
        </div>
    </div>
    </div>

    <div class="row center">
        <div class="col">
            <h3>Previous Listings</h3>
        </div>

    </div>

    <div class="row noMargin">
        <div class="card-deck propResults" style="margin-left: 15px;">

            <?php
            $rowsPerPage = 6; // edit the number of rows per page

            $query = "SELECT tbl_properties.property_id as propertyID, 
tbl_properties.title as propertyTitle, 
tbl_city.city as propertyCity, 
tbl_suburb.suburb as propertySuburb, 
tbl_properties.address as propertyAddress, 
tbl_bedrooms.bedrooms as propertyBedrooms, 
tbl_bathrooms.bathrooms as propertyBathrooms, 
tbl_parking.parking as propertyParking, 
tbl_sale_type.sale_type as propertySaleType, 
tbl_listing_status.listing_status as propertyListingStatus,
tbl_agents.agent_id as propertyAgentID
FROM tbl_properties 
INNER JOIN tbl_city ON tbl_properties.city_id_f=tbl_city.city_id 
INNER JOIN tbl_suburb ON tbl_properties.suburb_id_f=tbl_suburb.suburb_id
INNER JOIN tbl_bedrooms ON tbl_properties.bedrooms_id_f=tbl_bedrooms.bedrooms_id
INNER JOIN tbl_bathrooms ON tbl_properties.bathrooms_id_f=tbl_bathrooms.bathrooms_id
INNER JOIN tbl_parking ON tbl_properties.parking_id_f=tbl_parking.parking_id
INNER JOIN tbl_sale_type ON tbl_properties.sale_type_id_f=tbl_sale_type.sale_type_id
INNER JOIN tbl_listing_status ON tbl_properties.listing_status_id_f=tbl_listing_status.listing_status_id
INNER JOIN tbl_agents ON tbl_properties.agent_id_f=tbl_agents.agent_id
WHERE tbl_properties.listing_status_id_f=2
AND tbl_properties.agent_id_f=$agentID
ORDER BY tbl_properties.property_ID DESC";

            $pagingLink = getPagingLink($query, $rowsPerPage, "agentID=$agentID");
            $result = mysqli_query($link, getPagingQuery($query, $rowsPerPage));
            if (!$result) {
                printf("Error: %s\n", mysqli_error($link));
                exit();
            }

            while ($row = mysqli_fetch_array($result)) {

                $propertyID = $row['propertyID'];
            ?>
                <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4 noPadding d-flex">
                    <div class="card agentCard propertyCard center ">
                        <div class="card-header">
                            <p class="noMargin"><?php echo $row['propertyListingStatus']; ?></p>
                        </div>
                        <div id="carousel_<?php echo $propertyID; ?>" class="carousel slide" data-ride="carousel">
                            <div class="carousel-inner propertyCaro">
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
                                <?php } //end of image loop
                                ?>

                            </div>
                            <a class="carousel-control-prev" href="#carousel_<?php echo $propertyID; ?>" role="button" data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="carousel-control-next" href="#carousel_<?php echo $propertyID; ?>" role="button" data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </a>
                        </div>


                        <div class="card-body">
                            <h6 class="card-title"><?php echo $row['propertyTitle']; ?></h6>
                            <p class="card-text"></i> <?php echo $row['propertyAddress']; ?></p>
                            <p class="card-text center"><?php echo $row['propertyBedrooms']; ?> <i class="fas fa-bed propertyIcon"></i> <?php echo $row['propertyBathrooms']; ?> <i class="fas fa-bath propertyIcon"></i> <?php echo $row['propertyParking']; ?> <i class="fas fa-car propertyIcon"></i></p>
                            <a class="btn btn-secondary btn-sm text-center" href="property_profile.php?property_ID=<?php echo $row['propertyID']; ?>">View Details</a>
                        </div>
                    </div>
                </div>

            <?php
            } // end of while loop for properties
            ?>

        </div>

        <!-- display paging links if required -->
        <?php if ($pagingLink != '') {
            echo '<div class="pagingLinks center"><p>' . $pagingLink . '</p></div>';
        } ?>

    </div>


</section>

<!-- Footer -->
<?php include "include/footer.php"; ?>