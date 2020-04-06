<?php
include "include/config.php";
include "include/pagination.php";

$thisPage = 'Team';

include "include/login_modal.php";
include "include/header.php";
?>

<section class="header">
    <div class="container-fluid noPadding">
        <img src="images/header04.jpg" alt="" class="headerImg">
    </div>
</section>

<section style="padding-left: 30px; padding-right: 30px; margin-top: 30px;" class="mb-5">
    <div class="row center noMargin">
        <div class="col">
            <h1>Our Team</h1>
        </div>
    </div>

    <div class="card-deck agentsResults noMargin">
        <?php

        $rowsPerPage = 6; // edit the number of rows per page

        $query = "SELECT tbl_agents.agent_id as agent_id, tbl_agents.picture as picture, tbl_agents.fname as fname, tbl_agents.lname as lname, tbl_agents.phone as phone, tbl_agents.email as email, tbl_city.city as city, tbl_agents.about as about, tbl_user_type.user_type as user_type FROM tbl_agents INNER JOIN tbl_city ON tbl_agents.city_id_f=tbl_city.city_id INNER JOIN tbl_user_type ON tbl_agents.user_type_id_f=tbl_user_type.user_type_id ORDER BY tbl_agents.agent_id DESC";

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
                    <a href="agent_profile.php?agentID=<?php echo $row['agent_id']; ?>"><img src="<?php echo 'admin/images/' . $row['picture']; ?>" class="card-img-top" alt="Photo" title="photo" /></a>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $row['fname']; ?> <?php echo $row['lname']; ?></h5>
                        <p class="card-text"><i class="fas fa-envelope icon"></i> <?php echo $row['email']; ?></p>
                        <p class="card-text"><i class="fas fa-phone icon"></i> <?php echo $row['phone']; ?></p>
                        <p class="card-text"><i class="fas fa-map-marker-alt icon1"></i> <?php echo $row['city']; ?></p>
                        <p><a class="btn btn-secondary text-center btn-sm" href="agent_profile.php?agentID=<?php echo $row['agent_id']; ?>">View Full Profile</a></p>
                        <p><a class="btn btn-secondary text-center btn-sm " href="properties.php?agentID=<?php echo $row['agent_id']; ?>">View Listings</a></p>
                    </div>
                </div>
            </div>

        <?php
        } // end of while loop
        ?>

    </div>
    <!-- display paging links if required -->
    <?php if ($pagingLink != '') {
        echo '<div class="pagingLinks center"><p>' . $pagingLink . '</p></div>';
    } ?>

</section>

<!-- Footer -->
<?php include "include/footer.php"; ?>