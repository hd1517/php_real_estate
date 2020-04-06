<?php
include "include/user_check.php";
include "../include/image_creation.php";

$thisPage = "Add Agent";

// no access if not admin
if ($role != 'admin') {
  header("Location: ../signin.php");
}

// check if the user clicks the submit button
if (isset($_POST['addAgent'])) {

  $agentFName = mysqli_real_escape_string($link, $_POST['agentFName']);
  $agentLName = mysqli_real_escape_string($link, $_POST['agentLName']);
  $agentPhone = mysqli_real_escape_string($link, $_POST['agentPhone']);
  $agentEmail = mysqli_real_escape_string($link, $_POST['agentEmail']);
  $agentCity = mysqli_real_escape_string($link, $_POST['agentCity']);
  $agentAbout = mysqli_real_escape_string($link, $_POST['agentDescription']);
  $agentUserType = '';
  $agentPassWord = 'asdf';

  if (isset($_POST['adminRights'])) {
    $agentUserType = 1;
  } else {
    $agentUserType = 2;
  }

  if ($agentCity == 0) {
    $agentCity = 1;
  }

  //image file
  $imgName = $_FILES['fileImage']['name'];
  $tmpName = $_FILES['fileImage']['tmp_name'];
  $ext = strtolower(strrchr($imgName, ".")); // strtolower -- change to lowercase
  $newName = md5(rand() * time()) . $ext;
  $imgPath = PRODUCT_IMG_DIR . $newName;
  $width = 350;
  $height = 350;
  createThumbnail($tmpName, $imgPath, $width, $height);

  // Add this new agent to agent table
  $query = "INSERT INTO tbl_agents (user_type_id_f, fname, lname, phone, email, city_id_f, about, picture, password ) VALUES ('$agentUserType', '$agentFName', '$agentLName', '$agentPhone', '$agentEmail', '$agentCity', '$agentAbout', '$newName', '$agentPassWord') ";

  mysqli_query($link, $query); // execute the SQL 
  header("Location: agents.php");
} // end of if statement

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
          <h5 class='mt-2 admin'>ADMIN</h5>
          <h5 class='mr-3 mt-2'>Hello, <?php echo $_SESSION['fname']; ?></h5>

        </div>
      </nav>


      <div class="row pageTitle marginLeft">
        <div class="col center mb-3">
          <h1>Add New Agent</h1>
        </div>
      </div>


      <div class=" marginLeft">
        <!-- ADD AGENT SECTION -->
        <section class="addAgentSection">

          <form method="post" enctype="multipart/form-data" class="addAgent_form">
            <div class="form-row">
              <div class="form-group col-md-4 ">
                <label for="agentFName">First Name</label>
                <input type="text" class="form-control" name="agentFName" placeholder="First Name" required>
              </div>
              <div class="form-group col-md-4 ">
                <label for="agentLName">Last Name</label>
                <input type="text" class="form-control" name="agentLName" placeholder="Last Name" required>
              </div>
              <div class="form-group col-md-4">
                <label for="agentPhone">Phone (+64 XXX XXX XXX)</label>
                <input type="tel" class="form-control" name="agentPhone" placeholder="Phone" required>
              </div>
            </div>

            <div class="form-row">
              <div class="form-group col-md-6 ">
                <label for="agentEmail">Email</label>
                <input type="email" class="form-control" name="agentEmail" placeholder="Email" required>
              </div>
              <div class="form-group col-md-6">
                <label for="agentCity">City</label>
                <select name="agentCity" class="form-control" required>
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
            </div>

            <div class="form-row">
              <label for="profileImage" class="formAlign">Profile Photo</label>
            </div>
            <div class="form-row thumbnailAgent">
              <img src="../admin/images/profile-placeholder.png" class="thumbnailSize placeHolderThumbnailImg " alt="Photo" title="photo" />
            </div>

            <div class="form-row">
              <div class="form-group custom-file col-md-5">
                <input type="file" class="form-control-file" id="profileImage" name="fileImage" onchange="imagesPreview(this, 'thumbnailAgent')">
              </div>
              <div class="form-group col-md-4 offset-md-1 formSpacing">
                <div class="form-check myFormCheck">
                  <input class="form-check-input" type="checkbox" name="adminRights">
                  <label class="form-check-label" for="adminRights">
                    Give Administrator Access
                  </label>
                </div>
              </div>
            </div>

            <div class="form-group formSpacing">
              <label for="comment">Description</label>
              <textarea class="form-control" rows="5" name="agentDescription" required></textarea>
            </div>

            <div class="form-group">
              <p>Note: All new agents will be issued with a temporary password - 'asdf'. Passwords can then be edited by the agent after log in.</p>
            </div>

            <button type="submit" class="btn btn-secondary" name="addAgent">Add New Agent</button>
          </form>

        </section> <!-- End of Add Agent Section -->

      </div> <!-- End of Page Container div -->

    </section> <!-- End of wrapper div -->

    <?php include "include/footer.php"; ?>