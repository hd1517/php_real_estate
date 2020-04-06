<?php
include "../include/config.php";

$tbl = mysqli_real_escape_string($link, $_POST['table_name']);
$col = mysqli_real_escape_string($link, $_POST['col_name']);

$output = '';


if ($col == 'city' || $col == 'sale_type' || $col == 'listing_status') {
$query = "SELECT * FROM {$tbl} ORDER BY {$col}";
    $result = mysqli_query($link, $query);
    while ($row = mysqli_fetch_array($result)) {
        if ($col == 'city') {
            $colName = 'city';
            $tdClass = $colName . $row['city_id'];
            $rowColName = $row['city'];
            $rowColID = $row['city_id'];
        } else if ($col == 'sale_type') {
            $colName = 'sale_type';
            $tdClass = $colName . $row['sale_type_id'];
            $rowColName = $row['sale_type'];
            $rowColID = $row['sale_type_id'];
        } else {
            $colName = 'listing_status';
            $tdClass = $colName . $row['listing_status_id'];
            $rowColName = $row['listing_status'];
            $rowColID = $row['listing_status_id'];
        }
        $output = '<tr><td><span class=' . $tdClass . '>' . $rowColName . '</span><div class="editFields' . $tdClass . ' hide"><div class="input-group"><input type="text" class="form-control editInput' . $tdClass . '" value="' . $rowColName . '" name="newValue"><div class="input-group-append"><button class="btn btn-secondary editBtn" type="submit" name="edit" onclick="updateTable(\'' . $rowColID . '\', \'' . $colName . '\')">Update</button></div></div></div></td><td class="center"><a onclick="hideOriginal(\'' . $tdClass . '\')"><i class="far fa-edit icon' . $tdClass . '"></i></a><a onclick="cancelChange(\'' . $tdClass . '\' )" class="cancelLink' . $tdClass . ' hide cancelLink">Cancel</a></td><td class="center"><a onclick="deleteRecord(\'' . $colName . '\', \'' . $rowColID . '\')"><i class="far fa-trash-alt"></i></a></td></tr>';
        echo $output;
    } //end of while


} else if ($col == 'suburb') {

    $cityID_f = mysqli_real_escape_string($link, $_POST['viewSuburbByCity']);

    if ($cityID_f == 'undefined') {
        $query = "SELECT tbl_suburb.suburb_id as suburb_id, tbl_suburb.city_id_f as city_id_f, tbl_suburb.suburb as suburb, tbl_city.city as city FROM tbl_suburb INNER JOIN tbl_city ON tbl_suburb.city_id_f=tbl_city.city_id ORDER BY tbl_city.city";
    } else {
        if ($cityID_f == "all") {
            $query = "SELECT tbl_suburb.suburb_id as suburb_id, tbl_suburb.city_id_f as city_id_f, tbl_suburb.suburb as suburb, tbl_city.city as city FROM tbl_suburb INNER JOIN tbl_city ON tbl_suburb.city_id_f=tbl_city.city_id ORDER BY tbl_city.city";
        } else {
            $query = "SELECT tbl_suburb.suburb_id as suburb_id, tbl_suburb.city_id_f as city_id_f, tbl_suburb.suburb as suburb, tbl_city.city as city FROM tbl_suburb INNER JOIN tbl_city ON tbl_suburb.city_id_f=tbl_city.city_id WHERE tbl_city.city_id = $cityID_f ORDER BY tbl_city.city";
        }
    }       

   // $query = "SELECT tbl_suburb.suburb_id as suburb_id, tbl_suburb.city_id_f as city_id_f, tbl_suburb.suburb as suburb, tbl_city.city as city FROM tbl_suburb INNER JOIN tbl_city ON tbl_suburb.city_id_f=tbl_city.city_id";
    $result = mysqli_query($link, $query);
    while ($row = mysqli_fetch_array($result)) {
        $tdCityClass = 'suburbCity' . $row['suburb_id'];
        $tdClass = 'suburb' . $row['suburb_id'];

        $output = '<tr>
                    <td>
                     <span class="' . $tdCityClass . '">' . $row['city'] . '</span>
                     <div class="editFields' . $tdCityClass . ' hide">
                     <select class="custom-select dropdown_' . $tdCityClass . '" id="citySuburb">
                     <option disabled>Choose...</option>';
        $citySuburb_query = mysqli_query($link, "SELECT * FROM tbl_city ORDER BY city");
        while ($citySuburb_row = mysqli_fetch_array($citySuburb_query)) {
            extract($citySuburb_row);
            $output .= '<option ';
            if ($row['city_id_f'] == $city_id) {
                $output .= 'selected ';
            }
            $output .= 'value="' . $city_id . '">' . $city . '</option>';
        } //end of while loop
        $output .= '</select>
              </div>
              </td>
              <td>
              <span class="' . $tdClass . '">' . $row['suburb'] . '</span>
              <div class="editFields' . $tdClass . ' hide">
              <div class="input-group">
              <input type="text" class="form-control editInput' . $tdClass . '" value="' . $row['suburb'] . '" name="newValue">
              <div class="input-group-append">
              <button class="btn btn-secondary editBtn" type="submit" name="edit" onclick="updateTable(\'' . $row['suburb_id'] . '\', \'suburb\')">Update</button>
              </div>
              </div>
              </div>
              </td>
              <td class="center">
               <a onclick="hideOriginal(\'' . $tdClass . '\', \'' . $tdCityClass . '\')"><i class="far fa-edit icon' . $tdClass . '"></i></a>
               <a onclick="cancelChange(\'' . $tdClass . '\', \'' . $tdCityClass . '\')" class="cancelLink' . $tdClass . ' hide cancelLink">Cancel</a>
              </td>
              <td class="center"><a onclick="deleteRecord(\'suburb\', \'' . $row['suburb_id'] . '\')"><i class="far fa-trash-alt"></i></a></td>
              </tr>';
        echo $output;
    } //end of while

} else if ($col == 'citySuburb') {
    $output = '';
    $query = "SELECT * FROM tbl_city ORDER BY city";
    $result = mysqli_query($link, $query);
    while ($row = mysqli_fetch_array($result)) {
        extract($row);
        $output .= '<option value="' . $city_id . '">' . $city . '</option>';
    } //end of while loop
    echo $output;

} else {
    $query = "SELECT {$col} FROM {$tbl}";
    $result = mysqli_query($link, $query);
    while ($row = mysqli_fetch_array($result)) {
        $output = '<tr><td>' . $row[$col] . '</td></tr>';
        echo $output;
    } //end of while
}
