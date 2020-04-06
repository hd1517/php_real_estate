<?php
include "include/user_check.php";
include "../include/image_creation.php";

$noDelete_Error = "";

// if admin role has logged in, set this page as 'Manage Agents'
if ($role == 'admin') {
    $thisPage = "Manage Agents";
    $agentID = mysqli_real_escape_string($link, $_GET['agentID']);
} else { // if normal agent has logged in
    // if the agent is looking at other profiles    
    if (isset($_GET['agentID'])) {
        $thisPage = "All Agents";
        $agentID = mysqli_real_escape_string($link, $_GET['agentID']);
    } else { // agent is looking at their profile
        $thisPage = "My Profile";
        $agentID = $userID;
    }
}

// check if the user clicks the submit button
if (isset($_POST['editAgent'])) {

    $agentFName_new = mysqli_real_escape_string($link, $_POST['agentFName_new']);
    $agentLName_new = mysqli_real_escape_string($link, $_POST['agentLName_new']);
    $agentPhone_new = mysqli_real_escape_string($link, $_POST['agentPhone_new']);
    $agentEmail_new = mysqli_real_escape_string($link, $_POST['agentEmail_new']);
    $agentCity_new = mysqli_real_escape_string($link, $_POST['agentCity_new']);
    $agentAbout_new = mysqli_real_escape_string($link, $_POST['agentDescription_new']);
    $agentUserType_new = '';

    if ($role == 'admin') {
        if (isset($_POST['adminRights_new'])) {
            $agentUserType_new = 1;
        } else {
            $agentUserType_new = 2;
        }
    } else {
        $agentPass_new = mysqli_real_escape_string($link, $_POST['agentPass_new']);
    }

    //image file
    $imgName = $_FILES['fileImage_new']['name'];
    $tmpName = $_FILES['fileImage_new']['tmp_name'];
    $ext = strtolower(strrchr($imgName, ".")); // strtolower -- change to lowercase
    $newName = md5(rand() * time()) . $ext;
    $imgPath = PRODUCT_IMG_DIR . $newName;
    $width = 350;
    $height = 350;
    createThumbnail($tmpName, $imgPath, $width, $height);

    // check if the image is updated
    if ($tmpName != "") {
        // delete old image from folder
        $query = "SELECT picture FROM tbl_agents WHERE agent_id = '$agentID'";
        $result = mysqli_query($link, $query);
        while ($row = mysqli_fetch_array($result)) {
            unlink('images/' . $row['picture']);
        }

        // if user is admin
        if ($role == 'admin') {
            $query = "UPDATE tbl_agents SET fname='$agentFName_new', lname='$agentLName_new', phone='$agentPhone_new', email='$agentEmail_new', city_id_f='$agentCity_new', about='$agentAbout_new', picture='$newName', user_type_id_f='$agentUserType_new' WHERE agent_id='$agentID' ";
        } else { //if user is normal agent
            $query = "UPDATE tbl_agents SET fname='$agentFName_new', lname='$agentLName_new', phone='$agentPhone_new', email='$agentEmail_new', city_id_f='$agentCity_new', about='$agentAbout_new', picture='$newName', password='$agentPass_new' WHERE agent_id='$agentID' ";
        }
    } else {
        //if user is admin
        if ($role == 'admin') {
            $query = "UPDATE tbl_agents SET fname='$agentFName_new', lname='$agentLName_new', phone='$agentPhone_new', email='$agentEmail_new', city_id_f='$agentCity_new', about='$agentAbout_new', user_type_id_f='$agentUserType_new' WHERE agent_id='$agentID' ";
        } else { //if user is normal agent
            $query = "UPDATE tbl_agents SET fname='$agentFName_new', lname='$agentLName_new', phone='$agentPhone_new', email='$agentEmail_new', city_id_f='$agentCity_new', about='$agentAbout_new', password='$agentPass_new' WHERE agent_id='$agentID' ";
        }
    }

    mysqli_query($link, $query); // execute the SQL 

} // end of if statement


// locate the agent record from database
$query = "SELECT tbl_agents.fname as fname,
tbl_agents.lname as lname,
tbl_agents.phone as phone,
tbl_agents.email as email,
tbl_agents.about as about,
tbl_agents.picture as picture,
tbl_agents.password as pass,
tbl_city.city as city,
tbl_user_type.user_type as userType
FROM tbl_agents 
INNER JOIN tbl_city ON tbl_agents.city_id_f=tbl_city.city_id
INNER JOIN tbl_user_type ON tbl_agents.user_type_id_f=tbl_user_type.user_type_id
WHERE agent_id='$agentID'";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_array($result);
extract($row);
$fName = $fname;
$lName = $lname;
$Phone = $phone;
$Email = $email;
$City = $city;
$About = $about;
$Picture = $picture;
$User_type = $userType;
$pass = $pass;

