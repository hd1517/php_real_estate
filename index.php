<?php
include "include/config.php";

$thisPage = "Home";

// check if user is logged in
if (isset($_SESSION['user_id'])) {
    $userID = $_SESSION['user_id'];
} else {
    $userID = 'none';
}
include "include/login_modal.php";
include "include/header.php";
?>

<section class="header">
    <div class="container-fluid noPadding headerContainer">
        <div class="row headerImgDiv noMargin">
            <div class="col-lg-8 mx-auto align-self-center" style="padding-right: 50px; padding-left: 50px;">
                <div>
                    <h1 class="headerTitle">Find your new home</h1>
                </div>

                <div>
                    <!-- Custom rounded search bars with input group -->
                    <form action="properties.php" method="get">
                        <div class="p-1 bg-light rounded-pill shadow-sm mb-4 mt-3">
                            <div class="input-group ">
                                <input type="search" name="search" placeholder="Enter Street, Suburb or City to Search for Properties" aria-describedby="button-addon1" class="form-control mySearchInput border-0 bg-light" value="">
                                <div class="input-group-append">
                                    <button id="button-addon1" type="submit" class="btn btn-link text-primary" value=""><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>


            </div>
        </div>
    </div>

    </div>
</section>

<!-- Featured Property -->
<section class="center mb-2" style="margin-top: 30px;">
    <h1>Featured Listing</h1>
</section>


<div class="propertyCardCol mb-5">
    <div class="row noMargin propResults center">
        <?php
        $query = "SELECT tbl_properties.property_id as propertyID, tbl_properties.title as propertyTitle, tbl_city.city as propertyCity, tbl_suburb.suburb as propertySuburb, tbl_properties.address as propertyAddress, tbl_bedrooms.bedrooms as propertyBedrooms, tbl_bathrooms.bathrooms as propertyBathrooms, tbl_parking.parking as propertyParking, tbl_sale_type.sale_type as propertySaleType, tbl_listing_status.listing_status as propertyListingStatus
    FROM tbl_properties 
    INNER JOIN tbl_city ON tbl_properties.city_id_f=tbl_city.city_id 
    INNER JOIN tbl_suburb ON tbl_properties.suburb_id_f=tbl_suburb.suburb_id
    INNER JOIN tbl_bedrooms ON tbl_properties.bedrooms_id_f=tbl_bedrooms.bedrooms_id
    INNER JOIN tbl_bathrooms ON tbl_properties.bathrooms_id_f=tbl_bathrooms.bathrooms_id
    INNER JOIN tbl_parking ON tbl_properties.parking_id_f=tbl_parking.parking_id
    INNER JOIN tbl_sale_type ON tbl_properties.sale_type_id_f=tbl_sale_type.sale_type_id
    INNER JOIN tbl_listing_status ON tbl_properties.listing_status_id_f=tbl_listing_status.listing_status_id
    WHERE tbl_properties.featured = 'y'"; // show the featured property
        $result = mysqli_query($link, $query);
        if (!$result) {
            printf("Error: %s\n", mysqli_error($link));
            exit();
        }

        while ($row = mysqli_fetch_array($result)) {
            $propertyID = $row['propertyID'];
        ?>
            <div class="col-md-7 noPadding mx-auto">
                <div class="card agentCard propertyCard center ">
                    <div id="carousel_<?php echo $propertyID; ?>" class="carousel slide">
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


                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title"><?php echo $row['propertyTitle']; ?></h6>
                        <p class="card-text"></i> <?php echo $row['propertyAddress']; ?></p>
                        <p class="card-text center"><?php echo $row['propertyBedrooms']; ?> <i class="fas fa-bed propertyIcon"></i> <?php echo $row['propertyBathrooms']; ?> <i class="fas fa-bath propertyIcon"></i> <?php echo $row['propertyParking']; ?> <i class="fas fa-car propertyIcon"></i></p>
                        <div class="row noMargin mt-auto btnRow<?php echo $propertyID; ?>" style="justify-content: center;">
                            <a class="btn btn-secondary btn-sm text-center" href="property_profile.php?property_ID=<?php echo $row['propertyID']; ?>">View Details</a>
                            <?php
                            if ($userID == 'none') {
                                echo '<a style="margin-left: 20px;" class="btn btn-secondary text-light btn-sm text-center addWatchBtn' . $propertyID . '" onclick="addWatchlist(\'' . $userID . '\',\'' . $propertyID . '\')"><i class="far fa-star icon"></i> Add to Watchlist</a>';
                            } else {
                                $watchlist_query = "SELECT * FROM tbl_watchlist WHERE user_id_f=$userID AND property_id_f=$propertyID";
                                $watchlist_result = mysqli_query($link, $watchlist_query);
                                if (mysqli_num_rows($watchlist_result) == 0) {
                                    echo '<a style="margin-left: 20px;" class="btn btn-secondary text-light btn-sm text-center addWatchBtn' . $propertyID . '" onclick="addWatchlist(\'' . $userID . '\',\'' . $propertyID . '\')"><i class="far fa-star icon"></i> Add to Watchlist</a>';
                                } else {
                                    echo '<a style="margin-left: 20px;" class="btn btn-secondary text-light btn-sm text-center removeWatchBtn' . $propertyID . '" onclick="removeWatchlist(\'' . $userID . '\',\'' . $propertyID . '\')"><i class="fas fa-star icon"></i> Watched</a>';
                                }
                            }
                            ?>

                        </div>

                    </div>
                </div>
            </div>

        <?php
        } // end of while loop for properties
        ?>

    </div>
</div>

<!-- Footer -->
<?php include "include/footer.php"; ?>