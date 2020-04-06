<!-- Bootstrap navbar -->
<nav class="navbar navbar-light navbar-expand-lg fixed-top bg-white">
    <a class="navbar-brand" href="index.php">
        <div class="logoTop">Wisteria</div>
        <div class="logoBottom">Homes</div>
        </a> <!-- Logo -->

    <!-- Hamburger menu icon on collapse -->
    <button class="navbar-toggler myToggler" type="button" data-toggle="collapse" data-target="#navbarLinks">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarLinks">
        <ul class="navbar-nav ml-auto ">
            <li class="nav-item <?php if ($thisPage == 'Properties') echo 'active'; ?>">
                <a class="nav-link" href="properties.php">Properties</a>
            </li>
            <li class="nav-item <?php if ($thisPage == 'Team') echo 'active'; ?>">
                <a class="nav-link" href="team.php">Our Team</a>
            </li>
            <?php if (!isset($_SESSION['user_id'])) {  // if user is NOT logged in
                $loginLink = '<li class="nav-item">
                <a class="nav-link modalLink"';
                if ($thisPage != 'Login') { // link the modal if not the login.php page
                $loginLink .= 'data-toggle="modal" data-target="#loginModal"';
                } 
                $loginLink .= '>Login/Register</a></li>';
            echo $loginLink;
            } else { // if user IS logged in
                echo '<li class="nav-item dropdown active">
                <a class="nav-link dropdown-toggle" href="" id="navbarDropdownMenuLink-4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Hello, '.$_SESSION['user_fname'].' </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-cyan" aria-labelledby="navbarDropdownMenuLink-4">
                    <a class="dropdown-item" href="watchlist.php"><i class="customItem far fa-star iconNavItem"></i> My Watchlist</a>
                    <a class="dropdown-item" href="user_profile.php"><i class="fa fa-user icon"></i> My Profile</a>
                    <a class="dropdown-item" href="signout.php"><i class="fas fa-sign-out-alt iconNavItem2"></i> Logout</a>
                </div>
            </li>';
            } ?>           

        </ul>
    </div> <!-- End of collapse -->
</nav>
<!-- End of navbar -->