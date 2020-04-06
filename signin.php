<?php
include "include/config.php";
$thisPage = "Agent Login";

$error = ""; // set error message to empty; default no error condition

// check if the user submit the form
if (isset($_POST['login'])) {
  $email = mysqli_real_escape_string($link, $_POST['email']);
  $password = mysqli_real_escape_string($link, $_POST['password']);

  checkAgent($email, $password);
} // end of if

// adminLogin
if (isset($_POST['adminLogin'])) {
  $email = 'edna_mode@email.com';
  $password = 'asdf';
  checkAgent($email, $password);
} // end of if

// agent Login
if (isset($_POST['agentLogin'])) {
  $email = 'evelyn_deavor@email.com';
  $password = 'asdf';
  checkAgent($email, $password);
} // end of if

function checkAgent($email, $password) {
  global $link;
// check for valid email and password
$query = "SELECT * FROM tbl_agents WHERE email='$email' && password='$password' ";
$result = mysqli_query($link, $query); // execute the SQL 
if ($row = mysqli_fetch_array($result)) {
  // sign in is successful, set sessions 
  $_SESSION['agent_id'] = $row['agent_id'];
  $_SESSION['email'] = $row['email'];
  $_SESSION['fname'] = $row['fname'];
  $_SESSION['role'] = $row['user_type_id_f'];

  header("Location: admin/properties.php");
} else {
  $error = "Invalid email and/or password, please try again!";
} // end of else
}

include "include/login_modal.php";
include "include/header.php";
?>

<section class="pageWrap">
  <div class="container-fluid form-signin">
    <form class="form-signin mx-auto" method="post" enctype="multipart/form-data">
      <h1 class="h3 mb-3 font-weight-normal">AGENT LOGIN</h1>
      <?php
      // display error message if there is one
      if ($error != "") {
        echo "<h6 style='color:red;' class='mb-3'>" . $error . "</h6>";
      } else {
        echo "<h6 class='mb-3'>Please enter your email and password</h6>";
      }
      // end of if
      ?>

      <label for="inputEmail" class="sr-only">Email address</label>
      <input type="email" id="inputEmail" class="form-control" placeholder="Email address" required autofocus name="email">
      <label for="inputPassword" class="sr-only">Password</label>
      <input type="password" id="inputPassword" class="form-control" placeholder="Password" required name="password">
      <button class="btn btn-lg btn-secondary btn-block" type="submit" name="login">Sign in</button>
    </form>

    <form method="post" class="form-signin mx-auto">
      <div class="row">
        <div class="col-6" style="padding-right: 7px;">
          <button class="btn btn-sm btn-warning w-100" type="submit" name="adminLogin">Login as Admin</button>
        </div>
        <div class="col-6" style="padding-left: 7px;">
          <button class="btn btn-sm btn-info w-100" type="submit" name="agentLogin">Login as Agent</button>
        </div>
      </div>
    </form>
  </div>
</section>

<!-- Footer -->
<?php include "include/footer.php"; ?>
