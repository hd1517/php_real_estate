<?php
include "include/config.php";
include "include/pagination.php";

$thisPage = 'Properties';

// check if user is logged in
if (isset($_SESSION['user_id'])) {
    $userID = $_SESSION['user_id'];
} else {
    $userID = 'none';
}

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

<section class="header">
    <div class="container-fluid noPadding">
        <img src="images/header02.jpg" alt="" class="headerImg">
    </div>
</section>

<section class="center mb-2" style="margin-top: 30px;">
    <h1>Listed Properties</h1>
</section>

<section class="mb-5">
    <div class="row noMargin">
        <div class="col-md-3" style="padding-right: 10px;">
            <form method="get" class="searchForm">
                <div class="form-group center">
                    <h5>Search Properties</h5>
                </div>
                <div class="form-group">
                    <label for="by_city">City</label>
                    <select name="by_city" class="form-control by_city">
                        <!-- <option class="default" value="" hidden disabled selected>All Cities</option> -->
                        <option class="default" value="all">All Cities</option>
                        <?php
                        $query = "SELECT * FROM tbl_city ORDER BY city";
                        $result = mysqli_query($link, $query);
                        while ($row = mysqli_fetch_array($result)) {
                            extract($row);
                            $selectedVal1 = (isset($_GET['by_city'])) ? $_GET['by_city'] : '';
                        ?>
                            <option <?php if ($selectedVal1 == $city_id) echo 'selected'; ?> value="<?php echo $city_id; ?>"><?php echo $city; ?></option>
                        <?php
                        } //end of while loop
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="by_suburb">Suburb</label>
                    <select name="by_suburb" class="form-control by_suburb" <?php if (!isset($_GET['by_city'])) echo 'disabled'; ?>>
                        <!-- <option value="" selected hidden disabled>All Suburbs</option> -->
                        <option value="all">All Suburbs</option>
                        <?php

                        if (isset($_GET['by_city'])) {
                            $selectedCity = $_GET['by_city'];
                            $query = "SELECT * FROM tbl_suburb WHERE city_id_f='$selectedCity' ORDER BY suburb";
                        } else {
                            $query = "SELECT * FROM tbl_suburb ORDER BY suburb";
                        }

                        $result = mysqli_query($link, $query);
                        while ($row = mysqli_fetch_array($result)) {
                            extract($row);
                            $selectedVal2 = (isset($_GET['by_suburb'])) ? $_GET['by_suburb'] : '';
                        ?>
                            <option <?php if ($selectedVal2 == $suburb_id) echo 'selected'; ?> value="<?php echo $suburb_id; ?>"><?php echo $suburb; ?></option>
                        <?php
                        } //end of while loop
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <div class="row noMargin">
                        <div class="col-5 noPadding">
                            <label for="min_bed">Min. Beds</label>
                            <select name="min_bed" class="form-control">
                                <!-- <option value="" selected hidden disabled>Any</option> -->
                                <option value="all">Any</option>
                                <?php
                                $query = "SELECT * FROM tbl_bedrooms";
                                $result = mysqli_query($link, $query);
                                while ($row = mysqli_fetch_array($result)) {
                                    extract($row);
                                    $selectedVal3 = (isset($_GET['min_bed'])) ? $_GET['min_bed'] : '';
                                ?>
                                    <option <?php if ($selectedVal3 == $bedrooms_id) echo 'selected'; ?> value="<?php echo $bedrooms_id; ?>"><?php echo $bedrooms; ?></option>
                                <?php
                                } //end of while loop
                                ?>
                            </select>
                        </div>
                        <div class="col-5 noPadding offset-2">
                            <label for="max_bed">Max. Beds</label>
                            <select name="max_bed" class="form-control ">
                                <!-- <option value="" selected hidden disabled>Any</option> -->
                                <option value="all">Any</option>
                                <?php
                                $query = "SELECT * FROM tbl_bedrooms";
                                $result = mysqli_query($link, $query);
                                while ($row = mysqli_fetch_array($result)) {
                                    extract($row);
                                    $selectedVal4 = (isset($_GET['max_bed'])) ? $_GET['max_bed'] : '';
                                ?>
                                    <option <?php if ($selectedVal4 == $bedrooms_id) echo 'selected'; ?> value="<?php echo $bedrooms_id; ?>"><?php echo $bedrooms; ?></option>
                                <?php
                                } //end of while loop
                                ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row noMargin">
                        <div class="col-5 noPadding">
                            <label for="min_bath">Min. Bath</label>
                            <select name="min_bath" class="form-control">
                                <!-- <option value="" selected hidden disabled>Any</option> -->
                                <option value="all">Any</option>
                                <?php
                                $query = "SELECT * FROM tbl_bathrooms";
                                $result = mysqli_query($link, $query);
                                while ($row = mysqli_fetch_array($result)) {
                                    extract($row);
                                    $selectedVal5 = (isset($_GET['min_bath'])) ? $_GET['min_bath'] : '';
                                ?>
                                    <option <?php if ($selectedVal5 == $bathrooms_id) echo 'selected'; ?> value="<?php echo $bathrooms_id; ?>"><?php echo $bathrooms; ?></option>
                                <?php
                                } //end of while loop
                                ?>
                            </select>
                        </div>
                        <div class="col-5 noPadding offset-2">
                            <label for="max_bath">Max. Bath</label>
                            <select name="max_bath" class="form-control">
                                <!-- <option value="" selected hidden disabled>Any</option> -->
                                <option value="all">Any</option>
                                <?php
                                $query = "SELECT * FROM tbl_bathrooms";
                                $result = mysqli_query($link, $query);
                                while ($row = mysqli_fetch_array($result)) {
                                    extract($row);
                                    $selectedVal6 = (isset($_GET['max_bath'])) ? $_GET['max_bath'] : '';
                                ?>
                                    <option <?php if ($selectedVal6 == $bathrooms_id) echo 'selected'; ?> value="<?php echo $bathrooms_id; ?>"><?php echo $bathrooms; ?></option>
                                <?php
                                } //end of while loop
                                ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row noMargin" style="justify-content: space-between;">
                        <button type="submit" class="btn btn-secondary w-75 mr-1">Search</button>
                        <button type="button" class="resetBtn">Reset</button>
                    </div>

                </div>

            </form>

        </div>
        <div class="col-md-9 propertyCardCol">
            <div class="card-deck propResults">

                <?php
                $rowsPerPage = 9; // edit the number of rows per page

                $errors = array();

                $by_city = (isset($_GET['by_city'])) ? $_GET['by_city'] : '';
                $by_suburb = (isset($_GET['by_suburb'])) ? $_GET['by_suburb'] : '';
                $min_bed = (isset($_GET['min_bed'])) ? $_GET['min_bed'] : '';
                $max_bed = (isset($_GET['max_bed'])) ? $_GET['max_bed'] : '';
                $min_bath = (isset($_GET['min_bath'])) ? $_GET['min_bath'] : '';
                $max_bath = (isset($_GET['max_bath'])) ? $_GET['max_bath'] : '';

                // get search textbox
                $searchTerms = (isset($_GET['search'])) ? mysqli_real_escape_string($link, $_GET['search']) : '';

                if (isset($_GET['by_city']) || isset($_GET['by_suburb']) || isset($_GET['min_bed']) || isset($_GET['max_bed']) || isset($_GET['min_bath']) || isset($_GET['max_bath']) || isset($_GET['search'])) {

                    $sql = "SELECT tbl_properties.property_id as propertyID, 
    tbl_properties.title as propertyTitle, 
    tbl_city.city as propertyCity, 
    tbl_suburb.suburb as propertySuburb, 
    tbl_properties.address as propertyAddress, 
    tbl_bedrooms.bedrooms as propertyBedrooms, 
    tbl_bathrooms.bathrooms as propertyBathrooms, 
    tbl_parking.parking as propertyParking, 
    tbl_sale_type.sale_type as propertySaleType, 
    tbl_listing_status.listing_status as propertyListingStatus
    FROM tbl_properties 
    INNER JOIN tbl_city ON tbl_properties.city_id_f=tbl_city.city_id 
    INNER JOIN tbl_suburb ON tbl_properties.suburb_id_f=tbl_suburb.suburb_id
    INNER JOIN tbl_bedrooms ON tbl_properties.bedrooms_id_f=tbl_bedrooms.bedrooms_id
    INNER JOIN tbl_bathrooms ON tbl_properties.bathrooms_id_f=tbl_bathrooms.bathrooms_id
    INNER JOIN tbl_parking ON tbl_properties.parking_id_f=tbl_parking.parking_id
    INNER JOIN tbl_sale_type ON tbl_properties.sale_type_id_f=tbl_sale_type.sale_type_id
    INNER JOIN tbl_listing_status ON tbl_properties.listing_status_id_f=tbl_listing_status.listing_status_id";
                    $conditions = array();
                    $types = array();
                    $pagingParameters = array();

                    if ($by_city != "" && $by_city != "all") {
                        $conditions[] = "tbl_properties.city_id_f='$by_city'";
                        $pagingParameters[] = "by_city=$by_city";
                    }
                    if ($by_suburb != "" && $by_suburb != "all") {
                        $conditions[] = "tbl_properties.suburb_id_f='$by_suburb'";
                        $pagingParameters[] = "by_suburb=$by_suburb";
                    }
                    if ($min_bed != "" && $min_bed != "all") {
                        $conditions[] = "tbl_properties.bedrooms_id_f >='$min_bed'";
                        $pagingParameters[] = "min_bed=$min_bed";
                    }
                    if ($max_bed != "" && $max_bed != "all") {
                        $conditions[] = "tbl_properties.bedrooms_id_f <='$max_bed'";
                        $pagingParameters[] = "max_bed=$max_bed";
                    }
                    if ($min_bath != "" && $min_bath != "all") {
                        $conditions[] = "tbl_properties.bathrooms_id_f >='$min_bath'";
                        $pagingParameters[] = "min_bath=$min_bath";
                    }
                    if ($max_bath != "" && $max_bath != "all") {
                        $conditions[] = "tbl_properties.bathrooms_id_f <='$max_bath'";
                        $pagingParameters[] = "max_bath=$max_bath";
                    }

                    // if search has been done
                    if ($searchTerms != "") {
                        // check if the length is not less than 2 chars
                        if (strlen($searchTerms) < 3) {
                            $errors[] = "Your search term must be longer than 2 characters";
                        }

                        if (count($errors) < 1) {
                            $types[] = "tbl_city.city LIKE '%{$searchTerms}%' ";
                            $types[] = "tbl_suburb.suburb LIKE '%{$searchTerms}%' ";
                            $types[] = "tbl_properties.address LIKE '%{$searchTerms}%' ";
                            $pagingParameters[] = "search=$searchTerms";
                        }
                    }

                    $query = $sql;
                    $pageParameters = "";
                    $query .= " WHERE ";
                    if ($searchTerms != "" && count($errors) < 1) {
                        $query .= "( " . implode(" || ", $types) . " )";
                        $query .= " AND ";
                    } elseif ($searchTerms != "" && count($errors) > 0) {
                        $query .= " tbl_properties.property_id = '0' AND ";
                    }

                    if (count($conditions) > 0) {
                        $query .= implode(' AND ', $conditions);
                        $query .= " AND tbl_properties.listing_status_id_f = '1' ORDER BY tbl_properties.property_ID DESC";
                        $pageParameters .= implode('&', $pagingParameters);
                    } else {
                        $query .= "  tbl_properties.listing_status_id_f = '1' ORDER BY tbl_properties.property_ID DESC";
                    }
                } else {
                    $query = "SELECT tbl_properties.property_id as propertyID, tbl_properties.title as propertyTitle, tbl_city.city as propertyCity, tbl_suburb.suburb as propertySuburb, tbl_properties.address as propertyAddress, tbl_bedrooms.bedrooms as propertyBedrooms, tbl_bathrooms.bathrooms as propertyBathrooms, tbl_parking.parking as propertyParking, tbl_sale_type.sale_type as propertySaleType, tbl_listing_status.listing_status as propertyListingStatus
    FROM tbl_properties 
    INNER JOIN tbl_city ON tbl_properties.city_id_f=tbl_city.city_id 
    INNER JOIN tbl_suburb ON tbl_properties.suburb_id_f=tbl_suburb.suburb_id
    INNER JOIN tbl_bedrooms ON tbl_properties.bedrooms_id_f=tbl_bedrooms.bedrooms_id
    INNER JOIN tbl_bathrooms ON tbl_properties.bathrooms_id_f=tbl_bathrooms.bathrooms_id
    INNER JOIN tbl_parking ON tbl_properties.parking_id_f=tbl_parking.parking_id
    INNER JOIN tbl_sale_type ON tbl_properties.sale_type_id_f=tbl_sale_type.sale_type_id
    INNER JOIN tbl_listing_status ON tbl_properties.listing_status_id_f=tbl_listing_status.listing_status_id
    WHERE tbl_properties.listing_status_id_f = '1'"; // only show pending sale properties

                    if (isset($_GET['agentID'])) {
                        $urlAgentID = $_GET['agentID'];
                        $query .= " AND tbl_properties.agent_id_f = '$urlAgentID'";
                    }

                    $query .= " ORDER BY tbl_properties.property_ID DESC";
                    $pageParameters = "";
                }

                $pagingLink = getPagingLink($query, $rowsPerPage, $pageParameters);
                $result = mysqli_query($link, getPagingQuery($query, $rowsPerPage));
                if (!$result) {
                    printf("Error: %s\n", mysqli_error($link));
                    exit();
                }

                if (mysqli_num_rows($result) == 0) {
                    if (count($errors) > 0) {
                        echo '<p class="ml-3 mt-5">Your search term must be longer than 2 characters</p>';
                        //echo "" . implode("<br>", $errors);
                    } else {
                        echo '<p class="ml-3 mt-5">No properties found with the search terms</p>';
                    }
                }

                while ($row = mysqli_fetch_array($result)) {

                    $propertyID = $row['propertyID'];
                ?>
                    <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4 noPadding d-flex">
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
                                <div class="row noMargin mt-auto btnRow<?php echo $propertyID; ?>" style="justify-content: space-between;">
                                    <a class="btn btn-secondary btn-sm text-center" href="property_profile.php?property_ID=<?php echo $row['propertyID']; ?>">View Details</a>
                                    <?php
                                    if ($userID == 'none') {
                                        echo '<a class="btn btn-secondary text-light btn-sm text-center addWatchBtn' . $propertyID . '" onclick="addWatchlist(\'' . $userID . '\',\'' . $propertyID . '\')"><i class="far fa-star icon"></i> Add to Watchlist</a>';
                                    } else {
                                        $watchlist_query = "SELECT * FROM tbl_watchlist WHERE user_id_f=$userID AND property_id_f=$propertyID";
                                        $watchlist_result = mysqli_query($link, $watchlist_query);
                                        if (mysqli_num_rows($watchlist_result) == 0) {
                                            echo '<a class="btn btn-secondary text-light btn-sm text-center addWatchBtn' . $propertyID . '" onclick="addWatchlist(\'' . $userID . '\',\'' . $propertyID . '\')"><i class="far fa-star icon"></i> Add to Watchlist</a>';
                                        } else {
                                            echo '<a class="btn btn-secondary text-light btn-sm text-center removeWatchBtn' . $propertyID . '" onclick="removeWatchlist(\'' . $userID . '\',\'' . $propertyID . '\')"><i class="fas fa-star icon"></i> Watched</a>';
                                        }
                                    }
                                    ?>

                                </div>

                            </div>
                            <div class="card-footer">
                                <small class="text-muted"><?php echo $row['propertySaleType']; ?></small>
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