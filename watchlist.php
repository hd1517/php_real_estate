<?php
include "include/config.php";
include "include/pagination.php";

$thisPage = 'Watchlist';


// check if user is NOT sign in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
}

$userID = $_SESSION['user_id'];

include "include/header.php";
?>
<section class="header mt-5">
    <div class="container-fluid noPadding">
        <img src="images/header03.jpg" alt="" class="headerImg">
    </div>
</section>

<section class="center mt-5 mb-2">
    <h1>Watched Properties</h1>
</section>

<section>
    <div class="row noMargin">
        <div class="col propertyCardCol">
            <div class="card-deck propResults">

                <?php
                $rowsPerPage = 9; // edit the number of rows per page

                $query = "SELECT * FROM tbl_watchlist WHERE user_id_f=$userID ORDER BY watchlist_id DESC";
                $pageParameters = "";
                $pagingLink = getPagingLink($query, $rowsPerPage, $pageParameters);
                $result = mysqli_query($link, getPagingQuery($query, $rowsPerPage));
                if (!$result) {
                    printf("Error: %s\n", mysqli_error($link));
                    exit();
                }

                if (mysqli_num_rows($result) == 0) {
                    echo '<p class="ml-3 mt-5">You have no saved properties in your Watchlist.</p>';
                }

                while ($row = mysqli_fetch_array($result)) {
                    $propertyID = $row['property_id_f'];

                    $watchlisted_query = "SELECT tbl_properties.property_id as propertyID, tbl_properties.title as propertyTitle, tbl_city.city as propertyCity, tbl_suburb.suburb as propertySuburb, tbl_properties.address as propertyAddress, tbl_bedrooms.bedrooms as propertyBedrooms, tbl_bathrooms.bathrooms as propertyBathrooms, tbl_parking.parking as propertyParking, tbl_sale_type.sale_type as propertySaleType, tbl_listing_status.listing_status as propertyListingStatus
                        FROM tbl_properties 
                        INNER JOIN tbl_city ON tbl_properties.city_id_f=tbl_city.city_id 
                        INNER JOIN tbl_suburb ON tbl_properties.suburb_id_f=tbl_suburb.suburb_id
                        INNER JOIN tbl_bedrooms ON tbl_properties.bedrooms_id_f=tbl_bedrooms.bedrooms_id
                        INNER JOIN tbl_bathrooms ON tbl_properties.bathrooms_id_f=tbl_bathrooms.bathrooms_id
                        INNER JOIN tbl_parking ON tbl_properties.parking_id_f=tbl_parking.parking_id
                        INNER JOIN tbl_sale_type ON tbl_properties.sale_type_id_f=tbl_sale_type.sale_type_id
                        INNER JOIN tbl_listing_status ON tbl_properties.listing_status_id_f=tbl_listing_status.listing_status_id
                        WHERE tbl_properties.property_id='$propertyID'";
                    $watchlisted_result = mysqli_query($link, $watchlisted_query);
                    $watchlisted_row = mysqli_fetch_array($watchlisted_result);
                    //extract($watchlisted_row);

                ?>
                    <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4 noPadding d-flex">
                        <div class="card agentCard propertyCard center ">
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
                                <h6 class="card-title"><?php echo $watchlisted_row['propertyTitle']; ?></h6>
                                <p class="card-text"></i> <?php echo $watchlisted_row['propertyAddress']; ?></p>
                                <p class="card-text center"><?php echo $watchlisted_row['propertyBedrooms']; ?> <i class="fas fa-bed propertyIcon"></i> <?php echo $watchlisted_row['propertyBathrooms']; ?> <i class="fas fa-bath propertyIcon"></i> <?php echo $watchlisted_row['propertyParking']; ?> <i class="fas fa-car propertyIcon"></i></p>
                                <a class="btn btn-secondary btn-sm text-center" href="property_profile.php?property_ID=<?php echo $watchlisted_row['propertyID']; ?>">View Details</a>
                            </div>
                            <div class="card-footer">
                                <small class="text-muted"><?php echo $watchlisted_row['propertySaleType']; ?></small>
                            </div>
                        </div>
                    </div>

                <?php
                } // end of while loop for properties
                ?>

            </div>
            <div class="pagingLinks center">
                <p><?php echo $pagingLink; ?></p>
                <!-- display paging links -->
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<?php include "include/footer.php"; ?>