<?php
include "include/user_check.php";
include "../include/pagination.php";

// page title depending on user login 
if ($role == 'admin') {
    $thisPage = "Manage Properties";
} else {
    if (isset($_GET['properties'])) {
        $thisPage = "All Properties";
    } elseif (isset($_GET['agentID'])) {
        $urlAgentID = $_GET['agentID'];
        if ($urlAgentID == $userID) {
            $thisPage = "My Properties";
        } else {
            $thisPage = "All Properties";
        }
    } else {
        $thisPage = "My Properties";
    }
}

include "include/header.php";
?>
<!-- Page Content -->
<section class="content agentTableSection">

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


    <div class="row pageTitle marginLeft">
        <div class="col center mb-3">
            <h1>
                <?php if ($role == 'admin') {
                    echo 'Manage Properties';
                } else {
                    if (isset($_GET['properties'])) {
                        echo "Other Listed Properties";
                    } elseif (isset($_GET['agentID'])) {
                        $urlAgentID = $_GET['agentID'];
                        if ($urlAgentID == $userID) {
                            echo "My Properties";
                        } else {
                            echo "Other Listed Properties";
                        }
                    } else {
                        echo "My Properties";
                    }
                }
                ?>

            </h1>
        </div>
    </div>


    <div class="">
        <!-- VIEW ALL PROPERTIES SECTION -->
        <section class="viewProperties">

            <form method="get" class="adminSearchForm">
                <div class="filterBox">
                    <div class="row  ml-0 mr-0">
                        <div class="col-md-3 filterCols">
                            <select name="by_city" class="form-control by_city">
                                <option class="default" value="" disabled hidden selected>City</option>
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

                        <div class="col-md-3 filterCols">
                            <select name="by_suburb" class="form-control by_suburb" <?php if (!isset($_GET['by_city'])) echo 'disabled'; ?>>
                                <option value="" disabled hidden selected>Suburb</option>
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

                        <div class="col-md-3 filterCols">
                            <select name="by_agent" class="form-control by_agent">
                                <?php
                                $urlAgentID = (isset($_GET['agentID'])) ? $_GET['agentID'] : '';
                                $selectedVal3 = (isset($_GET['by_agent'])) ? $_GET['by_agent'] : '';
                                if ($role != 'agent' || ($role == 'agent' && (isset($_GET['properties'])))) {
                                    echo '<option value="" disabled hidden selected>Agent</option>
                                        <option value="all">All Agents</option>';
                                }
                                $query = "SELECT * FROM tbl_agents";
                                if ($role == 'agent') {
                                    if (($urlAgentID == $userID && !isset($_GET['properties'])) || (!isset($_GET['properties']) && $urlAgentID == '' && $selectedVal3 == $userID) || (!isset($_GET['properties']) && $selectedVal3 == $userID)) {
                                        // when agent is looking for their own property listing
                                        $query .= " WHERE agent_id = '$userID'";
                                    } else if (($urlAgentID != $userID && $urlAgentID != '' && !isset($_GET['properties'])) || (isset($_GET['properties'])) || ($urlAgentID != $userID && $urlAgentID != '') || ($selectedVal3 != $userID && $selectedVal3 != '')) {
                                        // agent is looking for other properties apart from theirs
                                        $query .= " WHERE NOT agent_id = '$userID'";
                                    } else {
                                        // when agent is looking for their own property listing
                                        $query .= " WHERE agent_id = '$userID'";
                                    }
                                }
                                $query .= " ORDER BY fname";
                                $result = mysqli_query($link, $query);
                                while ($row = mysqli_fetch_array($result)) {
                                    extract($row);

                                ?>
                                    <option <?php if ($selectedVal3 == $agent_id || $urlAgentID == $agent_id) echo 'selected'; ?> value="<?php echo $agent_id; ?>"><?php echo $fname . ' ' . $lname; ?></option>
                                <?php
                                } //end of while loop
                                ?>
                            </select>
                        </div>

                        <div class="col-md-3 filterCols" style="border-right:none;">
                            <select name="by_listingStatus" class="form-control">
                                <option value="" disabled hidden selected>Listing Status</option>
                                <option value="all">All Listings</option>
                                <?php
                                $query = "SELECT * FROM tbl_listing_status ORDER BY listing_status";
                                $result = mysqli_query($link, $query);
                                while ($row = mysqli_fetch_array($result)) {
                                    extract($row);
                                    $selectedVal4 = (isset($_GET['by_listingStatus'])) ? $_GET['by_listingStatus'] : '';
                                ?>
                                    <option <?php if ($selectedVal4 == $listing_status_id) echo 'selected'; ?> value="<?php echo $listing_status_id; ?>"><?php echo $listing_status; ?></option>
                                <?php
                                } //end of while loop
                                ?>
                            </select>
                        </div>
                    </div>



                    <div class="row  ml-0 mr-0">
                        <div class="col-md-3 filterCols">
                            <div class="row noMargin noPadding" style="justify-content:space-between;">
                                <div class="col-5 noPadding">
                                    <select name="min_bed" class="form-control min_bed">
                                        <option class="default" value="" disabled hidden selected>Min. Bed</option>
                                        <option value="all">Any</option>
                                        <?php
                                        $query = "SELECT * FROM tbl_bedrooms";
                                        $result = mysqli_query($link, $query);
                                        while ($row = mysqli_fetch_array($result)) {
                                            extract($row);
                                            $selectedVal5 = (isset($_GET['min_bed'])) ? $_GET['min_bed'] : '';
                                        ?>
                                            <option <?php if ($selectedVal5 == $bedrooms_id) echo 'selected'; ?> value="<?php echo $bedrooms_id; ?>"><?php echo $bedrooms . ' min bed'; ?></option>
                                        <?php
                                        } //end of while loop
                                        ?>
                                    </select>
                                </div>
                                <div style="margin-top: 5px;">-</div>
                                <div class="col-5 noPadding">
                                    <select name="max_bed" class="form-control max_bed ">
                                        <option class="default" value="" disabled hidden selected>Max. Bed</option>
                                        <option value="all">Any</option>
                                        <?php
                                        $query = "SELECT * FROM tbl_bedrooms";
                                        $result = mysqli_query($link, $query);
                                        while ($row = mysqli_fetch_array($result)) {
                                            extract($row);
                                            $selectedVal6 = (isset($_GET['max_bed'])) ? $_GET['max_bed'] : '';
                                        ?>
                                            <option <?php if ($selectedVal6 == $bedrooms_id) echo 'selected'; ?> value="<?php echo $bedrooms_id; ?>"><?php echo $bedrooms . ' max bed'; ?></option>
                                        <?php
                                        } //end of while loop
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 filterCols">
                            <div class="row noMargin noPadding" style="justify-content:space-between;">
                                <div class="col-5 noPadding">
                                    <select name="min_bath" class="form-control min_bath">
                                        <option class="default" value="" disabled hidden selected>Min. Bath</option>
                                        <option value="all">Any</option>
                                        <?php
                                        $query = "SELECT * FROM tbl_bathrooms";
                                        $result = mysqli_query($link, $query);
                                        while ($row = mysqli_fetch_array($result)) {
                                            extract($row);
                                            $selectedVal7 = (isset($_GET['min_bath'])) ? $_GET['min_bath'] : '';
                                        ?>
                                            <option <?php if ($selectedVal7 == $bathrooms_id) echo 'selected'; ?> value="<?php echo $bathrooms_id; ?>"><?php echo $bathrooms . ' min bath'; ?></option>
                                        <?php
                                        } //end of while loop
                                        ?>
                                    </select>
                                </div>
                                <div style="margin-top: 5px;">-</div>
                                <div class="col-5 noPadding">
                                    <select name="max_bath" class="form-control max_bath">
                                        <option class="default" value="" disabled hidden selected>Max. Bath</option>
                                        <option value="all">Any</option>
                                        <?php
                                        $query = "SELECT * FROM tbl_bathrooms";
                                        $result = mysqli_query($link, $query);
                                        while ($row = mysqli_fetch_array($result)) {
                                            extract($row);
                                            $selectedVal8 = (isset($_GET['max_bath'])) ? $_GET['max_bath'] : '';
                                        ?>
                                            <option <?php if ($selectedVal8 == $bathrooms_id) echo 'selected'; ?> value="<?php echo $bathrooms_id; ?>"><?php echo $bathrooms . ' max bath'; ?></option>
                                        <?php
                                        } //end of while loop
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 filterCols">
                            <div class="row noMargin noPadding" style="justify-content:space-between;">
                                <div class="col-5 noPadding">
                                    <select name="min_park" class="form-control min_park">
                                        <option class="default" value="" disabled hidden selected>Min. Park</option>
                                        <option value="all">Any</option>
                                        <?php
                                        $query = "SELECT * FROM tbl_parking";
                                        $result = mysqli_query($link, $query);
                                        while ($row = mysqli_fetch_array($result)) {
                                            extract($row);
                                            $selectedVal9 = (isset($_GET['min_park'])) ? $_GET['min_park'] : '';
                                        ?>
                                            <option <?php if ($selectedVal9 == $parking_id) echo 'selected'; ?> value="<?php echo $parking_id; ?>"><?php echo $parking . ' min park'; ?></option>
                                        <?php
                                        } //end of while loop
                                        ?>
                                    </select>
                                </div>
                                <div style="margin-top: 5px;">-</div>
                                <div class="col-5 noPadding">
                                    <select name="max_park" class="form-control max_park">
                                        <option class="default" value="" disabled hidden selected>Max. Park</option>
                                        <option value="all">Any</option>
                                        <?php
                                        $query = "SELECT * FROM tbl_parking";
                                        $result = mysqli_query($link, $query);
                                        while ($row = mysqli_fetch_array($result)) {
                                            extract($row);
                                            $selectedVal10 = (isset($_GET['max_park'])) ? $_GET['max_park'] : '';
                                        ?>
                                            <option <?php if ($selectedVal10 == $parking_id) echo 'selected'; ?> value="<?php echo $parking_id; ?>"><?php echo $parking . ' max park'; ?></option>
                                        <?php
                                        } //end of while loop
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 filterCols" style="border-right:none;">
                            <select name="by_saleType" class="form-control by_saleType">
                                <option value="" disabled hidden selected>Type of Sale</option>
                                <option value="all">All Types of Sale</option>
                                <?php
                                $query = "SELECT * FROM tbl_sale_type ORDER BY sale_type";
                                $result = mysqli_query($link, $query);
                                while ($row = mysqli_fetch_array($result)) {
                                    extract($row);
                                    $selectedVal11 = (isset($_GET['by_saleType'])) ? $_GET['by_saleType'] : '';
                                ?>
                                    <option <?php if ($selectedVal11 == $sale_type_id) echo 'selected'; ?> value="<?php echo $sale_type_id; ?>"><?php echo $sale_type; ?></option>
                                <?php
                                } //end of while loop
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="row mr-0 ml-0">
                        <div class="input-group ">
                            <input type="search" name="search" placeholder="Enter Street, Suburb or City to Search for Properties" aria-describedby="button-addon1" class="form-control mySearchInput border-0 bg-light" value="<?php if (isset($_GET['search'])) echo ($_GET['search']); ?>">
                            <!-- <div class="input-group-append">
                                        <button id="button-addon1" type="submit" class="btn btn-link text-primary" value=""><i class="fa fa-search"></i></button>
                                    </div> -->
                        </div>
                    </div>


                    <div class="row mr-0 ml-0">
                        <button class="col-9 bg-secondary center searchBtnBorder border-0 searchBtn" name="<?php if (isset($_GET['properties'])) echo 'properties'; ?>" type="submit">
                            <span class="d-flex align-items-center text-white justify-content-center h-100" style="padding:10px">SEARCH</span>
                        </button>
                        <div class="col-3 bg-primary center border-0 resetSearchBtn">
                            <span class="d-flex align-items-center text-white justify-content-center h-100">RESET SEARCH</span>
                        </div>

                    </div>
                </div>
                <!-- End of search Row -->
            </form>

            <div class="card-deck propResults">

                <?php
                $rowsPerPage = 6; // edit the number of rows per page

                $errors = array();

                $by_city = (isset($_GET['by_city'])) ? $_GET['by_city'] : '';
                $by_suburb = (isset($_GET['by_suburb'])) ? $_GET['by_suburb'] : '';
                $by_agent = (isset($_GET['by_agent'])) ? $_GET['by_agent'] : '';
                $by_listingStatus = (isset($_GET['by_listingStatus'])) ? $_GET['by_listingStatus'] : '';
                $min_bed = (isset($_GET['min_bed'])) ? $_GET['min_bed'] : '';
                $max_bed = (isset($_GET['max_bed'])) ? $_GET['max_bed'] : '';
                $min_bath = (isset($_GET['min_bath'])) ? $_GET['min_bath'] : '';
                $max_bath = (isset($_GET['max_bath'])) ? $_GET['max_bath'] : '';
                $min_park = (isset($_GET['min_park'])) ? $_GET['min_park'] : '';
                $max_park = (isset($_GET['max_park'])) ? $_GET['max_park'] : '';
                $by_saleType = (isset($_GET['by_saleType'])) ? $_GET['by_saleType'] : '';
                $searchTerms = (isset($_GET['search'])) ? mysqli_real_escape_string($link, $_GET['search']) : '';

                if (isset($_GET['by_city']) || isset($_GET['by_suburb']) || isset($_GET['by_agent']) || isset($_GET['by_listingStatus']) || isset($_GET['min_bed']) || isset($_GET['max_bed']) || isset($_GET['min_bath']) || isset($_GET['max_bath']) || isset($_GET['min_park']) || isset($_GET['max_park']) || isset($_GET['by_saleType']) || isset($_GET['search'])) {

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
                    if ($by_agent != "" && $by_agent != "all") {
                        $conditions[] = "tbl_properties.agent_id_f='$by_agent'";
                        $pagingParameters[] = "by_agent=$by_agent";
                    }
                    if ($by_listingStatus != "" && $by_listingStatus != "all") {
                        $conditions[] = "tbl_properties.listing_status_id_f='$by_listingStatus'";
                        $pagingParameters[] = "by_listingStatus=$by_listingStatus";
                    }
                    if ($by_saleType != "" && $by_saleType != "all") {
                        $conditions[] = "tbl_properties.sale_type_id_f='$by_saleType'";
                        $pagingParameters[] = "by_saleType=$by_saleType";
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
                    if ($min_park != "" && $min_park != "all") {
                        $conditions[] = "tbl_properties.parking_id_f >='$min_park'";
                        $pagingParameters[] = "min_park=$min_park";
                    }
                    if ($max_park != "" && $max_park != "all") {
                        $conditions[] = "tbl_properties.parking_id_f <='$max_park'";
                        $pagingParameters[] = "max_park=$max_park";
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

                    if ($searchTerms != "" || count($conditions) > 0) {
                        $query .= " WHERE ";
                    }

                    if ($searchTerms != "" && count($errors) < 1) {
                        $query .= "( " . implode(" || ", $types) . " )";
                    } elseif ($searchTerms != "" && count($errors) > 0) {
                        $query .= " tbl_properties.property_id = '0'";
                    }

                    if ($searchTerms != "" && count($conditions) > 0) {
                        $query .= " AND ";
                    }

                    if (count($conditions) > 0) {
                        $query .= implode(' AND ', $conditions);
                        $pageParameters .= implode('&', $pagingParameters);

                        if ($role == 'agent' && $by_agent == "") {
                            if (($urlAgentID == $userID && !isset($_GET['properties'])) || (!isset($_GET['properties']) && ($urlAgentID == '')) && ($selectedVal3 == $userID) || (!isset($_GET['properties']) && ($selectedVal3 == $userID))) {
                                // when agent is looking for their own property listing
                                $query .= " AND tbl_properties.agent_id_f = '$userID'";
                            } else if (($urlAgentID != $userID && $urlAgentID != '' && !isset($_GET['properties'])) || (isset($_GET['properties']) && ($urlAgentID != $userID)) && ($selectedVal3 != $userID)) {
                                // agent is looking for other properties apart from theirs
                                $query .= " AND NOT tbl_properties.agent_id_f = '$userID'";
                            } else {
                                // when agent is looking for their own property listing
                                $query .= " AND tbl_properties.agent_id_f = '$userID'";
                            }
                        }

                        $query .= " ORDER BY tbl_properties.property_ID DESC";
                    } else {
                        if (count($pagingParameters) > 0) {
                            $pageParameters .= implode('&', $pagingParameters);
                        }

                        if ($role == 'agent' && $by_agent == "") {
                            if (($urlAgentID == $userID && !isset($_GET['properties'])) || (!isset($_GET['properties']) && ($urlAgentID == '')) && ($selectedVal3 == $userID) || (!isset($_GET['properties']) && ($selectedVal3 == $userID))) {
                                // when agent is looking for their own property listing
                                $query .= " AND tbl_properties.agent_id_f = '$userID'";
                            } else if (($urlAgentID != $userID && $urlAgentID != '' && !isset($_GET['properties'])) || (isset($_GET['properties']) || ($urlAgentID != $userID)) && ($selectedVal3 != $userID)) {
                                // agent is looking for other properties apart from theirs
                                $query .= " AND NOT tbl_properties.agent_id_f = '$userID'";
                            } else {
                                // when agent is looking for their own property listing
                                $query .= " AND tbl_properties.agent_id_f = '$userID'";
                            }
                        }

                        $query .= " ORDER BY tbl_properties.property_ID DESC";
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
                            WHERE tbl_properties.listing_status_id_f = '1'"; //only show active listing

                    if (isset($_GET['agentID'])) {
                        $urlAgentID = $_GET['agentID'];
                        $query .= " AND tbl_properties.agent_id_f = '$urlAgentID'";
                    } elseif ($role == 'agent') {
                        if (($urlAgentID == $userID && !isset($_GET['properties'])) || (!isset($_GET['properties']) && ($urlAgentID == '')) && ($selectedVal3 == $userID) || (!isset($_GET['properties']) && ($selectedVal3 == $userID))) {
                            // when agent is looking for their own property listing
                            $query .= " AND tbl_properties.agent_id_f = '$userID'";
                        } else if (($urlAgentID != $userID && $urlAgentID != '' && !isset($_GET['properties'])) || (isset($_GET['properties']) && ($urlAgentID != $userID)) && ($selectedVal3 != $userID)) {
                            // agent is looking for other properties apart from theirs
                            $query .= " AND NOT tbl_properties.agent_id_f = '$userID'";
                        } else {
                            // when agent is looking for their own property listing
                            $query .= " AND tbl_properties.agent_id_f = '$userID'";
                        }
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
                        <div class="card agentCard propertyCard center">
                            <div class="card-header" style="padding: 5px;">
                                <small class="text-muted"><?php echo $row['propertyListingStatus']; ?></small>
                            </div>
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
                                            <a href="property_profile.php?<?php if (isset($_GET['properties'])) echo 'properties&'; ?>property_ID=<?php echo $row['propertyID']; ?>">
                                            <img src=<?php echo '../admin/images/' . $image_row['file_name']; ?> class="d-block w-100" alt="...">
                                            </a>
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
                                <p class="card-text center">
                                    <?php
                                    $watch_result = mysqli_query($link, "SELECT * FROM tbl_watchlist WHERE property_id_f='$propertyID'");
                                    $watchRow_count = mysqli_num_rows($watch_result);
                                    echo 'Watched by: ' . $watchRow_count . ' people';
                                    ?></p>
                                <a class="btn btn-secondary btn-sm text-center mt-auto" href="property_profile.php?<?php if (isset($_GET['properties'])) echo 'properties&'; ?>property_ID=<?php echo $row['propertyID']; ?>">View Details</a>
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


        </section>



    </div> <!-- End of Page Container div -->

</section> <!-- End of wrapper div -->

<?php include "include/footer.php"; ?>