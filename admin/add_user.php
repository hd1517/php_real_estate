<?php
include "include/user_check.php";
include "../include/image_creation.php";

$thisPage = "Add User";

// no access to non admin
if ($role != 'admin') {
    header("Location: ../signin.php");
}

// check if the user clicks the submit button
if (isset($_POST['addUser'])) {

    $userFName = mysqli_real_escape_string($link, $_POST['userFName']);
    $userLName = mysqli_real_escape_string($link, $_POST['userLName']);
    $userPhone = mysqli_real_escape_string($link, $_POST['userPhone']);
    $userEmail = mysqli_real_escape_string($link, $_POST['userEmail']);
    $userUserType = 3;
    $userPassWord = 'asdf';

    // Add this new agent to agent table
    $query = "INSERT INTO tbl_users (user_type_id_f, user_fname, user_lname, phone, email, password) VALUES ('$userUserType', '$userFName', '$userLName', '$userPhone', '$userEmail', '$userPassWord') ";

    mysqli_query($link, $query); // execute the SQL 
    header("Location: manage_users.php");
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

    <div class="row pageTitle marginLeft mb-4">
        <div class="col center">
            <h1>Add New User</h1>
        </div>
    </div>

    <div class=" marginLeft">
        <!-- ADD USER SECTION -->
        <section class="addUserSection">

            <form method="post" enctype="multipart/form-data" class="addUser_form">

                <div class="form-group row">
                    <label for="userFName" class="col-sm-2 col-form-label">First Name</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="userFName" placeholder="First Name" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="userLName" class="col-sm-2 col-form-label">Last Name</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="userLName" placeholder="Last Name" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="userEmail" class="col-sm-2 col-form-label">Email</label>
                    <div class="col-sm-10">
                        <input type="email" class="form-control" name="userEmail" placeholder="Email" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="userPhone" class="col-sm-2 col-form-label">Phone</label>
                    <div class="col-sm-10">
                        <input type="tel" class="form-control" name="userPhone" placeholder="Phone" required>
                    </div>
                </div>

                <div class="form-group ">
                    <p class="mt-4">Note: All new users will be issued with a temporary password - 'asdf'. Passwords can then be edited by the user after log in.</p>
                </div>
                <button type="submit" class="btn btn-secondary mt-1" name="addUser">Add New User</button>
            </form>

        </section> <!-- End of Add Agent Section -->

    </div> <!-- End of Page Container div -->

</section> <!-- End of wrapper div -->

<?php include "include/footer.php"; ?>