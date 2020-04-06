<?php
include "include/config.php";

// if user is logged in already, redirect
if (isset($_SESSION['user_id'])) {
  header("Location: index.php");
}

$error = (isset($_GET['error'])) ? mysqli_real_escape_string($link, $_GET['error']) : '';

$thisPage = 'Login';

// check if the user submit the form
if (isset($_POST['login'])) {
  $email = mysqli_real_escape_string($link, $_POST['email']);
  $password = mysqli_real_escape_string($link, $_POST['password']);

  checkUser($email, $password);
} // end of if

// adminLogin
if (isset($_POST['userLogin'])) {
  $email = 'bo_peep@email.com';
  $password = 'asdf';
  checkUser($email, $password);
} // end of if

function checkUser($email, $password) {
  global $link;
  
// check for valid email and password
$query = "SELECT * FROM tbl_users WHERE email='$email' && password='$password' ";
$result = mysqli_query($link, $query); // execute the SQL 
if ($row = mysqli_fetch_array($result)) {
   // sign in is successful, set sessions 
   $_SESSION['user_id'] = $row['user_id'];
   $_SESSION['email'] = $row['email'];
   $_SESSION['user_fname'] = $row['user_fname'];
   $_SESSION['role'] = $row['user_type_id_f'];

   if (isset($_SESSION['return_page'])) {
    header("Location: ".$_SESSION['return_page']);
    } // end of if
    else {
    header("Location: index.php");
    }
}
else {
  header("Location: login.php?error=invalid");
 } // end of else

} // end of if

// REGISTER
// check if the user submit the form
if (isset($_POST['register'])) {
  $newEmail = mysqli_real_escape_string($link, $_POST['newUserEmail']);
  $newFName = mysqli_real_escape_string($link, $_POST['newUserFName']);
  $newLName = mysqli_real_escape_string($link, $_POST['newUserLName']);
  $newPhone = mysqli_real_escape_string($link, $_POST['newUserPhone']);
  $newPassword = mysqli_real_escape_string($link, $_POST['newUserPassword']);
  $userType = 3;

  // check for duplicated email
  $query = "SELECT * FROM tbl_users WHERE email='$newEmail' ";
  $result = mysqli_query($link, $query); // execute the SQL 
  if ($row = mysqli_fetch_array($result)) {
    header("Location: login.php?error=existing_email");
  } else {
    // Add this member to tbl_member
    $query = "INSERT INTO tbl_users (user_fname, user_lname, email, phone, user_type_id_f, password) VALUES ('$newFName', '$newLName', '$newEmail', '$newPhone', '$userType', '$newPassword') ";
    mysqli_query($link, $query); // execute the SQL
    
    // find the new record
  $query = "SELECT * FROM tbl_users WHERE email='$newEmail' && password='$newPassword' ";
  $result = mysqli_query($link, $query); // execute the SQL 
  if ($row = mysqli_fetch_array($result)) {
    // sign in is successful, set sessions 
    $_SESSION['user_id'] = $row['user_id'];
    $_SESSION['email'] = $row['email'];
    $_SESSION['user_fname'] = $row['user_fname'];
    $_SESSION['role'] = $row['user_type_id_f'];

    if (isset($_SESSION['return_page'])) {
      header("Location: " . $_SESSION['return_page']);
    } // end of if
    else {
      header("Location: index.php");
    }
  }
  } // end of else

} // end of if

include "include/header.php";
?>

<section class="header mt-5">
    <div class="container-fluid noPadding">
    <img src="images/header4.jpg" alt="" class="headerImg">
    </div>

    <div class="container center mt-4">
    <h1>Login / Register</h1>
    </div>
</section>

