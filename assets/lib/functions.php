<?php
// Start the session
session_start();

####### Check current page #######
//------ Example Usage:
# if(!PageCheck($url, '404')){
#   if(!isLoggedIn()){ jsRedirect(SITE_ROOT . 'login'); }
# }
function pageCheck($url, $pageName)
{
  if ($url == $pageName) {
    return true;
  }
}

//------ Redirect User To Another Page Using JS
function jsAlert($alert)
{
  echo "<script>alert('$alert');</script>";
}

//------ Redirect User To Another Page Using JS
function jsRedirect($redirectURL)
{
  echo "<script language='JavaScript'>window.location='$redirectURL'</script>";
}

//------ Check Field Value, If $_POST exist, trim the $_POST value, else trim the default value
function fieldValue($inputName, $defaultValue)
{
  return isset($_POST[$inputName]) ? trim($_POST[$inputName]) : trim($defaultValue);
}

//------ Store Session during login (Teacher)
function loginTeacherSession($name, $email, $perm, $designation)
{
  $_SESSION["teacherName"] = $name;
  $_SESSION["teacherEmail"] = $email;
  $_SESSION["teacherPermission"] = $perm;
  $_SESSION["teacherDesignation"] = $designation;
}

//------ Store Session during login (Learner)
function loginLearnerSession($groupID, $groupName, $learnerID, $learnerName)
{
  $_SESSION["groupID"] = $groupID;
  $_SESSION["groupName"] = $groupName;
  $_SESSION["learnerID"] = $learnerID;
  $_SESSION["learnerName"] = $learnerName;
}

//------ Check If Learner Is LoggedIn 
function isLearnerLoggedIn()
{
  if (isset($_SESSION["groupID"]) && isset($_SESSION["groupName"]) && isset($_SESSION["learnerID"]) && isset($_SESSION["learnerName"])) { //email & name session exists
    return true;
  } else {
    return false;
  }
}

//------ Check If Teacher Is LoggedIn 
function isTeacherLoggedIn()
{
  if (isset($_COOKIE["teacherName"]) && isset($_COOKIE["teacherEmail"]) && isset($_COOKIE["teacherPermission"]) && isset($_COOKIE["teacherDesignation"])) {
    return true;
  } else {
    //Check if teacher is logged in
    if (isset($_SESSION["teacherName"]) && isset($_SESSION["teacherEmail"]) && isset($_SESSION["teacherPermission"]) && isset($_SESSION["teacherDesignation"])) { //email & name session exists
      return true;
    } else {
      return false;
    }
  }
}

function isHOD() // HOD teacher
{
  if (isset($_SESSION["teacherDesignation"])) {
    if ($_SESSION["teacherDesignation"] == 2) {
      return true;
    } else {
      return false;
    }
  } elseif (isset($_COOKIE["teacherDesignation"])) {
    if ($_COOKIE["teacherDesignation"] == 2) {
      return true;
    } else {
      return false;
    }
  }
}

function isAdmin()
{
  if (isset($_SESSION["teacherPermission"])) {
    if ($_SESSION["teacherPermission"] == 1) {
      return true;
    } else {
      return false;
    }
  } elseif (isset($_COOKIE["teacherPermission"])) {
    if ($_COOKIE["teacherPermission"] == 1) {
      return true;
    } else {
      return false;
    }
  }
}

//------ Store Session during login (Facilitator)
function loginFacilitatorSession($name, $email, $perm, $designation, $company)
{
  $_SESSION["facilitatorName"] = $name;
  $_SESSION["facilitatorEmail"] = $email;
  $_SESSION["facilitatorPermission"] = $perm;
  $_SESSION["facilitatorDesignation"] = $designation;
  $_SESSION["companyID"] = $company;
}

function loginFacilitatorCookies($name, $email, $perm, $designation, $company)
{
  // Set cookies for facilitator
  setcookie("facilitatorName", $name, time() + 60 * 60 * 24 * 30);
  setcookie("facilitatorEmail", $email, time() + 60 * 60 * 24 * 30);
  setcookie("facilitatorPermission", $perm, time() + 60 * 60 * 24 * 30);
  setcookie("facilitatorDesignation", $designation, time() + 60 * 60 * 24 * 30);
  setcookie("companyID", $company, time() + 60 * 60 * 24 * 30);
}

