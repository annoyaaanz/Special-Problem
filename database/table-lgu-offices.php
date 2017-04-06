<?php
    $connect = mysqli_connect("localhost", "root", "", "wdadviser");

    $table_string = "<div class=\"panel panel-success\">";
    $table_string.= "<div class=\"panel-heading\"><h4>";
    $table_string.="Local Government Unit Offices";
    $table_string.="</h4></div>";
    $table_string.="<div class=\"panel-body\">";
    $table_string.="<div class=\"table-responsive\">";
    $table_string.="    <table width='100%' class='table table-striped table-bordered table-hover'>";
    $table_string.="        <thead>";
    $table_string.="            <tr>";
    $table_string.="                <th class='center-text'>#</th>";
    $table_string.="                <th class='center-text'>Local Government Unit Office</th>";
    $table_string.="                <th colspan='2' class='center-text'>Action</th>";
    $table_string.="            </tr>";
    $table_string.="        </thead>";
    $table_string.="        <tbody>";

        /* for all the lgu offices */
        $retrieve_all_lgu_offices = mysqli_query($connect, "SELECT * FROM lgu_office");
        $number_of_offices = mysqli_num_rows($retrieve_all_lgu_offices);
        $current_no = 0;
        while($office = mysqli_fetch_assoc($retrieve_all_lgu_offices)){
            $lgu_agency = $office['lgu_agency'];
            $lgu_id = $office['lgu_office_id'];
            $current_no = $current_no+1;
            $table_string.="<tr>";
            $table_string.="    <td>$current_no</td>";
            $table_string.="    <td>$lgu_agency</td>";
            $table_string.="    <form action='#' method='POST'>";
            $table_string.="        <td class='center-text'>";
            $table_string.="            <input type='submit' class='btn btn-success btn-outline' name='edit-name-$lgu_id' value='Edit' id='edit-name-$lgu_id' /></td>";
            $table_string.="        <td class='center-text'>";
            $table_string.="                <input type='submit' class='btn btn-danger btn-outline' name='remove-name-$lgu_id' value='Remove' id='remove-name-$lgu_id' onClick=\"return rem = confirm('Do you really want to remove $lgu_agency?')\"";
            $table_string.="/></td>";
            $table_string.="        <input type='hidden' name='remove-lgu-id' id='remove-lgu-id' value='$lgu_id'/>";
            $table_string.="    </form>";
            $table_string.="</tr>";
        }
    $table_string.="        </tbody>";
    $table_string.="</table>";
    $table_string.="</div>";
    $table_string.="</div>";
    $table_string.="</div>";
    echo json_encode($table_string);