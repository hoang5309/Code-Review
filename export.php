<?php
ini_set("memory_limit", -1);
gc_enable();
gc_collect_cycles();
require("connection.php");
session_start();
$county = $_SESSION["county"];
//Set unset _POST array data
if(!isset($_POST["sch_code"])){
	$_POST["sch_code"] = array("ignore");
}
if(!isset($_POST["swis"])){
	$_POST["swis"] = array("ignore");
}
if(!isset($_POST["loc_zip"])){
	$_POST["loc_zip"] = array("ignore");
}
if(!isset($_POST["land_type1"])){
	$_POST["land_type1"] = array("ignore");
}
if(!isset($_POST["waterfront_type"])){
	$_POST["waterfront_type"] = array("ignore");
}
if(!isset($_POST["soil_rating"])){
	$_POST["soil_rating"] = array("ignore");
}
if(!isset($_POST["agricultural_properties1"])){
	$_POST["agricultural_properties1"] = array("ignore");
}
if(!isset($_POST["residential_properties1"])){
	$_POST["residential_properties1"] = array("ignore");
}
if(!isset($_POST["vacant_properties1"])){
	$_POST["vacant_properties1"] = array("ignore");
}
if(!isset($_POST["commercial_properties1"])){
	$_POST["commercial_properties1"] = array("ignore");
}
if(!isset($_POST["re_properties1"])){
	$_POST["re_properties1"] = array("ignore");
}
if(!isset($_POST["community_properties1"])){
	$_POST["community_properties1"] = array("ignore");
}
if(!isset($_POST["manufacturing_properties1"])){
	$_POST["manufacturing_properties1"] = array("ignore");
}
if(!isset($_POST["infrastructure_properties1"])){
	$_POST["infrastructure_properties1"] = array("ignore");
}
if(!isset($_POST["stateowned_properties1"])){
	$_POST["stateowned_properties1"] = array("ignore");
}
if(!isset($_POST["zoning_cd1"])){
	$_POST["zoning_cd1"] = array("ignore");
}
if(!isset($_POST["sewer_type1"])){
	$_POST["sewer_type1"] = array("ignore");
}
if(!isset($_POST["nbhd_rating1"])){
	$_POST["nbhd_rating1"] = array("ignore");
}
if(!isset($_POST["heat_type"])){
	$_POST["heat_type"] = array("ignore");
}
if(!isset($_POST["fuel_type"])){
	$_POST["fuel_type"] = array("ignore");
}
if(!isset($_POST["overall_cond"])){
	$_POST["overall_cond"] = array("ignore");
}
if(!isset($_POST["ext_wall_material"])){
	$_POST["ext_wall_material"] = array("ignore");
}
if(!isset($_POST["used_as_cd"])){
	$_POST["used_as_cd"] = array("ignore");
}
if(!isset($_POST["central_air"])){
	$_POST["central_air"] = "";
}
if(!isset($_POST["air_conditioning_pct"])){
	$_POST["air_conditioning_pct"] = "";
}
if(!isset($_POST["structure_cd"])){
	$_POST["structure_cd"] = array("ignore");
}
//Input POST data into section arrays
$section1_array = array($_POST["total_av_min"], $_POST["total_av_max"], $_POST["land_av_min"], $_POST["land_av_max"], $_POST["sch_code"]);
$section2_array = array($_POST["swis"], $_POST["loc_zip"]);
$section3_array = array($_POST["acres_min"], $_POST["acres_max"], $_POST["sqft_min"], $_POST["sqft_max"], $_POST["land_value_min"], $_POST["land_value_max"], $_POST["wf_feet_min"], $_POST["wf_feet_max"], $_POST["land_type1"], $_POST["waterfront_type"], $_POST["soil_rating"]);
$section4_array = array($_POST["agricultural_properties1"], $_POST["residential_properties1"], $_POST["vacant_properties1"], $_POST["commercial_properties1"], $_POST["re_properties1"], $_POST["community_properties1"], $_POST["manufacturing_properties1"], $_POST["infrastructure_properties1"], $_POST["stateowned_properties1"], $_POST["zoning_cd1"], $_POST["sewer_type1"], $_POST["nbhd_rating1"]);
$section5_array = array($_POST["central_air"], $_POST["yr_built_min"], $_POST["yr_built_max"], $_POST["yr_remodeled_min"], $_POST["yr_remodeled_max"], $_POST["heat_type"], $_POST["fuel_type"], $_POST["overall_cond"], $_POST["ext_wall_material"], $_POST["air_conditioning_pct"], $_POST["used_as_cd"], $_POST["yr_blt_min"], $_POST["yr_blt_max"]);
$section6_array = array($_POST["structure_cd"]);

