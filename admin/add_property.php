<?php
include "include/user_check.php";
include "../include/image_creation.php";

$thisPage = "Add Property";
$error = "";

// check if the user clicks the submit button
if (isset($_POST['addProperty'])) {

  // Checks if user didn't choose any photos
  if (!empty(array_filter($_FILES['files']['name']))) {

    $propertyTitle = mysqli_real_escape_string($link, $_POST['propertyTitle']);
    $propertyCity = mysqli_real_escape_string($link, $_POST['propertyCity']);
    $propertySuburb = mysqli_real_escape_string($link, $_POST['propertySuburb']);
    $propertyAddress = mysqli_real_escape_string($link, $_POST['propertyAddress']);
    $propertyBedrooms = mysqli_real_escape_string($link, $_POST['propertyBedrooms']);
    $propertyBathrooms = mysqli_real_escape_string($link, $_POST['propertyBathrooms']);
    $propertyParking = mysqli_real_escape_string($link, $_POST['propertyParking']);
    $propertySaleType = mysqli_real_escape_string($link, $_POST['propertySaleType']);
    $propertySaleDetails = mysqli_real_escape_string($link, $_POST['propertySaleDetails']);
    $propertyDescription = mysqli_real_escape_string($link, $_POST['propertyDescription']);
    $propertyListingStatus = 1; // Set Listing status to Pending Sale
    $propertyFeatured = '';

    if (isset($_POST['featured'])) {
      $propertyFeatured = 'y';
      // find the previous featured property and unset as featured
      $featured_query = "UPDATE `tbl_properties` SET `featured`= 'n' WHERE `featured`= 'y'";
      mysqli_query($link, $featured_query); // execute the SQL 
    } else {
      $propertyFeatured = 'n';
    }

    if ($role == 'admin') {
      $propertyAgent = mysqli_real_escape_string($link, $_POST['propertyAgent']);
    } else if ($role == 'agent') {
      $propertyAgent = $userID;
    }

    // Add this new property to tbl_properties
    $query1 = "INSERT INTO tbl_properties (title, city_id_f, suburb_id_f, address, bedrooms_id_f, bathrooms_id_f, parking_id_f, agent_id_f, sale_type_id_f, sale_details, description, listing_status_id_f, featured) 
  VALUES ('$propertyTitle', '$propertyCity', '$propertySuburb', '$propertyAddress', '$propertyBedrooms', '$propertyBathrooms', '$propertyParking', '$propertyAgent', '$propertySaleType', '$propertySaleDetails', '$propertyDescription', '$propertyListingStatus', '$propertyFeatured') ";

    mysqli_query($link, $query1); // execute the SQL 

    // Find the last property_id
    $entryQry = "SELECT property_id FROM tbl_properties ORDER BY property_id DESC LIMIT 1;";
    $entries = mysqli_query($link, $entryQry);
    if (mysqli_num_rows($entries) > 0) {
      $lastValue = mysqli_fetch_row($entries);
      $newPropertyID = $lastValue[0];
    }

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

      $query2 = "INSERT INTO tbl_images (property_id_f, file_name) VALUES ('$newPropertyID', '$newName')";
      mysqli_query($link, $query2); // execute the SQL 
      header("Location: properties.php");
    }
  } else {
    $error = "No files selected. Please Add Photos";
    //header("Location: add_property.php");   
  }
} // end of if statement