// Check if Facilitator Is LoggedIn 
function isFacilitatorLoggedIn()
{
  if (
    isset($_COOKIE["facilitatorName"]) &&
    isset($_COOKIE["facilitatorEmail"]) &&
    isset($_COOKIE["facilitatorPermission"]) &&
    isset($_COOKIE["facilitatorDesignation"]) &&
    isset($_COOKIE["companyID"])
  ) {
    return true;
  } else {
    // Check if facilitator is logged in using session variables
    if (
      isset($_SESSION["facilitatorName"]) &&
      isset($_SESSION["facilitatorEmail"]) &&
      isset($_SESSION["facilitatorPermission"]) &&
      isset($_SESSION["facilitatorDesignation"]) &&
      isset($_SESSION["companyID"])
    ) {
      return true;
    } else {
      return false;
    }
  }
}






//------ Create Name Initials (e.g. Manfred Lim = ML)
function makeInitials(string $name): string
{
  $words = explode(' ', $name);
  if (count($words) >= 2) {
    return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
  } else {
    return strtoupper(substr($words[0], 0, 1));
  }
  //return $this->makeInitialsFromSingleWord($name);
}


//------ Shorten String to 50 with a ... behind
function shortenString50($string)
{
  if (strlen($string) >= 50) {
    return substr($string, 0, 50) . " ... ";
  } else {
    return $string;
  }
}

//------ Shorten String to 20 with a ... behind
function shortenString20($string)
{
  if (strlen($string) >= 20) {
    return substr($string, 0, 20) . " ... ";
  } else {
    return $string;
  }
}

function shortenString($string, $number)
{
  if (strlen($string) >= $number) {
    return substr($string, 0, $number) . " ... ";
  } else {
    return $string;
  }
}

//------ Upload File ([!] Returns Array)
# $folder: Where you want the file to be upload to? (e.g. "images/upload")
# $name: What is the name of the file you want to rename to? (e.g. "profile-pic")
function uploadFile($folder, $name)
{
  $errorMessage = "";

  // // check whether the folder exists
  // if (!is_dir($folder)) {
  //   mkdir($folder);
  // } else { // delete existing file if so
  //   array_map('unlink', array_filter((array)glob("$folder*")));
  // }

  // get necessary file variables
  // function generateRandomString($length = 8)
  // {
  //   $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  //   $charactersLength = strlen($characters);
  //   $randomString = '';
  //   for ($i = 0; $i < $length; $i++) {
  //     $randomString .= $characters[rand(0, $charactersLength - 1)];
  //   }
  //   return $randomString;
  // }

  $fileActual = $_FILES[$name]["name"];
  $fileExtension = explode(".", $fileActual);
  $fileExtension = strtolower(array_pop($fileExtension));

  $fileStored = md5($fileActual . str_replace(".", "", microtime(true)));
  // $fileStored = md5($fileActual . generateRandomString() . str_replace(".", "", microtime(true)));
  $targetFile = strtolower("$folder$fileStored.$fileExtension");
  $tmpFilePath = $_FILES[$name]['tmp_name'];
  $fileSize = $_FILES[$name]['size'];

  // check for accepted file extensions / MIME types
  $extensionArr = array('jpg', 'jpeg', 'jpe', 'jif', 'jfif', 'jfi', 'png', 'webp', 'bmp', 'dib', 'heif', 'heic', 'svg', 'svgz');
  if (!in_array($fileExtension, $extensionArr)) {
    // $errorMessage .= "File [" . $fileActual . "] has an unaccepted extension\\n";
    // authErrorMsg($errorMessage);
  }

  // check for file size
  if ($fileSize > 2048000) {
    // $errorMessage .= "File [" . $fileActual . "] is too large to upload\\n";
    // authErrorMsg($errorMessage);
  }

  if ($errorMessage == '') {
    $isSuccess = move_uploaded_file($tmpFilePath, $targetFile);
    if ($isSuccess) {
      // $errorMessage = "File [" . $fileActual . "] uploaded successfully\\n";
      // authCorrectMsg($errorMessage);
    } else {
      // $errorMessage = "Encountered an error uploading File [" . $fileActual . "]\\n";
      // authErrorMsg($errorMessage);
    }
  } else {
    $isSuccess = false;
  }

  if ($isSuccess) {
    $file = "$fileStored.$fileExtension";
  } else {
    $file = null;
  }

  return array('file' => $file, 'message' => $errorMessage);
}