//Check which sections are set
$used_section1 = FALSE;
$used_section2 = FALSE;
$used_section3 = FALSE;
$used_section4 = FALSE;
$used_section5 = FALSE;
$used_section6 = FALSE;
for($i = 0; $i < count($section1_array); $i++){
	if(is_array($section1_array[$i])){
		if($section1_array[$i][0] != "ignore" && count($section1_array[$i]) > 0){
			$used_section1 = TRUE;
			break;
		}
	}
	else if($section1_array[$i] != ""){
		$used_section1 = TRUE;
		break;
	}
}
for($i = 0; $i < count($section2_array); $i++){
	if($section2_array[$i][0] != "ignore" && count($section2_array[$i]) > 0){
		$used_section2 = TRUE;
		break;
	}
}
for($i = 0; $i < count($section3_array); $i++){
	if(is_array($section3_array[$i])){
		if($section3_array[$i][0] != "ignore" && count($section3_array[$i]) > 0){
			$used_section3 = TRUE;
			break;
		}
	}
	else{
		if($section3_array[$i] != ""){
			$used_section3 = TRUE;
			break;
		}
	}
}
for($i = 0; $i < count($section4_array); $i++){
	if($section4_array[$i][0] != "ignore"){
		$used_section4 = TRUE;
		break;
	}
}
for($i = 0; $i < count($section5_array); $i++){
	if(is_array($section5_array[$i])){
		if($section5_array[$i][0] != "ignore" && count($section5_array[$i]) > 0){
			$used_section5 = TRUE;
			break;
		}
	}
	else if($section5_array[$i] != ""){
		$used_section5 = TRUE;
		break;
	}
}
for($i = 0; $i < count($section6_array); $i++){
	if($section6_array[$i] != ""){
		if($section6_array[$i][0] != "ignore" && count($section6_array[$i]) > 0){
			$used_section6 = TRUE;
			break;
		}
	}
}
//die($used_section5);
//Run Separate lists while retrieving muni_code, parcel_id
//OWNER INFORMATION COMES FIRST
//HEADERS INCLUDED FOR CSV UPLOAD
$assessment_info = array();
$parcel_info = array();
$land_info = array();
$site_info = array();
$res_building_info = array();
$improvement_info = array();
$owner_information = array();
$owner_keys = array();
$all_keys = array();
$headers = array();

//STEP 1: Owner information
$table_owner = $county . "_owner";
$sql_owner = "SELECT muni_code, owner_id, parcel_id, owner_first_name, owner_init_name, owner_last_name, secondary_name, concatenated_address_1, concatenated_address_2, mail_city, owner_mail_state, mail_zip, crrt, dp3,  mail_country FROM $table_owner GROUP BY owner_first_name, owner_init_name, owner_last_name, concatenated_address_1, mail_city, owner_mail_state, mail_zip, mail_country ORDER BY owner_last_name";
$result_owner = mysqli_query($link, $sql_owner) or die("error");
while($row = $result_owner->fetch_assoc()){
	$id = $row["muni_code"] . "_" . $row["parcel_id"] . "_" . $row["owner_id"];
	//echo $id. "</br>";
	$owner_information[$id] = array($row["owner_id"], $row["owner_first_name"], $row["owner_init_name"], $row["owner_last_name"], $row["secondary_name"], $row["concatenated_address_1"], $row["concatenated_address_2"], $row["mail_city"], $row["owner_mail_state"], $row["mail_zip"], $row["crrt"], $row["dp3"],  $row["mail_country"]);
	array_push($owner_keys, $id);
}
array_push($headers, "Owner ID", "First Name", "Middle Name", "Last Name", "Suffix", "Secondary Name", "Address Line 1", "Address Line 2", "City", "State", "ZIP", "CRRT", "DP3",  "Country");
//STEP 2: Upload data for all set sections
if($used_section1 == TRUE){
	$used_total_av = FALSE;
	$used_land_av = FALSE;
	$used_sch_code = FALSE;
	$total_av_min = $section1_array[0];
	$total_av_max = $section1_array[1];
	$land_av_min = $section1_array[2];
	$land_av_max = $section1_array[3];
	$table_assessment = $county . "_assessment";
	$sql_assessment = "SELECT muni_code, parcel_id, land_av, total_av, sch_code, sch_code_def FROM $table_assessment";
	//total av check in assessment
	if($total_av_min != "" && $total_av_max != ""){
		$sql_assessment .= " WHERE (total_av >= $total_av_min AND total_av <= $total_av_max)";
		$used_total_av = TRUE;
	}
	else if($total_av_min == "" && $total_av_max != ""){
		$sql_assessment .= " WHERE (total_av <= $total_av_max)";
		$used_total_av = TRUE;
	}
	else if($total_av_min != "" && $total_av_max == ""){
		$sql_assessment .= " WHERE (total_av >= $total_av_min)";
		$used_total_av = TRUE;
	}
	//land av check in assessment
	if($land_av_min != "" && $land_av_max != ""){
		if($used_total_av == TRUE){
			$sql_assessment .= " AND (land_av >= $land_av_min AND land_av <= $land_av_max)";
		}
		else{
			$sql_assessment .= " WHERE (land_av >= $land_av_min AND land_av <= $land_av_max)";
		}
		$used_land_av = TRUE;
	}
	else if($land_av_min == "" && $land_av_max != ""){
		if($used_total_av == TRUE){
			$sql_assessment .= " AND (land_av <= $land_av_max)";
		}
		else{
			$sql_assessment .= " WHERE (land_av <= $land_av_max)";
		}
		$used_land_av = TRUE;
	}
	else if($land_av_min != "" && $land_av_max == ""){
		if($used_total_av == TRUE){
			$sql_assessment .= " AND (land_av >= $land_av_min)";
		}
		else{
			$sql_assessment .= " WHERE (land_av >= $land_av_min)";
		}
		$used_land_av = TRUE;
	}
	
	//check school codes
	if($section1_array[4][0] != "ignore"){
		for($i = 0; $i < count($section1_array[4]); $i++){
			$sch_code = $section1_array[4][$i];
			if($i == 0 && ($used_land_av == TRUE || $used_total_av == TRUE)){
				$sql_assessment .= " AND (sch_code = '$sch_code'";
			}
			else if($i == 0 && $used_land_av == FALSE && $used_total_av == FALSE){
				$sql_assessment .= " WHERE (sch_code = '$sch_code'";
			}
			else{
				$sql_assessment .= " OR sch_code = '$sch_code'";
			}
		}
		$sql_assessment .= ")";
		$used_sch_code = TRUE;
	}
	$result_assessment = mysqli_query($link, $sql_assessment);
	$assessment_keys = array();
	while($row = $result_assessment->fetch_assoc()){
		$id = $row["muni_code"] . "_" . $row["parcel_id"];
		$assessment_info[$id] = array("", $row["muni_code"], $row["parcel_id"], $row["total_av"], $row["land_av"], $row["sch_code_def"]);
		$assessment_keys[$id] = $id;
	}
	array_push($all_keys, $assessment_keys);
	array_push($headers, "--", "Muni Code Assessment", "Parcel ID Assessment", "Total Assessment", "Land Assessment", "School Code");
}

