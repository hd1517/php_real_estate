<?php
include "../include/config.php";

$propID = mysqli_real_escape_string($link, $_POST['propertyID']);

$output = '';

$query = "SELECT * FROM tbl_images WHERE property_id_f=$propID";
$result = mysqli_query($link, $query);
while ($row = mysqli_fetch_array($result)) {
    $output .= '<tr><td class="noPadding"><img src="../admin/images/' . $row['file_name'] .'" class="thumbnailSize mr-2" alt="Property Photo" /><a onclick="deletePropImage(\'' . $row['file_name'] .'\', \'' .$propID. '\')"> Delete </a></td></tr>';
    
} //end of while loop

$output .= '<tr><td><a type="button" class="btn btn-danger text-white" onclick="deletePropImage(\'all\', \'' .$propID. '\')">Delete All Current Photos </a></td></tr>';

echo $output;


?>