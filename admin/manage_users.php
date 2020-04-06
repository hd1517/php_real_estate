<?php
include "include/user_check.php";
include "../include/pagination.php";

$thisPage = "Manage Users";

// no access for non admin
if ($role != 'admin') {
    header("Location: ../signin.php");
}

// UPDATE USER
if (isset($_POST['update'])) {

    $newFName = mysqli_real_escape_string($link, $_POST['newFName']);
    $newLName = mysqli_real_escape_string($link, $_POST['newLName']);
    $newEmail = mysqli_real_escape_string($link, $_POST['newEmail']);
    $newPhone = mysqli_real_escape_string($link, $_POST['newPhone']);
    $userID = mysqli_real_escape_string($link, $_POST['userID']);

    $query = "UPDATE tbl_users SET user_fname='$newFName', user_lname='$newLName', email='$newEmail', phone='$newPhone' WHERE user_id='$userID'";
    mysqli_query($link, $query); // execute the SQL 
    header("Location: manage_users.php");
} // end of if statement

//DELETE 
if (isset($_POST['delete'])) {
    $userID = mysqli_real_escape_string($link, $_POST['userID']);

    $query1 = "DELETE FROM tbl_users WHERE user_id = '$userID'";
    mysqli_query($link, $query1);
    header("manage_users.php");
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
            <h5 class='mt-2 admin'>ADMIN</h5>
            <h5 class='mr-3 mt-2'>Hello, <?php echo $_SESSION['fname']; ?></h5>

        </div>
    </nav>

    <div class="row pageTitle marginLeft mb-4">
        <div class="col center">
            <h1>Manage Users</h1>
        </div>
    </div>

    <!-- Not Mobile View -->
    <section class="notMobile d-none d-md-block">

        <table class="table table-striped ">
            <thead class="thead-dark">
                <tr>
                    <th scope="col" style="width: 20%">First Name</th>
                    <th scope="col" style="width: 20%">Last Name</th>
                    <th scope="col" style="width: 25%">Email</th>
                    <th scope="col" style="width: 15%">Phone</th>
                    <th scope="col" style="width: 10%" class="center col-1">Edit</th>
                    <th scope="col" style="width: 10%" class="center col-1">Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $rowsPerPage = 5; // number of results to show
                $query = "SELECT * FROM tbl_users ORDER BY user_fname";
                $pagingLink = getPagingLink($query, $rowsPerPage, "");
                $result = mysqli_query($link, getPagingQuery($query, $rowsPerPage));
                while ($row = mysqli_fetch_array($result)) {
                    $userID = $row['user_id'];
                ?>
                    <tr class="userRow<?php echo $userID; ?>">

                        <td><span class="ogValue<?php echo $userID; ?> <?php echo 'userFName' . $userID; ?>"><?php echo $row['user_fname']; ?></span>
                            <div class="editDiv<?php echo $userID; ?> <?php echo 'edit_userFNameDiv' . $userID; ?> hide ">
                                <div class="input-group">
                                    <input type="text" class="form-control edit_userFName<?php echo $userID; ?>" value="<?php echo $row['user_fname']; ?>" name="newValue_fName">
                                </div>
                            </div>
                        </td>

                        <td><span class="ogValue<?php echo $userID; ?> <?php echo 'userLName' . $userID; ?>"><?php echo $row['user_lname']; ?></span>
                            <div class="editDiv<?php echo $userID; ?> <?php echo 'edit_userLNameDiv' . $userID; ?> hide ">
                                <div class="input-group">
                                    <input type="text" class="form-control edit_userLName<?php echo $userID; ?>" value="<?php echo $row['user_lname']; ?>" name="newValue_lName">
                                </div>
                            </div>
                        </td>

                        <td><span class="ogValue<?php echo $userID; ?> <?php echo 'userEmail' . $userID; ?>"><?php echo $row['email']; ?></span>
                            <div class="editDiv<?php echo $userID; ?> <?php echo 'edit_userEmailDiv' . $userID; ?> hide ">
                                <div class="input-group">
                                    <input type="text" class="form-control edit_userEmail<?php echo $userID; ?>" value="<?php echo $row['email']; ?>" name="newValue_email">
                                </div>
                            </div>
                        </td>

                        <td><span class="ogValue<?php echo $userID; ?> <?php echo 'userPhone' . $userID; ?>"><?php echo $row['phone']; ?></span>
                            <div class="editDiv<?php echo $userID; ?> <?php echo 'edit_userPhoneDiv' . $userID; ?> hide ">
                                <div class="input-group">
                                    <input type="text" class="form-control edit_userPhone<?php echo $userID; ?>" value="<?php echo $row['phone']; ?>" name="newValue_phone">
                                </div>
                            </div>
                        </td>

                        <td class="center">
                            <a onclick="hideOriginal('user', '<?php echo $userID; ?>')"><i class="far fa-edit userIcon<?php echo $userID; ?>"></i></a>
                            <button class="btn btn-secondary editBtn editUserBtn<?php echo $userID; ?> hide" type="submit" name="edit" onclick="editUser('<?php echo $userID; ?>','notMob')">Update</button>
                            <a onclick="cancelChange('user', '<?php echo $userID; ?>')" class="userCancelLink<?php echo $userID; ?> hide cancelLink">Cancel</a>
                        </td>
                        <td class="center"><a onclick=" deleteUser('<?php echo $userID; ?>')"><i class="far fa-trash-alt"></i></a></td>

                    </tr>
                <?php
                } //end of while
                ?>
            </tbody>
        </table>
        <div class="pagingLinks center">
            <p><?php echo $pagingLink; ?></p>
            <!-- display paging links -->
        </div>
    </section>

    <!-- Mobile View -->
    <section class="mobileVersion d-md-none">
        <div class="card-deck">
            <?php
            $rowsPerPage = 6; // number of results to show
            $query = "SELECT * FROM tbl_users ORDER BY user_fname";
            $pagingLink = getPagingLink($query, $rowsPerPage, "");
            $result = mysqli_query($link, getPagingQuery($query, $rowsPerPage));
            while ($row = mysqli_fetch_array($result)) {
                $userID = $row['user_id'];
            ?>

                <div class="col-sm-6 col-md-4 col-lg-4 col-xl-3 noPadding ">
                    <div class="card agentCard userRow<?php echo $userID; ?>">
                        <div class="card-body center ogValue<?php echo $userID; ?>">
                            <h5 class="card-title"><span class="<?php echo 'userFName' . $userID; ?>"><?php echo $row['user_fname']; ?></span> <span class="<?php echo 'userLName' . $userID; ?>"><?php echo $row['user_lname']; ?></span></h5>
                            <p class="card-text"><i class="fas fa-envelope icon mr-1"></i> <span class="<?php echo 'userEmail' . $userID; ?>"><?php echo $row['email']; ?></span></p>
                            <p class="card-text"><i class="fas fa-phone icon mr-1"></i> <span class="<?php echo 'userPhone' . $userID; ?>"><?php echo $row['phone']; ?></span></p>
                        </div>
                        <div class="card-body hide editDiv<?php echo $userID; ?>">
                            <form>
                                <div class="form-group">
                                    <label for="newValue_fName">First Name</label>
                                    <input type="text" class="form-control editMob_userFName<?php echo $userID; ?>" value="<?php echo $row['user_fname']; ?>" name="newValue_fName_mob">
                                </div>
                                <div class="form-group">
                                    <label for="newValue_lName">Last Name</label>
                                    <input type="text" class="form-control editMob_userLName<?php echo $userID; ?>" value="<?php echo $row['user_lname']; ?>" name="newValue_lName_mob">
                                </div>
                                <div class="form-group">
                                    <label for="newValue_email">Email</label>
                                    <input type="text" class="form-control editMob_userEmail<?php echo $userID; ?>" value="<?php echo $row['email']; ?>" name="newValue_email_mob">
                                </div>
                                <div class="form-group">
                                    <label for="newValue_phone">Phone</label>
                                    <input type="text" class="form-control editMob_userPhone<?php echo $userID; ?>" value="<?php echo $row['phone']; ?>" name="newValue_phone_mob">
                                </div>
                                <button class="btn btn-secondary editBtn btn-sm editUserBtn<?php echo $userID; ?> " type="submit" name="edit" onclick="editUser('<?php echo $userID; ?>', 'mob')">Update</button>
                            </form>
                        </div>
                        <div class="card-footer center">
                            <small class="text-muted">
                                <a class="btn btn-secondary text-center btn-sm userIcon<?php echo $userID; ?>" onclick="hideOriginal('user', '<?php echo $userID; ?>')"><i class="far fa-edit"></i> Edit User</a>
                                <a class="btn btn-secondary text-center btn-sm userCancelLink<?php echo $userID; ?> hide" onclick="cancelChange('user', '<?php echo $userID; ?>')">Cancel</a></small>
                        </div>
                        <div class="card-footer center">
                            <small class="text-muted"><a class="btn btn-danger btn-sm text-center text-white" onclick="deleteUser('<?php echo $userID; ?>')"><i class="far fa-trash-alt mr-1"></i> Delete User</a></small>
                        </div>
                    </div>
                </div>

            <?php
            } //end of while
            ?>
        </div>
        <div class="pagingLinks center">
            <p><?php echo $pagingLink; ?></p>
            <!-- display paging links -->
        </div>
    </section>


</section>
<!-- End of Page Content -->

<?php
include "include/close_modal.php";
include "include/footer.php"; ?>