<section class="loginSection">
<div class="card mb-3">
          <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs pull-right" id="myTab" role="tablist">
              <li class="nav-item">
                <a class="nav-link <?php if ($error == "invalid") echo 'active'; ?>" id="login-tab" data-toggle="tab" href="#login" role="tab" aria-controls="login" aria-selected="<?php if ($error == "invalid") { echo 'true'; } else { echo 'false';} ?>">Login</a>
              </li>
              <li class="nav-item ">
                <a class="nav-link <?php if ($error == "existing_email") echo 'active'; ?>" id="register-tab" data-toggle="tab" href="#register" role="tab" aria-controls="register" aria-selected="<?php if ($error == "existing_email") { echo 'true'; } else { echo 'false';} ?>">Register</a>
              </li>
            </ul>
          </div>
          <div class="card-body">
            <div class="tab-content" id="myTabContent">
              <!-- login form -->
              <div class="tab-pane fade <?php if ($error == "invalid") echo 'active show'; ?>" id="login" role="tabpanel" aria-labelledby="login-tab">
                <form method="post">
                  <div class="form-group">
                    <?php if ($error == "invalid") { echo '<label class="formLabel noMargin errorMsg">Sorry we couldn\'t match your username and password, please try again.</label>'; } else { echo '<label class="formLabel noMargin">Please enter your email address and password:</label>'; } ?>
                    <hr>
                  </div>
                  
                  <div class="form-group">
                    <label for="userEmail">Email address</label>
                    <input type="email" class="form-control" id="userEmail" aria-describedby="userEmail" placeholder="Enter email" required name="email">
                  </div>
                  <div class="form-group">
                    <label for="userPassword">Password</label>
                    <input type="password" class="form-control" id="userPassword" placeholder="Password" required name="password">
                    <small id="forgotPass" class="form-text text-muted">Forgot your password?</small>
                  </div>

                  <button type="submit" class="btn btn-secondary w-100 mb-3" name="login">Submit</button>
                </form>

                <form method="post">
                  <button class="btn btn-sm btn-info w-100" type="submit" name="userLogin">Example User Login</button>
                </form>
              </div>

              <!-- register form -->
              <div class="tab-pane fade <?php if ($error == "existing_email") echo 'active show'; ?>" id="register" role="tabpanel" aria-labelledby="register-tab">
                <form method="post" oninput='confirmPass.setCustomValidity(confirmPass.value != newUserPassword.value ? "Passwords do not match." : "")'>
                  <div class="form-group">
                  <?php if ($error == "existing_email") { echo '<label class="formLabel noMargin errorMsg">An account with this email already exists.</label>'; } else { echo '<label class="formLabel noMargin">Create an account:</label>'; } ?>
                    <hr>
                  </div>
                  <div class="form-row">
                  <div class="form-group col-md-6">
                    <label for="newUserFName">First Name</label>
                    <input type="text" class="form-control" id="newUserFName" aria-describedby="newUserFName" placeholder="Enter your first name" required name="newUserFName">
                  </div>
                  <div class="form-group col-md-6">
                    <label for="newUserLName">Last Name</label>
                    <input type="text" class="form-control" id="newUserLName" aria-describedby="newUserLName" placeholder="Enter your last name" required name="newUserLName">
                  </div>
                  </div>
                  
                  <div class="form-group">
                    <label for="newUserEmail">Email address</label>
                    <input type="email" class="form-control" id="newUserEmail" aria-describedby="newUserEmail" placeholder="Enter email" required name="newUserEmail">
                  </div>
                  <div class="form-group">
                    <label for="newUserPhone">Phone</label>
                    <input type="text" class="form-control" id="newUserPhone" aria-describedby="newUserPhone" placeholder="Enter phone number" required name="newUserPhone">
                  </div>
                  <div class="form-group">
                    <label for="newUserPassword">Password</label>
                    <input type="password" class="form-control" id="newUserPassword" placeholder="Create your password" required name="newUserPassword">
                  </div>
                  <div class="form-group">
                    <label for="newUserPassword">Confirm Password</label>
                    <input type="password" class="form-control" id="confirmPassword" placeholder="Create your password" required name="confirmPass">
                  </div>

                  <button type="submit" class="btn btn-secondary w-100 mb-3" name="register">Register</button>
                </form>

              </div>

            </div>
          </div>
        </div>

</section>

<!-- Footer -->
<?php include "include/footer.php"; ?>