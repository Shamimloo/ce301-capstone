<?php
//------ Check If Field Is Empty
function isBlankField($data)
{
  if (trim($data) == "") {
    return true;
  } else {
    return false;
  }
}

//------ Check If Email Is Valid
function isValidEmail($input)
{
  // First, we check that there's one @ symbol, and that the lengths are right
  if (!preg_match("/^[^@]{1,64}@[^@]{1,255}$/", $input)) {
    // Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
    return false;
  }
  // Split it into sections to make life easier
  $email_array = explode("@", $input);
  $local_array = explode(".", $email_array[0]);
  for ($i = 0; $i < sizeof($local_array); $i++) {
    if (!preg_match("/^(([A-Za-z0-9!#$%&'*+\/=?^_`{|}~-][A-Za-z0-9!#$%&'*+\/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$/", $local_array[$i])) {
      return false;
    }
  }
  if (!preg_match("/^\[?[0-9\.]+\]?$/", $email_array[1])) { // Check if domain is IP. If not, it should be valid domain name
    $domain_array = explode(".", $email_array[1]);
    if (sizeof($domain_array) < 2) {
      return false; // Not enough parts to domain
    }
    for ($i = 0; $i < sizeof($domain_array); $i++) {
      if (!preg_match("/^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$/", $domain_array[$i])) {
        return false;
      }
    }
  }
  return true;
}

//------ Check If Name Is Valid
function isValidName($input)
{
  $regex = '/^[0-9a-zA-Z ]{0,100}$/';

  if (preg_match($regex, $input)) {
    return true;
  } else {
    return false;
  }
}

//------ Check If Password Is Valid
function isValidPassword($input)
{
  // Validate password strength
  // Password must be at least 8 characters in length.
  // Password must include at least one upper case letter.
  // Password must include at least one number.
  // Password must include at least one special character.
  $uppercase = preg_match('@[A-Z]@', $input);
  $lowercase = preg_match('@[a-z]@', $input);
  $number    = preg_match('@[0-9]@', $input);
  $specialChars = preg_match('@[^\w]@', $input);

  if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($input) < 8) {
    return false;
  } else {
    return true;
  }
}

//------ Check If Contact Number Is Valid
function isValidContactNumber($input)
{
  $regex = '/^[0-9+()\- ]{1,30}$/';

  if (preg_match($regex, $input)) {
    return true;
  } else {
    return false;
  }
}

//------ Sanitize the input from the input field
function filterInput($input)
{
  $input = trim($input); //remove unnecessary spaces, tabs, or new lines
  $input = stripslashes($input); //remove backslashes
  $input = htmlspecialchars($input); //remove any special html characters that may harm your code
  return $input; //final filtered input
}
