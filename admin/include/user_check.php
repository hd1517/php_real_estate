<?php
include "../include/config.php";

$userID = $_SESSION['agent_id'];

// check if agent is NOT logged in
if (!isset($_SESSION['agent_id'])) {
    header("Location: ../signin.php");
}

// if user neither admin/agent, no access
if ($_SESSION['role'] == 3) {
    header("Location: ../signin.php");
}

// check for role
if ($_SESSION['role'] == 1) {
    $role = 'admin';
} else if ($_SESSION['role'] == 2) {
    $role = 'agent';
}

?>