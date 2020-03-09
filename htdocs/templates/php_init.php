<?php
  # youclan PHP PAGE INITIALISER #

  # BUFFERING, SESSION START AND ERROR REPORTING #
  // Start session and output buffer
  ob_start();
  session_start();

  // Turn off error reporting
  error_reporting(0);

  // Internal Testing
  if (strpos(__DIR__, "/home/jacobsp/WebDev/") !== false) {
    ini_set('display_errors', 1);
    error_reporting(E_ERROR | E_WARNING | E_PARSE);
  }

  # FUNCTIONS #
  function getIP(){
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
      $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
      $ip = $_SERVER['REMOTE_ADDR'];
    }

    return $ip;
  }

  function getLanguage() {
    $valid_langs = ['es', 'en'];
    $lang = "en";

    $HTTP_LANG = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
    $PROCESSED_LANG_ARRAY = [];

    // Create array of browser languages with preference values
    if (!empty($HTTP_LANG)){
      $USER_LANGS = explode(",", $HTTP_LANG);

      foreach ($USER_LANGS as $LANG_INSTANCE) {
        $LANG_Q = 1;

        if (strpos($LANG_INSTANCE, ";") !== FALSE) {
          if (strpos($LANG_INSTANCE, "=") !== FALSE){
            $LANG_Q = explode("=", $LANG_INSTANCE)[1];
          }
        }

        $PROCESSED_LANG_ARRAY[$LANG_INSTANCE] = $LANG_Q;
      }

      // Search found browser languages in our existing ones
      if (!empty($PROCESSED_LANG_ARRAY)) {
        $MAX_LANG_Q = (int) max($PROCESSED_LANG_ARRAY);
        $browser_lang = substr(array_search($MAX_LANG_Q, $PROCESSED_LANG_ARRAY), 0, 2);
        if (in_array($browser_lang, $valid_langs)) {
          $lang = $browser_lang;
        }
      }
    }

    return $lang;
  }

  function setStrings($lang) {
    global $file_root;

    // Check if language was changed
    $lang_changed = FALSE;

    if (isset($_SESSION['lang'])) {
      if ($lang !== $_SESSION['lang']) {
        $lang_changed = TRUE;
      }
    }

    // For the meantime
    $lang_changed = TRUE;

    // Load strings if language changed
    if (isset($_SESSION['main_strings']) && !$lang_changed) {
      $main_strings = $_SESSION['main_strings'];
    } else {
      $main_strings = json_decode(file_get_contents($file_root . 'strings/' . $lang . '/main_strings.json'), TRUE);
      $_SESSION['main_strings'] = $main_strings;
      $_SESSION['lang'] = $lang;
    }

    return $main_strings;
  }

  function recoverBool($sessionVar) {
    $sessionBool = FALSE;
    if (isset($_SESSION[$sessionVar]) && $_SESSION[$sessionVar] === TRUE) {
      $sessionBool = TRUE;
    }

    return $sessionBool;
  }

  function recoverStr($sessionVar, $default = "") {
    $sessionStr = $default;
    if (isset($_SESSION[$sessionVar])) {
      $sessionStr = $_SESSION[$sessionVar];
    }

    return $sessionStr;
  }

  # SESSION VARIABLE RECOVERY #
  $loggedIn = recoverBool('logged_in');
  $tempLoggedIn = recoverBool('temp_logged_in');
  $Username_Session = recoverStr('username', "Default");

  // If user had to log in before searching something
  $SearchLogin_Session = recoverBool('search_login');
  $Query_Session = recoverStr('user_query');

  $loginHeader = !$loggedIn;
  $registerHeader = !$loggedIn;

  $ID_Session = recoverStr('user_id', "0");

  $Name_Session = recoverStr('name', "John");
  $Surnames_Session = recoverStr('surnames', "Hrycak");
  $Verified_Session = recoverBool('verified');
  $Verify_Token = recoverStr('verify_token');

  // Lost Account (needs to reestablish account)
  // ALSO EQUIVALENT OF tempLoggedIn
  $Lost_Session = recoverBool('lost_account');
  $Setup_Session = recoverBool('setup_account');

  $Picture_Session = "assets/defaultProfile.svg";
  $Country_Session = recoverStr('country');
  $Course_Session = recoverStr('course');
  $Level_Session = recoverStr('level');

  // Check if Temporarily Logged In
  if (isset($_SESSION['picture'])) {
    $Picture_Session = "uploads/" . $_SESSION['picture'];
  }

  # OTHER #
  $lang = getLanguage();
  $main_strings = setStrings($lang);

  $current_title = $main_strings['website_title'];

  // if (strpos(__DIR__, "/home/jacobsp/WebDev/") !== false) {
    // $loggedIn = TRUE;
    // $Verified_Session = TRUE;
  // }

?>
