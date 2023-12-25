<?php
// include "assets/lib/db.class.php";
// include "assets/lib/config.php";
// include "assets/lib/functions.php";
// include "assets/lib/validation.php";
// include "assets/lib/alerts.php";
// include "assets/lib/database.php";

// Redirect to the dashboard
// jsRedirect(SITE_ROOT . "dashboard/");

####### Difference between PHP include and require Statements #######
# require - will produce a fatal error (E_COMPILE_ERROR) and stop the script
# include - will only produce a warning (E_WARNING) and the script will continue

// function PATH_Root() {
//     return "D:/Apache24/htdocs/folder/";
// }

// function PATH_Host() {
//     return "http://domainhere.com/";
// }

// PATH_Root() . 'folderhere/filehere';

//----- Include Site Config
require 'assets/lib/config.php';

//----- Include DB Config
require 'assets/lib/db.class.php';

//----- Include Functions
require 'assets/lib/alerts.php';
// require 'assets/lib/encrypt.php';
require 'assets/lib/database.php';
require 'assets/lib/functions.php';
require 'assets/lib/validation.php';

if (isset($_GET['site'])) {


  $url = filter_input(INPUT_GET, 'site', FILTER_UNSAFE_RAW);

  // include headers
  // if($url == 'login' || $url == 'register' || $url == 'reset-password' || $url == 'forgot-password' || $url == 'quiz'){
  //     include 'assets/templates/quiz/header.php';
  // }else if($url == 'admin' || $url == 'settings' || $url == 'faq' || $url == 'admin-dashboard' || $url == 'admin-users' || $url == 'admin-edit-user'){
  //     include 'assets/templates/dashboard/auth/header.php';
  //     // include 'assets/templates/dashboard/footer.php';
  // }else if($url == '404'){
  //     include 'assets/templates/quiz/404/header.php';
  // }

  switch ($url) {

      // For Quizzes --------------------------------------------------

      //----- Quizzes -----
    case 'quizzes':
      include 'quiz/index.php';
      break;

      //----- Quizzes login -----
    case 'quizzes-login':
      include 'quiz/login.php';
      break;

      //----- Quizzes info-page -----
    case 'quizzes-info-page':
      include 'quiz/info-page.php';
      break;

      //----- Quizzes info -----
    case 'quizzes-info':
      include 'quiz/info.php';
      break;

      //----- Quizzes quiz-info -----
    case 'quizzes-quiz-info':
      include 'quiz/quiz-info.php';
      break;

      //----- Quizzes quiz -----
    case 'quizzes-quiz':
      include 'quiz/quiz.php';
      break;

      //----- Quizzes login-filter AJAX-----
    case 'quizzes-login-filter':
      include 'quiz/login-filter.php';
      break;

      //----- Quizzes nextQuestion AJAX-----
    case 'quizzes-nextQuestion':
      include 'quiz/nextQuestion.php';
      break;

      //----- Quizzes logout -----
    case 'quizzes-logout':
      include 'quiz/logout.php';
      break;

      //----- Quizzes 404 -----
    case 'quizzes-404':
      include 'quiz/404.php';
      break;

      // For Dashboard --------------------------------------------------

      //----- Dashboard -----
    case 'admin':
      include 'dashboard/index.php';
      break;

      //----- Login -----
    case 'login':
      include 'dashboard/login.php';
      break;

      //----- Register -----
      // case 'register':
      //     include 'dashboard/register.php';
      //     break;

      //----- Profile Edit -----
    case 'profile-edit':
      include 'dashboard/profile-edit.php';
      break;

      //----- Report Summary -----
    case 'report-summary':
      include 'dashboard/report-summary.php';
      break;

      //----- Facilitator Summary -----
    case 'facilitator-summary':
      include 'dashboard/facilitator-summary.php';
      break;

      //----- Facilitator Summary -----
    case 'facilitator-add':
      include 'dashboard/facilitator-add.php';
      break;

      //----- Facilitator Deactivate -----
    case 'facilitator-deactivate':
      include 'dashboard/facilitator-deactivate.php';
      break;

      //----- Facilitator Activate -----
    case 'facilitator-activate':
      include 'dashboard/facilitator-activate.php';
      break;

      //----- Facilitator Edit -----
    case 'facilitator-edit':
      include 'dashboard/facilitator-edit.php';
      break;

      //----- Facilitator Delete -----
    case 'facilitator-delete':
      include 'dashboard/facilitator-delete.php';
      break;

      //----- Student Summary -----
    case 'learner-summary':
      include 'dashboard/learner-summary.php';
      break;

      //----- Student Add -----
    case 'learner-add':
      include 'dashboard/learner-add.php';
      break;

      //----- Student studentaddfilter AJAX -----
    case 'learneraddfilter':
      include 'dashboard/learneraddfilter.php';
      break;

      //----- Student Deactivate -----
    case 'learner-deactivate':
      include 'dashboard/learner-deactivate.php';
      break;

      //----- Student Activate -----
    case 'learner-activate':
      include 'dashboard/learner-activate.php';
      break;

      //----- Student Edit -----
    case 'learner-edit':
      include 'dashboard/learner-edit.php';
      break;

      //----- Student Delete -----
    case 'learner-delete':
      include 'dashboard/learner-delete.php';
      break;
     
      //----- Infopage Summary -----
    case 'infopage-summary':
      include 'dashboard/infopage-summary.php';
      break;

      //----- Infopage Add -----
    case 'infopage-add':
      include 'dashboard/infopage-add.php';
      break;

      //----- Infopage Unpublish -----
    case 'infopage-unpublish':
      include 'dashboard/infopage-unpublish.php';
      break;

      //----- Infopage Publish -----
    case 'infopage-publish':
      include 'dashboard/infopage-publish.php';
      break;

      //----- Infopage Edit -----
    case 'infopage-edit':
      include 'dashboard/infopage-edit.php';
      break;

      //----- Infopage Delete -----
    case 'infopage-delete':
      include 'dashboard/infopage-delete.php';
      break;

      //----- Infopage Mass Delete -----
    case 'infopage-massdelete':
      include 'dashboard/infopage-massdelete.php';
      break;

      //----- Level Edit -----
    case 'level-edit':
      include 'dashboard/level-edit.php';
      break;

      //----- category Summary -----
    case 'category-summary':
      include 'dashboard/category-summary.php';
      break;

      //----- category Add -----
    case 'category-add':
      include 'dashboard/category-add.php';
      break;

      //----- category Deactivate -----
    case 'category-deactivate':
      include 'dashboard/category-deactivate.php';
      break;

      //----- category Activate -----
    case 'category-activate':
      include 'dashboard/category-activate.php';
      break;

      //----- category Edit -----
    case 'category-edit':
      include 'dashboard/category-edit.php';
      break;

      //----- category Delete -----
    case 'category-delete':
      include 'dashboard/category-delete.php';
      break;

      //----- Question Summary -----
    case 'question-summary':
      include 'dashboard/question-summary.php';
      break;

      //----- Question Add -----
    case 'question-add':
      include 'dashboard/question-add.php';
      break;

      //----- Question Delete -----
    case 'question-delete':
      include 'dashboard/question-delete.php';
      break;

      //----- Question Edit trueOrFalse -----
    case 'question-edit-trueOrFalse':
      include 'dashboard/question-edit-trueOrFalse.php';
      break;

      //----- Question Edit multiChoiceText -----
    case 'question-edit-multiChoiceText':
      include 'dashboard/question-edit-multiChoiceText.php';
      break;

      //----- Question Edit multiChoiceImage -----
    case 'question-edit-multiChoiceImage':
      include 'dashboard/question-edit-multiChoiceImage.php';
      break;

      //----- Question Edit multiAnswerText -----
    case 'question-edit-multiAnswerText':
      include 'dashboard/question-edit-multiAnswerText.php';
      break;

      //----- Quiz Summary -----
    case 'quiz-summary':
      include 'dashboard/quiz-summary.php';
      break;

      //----- Quiz Add -----
    case 'quiz-add':
      include 'dashboard/quiz-add.php';
      break;

      //----- Quiz Unpublish -----
    case 'quiz-unpublish':
      include 'dashboard/quiz-unpublish.php';
      break;

      //----- Quiz Publish -----
    case 'quiz-publish':
      include 'dashboard/quiz-publish.php';
      break;

      //----- Quiz Edit -----
    case 'quiz-edit':
      include 'dashboard/quiz-edit.php';
      break;

      //----- Quiz Delete -----
    case 'quiz-delete':
      include 'dashboard/quiz-delete.php';
      break;

      //----- Logout -----
    case 'logout':
      include 'dashboard/logout.php';
      break;

      //----- 404
    case '404':
      include 'dashboard/404.php';
      break;

      // Default --------------------------------------------------

      //----- Default -----
    default:
      jsRedirect(SITE_URL . '404');
      break;

      // ---------------------------------------------------------------

      // //----- AJAX Edit Settings
      // case 'ajax-edit-settings':
      //     include 'app/settings/ajax/edit.php';
      //     break;

      // //----- Action
      // case 'action':
      //     include 'app/action.php';
      //     break;

      // //----- Action
      // // case 'action':
      // //     include 'app/action.php';
      // //     break;

      // //----- AJAX Login
      // case 'ajax-login':
      //     include 'app/auth/ajax/login.php';
      //     break;

      // //----- Register
      // // case 'register':
      // //     include 'app/auth/register.php';
      // //     break;

      // //----- AJAX Register
      // case 'ajax-register':
      //     include 'app/auth/ajax/register.php';
      //     break;

      // //----- Reset Password
      // case 'reset-password':
      //     include 'app/auth/reset-password.php';
      //     break;

      // //----- AJAX Reset Password
      // case 'ajax-reset-password':
      //     include 'app/auth/ajax/reset-password.php';
      //     break;

      // //----- Forgot Password
      // case 'forgot-password':
      //     include 'app/auth/forgot-password.php';
      //     break;

      // //----- AJAX Forgot Password
      // case 'ajax-forgot-password':
      //     include 'app/auth/ajax/forgot-password.php';
      //     break;

      // //----- Cron Job
      // case 'cron':
      //     include 'app/cron.php';
      //     break;

      // //----- FAQ
      // case 'faq':
      //     include 'app/faq.php';
      //     break;

      // //----- Privacy
      // case 'privacy':
      //     include 'app/privacy.php';
      //     break;

      // //----- START OF DASHBOARD -----//

      // //----- Admin Dashboard
      // // case 'admin':
      // //     include 'dashboard/login.php';
      // //     break;

      // //----- Admin Users
      // case 'admin-users':
      //     include 'admin/users/manage.php';
      //     break;

      // //----- Admin AJAX Add User
      // case 'admin-add-user':
      //     include 'admin/users/ajax/add.php';
      //     break;

      // //----- Admin Edit User
      // case 'admin-edit-user':
      //     include 'admin/users/edit.php';
      //     break;

      // //----- Admin AJAX Edit User
      // case 'admin-ajax-edit-user':
      //     include 'admin/users/ajax/edit.php';
      //     break;

      // //----- Admin AJAX Loginas User
      // case 'admin-loginas-user':
      //     include 'admin/users/ajax/loginas.php';
      //     break;

      // //----- Admin AJAX Delete User
      // case 'admin-delete-user':
      //     include 'admin/users/ajax/delete.php';
      //     break;
  }

  // include footers
  // if($url == 'login' || $url == 'register' || $url == 'reset-password' || $url == 'forgot-password'){
  //     include 'assets/templates/auth/footer.php';
  // }else if($url == 'dashboard' || $url == '404' || $url == 'settings' || $url == 'faq' || $url == 'admin-dashboard' || $url == 'admin-users' || $url == 'admin-edit-user'){
  //     include 'assets/templates/footer.php';
  // }else if($url == 'quiz'){
  //     include 'assets/templates/quiz/footer.php';
  // }

} else {
  // $link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
  // var_dump($link);
  // var_dump(substr(parse_url($link, PHP_URL_PATH ), 0, 6));

  // if(substr(parse_url($link, PHP_URL_PATH ), 0, 6) == '/admin'){
  //     jsRedirect(SITE_ROOT . '?site=admin');
  // } elseif(substr(parse_url($link, PHP_URL_PATH ), 0, 5) == '/quiz' || substr(parse_url($link, PHP_URL_PATH ), 0, 5) == "/") {
  //     jsRedirect(SITE_ROOT . '?site=quiz');
  // } else {
  //     jsRedirect(SITE_ROOT . '?site=404');
  // }

  jsRedirect(SITE_URL . 'admin');
}
