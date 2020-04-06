        <!-- Sidebar  -->
        <nav id="sidebar">
            <div class="sidebar-header center">
                <h4 style="letter-spacing: 2px;">WISTERIA</h4>
                <h6 style="margin-top: -5px; margin-bottom: 0;">HOMES</h6>
            </div>
            <ul class="list-unstyled CTAs">
                <li>
                    <a href="../admin/add_property.php" class="download center"><i class="fas fa-home mr-1"></i>Add New Property</a>
                </li>
            </ul>

            <ul class="list-unstyled components">
                <li <?php if ($thisPage == "My Profile") echo "class=\"active\""; ?>>
                    <a href="../admin/agent_profile.php">My Profile</a>
                </li>
                <li <?php if ($thisPage == "My Properties") echo "class=\"active\""; ?>>
                    <a href="../admin/properties.php">My Properties</a>
                </li>

                <li <?php if ($thisPage == "Manage Agents") echo "class=\"active\""; ?>>
                    <a href="../admin/agents.php">Other Agents</a>
                </li>

                <li <?php if ($thisPage == "All Properties") echo "class=\"active\""; ?>>
                    <a href="../admin/properties.php?properties">Other Properties</a>
                </li>

                <li <?php if ($thisPage == "Manage Fields") echo "class=\"active\""; ?>>
                    <a href="../admin/manage_fields.php">Manage Listing Fields</a>
                </li>
            </ul>

            <ul class="list-unstyled CTAs center">
                <li>
                    <a href="../signout.php" class="article ">Log out</a>
                </li>
            </ul>
        </nav>

        <!-- Error Modal -->
        <div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content border-danger">
                    <div class="modal-header">
                        <h5 class="modal-title">Featured Property</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p style="font-size:1rem;">This property cannot be deleted as it has been set as the Featured Listing.</p>
                        <p style="font-size:1rem;">Please choose another property to feature before deleting this one.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>