<div class="pageContainer marginLeft">
    <a class="btn btn-block btn-primary text-white col-10 col-md-4 cancelEditBtn_property hide" onclick="cancelEdit('property')" style="margin-top: 0;">
        << Back to Property Profile</a> <!-- EDIT PROPERTY LISTING -->
            <section class="editSection_property mt-3">
                <form method="post" enctype="multipart/form-data" class="addProperty_form form_css">
                    <div class="form-row">
                        <div class="form-group col">
                            <label for="newPropertyTitle">Title</label>
                            <input type="text" class="form-control" name="newPropertyTitle" placeholder="Listing Title" value="<?php echo $propTitle; ?>" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="newPropertyCity">City</label>
                            <select name="newPropertyCity" class="form-control propertyCity" required>
                                <option selected disabled>Choose...</option>
                                <?php
                                $query = "SELECT * FROM tbl_city";
                                $result = mysqli_query($link, $query);
                                while ($row = mysqli_fetch_array($result)) {
                                    extract($row);
                                    ?>
                                    <option <?php if ($propCity == $city) {
                                                    echo "selected";
                                                } ?> value="<?php echo $city_id; ?>"><?php echo $city; ?></option>
                                <?php
                                } //end of while loop
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="newPropertySuburb">Suburb</label>
                            <select name="newPropertySuburb" class="form-control propertySuburb">
                                <option selected disabled>Choose...</option>
                                <?php
                                $query = "SELECT * FROM tbl_suburb";
                                $result = mysqli_query($link, $query);
                                while ($row = mysqli_fetch_array($result)) {
                                    extract($row);
                                    ?>
                                    <option <?php if ($propSuburb == $suburb) {
                                                    echo "selected";
                                                } ?> value="<?php echo $suburb_id; ?>"><?php echo $suburb; ?></option>
                                <?php
                                } //end of while loop
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="newPropertyAddress">Address</label>
                            <input type="text" class="form-control" name="newPropertyAddress" placeholder="Property Address" value="<?php echo $propAddress; ?>" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="newPropertyBedrooms">Number of Bedrooms</label>
                            <select name="newPropertyBedrooms" class="form-control propertyBedrooms" required>
                                <option selected disabled>Choose...</option>
                                <?php
                                $query = "SELECT * FROM tbl_bedrooms";
                                $result = mysqli_query($link, $query);
                                while ($row = mysqli_fetch_array($result)) {
                                    extract($row);
                                    ?>
                                    <option <?php if ($propBedrooms == $bedrooms) {
                                                    echo "selected";
                                                } ?> value="<?php echo $bedrooms_id; ?>"><?php echo $bedrooms; ?> <?php if ($bedrooms == 1) {
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
                            <label for="newPropertyBathrooms">Number of Bathrooms</label>
                            <select name="newPropertyBathrooms" class="form-control propertyBathrooms" required>
                                <option selected disabled>Choose...</option>
                                <?php
                                $query = "SELECT * FROM tbl_bathrooms";
                                $result = mysqli_query($link, $query);
                                while ($row = mysqli_fetch_array($result)) {
                                    extract($row);
                                    ?>
                                    <option <?php if ($propBathrooms == $bathrooms) {
                                                    echo "selected";
                                                } ?> value="<?php echo $bathrooms_id; ?>"><?php echo $bathrooms; ?> <?php if ($bathrooms == 1) {
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
                            <label for="newPropertyParking">Number of Parking Spaces</label>
                            <select name="newPropertyParking" class="form-control propertyParking" required>
                                <option selected disabled>Choose...</option>
                                <?php
                                $query = "SELECT * FROM tbl_parking";
                                $result = mysqli_query($link, $query);
                                while ($row = mysqli_fetch_array($result)) {
                                    extract($row);
                                    ?>
                                    <option <?php if ($propParking == $parking) {
                                                    echo "selected";
                                                } ?> value="<?php echo $parking_id; ?>"><?php echo $parking; ?> <?php if ($parking == 1) {
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
                        <!-- if user is admin, show the agents dropdown input -->
                        <?php if ($role == 'admin') {
                            $inputForAgents = '<div class="form-group col-md-4">
                                <label for="newPropertyAgent">Agent for the Property</label>
                                <select name="newPropertyAgent" class="form-control propertyAgent" required>
                                <option selected disabled>Choose...</option>';
                            $query = "SELECT * FROM tbl_agents";
                            $result = mysqli_query($link, $query);
                            while ($row = mysqli_fetch_array($result)) {
                                extract($row);
                                $inputForAgents .= '<option ';
                                if ($propAgentID == $agent_id) {
                                    $inputForAgents .= 'selected ';
                                }
                                $inputForAgents .= 'value="' . $agent_id . '">' . $fname . ' ' . $lname . '</option>';
                            } //end of while loop
                            $inputForAgents .= '</select></div>';
                            echo $inputForAgents;
                        }
                        ?>

                        <div class="form-group col-md-4">
                            <label for="newPropertySaleType">Type of Sale</label>
                            <select name="newPropertySaleType" class="form-control propertySaleType" required>
                                <option selected disabled>Choose...</option>
                                <?php
                                $query = "SELECT * FROM tbl_sale_type";
                                $result = mysqli_query($link, $query);
                                while ($row = mysqli_fetch_array($result)) {
                                    extract($row);
                                    ?>
                                    <option <?php if ($propSaleType == $sale_type) {
                                                    echo "selected";
                                                } ?> value="<?php echo $sale_type_id; ?>"><?php echo $sale_type; ?></option>
                                <?php
                                } //end of while loop
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="newPropertySaleDetails">Sale Details</label>
                            <input type="text" class="form-control" name="newPropertySaleDetails" placeholder="i.e. Auction Date, Asking Price, etc" value="<?php echo $propSaleDetails; ?>" required>
                        </div>
                    </div>

                    <div class="form-row mt-3">
                        <label>Current Photos of Property</label>
                    </div>

                    <div class="form-row">
                        <table class="table table-borderless">
                            <tbody class="propertyImagesTable">
                                <?php
                                $query = "SELECT * FROM tbl_images WHERE property_id_f=$propID";
                                $result = mysqli_query($link, $query);
                                while ($row = mysqli_fetch_array($result)) {
                                    extract($row);
                                    ?>
                                    <tr>
                                        <td class="noPadding"><img src="<?php echo '../admin/images/' . $file_name; ?>" class="thumbnailSize mr-2" alt="Property Photo" /> <a onclick="deletePropImage('<?php echo $file_name; ?>', '<?php echo $propertyID; ?>')">Delete</a></td>
                                    </tr>
                                <?php
                                } //end of while loop
                                ?>
                                <tr>
                                    <td><a type="button" class="btn btn-danger text-white" onclick="deletePropImage('all', '<?php echo $propertyID; ?>')">Delete All Current Photos </a></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="form-row">
                        <label for="files[]" class="formAlign">Upload New Photos of Property</label>
                    </div>

                    <div class="form-row thumbnailProperty">
                        <img src="../admin/images/add_agent.png" class="thumbnailSize placeHolderThumbnailImg" alt="Photo" title="photo" />
                    </div>

                    <div class="form-row mb-3">
                        <div class="form-group custom-file col-md-5">
                            <input type="file" class="form-control-file" id="newPropertyImage" name="files[]" multiple onchange="imagesPreview(this, 'thumbnailProperty')">
                        </div>
                    </div>

                    <div class="form-group formSpacing">
                        <label for="newPropertyDescription">Description</label>
                        <textarea class="form-control" rows="5" name="newPropertyDescription" required><?php echo $propDescription; ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-secondary" name="editProperty">Edit Property Details</button>
                </form>

            </section> <!-- End of Edit Section -->

</div> <!-- End of Page Container div -->