if($used_section2 == TRUE){
	$used_swis_code = FALSE;
	$used_zip_code = FALSE;
	$table_parcel = $county . "_parcel";
	$sql_parcel = "SELECT muni_code, parcel_id, loc_muni_name, loc_prefix_dir, loc_st_nbr, loc_st_name, loc_mail_st_suff, loc_post_dir, loc_unit_name, loc_unit_nbr, loc_zip FROM $table_parcel";
	$zip = $section2_array[1];
	$parcel_info = array();
	//swis codes in parcel
	if($section2_array[0][0] != "ignore"){
		for($i = 0; $i < count($section2_array[0]); $i++){
			$muni_code = $section2_array[0][$i];
			if(strlen($muni_code) == 6){
				$swis_co = substr($muni_code, 0, 2);
				//echo $swis_co . " ";
				$swis_muni = substr($muni_code, 2, 2);
				//echo $swis_muni . " ";
				$swis_vlg = substr($muni_code, 4, 2);
				//echo $swis_vlg . " ";
			}
			if($i == 0){
				/*
				if($swis_vlg == "00"){
					$swis_vlg = "0";
				}*/
				$sql_parcel .= " WHERE ((swis_co = '$swis_co' AND swis_muni = '$swis_muni' AND swis_vlg = '$swis_vlg')";
			}
			else{
				$sql_parcel .= " OR (swis_co = '$swis_co' AND swis_muni = '$swis_muni' AND swis_vlg = '$swis_vlg')";
			}
		}
		$sql_parcel .= ")";
		$used_swis_code = TRUE;
	}
	//zip codes in parcel
	if($section2_array[1][0] != "ignore"){
		for($i = 0; $i < count($section2_array[1]); $i++){
			$loc_zip = $section2_array[1][$i];
			if($i == 0 && $used_swis_code == TRUE){
				$sql_parcel .= " AND (loc_zip = '$loc_zip'";
			}
			else if($i == 0 && $used_swis_code == FALSE){
				$sql_parcel .= " WHERE (loc_zip = '$loc_zip'";
			}
			else{
				$sql_parcel .= " OR loc_zip = '$loc_zip'";
			}
		}
		$sql_parcel .= ")";
		$used_zip_code = TRUE;
	}
	$result_parcel = mysqli_query($link, $sql_parcel);
	//echo $sql_parcel;
	$parcel_keys = array();
	while($row = $result_parcel->fetch_assoc()){
		$id = $row["muni_code"] . "_" . $row["parcel_id"];
		$physical_address = $row["loc_st_nbr"] . " " . $row["loc_prefix_dir"] . " " . $row["loc_st_name"] . " " . $row["loc_mail_st_suff"] . " " . $row["loc_post_dir"] . " " . $row["loc_unit_name"] . " " . $row["loc_unit_nbr"];
		$physical_address = trim($physical_address);
		$parcel_info[$id] = array("", $row["parcel_id"], $row["loc_zip"], $row["loc_muni_name"], $physical_address);
		$parcel_keys[$id] = $id;
		//echo $row["parcel_id"]. "</br>";
	}
	unset($result_parcel);
	echo count($parcel_keys);
	array_push($all_keys, $parcel_keys);
	array_push($headers, "--", "Muni Code Parcel", "Parcel ID Parcel", "Parcel ZIP Code", "Physical Address");
}

