        <!-- Sidebar  -->
        <nav id="sidebar">
            <div class="sidebar-header center">
                <h4 style="letter-spacing: 2px;">WISTERIA</h4>
                <h6 style="margin-top: -5px; margin-bottom: 0;">HOMES</h6>
            </div>
            <ul class="list-unstyled CTAs">
                <li>
                    <a href="../admin/add_property.php" class="download center"><i class="fas fa-home mr-1"></i> Add New Property</a>
                </li>
                <li>
                    <a href="../admin/add_agent.php" class="download center"><i class="fas fa-user-tie mr-1"></i> Add New Agent</a>
                </li>
                <li>
                    <a href="../admin/add_user.php" class="download center"><i class="fas fa-user mr-1"></i> Add New User</a>
                </li>
            </ul>

            <ul class="list-unstyled components">

                <li <?php if ($thisPage == "Manage Properties") echo "class=\"active\""; ?>>
                    <a href="../admin/properties.php">Manage Properties</a>
                </li>
                <li <?php if ($thisPage == "Manage Agents") echo "class=\"active\""; ?>>
                    <a href="../admin/agents.php">Manage Agents</a>
                </li>
                <li <?php if ($thisPage == "Manage Users") echo "class=\"active\""; ?>>
                    <a href="../admin/manage_users.php">Manage Users</a>
                </li>
                <li <?php if ($thisPage == "Manage Fields") echo "class=\"active\""; ?>>
                    <a href="../admin/manage_fields.php">Manage Listing Fields</a>
                </li>

            </ul>

            <ul class="list-unstyled CTAs center">
                <li>
                    <a href="../signout.php" class="article">Log out</a>
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

         <!-- Error Modal -->
         <div class="modal fade" id="errorAgentModal" tabindex="-1" role="dialog" aria-labelledby="errorAgentModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content border-danger">
                    <div class="modal-header">
                        <h5 class="modal-title">Agent has active listings</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p style="font-size:1rem;">This agent cannot be deleted as he/she has active listings.</p>
                        <p style="font-size:1rem;">Please reassign active listings to active agents before deleting this agent.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>