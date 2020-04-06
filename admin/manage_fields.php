<?php
include "include/user_check.php";

$thisPage = "Manage Fields";

// UPDATE
if (isset($_POST['newValue'])) {

    $newValue = mysqli_real_escape_string($link, $_POST['newValue']);
    $tbl_id_num = mysqli_real_escape_string($link, $_POST['valueID']); //id of entry to be changed
    $tblSuffix = mysqli_real_escape_string($link, $_POST['colName']); // column name of table
    $citySuburb = mysqli_real_escape_string($link, $_POST['citySuburb']); // city for suburb entry

    $tbl = 'tbl_' . $tblSuffix; // full table name, prefix plus column name
    $col_id = $tblSuffix . '_id'; //primary column name

    if ($tblSuffix == 'suburb') {
        $query = "UPDATE tbl_suburb SET suburb='$newValue', city_id_f='$citySuburb' WHERE suburb_id='$tbl_id_num' ";
    } else {
        $query = "UPDATE {$tbl} SET {$tblSuffix}='$newValue' WHERE {$col_id}='$tbl_id_num' ";
    }


    mysqli_query($link, $query); // execute the SQL 
    header("Location: manage_fields.php");
} // end of if statement

// ADD
if (isset($_POST['table_name'])) {

    $tbl = mysqli_real_escape_string($link, $_POST['table_name']);
    $col = mysqli_real_escape_string($link, $_POST['col_name']);

    if ($col == 'city' || $col == 'sale_type' || $col == 'listing_status') {
        $newRecord = mysqli_real_escape_string($link, $_POST['newValue']);
        $query = "INSERT INTO {$tbl} ({$col}) VALUES ('$newRecord') ";

        mysqli_query($link, $query); // execute the SQL 
        header("Location: manage_fields.php");
    } else if ($col == 'suburb') {
        $newRecord = mysqli_real_escape_string($link, $_POST['newValue']);
        $cityForSuburb = mysqli_real_escape_string($link, $_POST['cityForSuburb']);

        $query = "INSERT INTO tbl_suburb (city_id_f, suburb ) VALUES ('$cityForSuburb', '$newRecord') ";
        mysqli_query($link, $query); // execute the SQL 
        header("Location: manage_fields.php");
    } else {
        $entryQry = "SELECT {$col} FROM {$tbl} ORDER BY {$col}_id DESC LIMIT 1;";
        $entries = mysqli_query($link, $entryQry);
        if (mysqli_num_rows($entries) > 0) {
            $lastValue = mysqli_fetch_row($entries);
            $lastVal = $lastValue[0];
        }

        $addRooms = ++$lastVal;
        $column_id = $col . '_id';

        // Add new
        $query = "INSERT INTO {$tbl} ({$column_id}, {$col}) VALUES ('$addRooms', '$addRooms') ";

        mysqli_query($link, $query); // execute the SQL 

        header("Location: manage_fields.php");
    }
} // end of if statement


//DELETE 
if (isset($_POST['delete'])) {

    $tbl = mysqli_real_escape_string($link, $_POST['delete_table_name']);
    $col = mysqli_real_escape_string($link, $_POST['delete_col_name']);
    $col_id = $col . '_id';
    $entry_id = mysqli_real_escape_string($link, $_POST['delete_entryID']);

    if ($col == 'bedrooms' || $col == 'bathrooms' || $col == 'parking') {
        $query = "DELETE FROM {$tbl} ORDER BY {$col} DESC LIMIT 1";
    } else {
        $query = "DELETE FROM {$tbl} WHERE {$col_id} ='$entry_id' ";
    }

    mysqli_query($link, $query);

    header("manage_fields.php");
} // end of if statement for Delete

include "include/header.php";
?>