if($used_section3 == TRUE){
	$table_land = $county . "_land";
	$sql_land = "SELECT muni_code, parcel_id, land_nbr, site_nbr, acres, sqft, land_value, wf_feet, land_type, waterfront_type, soil_rating FROM $table_land";
	$used_acres = FALSE;
	$used_sqft = FALSE;
	$used_land_value = FALSE;
	$used_wf_feet = FALSE;
	$used_land_type = FALSE;
	$used_waterfront_type = FALSE;
	$used_soil_rating = FALSE;
	//Check for acres of land
	//-----------------------
	$acres_min = $section3_array[0];
	$acres_max = $section3_array[1];
	if($acres_min != "" && $acres_max != ""){
		$sql_land .= " WHERE (acres >= $acres_min AND acres <= $acres_max)";
		$used_acres = TRUE;
	}
	else if($acres_min == "" && $acres_max != ""){
		$sql_land .= " WHERE (acres <= $acres_max)";
		$used_acres = TRUE;
	}
	else if($acres_min != "" && $acres_max == ""){
		$sql_land .= " WHERE (acres >= $acres_min)";
		$used_acres = TRUE;
	}
	//-----------------------
	//Check for sqft of land
	//-----------------------
	$sqft_min = $section3_array[2];
	$sqft_max = $section3_array[3];
	if($used_acres == FALSE){
		if($sqft_min != "" && $sqft_max != ""){
			$sql_land .= " WHERE (sqft >= $sqft_min AND sqft <= $sqft_max)";
			$used_sqft = TRUE;
		}
		else if($sqft_min == "" && $sqft_max != ""){
			$sql_land .= " WHERE (sqft <= $sqft_max)";
			$used_sqft = TRUE;
		}
		else if($sqft_min != "" && $sqft_max == ""){
			$sql_land .= " WHERE (sqft >= $sqft_min)";
			$used_sqft = TRUE;
		}
	}
	else{
		if($sqft_min != "" && $sqft_max != ""){
			$sql_land .= " AND (sqft >= $sqft_min AND sqft <= $sqft_max)";
			$used_sqft = TRUE;
		}
		else if($sqft_min == "" && $sqft_max != ""){
			$sql_land .= " AND (sqft <= $sqft_max)";
			$used_sqft = TRUE;
		}
		else if($sqft_min != "" && $sqft_max == ""){
			$sql_land .= " AND (sqft >= $sqft_min)";
			$used_sqft = TRUE;
		}
	}
	//-----------------------
	//Check for Land Value of Land
	//-----------------------
	$land_value_min = $section3_array[4];
	$land_value_max = $section3_array[5];
	if($used_acres == FALSE && $used_sqft == FALSE){
		if($land_value_min != "" && $land_value_max != ""){
			$sql_land .= " WHERE (land_value >= $land_value_min AND land_value <= $land_value_max)";
			$used_land_value = TRUE;
		}
		else if($land_value_min == "" && $land_value_max != ""){
			$sql_land .= " WHERE (land_value <= $land_value_max)";
			$used_land_value = TRUE;
		}
		else if($land_value_min != "" && $land_value_max == ""){
			$sql_land .= " WHERE (land_value >= $land_value_min)";
			$used_land_value = TRUE;
		}
	}
	else{
		if($land_value_min != "" && $land_value_max != ""){
			$sql_land .= " AND (land_value >= $land_value_min AND land_value <= $land_value_max)";
			$used_land_value = TRUE;
		}
		else if($land_value_min == "" && $land_value_max != ""){
			$sql_land .= " AND (land_value <= $land_value_max)";
			$used_land_value = TRUE;
		}
		else if($land_value_min != "" && $land_value_max == ""){
			$sql_land .= " AND (land_value >= $land_value_min)";
			$used_land_value = TRUE;
		}
	}
	//-----------------------
	//Check for Feet from Waterfront of Land
	//-----------------------
	$wf_feet_min = $section3_array[6];
	$wf_feet_max = $section3_array[7];
	if($used_acres == FALSE && $used_sqft == FALSE && $used_land_value == FALSE){
		if($wf_feet_min != "" && $wf_feet_max != ""){
			$sql_land .= " WHERE (wf_feet >= $wf_feet_min AND wf_feet <= $wf_feet_max)";
			$used_wf_feet = TRUE;
		}
		else if($wf_feet_min == "" && $wf_feet_max != ""){
			$sql_land .= " WHERE (wf_feet <= $wf_feet_max)";
			$used_wf_feet = TRUE;
		}
		else if($wf_feet_min != "" && $wf_feet_max == ""){
			$sql_land .= " WHERE (wf_feet >= $wf_feet_min)";
			$used_wf_feet = TRUE;
		}
	}
	else{
		if($wf_feet_min != "" && $wf_feet_max != ""){
			$sql_land .= " AND (wf_feet >= $wf_feet_min AND wf_feet <= $wf_feet_max)";
			$used_wf_feet = TRUE;
		}
		else if($wf_feet_min == "" && $wf_feet_max != ""){
			$sql_land .= " AND (wf_feet <= $wf_feet_max)";
			$used_wf_feet = TRUE;
		}
		else if($wf_feet_min != "" && $wf_feet_max == ""){
			$sql_land .= " AND (wf_feet >= $wf_feet_min)";
			$used_wf_feet = TRUE;
		}
	}
	//-----------------------
	//Check for Land Type of Land
	//-----------------------
	if($used_acres == FALSE && $used_sqft == FALSE && $used_land_value == FALSE && $used_wf_feet == FALSE){
		if($section3_array[8][0] != "ignore"){
			for($i = 0; $i < count($section3_array[8]); $i++){
				if($i == 0){
					$type = $section3_array[8][$i];
					$sql_land .= " WHERE (land_type = $type";
				}
				else{
					$type = $section3_array[8][$i];
					$sql_land .= " OR land_type = $type";
				}
			}
			$sql_land .= ")";
			$used_land_type = TRUE;
		}
	}
	else{
		if($section3_array[8][0] != "ignore"){
			for($i = 0; $i < count($section3_array[8]); $i++){
				if($i == 0){
					$type = $section3_array[8][$i];
					$sql_land .= " AND (land_type = $type";
				}
				else{
					$type = $section3_array[8][$i];
					$sql_land .= " OR land_type = $type";
				}
			}
			$sql_land .= ")";
			$used_land_type = TRUE;
		}
	}
	//-----------------------
	//Check for Waterfront Type of Land
	//-----------------------
	if($used_acres == FALSE && $used_sqft == FALSE && $used_land_value == FALSE && $used_wf_feet == FALSE && $used_land_type == FALSE){
		if($section3_array[9][0] != "ignore"){
			for($i = 0; $i < count($section3_array[9]); $i++){
				if($i == 0){
					$type = $section3_array[9][$i];
					$sql_land .= " WHERE (waterfront_type = $type";
				}
				else{
					$type = $section3_array[9][$i];
					$sql_land .= " OR waterfront_type = $type";
				}
			}
			$sql_land .= ")";
			$used_waterfront_type = TRUE;
		}
	}
	else{
		if($section3_array[9][0] != "ignore"){
			for($i = 0; $i < count($section3_array[9]); $i++){
				if($i == 0){
					$type = $section3_array[9][$i];
					$sql_land .= " AND (waterfront_type = $type";
				}
				else{
					$type = $section3_array[9][$i];
					$sql_land .= " OR waterfront_type = $type";
				}
			}
			$sql_land .= ")";
			$used_waterfront_type = TRUE;
		}
	}
	//-----------------------
	//Check for Soil Rating of Land
	//-----------------------
	$soil_rating = $section3_array[10];
	if($used_acres == FALSE && $used_sqft == FALSE && $used_land_value == FALSE && $used_wf_feet == FALSE && $used_land_type == FALSE && $used_waterfront_type == FALSE){
		if($section3_array[10][0] != "ignore"){
			for($i = 0; $i < count($section3_array[10]); $i++){
				if($i == 0){
					$type = $section3_array[10][$i];
					$sql_land .= " WHERE (soil_rating = $type";
				}
				else{
					$type = $section3_array[10][$i];
					$sql_land .= " OR soil_rating = $type";
				}
			}
			$sql_land .= ")";
			$used_soil_rating = TRUE;
		}
	}
	else{
		if($section3_array[10][0] != "ignore"){
			for($i = 0; $i < count($section3_array[10]); $i++){
				if($i == 0){
					$type = $section3_array[10][$i];
					$sql_land .= " AND (soil_rating = $type";
				}
				else{
					$type = $section3_array[10][$i];
					$sql_land .= " OR soil_rating = $type";
				}
			}
			$sql_land .= ")";
			$used_soil_rating = TRUE;
		}
	}
	$result_land = mysqli_query($link, $sql_land);
	$land_keys = array();
	while($row = $result_land->fetch_assoc()){
		$id = $row["muni_code"] . "_" . $row["parcel_id"];
		$land_info[$id] = array("", $row["muni_code"], $row["parcel_id"], $row["land_nbr"], $row["site_nbr"], $row["acres"], $row["sqft"], $row["land_value"], $row["wf_feet"], $row["land_type"], $row["waterfront_type"], $row["soil_rating"]);
		$land_keys[$id] = $id;
	}
	array_push($all_keys, $land_keys);
	array_push($headers, "--", "Muni Code Land", "Parcel ID Land", "Land Number", "Site Number", "Acres", "Square Ft.", "Land Value", "Feet From Waterfront", "Land Type", "Waterfront Type", "Soil Rating");
}

