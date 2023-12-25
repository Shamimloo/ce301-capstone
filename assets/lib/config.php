<?php
// show error logs (if any)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// setting default timezone
date_default_timezone_set('Asia/Singapore');

// define Dashboard Constants
define("SITE_ROOT", "http://localhost:8888/");
define("SITE_DASHBOARD", "http://localhost:8888/dashboard/");
define("SITE_QUIZ", "http://localhost:8888/quiz/");
define("SITE_TITLE", "Quizzes");
//for indexing purposes
define("SITE_URL", "http://localhost:8888/?site=");