// For Agent Role, check City that agent is based in
$queryForAgentLocation = "SELECT city_id_f FROM tbl_agents WHERE agent_id = '$userID'";
$agentCityResult = mysqli_query($link, $queryForAgentLocation);
while ($row = mysqli_fetch_array($agentCityResult)) {
  extract($row);
  $agentCity = $row['city_id_f'];
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
      <h1>Add New Property</h1>
    </div>
    <?php if ($error != "") echo "<div class='col center mb-3'><h3 style='color: red;'>" . $error . "</h3></div>"; ?>
  </div>


  <div class=" marginLeft">
    <!-- ADD NEW PROPERTY LISTING -->
    <section class="addPropertySection">

      <form method="post" enctype="multipart/form-data" class="addProperty_form form_css">

        <?php
        if ($role == 'admin') {
          echo '<div class="form-row">
          <div class="col mb-3">
            <div class="form-group noMargin setFeatured">
              <div class="form-check myFormCheck">
                <input class="form-check-input featureCheck" type="checkbox" name="featured">
                <label class="form-check-label" for="featured">
                  Set as Featured Listing
                </label>
              </div>
            </div>
          </div>
        </div>';
        }
        ?>


        <div class="form-row">
          <div class="form-group col">
            <label for="propertyTitle">Title</label>
            <input type="text" class="form-control" name="propertyTitle" placeholder="Listing Title" required>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-md-3">
            <label for="propertyCity">City</label>
            <select name="propertyCity" class="form-control propertyCity" required>
              <option value="" selected disabled>Choose...</option>
              <?php
              $query = "SELECT * FROM tbl_city";
              $result = mysqli_query($link, $query);
              while ($row = mysqli_fetch_array($result)) {
                extract($row);
              ?>
                <option <?php if ($role == 'agent' && $city_id == $agentCity) echo 'selected'; ?> value="<?php echo $city_id; ?>"><?php echo $city; ?></option>
              <?php
              } //end of while loop
              ?>
            </select>
          </div>
          <div class="form-group col-md-3">
            <label for="propertySuburb">Suburb</label>
            <?php if ($role == 'admin') {
              echo '<select name="propertySuburb" class="form-control propertySuburb" disabled required></select>';
            } else {
              $inputForSuburb = '<select name="propertySuburb" class="form-control propertySuburb" required>
                <option value="" selected disabled>Choose...</option>';
              $query = "SELECT * FROM tbl_suburb WHERE city_id_f='$agentCity'";
              $result = mysqli_query($link, $query);
              while ($row = mysqli_fetch_array($result)) {
                extract($row);
                $inputForSuburb .= '<option value="' . $suburb_id . '">' . $suburb . '</option>';
              } //end of while loop
              $inputForSuburb .= '</select>';
              echo $inputForSuburb;
            }
            ?>

          </div>
          <div class="form-group col-md-6">
            <label for="propertyAddress">Address</label>
            <input type="text" class="form-control" name="propertyAddress" placeholder="Property Address" required>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-md-4">
            <label for="propertyBedrooms">Number of Bedrooms</label>
            <select name="propertyBedrooms" class="form-control propertyBedrooms" required>
              <option value="" selected disabled>Choose...</option>
              <?php
              $query = "SELECT * FROM tbl_bedrooms";
              $result = mysqli_query($link, $query);
              while ($row = mysqli_fetch_array($result)) {
                extract($row);
              ?>
                <option value="<?php echo $bedrooms_id; ?>"><?php echo $bedrooms; ?> <?php if ($bedrooms == 1) {
                                                                                        echo 'bedroom';
                                                                                      } else {
                                                                                        echo 'bedrooms';
                                                                                      } ?></option>
              <?php
              } //end of while loop
              ?>
            </select>
          </div>
          <div class="form-group col-md-4">
            <label for="propertyBathrooms">Number of Bathrooms</label>
            <select name="propertyBathrooms" class="form-control propertyBathrooms" required>
              <option value="" selected disabled>Choose...</option>
              <?php
              $query = "SELECT * FROM tbl_bathrooms";
              $result = mysqli_query($link, $query);
              while ($row = mysqli_fetch_array($result)) {
                extract($row);
              ?>
                <option value="<?php echo $bathrooms_id; ?>"><?php echo $bathrooms; ?> <?php if ($bathrooms == 1) {
                                                                                          echo 'bathroom';
                                                                                        } else {
                                                                                          echo 'bathrooms';
                                                                                        } ?></option>
              <?php
              } //end of while loop
              ?>
            </select>
          </div>
          <div class="form-group col-md-4">
            <label for="propertyParking">Number of Parking Spaces</label>
            <select name="propertyParking" class="form-control propertyParking" required>
              <option value="" selected disabled>Choose...</option>
              <?php
              $query = "SELECT * FROM tbl_parking";
              $result = mysqli_query($link, $query);
              while ($row = mysqli_fetch_array($result)) {
                extract($row);
              ?>
                <option value="<?php echo $parking_id; ?>"><?php echo $parking; ?> <?php if ($parking == 1) {
                                                                                      echo 'space';
                                                                                    } else {
                                                                                      echo 'spaces';
                                                                                    } ?></option>
              <?php
              } //end of while loop
              ?>
            </select>
          </div>
        </div>

        <div class="form-row">
          <?php
          // if admin has logged in, then show div where Agent input can be chosen
          if ($role == 'admin') {
            $chooseAgentInput = '<div class="form-group col-md-4">
              <label for="propertyAgent">Agent for the Property</label>
              <select name="propertyAgent" class="form-control propertyAgent" required>
                <option value="" selected disabled>Choose...</option>';
            $query = "SELECT * FROM tbl_agents";
            $result = mysqli_query($link, $query);
            while ($row = mysqli_fetch_array($result)) {
              extract($row);
              $chooseAgentInput .= '<option value="' . $agent_id . '">' . $fname . ' ' . $lname . '</option>';
            } //end of while loop
            $chooseAgentInput .= '</select></div>';
            echo $chooseAgentInput;
          }
          ?>

          <div class="form-group col-md-4">
            <label for="propertySaleType">Type of Sale</label>
            <select name="propertySaleType" class="form-control propertySaleType" required>
              <option value="" selected disabled>Choose...</option>
              <?php
              $query = "SELECT * FROM tbl_sale_type";
              $result = mysqli_query($link, $query);
              while ($row = mysqli_fetch_array($result)) {
                extract($row);
              ?>
                <option value="<?php echo $sale_type_id; ?>"><?php echo $sale_type; ?></option>
              <?php
              } //end of while loop
              ?>
            </select>
          </div>
          <div class="form-group col-md-4">
            <label for="propertySaleDetails">Sale Details</label>
            <input type="text" class="form-control" name="propertySaleDetails" placeholder="i.e. Auction Date, Asking Price, etc" required>
          </div>
        </div>

        <div class="form-row">
          <label for="files[]" class="formAlign">Upload Photos of Property</label>
        </div>

        <div class="form-row thumbnailProperty">
          <img src="../admin/images/add_agent.png" class="thumbnailSize placeHolderThumbnailImg" alt="Photo" title="photo" />
        </div>

        <div class="form-row">
          <div class="form-group custom-file col-md-5">
            <input type="file" class="form-control-file" id="propertyImage" name="files[]" multiple onchange="imagesPreview(this, 'thumbnailProperty')" required>
          </div>
        </div>

        <div class="form-group formSpacing">
          <label for="propertyDescription">Description</label>
          <textarea class="form-control" rows="5" name="propertyDescription" required></textarea>
        </div>

        <button type="submit" class="btn btn-secondary" name="addProperty">Add New Property</button>
      </form>

    </section> <!-- End of Add Agent Section -->

  </div> <!-- End of Page Container div -->

</section> <!-- End of wrapper div -->

<?php include "include/footer.php"; ?>