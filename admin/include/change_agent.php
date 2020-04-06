<div class="pageContainer marginLeft">

        <!-- EDIT AGENT PROFILE SECTION -->
        <a class="btn btn-block btn-primary text-white col-10 col-md-4 cancelEditBtn_agent hide" onclick="cancelEdit('agent')" style="margin-top: 0;">
                    << Back to Agent Profile</a> 
        <section class="editSection_agent mt-3">
            <form method="post" enctype="multipart/form-data" class="addAgent_form">
                <div class="center formTitle">
                    <h3>Edit Profile</h3>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4 ">
                        <label for="agentFName_new">First Name</label>
                        <input type="text" class="form-control" name="agentFName_new" value="<?php echo $fName; ?>">
                    </div>
                    <div class="form-group col-md-4 ">
                        <label for="agentLName_new">Last Name</label>
                        <input type="text" class="form-control" name="agentLName_new" value="<?php echo $lName; ?>">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="agentPhone_new">Phone (+64 XXX XXX XXX)</label>
                        <input type="tel" class="form-control" name="agentPhone_new" value="<?php echo $Phone; ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6 ">
                        <label for="agentEmail_new">Email</label>
                        <input type="email" class="form-control" name="agentEmail_new" value="<?php echo $Email; ?>">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="agentCity_new">City</label>
                        <select name="agentCity_new" class="form-control">
                            <?php
                            $query = "SELECT * FROM tbl_city ORDER BY city";
                            $result = mysqli_query($link, $query);
                            while ($row = mysqli_fetch_array($result)) {
                                extract($row);
                                ?>
                                <option <?php if ($City == $city) {
                                                echo "selected";
                                            } ?> value="<?php echo $city_id; ?>"><?php echo $city; ?></option>
                            <?php
                            } //end of while loop
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <label for="profileImage" class="formAlign">Profile Photo</label>
                </div>
                <div class="form-row thumbnailEditAgent">
                    <img src="<?php echo '../admin/images/' . $Picture; ?>" class="thumbnailSize placeHolderThumbnailImg" alt="Photo" title="photo" />
                </div>

                <div class="form-row">
                    <div class="form-group custom-file col-md-5">
                        <input type="file" class="form-control-file" id="profileImage" name="fileImage_new" onchange="imagesPreview(this, 'thumbnailEditAgent')">
                    </div>
                    <?php
                    // if admin logged in, show the give admin rights checkbox 
                    if ($role == 'admin') {
                        $inputCheckBox = '<div class="form-group col-md-4 offset-md-1 formSpacing">
                        <div class="form-check myFormCheck">
                        <input class="form-check-input" type="checkbox" name="adminRights_new"';
                         if ($User_type == 'Admin') {
                            $inputCheckBox .= "checked='checked'";
                         } 
                         $inputCheckBox .= '<label class="form-check-label" for="adminRights_new">Administrator Access</label></div></div>';
                         echo $inputCheckBox;
                    } 
                    ?>
                    
                </div>

                <?php
                // if agent is logged in and trying to change their profile, show password input
                if ($role == 'agent' && $agentID == $userID) {
                    echo '<div class="form-row mt-2">
                    <div class="form-group col-md-6">
                    <label for="agentPass_new">Password</label>
                    <input type="tel" class="form-control" name="agentPass_new" value="'.$pass.'"></div></div>';
                }
                ?>

                <div class="form-group formSpacing">
                    <label for="agentDescription_new">Description</label>
                    <textarea class="form-control" rows="5" name="agentDescription_new"><?php echo $About; ?></textarea>
                </div>

                <button type="submit" class="btn btn-primary" name="editAgent">Save Profile</button>
            </form>

        </section> <!-- End of Edit Agent Section -->

    </div> <!-- End of Page Container div -->