//------ Get Location (Country) By function getLocationInfoByIp()
{
  $client  = @$_SERVER['HTTP_CLIENT_IP'];
  $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
  $remote  = @$_SERVER['REMOTE_ADDR'];
  $result  = array('country' => '', 'city' => '');
  if (filter_var($client, FILTER_VALIDATE_IP)) {
    $ip = $client;
  } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
    $ip = $forward;
  } else {
    $ip = $remote;
  }
  $ip_data = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
  if ($ip_data && $ip_data->geoplugin_countryName != null) {
    $result['country'] = $ip_data->geoplugin_countryCode;
    $result['city'] = $ip_data->geoplugin_city;
  }
  return $result;
}

//------ Get User's Device Type
function getDevice($httpUserAgent)
{
  $u_agent = $httpUserAgent;
  $platform = 'Unknown';
  //First get the platform?
  if (preg_match('/android/i', $u_agent)) {
    $platform = 'Android';
  } elseif (preg_match('/iPhone|iPhone OS/i', $u_agent)) {
    $platform = 'iPhone';
  } elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
    $platform = 'Mac';
  } elseif (preg_match('/windows|win32/i', $u_agent)) {
    $platform = 'Windows';
  } elseif (preg_match('/linux/i', $u_agent)) {
    $platform = 'Linux';
  } else {
    $platform = 'Unknown';
  }

  $ua = array('platform'  => $platform);

  return $ua['platform'];
}

//------ Get User's Browser Type
function getBrowserInfo($httpUserAgent)
{
  $u_agent = $httpUserAgent;
  $bname = 'Unknown';
  $platform = 'Unknown';
  $version = "";

  //First get the platform?
  if (preg_match('/android/i', $u_agent)) {
    $platform = 'Android';
  } elseif (preg_match('/iPhone|iPhone OS/i', $u_agent)) {
    $platform = 'iPhone';
  } elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
    $platform = 'Mac';
  } elseif (preg_match('/windows|win32/i', $u_agent)) {
    $platform = 'Windows';
  } elseif (preg_match('/linux/i', $u_agent)) {
    $platform = 'Linux';
  } else {
    $platform = 'Unknown';
  }

  // Next get the name of the useragent yes seperately and for good reason
  if (preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent)) {
    $bname = 'Internet Explorer';
    $ub = "MSIE";
  } elseif (preg_match('/Firefox/i', $u_agent)) {
    $bname = 'Mozilla Firefox';
    $ub = "Firefox";
  } elseif (preg_match('/OPR/i', $u_agent)) {
    $bname = 'Opera';
    $ub = "Opera";
  } elseif (preg_match('/Chrome/i', $u_agent) && !preg_match('/Edge/i', $u_agent)) {
    $bname = 'Google Chrome';
    $ub = "Chrome";
  } elseif (preg_match('/Safari/i', $u_agent) && !preg_match('/Edge/i', $u_agent)) {
    $bname = 'Apple Safari';
    $ub = "Safari";
  } elseif (preg_match('/Netscape/i', $u_agent)) {
    $bname = 'Netscape';
    $ub = "Netscape";
  } elseif (preg_match('/Edge/i', $u_agent)) {
    $bname = 'Edge';
    $ub = "Edge";
  } elseif (preg_match('/Trident/i', $u_agent)) {
    $bname = 'Internet Explorer';
    $ub = "MSIE";
  }

  // finally get the correct version number
  $known = array('Version', $ub, 'other');
  $pattern = '#(?<browser>' . join('|', $known) .
    ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
  if (!preg_match_all($pattern, $u_agent, $matches)) {
    // we have no matching number just continue
  }
  // see how many we have
  $i = count($matches['browser']);
  if ($i != 1) {
    //we will have two since we are not using 'other' argument yet
    //see if version is before or after the name
    if (strripos($u_agent, "Version") < strripos($u_agent, $ub)) {
      $version = $matches['version'][0];
    } else {
      $version = $matches['version'][1];
    }
  } else {
    $version = $matches['version'][0];
  }

  // check if we have a number
  if ($version == null || $version == "") {
    $version = "?";
  }

  $ua = array(
    'userAgent' => $u_agent,
    'browserName' => $bname,
    'version'   => $version,
    'platform'  => $platform,
    'pattern'    => $pattern
  );

  return $ua['browserName'] . " (ver: " . $ua['version'] . ") on " . $ua['platform'];
}