if($used_section4 == TRUE){
	$table_site = $county . "_site";
	$sql_site = "SELECT muni_code, parcel_id, site_nbr, prop_class, zoning_cd, sewer_type, nbhd_rating FROM $table_site";
	$used_prop_class = FALSE;
	$used_zoning_cd = FALSE;
	$used_sewer_type = FALSE;
	$used_nbhd_rating = FALSE;
	if($section4_array[0][0] != "ignore" || $section4_array[1][0] != "ignore" || $section4_array[2][0] != "ignore" || $section4_array[3][0] != "ignore" || $section4_array[4][0] != "ignore" || $section4_array[5][0] != "ignore" || $section4_array[6][0] != "ignore" || $section4_array[7][0] != "ignore" || $section4_array[8][0] != "ignore"){
		$sql_site .= " WHERE (";
		$used_first = FALSE;
		for($i = 0; $i < 9; $i++){
			if($section4_array[$i][0] != "ignore"){
				for($ii = 0; $ii < count($section4_array[$i]); $ii++){
					$code = $section4_array[$i][$ii];
					if($used_first == FALSE){
						$sql_site .= "prop_class = '$code'";
						$used_first = TRUE;
					}
					else{
						$sql_site .= " OR prop_class = '$code'";
					}
				}
			}
		}
		$sql_site .= ")";
		$used_prop_class = TRUE;
	}
	if($section4_array[9][0] != "ignore"){
		for($i = 0; $i < count($section4_array[9]); $i++){
			$code = $section4_array[9][$i];
			if($i == 0 && $used_prop_class == TRUE){
				$sql_site .= " AND (zoning_cd = '$code'";
			}
			else if($i == 0 && $used_prop_class == FALSE){
				$sql_site .= " WHERE (zoning_cd = '$code'";
			}
			else{
				$sql_site .= " OR zoning_cd = '$code'";
			}
		}
		$sql_site .= ")";
		$used_zoning_cd = TRUE;
	}
	if($section4_array[10][0] != "ignore"){
		for($i = 0; $i < count($section4_array[10]); $i++){
			$code = $section4_array[10][$i];
			if($i == 0 && ($used_prop_class == TRUE || $used_zoning_cd == TRUE)){
				$sql_site .= " AND (sewer_type = '$code'";
			}
			else if($i == 0 && $used_prop_class == FALSE && $used_zoning_cd == FALSE){
				$sql_site .= " WHERE (sewer_type = '$code'";
			}
			else{
				$sql_site .= " OR sewer_type = '$code'";
			}
		}
		$sql_site .= ")";
		$used_sewer_type = TRUE;
	}
	if($section4_array[11][0] != "ignore"){
		for($i = 0; $i < count($section4_array[11]); $i++){
			$code = $section4_array[11][$i];
			if($i == 0 && ($used_prop_class == TRUE || $used_zoning_cd == TRUE) || $used_sewer_type == TRUE){
				$sql_site .= " AND (nbhd_rating = '$code'";
			}
			else if($i == 0 && $used_prop_class == FALSE && $used_zoning_cd == FALSE && $used_sewer_type == FALSE){
				$sql_site .= " WHERE (nbhd_rating = '$code'";
			}
			else{
				$sql_site .= " OR nbhd_rating = '$code'";
			}
		}
		$sql_site .= ")";
		$used_nbhd_rating = TRUE;
	}
	$result_site = mysqli_query($link, $sql_site);
	$site_keys = array();
	while($row = $result_site->fetch_assoc()){
		$id = $row["muni_code"] . "_" . $row["parcel_id"];
		$site_info[$id] = array("", $row["muni_code"], $row["parcel_id"], $row["site_nbr"], $row["prop_class"], $row["zoning_cd"], $row["sewer_type"], $row["nbhd_rating"]);
		$site_keys[$id] = $id;
	}
	array_push($all_keys, $site_keys);
	array_push($headers, "--", "Muni Code Site", "Parcel ID Site", "Site Number", "Property Class", "Zoning Code", "Sewer Type", "Neighborhood Rating");
}
if($used_section5 == TRUE){
	$table_res_bldg = $county . "_res_bldg";
	$sql_res_bldg = "SELECT muni_code, parcel_id, site_nbr, central_air, yr_blt, yr_remodeled, heat_type, fuel_type, overall_cond, ext_wall_material FROM $table_res_bldg";
	$used_central_air = FALSE;
	$used_yr_blt = FALSE;
	$used_yr_remodeled = FALSE;
	$used_heat_type = FALSE;
	$used_fuel_type = FALSE;
	$used_overall_cond = FALSE;
	$used_ext_wall_material = FALSE;
	if($section5_array[0] != ""){
		$sql_res_bldg .= " WHERE central_air = '1'";
		$used_central_air = TRUE;
	}
	
	$yr_built_min = $section5_array[1];
	$yr_built_max = $section5_array[2];
	
	if($used_central_air == FALSE){
		if($yr_built_min != "" && $yr_built_max != ""){
			$sql_res_bldg .= " WHERE (yr_blt >= $yr_built_min AND yr_blt <= $yr_built_max)";
			$used_yr_blt = TRUE;
		}
		else if($yr_built_min == "" && $yr_built_max != ""){
			$sql_res_bldg .= " WHERE (yr_blt <= $yr_built_max)";
			$used_yr_blt = TRUE;
		}
		else if($yr_built_min != "" && $yr_built_max == ""){
			$sql_res_bldg .= " WHERE (yr_blt >= $yr_built_min)";
			$used_yr_blt = TRUE;
		}
	}
	else{
		if($yr_built_min != "" && $yr_built_max != ""){
			$sql_res_bldg .= " AND (yr_blt >= $yr_built_min AND yr_blt <= $yr_built_max)";
			$used_yr_blt = TRUE;
		}
		else if($yr_built_min == "" && $yr_built_max != ""){
			$sql_res_bldg .= " AND (yr_blt <= $yr_built_max)";
			$used_yr_blt = TRUE;
		}
		else if($yr_built_min != "" && $yr_built_max == ""){
			$sql_res_bldg .= " AND (yr_blt >= $yr_built_min)";
			$used_yr_blt = TRUE;
		}
	}
	
	$yr_remodeled_min = $section5_array[3];
	$yr_remodeled_max = $section5_array[4];
	
	if($used_central_air == FALSE && $used_yr_blt == FALSE){
		if($yr_remodeled_min != "" && $yr_remodeled_max != ""){
			$sql_res_bldg .= " WHERE (yr_remodeled >= $yr_remodeled_min AND yr_blt <= $yr_remodeled_max)";
			$used_yr_remodeled = TRUE;
		}
		else if($yr_remodeled_min == "" && $yr_remodeled_max != ""){
			$sql_res_bldg .= " WHERE (yr_remodeled <= $yr_remodeled_max)";
			$used_yr_remodeled = TRUE;
		}
		else if($yr_remodeled_min != "" && $yr_remodeled_max == ""){
			$sql_res_bldg .= " WHERE (yr_remodeled >= $yr_remodeled_min)";
			$used_yr_remodeled = TRUE;
		}
	}
	else{
		if($yr_remodeled_min != "" && $yr_remodeled_max != ""){
			$sql_res_bldg .= " AND (yr_remodeled >= $yr_remodeled_min AND yr_blt <= $yr_remodeled_max)";
			$used_yr_remodeled = TRUE;
		}
		else if($yr_remodeled_min == "" && $yr_remodeled_max != ""){
			$sql_res_bldg .= " AND (yr_remodeled <= $yr_remodeled_max)";
			$used_yr_remodeled = TRUE;
		}
		else if($yr_remodeled_min != "" && $yr_remodeled_max == ""){
			$sql_res_bldg .= " AND (yr_remodeled >= $yr_remodeled_min)";
			$used_yr_remodeled = TRUE;
		}
	}
	
	$result_res_bldg = mysqli_query($link, $sql_res_bldg);
	$res_bldg_keys = array();
	while($row = $result_res_bldg->fetch_assoc()){
		$id = $row["muni_code"] . "_" . $row["parcel_id"];
		if($row["central_air"] == '1'){
			$row["central_air"] = "Yes";
		}
		else{
			$row["central_air"] = "No";
		}
		$res_building_info[$id] = array("", $row["muni_code"], $row["parcel_id"], $row["site_nbr"], $row["central_air"], $row["yr_blt"], $row["yr_remodeled"], $row["heat_type"], $row["fuel_type"], $row["overall_cond"], $row["ext_wall_material"]);
		$res_bldg_keys[$id] = $id;
	}
	array_push($all_keys, $res_bldg_keys);
	array_push($headers, "--", "Muni Code Res. Bulding", "Parcel ID Res. Building", "Site Number", "Air Conditioning", "Year Built", "Year Re-modeled", "Heat Type", "Fuel Type", "Overall Condition", "Exterior Wall Material");
}