//DELETE 
if (isset($_POST['delete'])) {
    $agent_ID = mysqli_real_escape_string($link, $_POST['delete_entryID']);
    // find number of listings for agent
    $listings_result = mysqli_query($link, "SELECT * FROM tbl_properties WHERE agent_id_f='$agent_ID'");
    $listingsRow_count = mysqli_num_rows($listings_result);
    // if no listings, then able to delete
    if ($listingsRow_count < 1) {
        // delete image from folder
        $query = "SELECT picture FROM tbl_agents WHERE agent_id = '$agent_ID'";
        $result = mysqli_query($link, $query);
        while ($row = mysqli_fetch_array($result)) {
            unlink('images/' . $row['picture']);
        }

        // delete agent from agent table in db
        $query = "DELETE FROM tbl_agents WHERE agent_id = '$agent_ID' ";
        mysqli_query($link, $query);
        header("Location: agents.php");
    }
}
// end of if statement for Delete

include "include/header.php";
?>

<!-- Page Content -->
<section class="content agentTableSection ">

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
    </nav>

    <div class="row pageTitle marginLeft">
        <div class="col center">
            <h1>
                <?php if ($agentID == $userID) {
                    echo 'My Profile';
                } else {
                    echo 'Agent Profile';
                }
                ?>
            </h1>
        </div>
    </div>

    <div class="profileSection_agent">

        <?php if ($role == 'admin') {
            $output = '<div class="row ml-4 ml-md-4 noPadding mr-4 mr-md-5 mt-3">
                            <div class="col-md-6 mb-1">
                                <a class="btn btn-block btn-secondary" onclick="showEditForm(\'agent\')">Edit Profile</a>
                            </div>
                            <div class="col-md-6 mb-1">
                                <a class="btn btn-danger btn-block text-center" onclick="deleteRecord(\'agents\',\'' . $agentID . '\')">Delete Agent</a>
                            </div>
                            </div>';
            echo $output;
        } else if ($agentID == $userID) {
            $output = '<div class="row noPadding ml-3 mr-3">
                    <div class="col mx-auto mt-3">
                        <a class="btn btn-block btn-secondary" onclick="showEditForm(\'agent\')">Edit Profile</a>
                    </div>
                    </div>';
            echo $output;
        }

        ?>

        <div class="row agentProfileSection mb-5">
            <div class="col-sm-6 col-md-4">
                <div class="card agentCard profileCard center">
                    <img src="<?php echo '../admin/images/' . $Picture; ?>" class="card-img-top" alt="Photo" title="photo" />
                    <div class="card-body">
                        <h5 class="card-title "><?php echo $fName; ?> <?php echo $lName; ?></h5>
                        <p class="card-text"><i class="fas fa-envelope icon"></i> <?php echo $Email; ?></p>
                        <p class="card-text"><i class="fas fa-phone icon"></i> <?php echo $Phone; ?></p>
                        <p class="card-text"><i class="fas fa-map-marker-alt icon1"></i> <?php echo $City; ?></p>
                        <a class="btn btn-secondary listingNumber" href="properties.php?<?php if ($role == 'agent' && $agentID != $userID) echo 'properties&' ?>agentID=<?php echo $agentID; ?>">View Listings<?php
                                                                                                                                                                                                                $listings_result = mysqli_query($link, "SELECT * FROM tbl_properties WHERE agent_id_f='$agentID' AND listing_status_id_f='1'");
                                                                                                                                                                                                                $listingsRow_count = mysqli_num_rows($listings_result);
                                                                                                                                                                                                                echo ' (' . $listingsRow_count . ')'; ?></a>
                    </div>
                    <div class="card-footer">
                        <small class="text-muted"><?php echo $User_type; ?></small>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-8 aboutProfile">
                <div class="row">
                    <h3>About <?php echo $fName; ?></h3>
                    <hr>
                    <p><?php echo $About; ?></p>
                </div>
            </div>
        </div>
    </div>

    <?php
    if ($agentID == $userID || $role == 'admin') {
        include "../admin/include/change_agent.php";
    }
    ?>

</section> <!-- End of wrapper div -->

<?php 
include "include/close_modal.php";
include "include/footer.php"; ?>