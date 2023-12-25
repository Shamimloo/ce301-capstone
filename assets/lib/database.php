<?php
####### Verify Input Fields Before Adding To DB #######
//------ $controlName: name of $_POST. (e.g. $_POST["email"], just input "email")
//------ $isAllLowercase: "true" or "false" (default is false, leave it empty for false)

function prepareDBVariables($controlName, $isAllLowercase = false)
{
  if (isset($_POST[$controlName]) && $_POST[$controlName] != "") {
    $var = trim($_POST[$controlName]); //------ Strip unnecessary characters (extra space, tab, newline) from the user input data
    $var = stripslashes($_POST[$controlName]); //------ Remove backslashes (\) from the user input data
    $var = htmlspecialchars($_POST[$controlName]); //------ Converts special characters to HTML entities. (e.g. it will replace HTML characters like < and > with &lt; and &gt;)
  }
  if ($isAllLowercase) { //------ If "true" is pass back
    $var = strtolower($var); //------ Change all characters to lowercase
  }
  return $var;
}

//------ Format Date to YYYY-MM-DD format
function mySQLDate($date)
{
  return date('Y-m-d', strtotime($date));
}

//------ Format DateTime to YYYY-MM-DD HH:MM:SS format
function mySQLDateTime($date)
{
  return date('Y-m-d H:i:s', strtotime($date));
}

//------ Format Time to HH:MM:SS format
function mySQLTime($date)
{
  return date('H:i:s', strtotime($date));
}

//------ Format Date to YYYY-MM-DD HH:MM:SS, and add hour(s) to the time
function mySQLDateTimeAddHour($date, $hour)
{
  return date('Y-m-d H:i:s', strtotime($date) + $hour * 3600);
}

//------ Format Date to YYYY-MM-DD HH:MM:SS, and add day(s) to the time
function mySQLDateTimeAddDay($date, $day)
{
  return date('Y-m-d H:i:s', strtotime($date) + $day * 3600 * 24);
}