if($used_section6 == TRUE){
	$table_improvement = $county . "_improvement";
	$sql_improvement = "SELECT muni_code, parcel_id, structure_cd FROM $table_improvement";
	$used_structure_cd = FALSE;
	for($i = 0; $i < count($section6_array[0]); $i++){
		$structure_cd = $section6_array[0][$i];
		if($i == 0){
			$sql_improvement .= " WHERE (structure_cd = '$structure_cd'";
		}
		else{
			$sql_improvement .= " OR structure_cd = '$structure_cd'";
		}
	}
	$sql_improvement .= ")";
	$used_structure_cd = TRUE;
	
	$result_improvement = mysqli_query($link, $sql_improvement);
	$improvement_keys = array();
	while($row = $result_improvement->fetch_assoc()){
		$id = $row["muni_code"] . "_" . $row["parcel_id"];
		$improvement_info[$id] = array("", $row["structure_cd"]);
		$improvement_keys[$id] = $id;
	}
	array_push($all_keys, $improvement_keys);
	array_push($headers, "--", "Structure Code");
}
//Intersect All Keys
if(count($all_keys) > 1){
	$all_keys = call_user_func_array('array_intersect', $all_keys);
}
else if(count($all_keys) == 1){
	//echo count($all_keys);
	$all_keys = $all_keys[0];
}