<!-- Page Content -->
<section class="content ">

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

    <div class="row pageTitle marginLeft mb-4">
        <div class="col center">
            <h1>Listing Fields</h1>
        </div>
    </div>


    <!-- CARD CONTENT -->

    <div class="card mb-3">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs pull-right" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#bedrooms" role="tab" aria-controls="bedrooms" aria-selected="true">Bedrooms</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#bathrooms" role="tab" aria-controls="bathrooms" aria-selected="false">Bathrooms</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="contact-tab" data-toggle="tab" href="#parking" role="tab" aria-controls="parking" aria-selected="false">Parking</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="contact-tab" data-toggle="tab" href="#city" role="tab" aria-controls="city" aria-selected="false">City</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="contact-tab" data-toggle="tab" href="#suburb" role="tab" aria-controls="suburb" aria-selected="false">Suburb</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="contact-tab" data-toggle="tab" href="#saleType" role="tab" aria-controls="type of sale" aria-selected="false">Type of Sale</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="contact-tab" data-toggle="tab" href="#listingStatus" role="tab" aria-controls="listing status" aria-selected="false">Listing Status</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="bedrooms" role="tabpanel" aria-labelledby="bedrooms-tab">
                    <table class="table col-md-5">
                        <thead class="thead-dark">
                            <tr>
                                <th>Number of Bedrooms</th>
                            </tr>
                        </thead>
                        <tbody class="table_bedrooms">
                            <?php
                            $query = "SELECT * FROM tbl_bedrooms";
                            $result = mysqli_query($link, $query);
                            while ($row = mysqli_fetch_array($result)) {
                            ?>
                                <tr>
                                    <td scope="row"><?php echo $row['bedrooms']; ?></td>
                                </tr>
                            <?php
                            } //end of while
                            ?>

                        </tbody>
                    </table>


                    <div class="input-group mb-3 noPadding">
                        <button class="btn btn-secondary " type="button" onclick="addRecord('bedrooms')">Add More Bedrooms</button>
                        <button class="btn btn-secondary offset-md-1" type="button" onclick="deleteRecord('bedrooms')">Delete Bedrooms</button>
                    </div>
                </div>
                <div class="tab-pane fade" id="bathrooms" role="tabpanel" aria-labelledby="bathrooms-tab">
                    <table class="table col-md-5">
                        <thead class="thead-dark">
                            <tr>
                                <th>Number of Bathrooms</th>
                            </tr>
                        </thead>
                        <tbody class="table_bathrooms">
                            <?php
                            $query = "SELECT * FROM tbl_bathrooms";
                            $result = mysqli_query($link, $query);
                            while ($row = mysqli_fetch_array($result)) {
                            ?>
                                <tr>
                                    <td scope="row"><?php echo $row['bathrooms']; ?></td>
                                </tr>
                            <?php
                            } //end of while
                            ?>

                        </tbody>
                    </table>

                    <div class="input-group mb-3 noPadding">
                        <button class="btn btn-secondary" type="button" onclick="addRecord('bathrooms')">Add More Bathrooms</button>
                        <button class="btn btn-secondary offset-md-1" type="button" onclick="deleteRecord('bathrooms')">Delete Bathrooms</button>
                    </div>

                </div>
                <div class="tab-pane fade" id="parking" role="tabpanel" aria-labelledby="parking-tab">
                    <table class="table col-md-5">
                        <thead class="thead-dark">
                            <tr>
                                <th>Number of Garage Parking Available</th>
                            </tr>
                        </thead>
                        <tbody class="table_parking">
                            <?php
                            $query = "SELECT * FROM tbl_parking";
                            $result = mysqli_query($link, $query);
                            while ($row = mysqli_fetch_array($result)) {
                            ?>
                                <tr>
                                    <td scope="row"><?php echo $row['parking']; ?></td>
                                </tr>
                            <?php
                            } //end of while
                            ?>

                        </tbody>
                    </table>

                    <div class="input-group mb-3 noPadding">
                        <button class="btn btn-secondary" type="button" onclick="addRecord('parking')">Add More Garage Parking</button>
                        <button class="btn btn-secondary offset-md-1" type="button" onclick="deleteRecord('parking')">Delete Parking</button>
                    </div>

                </div>
                <div class="tab-pane fade" id="city" role="tabpanel" aria-labelledby="city-tab">
                    <table class="table col-md-7">
                        <thead class="thead-dark">
                            <tr>
                                <th class="col-3">Cities</th>
                                <th class="col-1 center">Edit</th>
                                <th class="col-1 center">Delete</th>
                            </tr>
                        </thead>
                        <tbody class="table_city">
                            <?php
                            $query = "SELECT * FROM tbl_city ORDER BY city ";
                            $result = mysqli_query($link, $query);
                            while ($row = mysqli_fetch_array($result)) {
                                $tdClass = 'city' . $row['city_id'];
                            ?>
                                <tr>
                                    <td><span class="<?php echo $tdClass; ?>"><?php echo $row['city']; ?></span>
                                        <div class="<?php echo 'editFields' . $tdClass; ?> hide ">
                                            <div class="input-group">
                                                <input type="text" class="form-control editInput<?php echo $tdClass; ?>" value="<?php echo $row['city']; ?>" name="newValue">
                                                <div class="input-group-append">
                                                    <button class="btn btn-secondary editBtn" type="submit" name="edit" onclick="updateTable(<?php echo $row['city_id']; ?>, 'city')">Update</button>
                                                </div>
                                            </div>
                                        </div>

                                    </td>
                                    <td class="center">
                                        <a onclick="hideOriginal('<?php echo $tdClass; ?>')"><i class="far fa-edit icon<?php echo $tdClass; ?>"></i></a>
                                        <a onclick="cancelChange('<?php echo $tdClass; ?>' )" class="cancelLink<?php echo $tdClass; ?> hide cancelLink">Cancel</a>
                                    </td>
                                    <td class="center"><a onclick="deleteRecord('city', '<?php echo $row['city_id']; ?>')"><i class="far fa-trash-alt"></i></a></td>

                                </tr>
                            <?php
                            } //end of while
                            ?>


                        </tbody>
                    </table>

                    <form class="addCityForm addMarginTop">
                        <h5>Add New City</h5>
                        <div class="input-group mb-3 col-md-4 noPadding">
                            <input type="text" class="form-control addInput_city" placeholder="Type in new city" name="city">
                            <div class="input-group-append">
                                <button class="btn btn-secondary" type="button" onclick="addRecord('city')">Add</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="tab-pane fade" id="suburb" role="tabpanel" aria-labelledby="suburb-tab">
                    <table class="table col suburbTable">
                        <thead class="thead-dark">
                            <tr>
                                <th style="width: 40%">
                                    <select name="viewSuburbByCity" class="form-control viewSuburbByCity_dropdown" onchange="updateTable('viewSuburbByCity')">
                                        <option value="all">All Cities</option>
                                        <?php
                                        $query = "SELECT * FROM tbl_city ORDER BY city";
                                        $result = mysqli_query($link, $query);
                                        while ($row = mysqli_fetch_array($result)) {
                                            extract($row);
                                        ?>
                                            <option value="<?php echo $city_id; ?>"><?php echo $city; ?></option>
                                        <?php
                                        } //end of while loop
                                        ?>
                                    </select>
                                </th>
                                <th style="width: 40%">Suburb</th>
                                <th style="width: 10%" class="center">Edit</th>
                                <th style="width: 10%" class="center">Delete</th>
                            </tr>
                        </thead>
                        <tbody class="table_suburb">
                            <?php

                            if (isset($_POST['viewSuburbByCity'])) {

                                $cityID_f = mysqli_real_escape_string($link, $_POST['viewSuburbByCity']);

                                if ($cityID_f == "all") {
                                    $query = "SELECT tbl_suburb.suburb_id as suburb_id, tbl_suburb.city_id_f as city_id_f, tbl_suburb.suburb as suburb, tbl_city.city as city FROM tbl_suburb INNER JOIN tbl_city ON tbl_suburb.city_id_f=tbl_city.city_id ORDER BY tbl_city.city";
                                } else {
                                    $query = "SELECT tbl_suburb.suburb_id as suburb_id, tbl_suburb.city_id_f as city_id_f, tbl_suburb.suburb as suburb, tbl_city.city as city FROM tbl_suburb INNER JOIN tbl_city ON tbl_suburb.city_id_f=tbl_city.city_id WHERE tbl_city.city_id = $cityID_f  ORDER BY ORDER BY tbl_city.city";
                                }
                            } else {
                                $query = "SELECT tbl_suburb.suburb_id as suburb_id, tbl_suburb.city_id_f as city_id_f, tbl_suburb.suburb as suburb, tbl_city.city as city FROM tbl_suburb INNER JOIN tbl_city ON tbl_suburb.city_id_f=tbl_city.city_id  ORDER BY tbl_city.city";
                            }

                            // $query = "SELECT tbl_suburb.suburb_id as suburb_id, tbl_suburb.city_id_f as city_id_f, tbl_suburb.suburb as suburb, tbl_city.city as city FROM tbl_suburb INNER JOIN tbl_city ON tbl_suburb.city_id_f=tbl_city.city_id";
                            $result = mysqli_query($link, $query);
                            if (!$result) {
                                printf("Error: %s\n", mysqli_error($link));
                                exit();
                            }
                            while ($row = mysqli_fetch_array($result)) {
                                $tdCityClass = 'suburbCity' . $row['suburb_id'];
                                $tdClass = 'suburb' . $row['suburb_id'];
                            ?>
                                <tr>
                                    <td>
                                        <span class="<?php echo $tdCityClass; ?>"><?php echo $row['city']; ?></span>
                                        <div class="<?php echo 'editFields' . $tdCityClass; ?>  hide">
                                            <select class="custom-select dropdown_<?php echo $tdCityClass; ?>" id="citySuburb">
                                                <option disabled>Choose...</option>
                                                <?php
                                                $citySuburb_query = mysqli_query($link, "SELECT * FROM tbl_city  ORDER BY city");
                                                while ($citySuburb_row = mysqli_fetch_array($citySuburb_query)) {
                                                    extract($citySuburb_row);
                                                ?>
                                                    <option <?php if ($row['city_id_f'] == $city_id) {
                                                                echo "selected";
                                                            } ?> value="<?php echo $city_id; ?>"><?php echo $city; ?></option>
                                                <?php
                                                } //end of while loop
                                                ?>

                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="<?php echo $tdClass; ?>"><?php echo $row['suburb']; ?></span>
                                        <div class="<?php echo 'editFields' . $tdClass; ?> hide">
                                            <div class="input-group">
                                                <input type="text" class="form-control editInput<?php echo $tdClass; ?>" value="<?php echo $row['suburb']; ?>" name="newValue">
                                                <div class="input-group-append">
                                                    <button class="btn btn-secondary editBtn" type="submit" name="edit" onclick="updateTable(<?php echo $row['suburb_id']; ?>, 'suburb')">Update</button>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="center">
                                        <a onclick="hideOriginal('<?php echo $tdClass; ?>', '<?php echo $tdCityClass; ?>')"><i class="far fa-edit icon<?php echo $tdClass; ?>"></i></a>
                                        <a onclick="cancelChange('<?php echo $tdClass; ?>', '<?php echo $tdCityClass; ?>')" class="cancelLink<?php echo $tdClass; ?> hide cancelLink">Cancel</a>
                                    </td>
                                    <td class="center"><a onclick="deleteRecord('suburb', '<?php echo $row['suburb_id']; ?>')"><i class="far fa-trash-alt"></i></a></td>

                                </tr>
                            <?php
                            } //end of while
                            ?>

                        </tbody>
                    </table>

                    <form method="post" class="addMarginTop ">
                        <div class="form-group col-md-4 noPadding">
                            <h5>Add New Suburb</h5>
                            <label for="cityForSuburb">Choose city where new suburb is located:</label>
                            <select name="cityForSuburb" class="form-control cityForSuburbSelected">
                                <option selected disabled>Choose...</option>
                                <?php
                                $query = "SELECT * FROM tbl_city ORDER BY city";
                                $result = mysqli_query($link, $query);
                                while ($row = mysqli_fetch_array($result)) {
                                    extract($row);
                                ?>
                                    <option value="<?php echo $city_id; ?>"><?php echo $city; ?></option>
                                <?php
                                } //end of while loop
                                ?>
                            </select>
                        </div>
                        <div class="input-group mb-3 col-md-4 noPadding ">
                            <input type="text" class="form-control suburbField addInput_suburb" placeholder="Type in new suburb" name="suburb" disabled>
                            <div class="input-group-append">
                                <button class="btn btn-secondary" type="button" onclick="addRecord('suburb')">Add</button>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="tab-pane fade" id="saleType" role="tabpanel" aria-labelledby="saleType-tab">
                    <table class="table col-7">
                        <thead class="thead-dark">
                            <tr>
                                <th class="col-3">Type of Sale</th>
                                <th class="col-1 center">Edit</th>
                                <th class="col-1 center">Delete</th>
                            </tr>
                        </thead>
                        <tbody class="table_sale_type">
                            <?php
                            $query = "SELECT * FROM tbl_sale_type ORDER BY sale_type";
                            $result = mysqli_query($link, $query);
                            while ($row = mysqli_fetch_array($result)) {
                                $tdClass = 'sale_type' . $row['sale_type_id'];
                            ?>
                                <tr>
                                    <td><span class="<?php echo $tdClass; ?>"><?php echo $row['sale_type']; ?></span>
                                        <div class="<?php echo 'editFields' . $tdClass; ?> hide ">
                                            <div class="input-group">
                                                <input type="text" class="form-control editInput<?php echo $tdClass; ?>" value="<?php echo $row['sale_type']; ?>" name="newValue">
                                                <div class="input-group-append">
                                                    <button class="btn btn-secondary editBtn" type="submit" name="edit" onclick="updateTable(<?php echo $row['sale_type_id']; ?>, 'sale_type')">Update</button>
                                                </div>
                                            </div>
                                        </div>

                                    </td>
                                    <td class="center">
                                        <a onclick="hideOriginal('<?php echo $tdClass; ?>')"><i class="far fa-edit icon<?php echo $tdClass; ?>"></i></a>
                                        <a onclick="cancelChange('<?php echo $tdClass; ?>' )" class="cancelLink<?php echo $tdClass; ?> hide cancelLink">Cancel</a>
                                    </td>
                                    <td class="center"><a onclick="deleteRecord('sale_type', '<?php echo $row['sale_type_id']; ?>')"><i class="far fa-trash-alt"></i></a></td>

                                </tr>
                            <?php
                            } //end of while
                            ?>

                        </tbody>
                    </table>

                    <form method="post" class="addMarginTop">
                        <h5>Add New Sale Type</h5>
                        <div class="input-group mb-3 col-md-4 noPadding">
                            <input type="text" class="form-control addInput_sale_type" placeholder="Add type of sale" name="sale_type">
                            <div class="input-group-append">
                                <button class="btn btn-secondary" type="button" onclick="addRecord('sale_type')">Add</button>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="tab-pane fade" id="listingStatus" role="tabpanel" aria-labelledby="listingStatus-tab">
                    <table class="table col-7">
                        <thead class="thead-dark">
                            <tr>
                                <th class="col-3">Listing Status</th>
                                <th class="col-1 center">Edit</th>
                                <th class="col-1 center">Delete</th>
                            </tr>
                        </thead>
                        <tbody class="table_listing_status">
                            <?php
                            $query = "SELECT * FROM tbl_listing_status ORDER BY listing_status";
                            $result = mysqli_query($link, $query);
                            while ($row = mysqli_fetch_array($result)) {
                                $tdClass = 'listing_status' . $row['listing_status_id'];
                            ?>
                                <tr>
                                    <td><span class="<?php echo $tdClass; ?>"><?php echo $row['listing_status']; ?></span>
                                        <div class="<?php echo 'editFields' . $tdClass; ?> hide ">
                                            <div class="input-group">
                                                <input type="text" class="form-control editInput<?php echo $tdClass; ?>" value="<?php echo $row['listing_status']; ?>" name="newValue">
                                                <div class="input-group-append">
                                                    <button class="btn btn-secondary editBtn" type="submit" name="edit" onclick="updateTable(<?php echo $row['listing_status_id']; ?>, 'listing_status')">Update</button>
                                                </div>
                                            </div>
                                        </div>

                                    </td>
                                    <td class="center">
                                        <a onclick="hideOriginal('<?php echo $tdClass; ?>')"><i class="far fa-edit icon<?php echo $tdClass; ?>"></i></a>
                                        <a onclick="cancelChange('<?php echo $tdClass; ?>' )" class="cancelLink<?php echo $tdClass; ?> hide cancelLink">Cancel</a>
                                    </td>
                                    <td class="center"><a onclick="deleteRecord('listing_status', '<?php echo $row['listing_status_id']; ?>')"><i class="far fa-trash-alt"></i></a></td>

                                </tr>
                            <?php
                            } //end of while
                            ?>

                        </tbody>
                    </table>

                    <form method="post" class="addMarginTop">
                        <h5>Add New Listing Status</h5>
                        <div class="input-group mb-3 col-md-4 noPadding">
                            <input type="text" class="form-control addInput_listing_status" placeholder="Add new listing status" name="listing_status">
                            <div class="input-group-append">
                                <button class="btn btn-secondary" type="button" onclick="addRecord('listing_status')">Add</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>



</section>
<!-- End of Page Content -->

<?php 
include "include/close_modal.php";
include "include/footer.php"; ?>