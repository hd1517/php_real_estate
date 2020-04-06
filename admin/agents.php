<?php
include "include/user_check.php";
include "../include/pagination.php";

$thisPage = "Manage Agents";

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
      <?php
      if ($role == 'admin') {
        echo "<h5 class='mt-2 admin'>ADMIN</h5>";
      }
      ?>
      <h5 class='mr-3 mt-2'>Hello, <?php echo $_SESSION['fname']; ?></h5>

    </div>
  </nav>

  <div class="row pageTitle marginLeft">
    <div class="col center">
      <?php
      if ($role == 'admin') {
        echo "<h1>Manage Agents</h1>";
      } else {
        echo "<h1>Other Agents</h1>";
      }
      ?>

    </div>
  </div>

  <div class="pageContainer marginLeft">
    <!-- VIEW ALL AGENTS SECTION -->
    <section class="viewAgents">
      <div class="row">
        <div class="col-6 col-md-4 col-lg-3">
          <form id='viewByCity_form' action='' method='get'>
            <label for="viewByCity">View Agents by City:</label>
            <select name="viewByCity" class="form-control" onchange="this.form.submit()">
              <option selected disabled>Choose...</option>
              <option value="all">View All</option>
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
          </form>
        </div>
        <div class="col-6 col-sm-4 col-md-3">

        </div>
      </div>
      <div class="card-deck agentsResults">
        <?php

        $rowsPerPage = 6; // edit the number of rows per page

        if (isset($_GET['viewByCity'])) {

          $cityID = $_GET['viewByCity'];

          if ($cityID == "all") {
            $query = "SELECT tbl_agents.agent_id as agent_id, tbl_agents.picture as picture, tbl_agents.fname as fname, tbl_agents.lname as lname, tbl_agents.phone as phone, tbl_agents.email as email, tbl_city.city as city, tbl_agents.about as about, tbl_user_type.user_type as user_type FROM tbl_agents INNER JOIN tbl_city ON tbl_agents.city_id_f=tbl_city.city_id INNER JOIN tbl_user_type ON tbl_agents.user_type_id_f=tbl_user_type.user_type_id";
            if ($role == 'agent') {
              $query .= " WHERE NOT tbl_agents.agent_id = '$userID'";
            }
            $query .= " ORDER BY tbl_agents.agent_id DESC";
          } else {
            $query = "SELECT tbl_agents.agent_id as agent_id, tbl_agents.picture as picture, tbl_agents.fname as fname, tbl_agents.lname as lname, tbl_agents.phone as phone, tbl_agents.email as email, tbl_city.city as city, tbl_agents.about as about, tbl_user_type.user_type as user_type FROM tbl_agents INNER JOIN tbl_city ON tbl_agents.city_id_f=tbl_city.city_id INNER JOIN tbl_user_type ON tbl_agents.user_type_id_f=tbl_user_type.user_type_id WHERE tbl_city.city_id = '$cityID' ORDER BY tbl_agents.agent_id DESC ";
          }
        } else {
          $query = "SELECT tbl_agents.agent_id as agent_id, tbl_agents.picture as picture, tbl_agents.fname as fname, tbl_agents.lname as lname, tbl_agents.phone as phone, tbl_agents.email as email, tbl_city.city as city, tbl_agents.about as about, tbl_user_type.user_type as user_type FROM tbl_agents INNER JOIN tbl_city ON tbl_agents.city_id_f=tbl_city.city_id INNER JOIN tbl_user_type ON tbl_agents.user_type_id_f=tbl_user_type.user_type_id";
          if ($role == 'agent') {
            $query .= " WHERE NOT tbl_agents.agent_id = '$userID'";
          }
          $query .= " ORDER BY tbl_agents.agent_id DESC";
        }

        $pagingLink = getPagingLink($query, $rowsPerPage, "");

        $result = mysqli_query($link, getPagingQuery($query, $rowsPerPage));
        if (!$result) {
          printf("Error: %s\n", mysqli_error($link));
          exit();
        }
        while ($row = mysqli_fetch_array($result)) {
        ?>
          <div class="col-sm-6 col-md-4 col-lg-4 col-xl-3 noPadding ">
            <div class="card agentCard center">
              <a href="agent_profile.php?agentID=<?php echo $row['agent_id']; ?>"><img src="<?php echo '../admin/images/' . $row['picture']; ?>" class="card-img-top" alt="Photo" title="photo" /></a>
              <div class="card-body">
                <h5 class="card-title"><?php echo $row['fname']; ?> <?php echo $row['lname']; ?></h5>
                <p class="card-text"><i class="fas fa-envelope icon"></i> <?php echo $row['email']; ?></p>
                <p class="card-text"><i class="fas fa-phone icon"></i> <?php echo $row['phone']; ?></p>
                <p class="card-text"><i class="fas fa-map-marker-alt icon1"></i> <?php echo $row['city']; ?></p>
                <p><a class="btn btn-secondary text-center btn-sm" href="agent_profile.php?agentID=<?php echo $row['agent_id']; ?>">View Full Profile</a></p>
                <p><a class="btn btn-secondary text-center btn-sm " href="properties.php?<?php if ($role == 'agent' && $agentID != $userID) echo 'properties&' ?>agentID=<?php echo $row['agent_id']; ?>">View Listings</a></p>
              </div>
              <div class="card-footer">
                <small class="text-muted"><?php echo $row['user_type']; ?></small>
              </div>
            </div>
          </div>

        <?php
        } // end of while loop
        ?>

      </div>
      <div class="pagingLinks center">
        <p><?php echo $pagingLink; ?></p>
        <!-- display paging links -->
      </div>

    </section>

  </div> <!-- End of Page Container div -->

</section> <!-- End of wrapper div -->

<?php include "include/footer.php"; ?>