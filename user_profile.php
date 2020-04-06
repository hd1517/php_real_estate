<?php
include "include/config.php";

// if user is logged in already, redirect
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
}

$thisPage = 'User Profile';
$userID = $_SESSION['user_id'];

// UPDATE USER
if (isset($_POST['edit'])) {

    $newFName = mysqli_real_escape_string($link, $_POST['newFName']);
    $newLName = mysqli_real_escape_string($link, $_POST['newLName']);
    $newEmail = mysqli_real_escape_string($link, $_POST['newEmail']);
    $newPhone = mysqli_real_escape_string($link, $_POST['newPhone']);
    $newPass = mysqli_real_escape_string($link, $_POST['newPass']);

    $query = "UPDATE tbl_users SET user_fname='$newFName', user_lname='$newLName', email='$newEmail', phone='$newPhone', password='$newPass' WHERE user_id='$userID'";
    mysqli_query($link, $query); // execute the SQL 
    header("Location: user_profile.php");
} // end of if statement

// locate the user record from database
$query = "SELECT * FROM tbl_users WHERE user_id = $userID";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_array($result);
extract($row);

include "include/header.php";
?>

<section class="header mt-5">
    <div class="container-fluid noPadding">
        <img src="images/header04.jpg" alt="" class="headerImg">
    </div>

    <div class="container center mt-4">
        <h1>My Profile</h1>
        <p>Changes will be reflected the next time you log in.</p>
    </div>
</section>

<section class="loginSection">
    <div class="card mb-3">
        <div class="card-body">
            <div>
                <!-- login form -->
                <div>
                    <form method="post">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="newFName">First Name</label>
                                <input type="text" class="form-control edit_userFName<?php echo $userID; ?>" value="<?php echo $row['user_fname']; ?>" name="newFName">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="newLName">Last Name</label>
                                <input type="text" class="form-control edit_userLName<?php echo $userID; ?>" value="<?php echo $row['user_lname']; ?>" name="newLName">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="newEmail">Email</label>
                            <input type="text" class="form-control edit_userEmail<?php echo $userID; ?>" value="<?php echo $row['email']; ?>" name="newEmail">
                        </div>
                        <div class="form-group">
                            <label for="newPhone">Phone</label>
                            <input type="text" class="form-control edit_userPhone<?php echo $userID; ?>" value="<?php echo $row['phone']; ?>" name="newPhone">
                        </div>
                        <div class="form-group">
                            <label for="newPass">Password</label>
                            <input type="text" class="form-control edit_userPass<?php echo $userID; ?>" value="<?php echo $row['password']; ?>" name="newPass">
                        </div>
                        <button type="submit" class="btn btn-secondary w-100 mb-3" name="edit">Update</button>
                    </form>
                </div>

            </div>
        </div>
    </div>

</section>

<!-- Footer -->
<?php include "include/footer.php"; ?>