<?php
include "../include/config.php";

$tbl = mysqli_real_escape_string($link, $_POST['table_name']);
$col = mysqli_real_escape_string($link, $_POST['col_name']);
$cityID = mysqli_real_escape_string($link, $_POST['cityID']);
$entryIDcol = $col.'_id';

$output = '';

$query = "SELECT * FROM {$tbl} WHERE city_id_f = '$cityID'";
$result = mysqli_query($link, $query);
if (!$result) {
    printf("Error: %s\n", mysqli_error($link));
    exit();
  }
while($row = mysqli_fetch_array($result)) {
   $output .= '<option value="'.$row[$entryIDcol].'">'.$row[$col].'</option>';

}

echo $output;

?>

