<div class="row ml-4 ml-md-4 noPadding mr-4 mr-md-5">
                <div class="col-md-4 mb-1">
                    <a class="btn btn-block btn-secondary " onclick="showEditForm('property')">Edit Property</a>
                </div>
                <div class="col-md-4 mb-1">
                    <a class="btn btn-block btn-secondary" onclick="deleteRecord('properties','<?php echo $propID; ?>')">Delete Property</a>
                </div>
                <div class="col-md-4 mb-1" style="padding-right: 30px">
                    <div class="form">
                        <div class="form-group row">
                        <label for="newPropertyListingStatus" class="col-3 col-md-5 col-form-label">Listing Status</label>
                                       
                        <select name="newPropertyListingStatus" class="form-control col-9 col-md-7 newPropertyListingStatus" onchange="updateListingStat('<?php echo $propID ?>')" required>
                            <?php
                            $query = "SELECT * FROM tbl_listing_status";
                            $result = mysqli_query($link, $query);
                            while ($row = mysqli_fetch_array($result)) {
                                extract($row);
                                ?>
                                <option <?php if ($propListingStatus == $listing_status) {
                                                echo "selected";
                                            } ?> value="<?php echo $listing_status_id; ?>"><?php echo $listing_status; ?></option>
                            <?php
                            } //end of while loop
                            ?>
                        </select>
                        </div>
                    </div>
                </div>
            </div>