//Combine data into one information array
$all_info = array();
for($i = 0; $i < count($owner_keys); $i++){
	$split_owner_key = explode("_", $owner_keys[$i]);
	$id = $split_owner_key[0] . "_" . $split_owner_key[1];
	$temp_array = array();
	$suffix = "";
	//echo $id. "</br>"; 
	if(isset($all_keys[$id])){
		//echo "hi1";
		array_push($temp_array, $owner_information[$owner_keys[$i]][0], $owner_information[$owner_keys[$i]][1], $owner_information[$owner_keys[$i]][2], $owner_information[$owner_keys[$i]][3], $suffix, $owner_information[$owner_keys[$i]][4], $owner_information[$owner_keys[$i]][5], $owner_information[$owner_keys[$i]][6], $owner_information[$owner_keys[$i]][7], $owner_information[$owner_keys[$i]][8], $owner_information[$owner_keys[$i]][9], $owner_information[$owner_keys[$i]][10], $owner_information[$owner_keys[$i]][11], $owner_information[$owner_keys[$i]][12]);

		if(isset($assessment_info[$id])){
			array_push($temp_array, $assessment_info[$id][0], $assessment_info[$id][1], $assessment_info[$id][2], $assessment_info[$id][3], $assessment_info[$id][4], $assessment_info[$id][5]);
		}
		if(isset($parcel_info[$id])){
			//echo "hi2";
			array_push($temp_array, $parcel_info[$id][0], $parcel_info[$id][3], $parcel_info[$id][1], $parcel_info[$id][2], $parcel_info[$id][4]);
		}
		if(isset($land_info[$id])){
			array_push($temp_array, $land_info[$id][0], $land_info[$id][1], $land_info[$id][2], $land_info[$id][3], $land_info[$id][4], $land_info[$id][5], $land_info[$id][6], $land_info[$id][7], $land_info[$id][8], $land_info[$id][9], $land_info[$id][10], $land_info[$id][11]);
		}
		if(isset($site_info[$id])){
			array_push($temp_array, $site_info[$id][0], $site_info[$id][1], $site_info[$id][2], $site_info[$id][3], $site_info[$id][4], $site_info[$id][5], $site_info[$id][6], $site_info[$id][7]);
		}
		if(isset($res_building_info[$id])){
			array_push($temp_array, $res_building_info[$id][0], $res_building_info[$id][1], $res_building_info[$id][2], $res_building_info[$id][3], $res_building_info[$id][4], $res_building_info[$id][5], $res_building_info[$id][6], $res_building_info[$id][7], $res_building_info[$id][8], $res_building_info[$id][9]);
		}
		if(isset($improvement_info[$id])){
			array_push($temp_array, $improvement_info[$id][0], $improvement_info[$id][1]);
		}
		array_push($all_info, $temp_array);
		unset($temp_array);
	}
}
//echo json_encode($all_info);
//Household
$count = 0;
$index_at = 0;
$previous_name = $all_info[$index_at][3];
if(count($all_info) > 1){
	for($i = 1; $i < count($all_info); $i++){
		if($all_info[$i][3] == $previous_name && $all_info[$i][5] == $all_info[$index_at][5] && $all_info[$i][6] == $all_info[$index_at][6] && $all_info[$i][7] == $all_info[$index_at][7]){
			$all_info[$i][0] = "---";
			$all_info[$i][1] = "---";
			$all_info[$i][2] = "---";
			$all_info[$i][3] = "---";
			$all_info[$i][4] = "---";
			if($count == 0){
				$previous_name = $all_info[$index_at][3];
				if(strpos($all_info[$index_at][3], "Llc") === false){
					$all_info[$index_at][3] = $all_info[$index_at][3];
				}
				$all_info[$index_at][0] = "";
				$all_info[$index_at][1] = "The";
				$all_info[$index_at][2] = "";
				$all_info[$index_at][4] = "Family";
			}
			$count++;
		}
		else{
			$index_at = $i;
			$previous_name = $all_info[$index_at][3];
			$count = 0;
		}
	}
}
//EXPORT
$handle = fopen("file.txt", "w");
fputcsv($handle, $headers);
for($i = 0; $i < count($all_info); $i++){
	for($ii = 0; $ii < count($all_info[$i]); $ii++){
		$all_info[$i][$ii] = rtrim($all_info[$i][$ii]);
	}
	fputcsv($handle, $all_info[$i]);
}
fclose($handle);
gc_collect_cycles();
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename='.basename('file.txt'));
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize('file.txt'));
readfile('file.txt');
exit;
gc_disable();
?>