<?php
  ob_start();
  session_start();

  // Report simple running errors
  error_reporting(0);

  # FUNCTIONS

  function getIP(){
    // Probar si es un cliente compartido
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
      $ip = $_SERVER['HTTP_CLIENT_IP'];
    // Es un proxy
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

    // If possible, get user browser languages
    if ($HTTP_LANG != NULL && !empty($HTTP_LANG)){
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
    }

    // Search found browser languages in our existing ones
    if (!empty($PROCESSED_LANG_ARRAY)) {
      $MAX_LANG_Q = (int) max($PROCESSED_LANG_ARRAY);
      $browser_lang = substr(array_search($MAX_LANG_Q, $PROCESSED_LANG_ARRAY), 0, 2);
      if (in_array($browser_lang, $valid_langs)) {
        $lang = $browser_lang;
      }
    }

    return $lang;
  }

  # LANGUAGE

  $lang = getLanguage();

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
  if (isset($_SESSION['main_strings']) && $lang_changed === FALSE) {
    $main_strings = $_SESSION['main_strings'];
  } else {
    $main_strings = json_decode(file_get_contents($file_root . 'strings/' . $lang . '/main_strings.json'), TRUE);
    $_SESSION['main_strings'] = $main_strings;
    $_SESSION['lang'] = $lang;
  }

  $html_file_root = "/";

  if (!empty($file_root)) {
    $html_file_root = $file_root;
  }

  # SESSION VARIABLES

  $loggedIn = FALSE;

  // Check if Logged In
  if (isset($_SESSION['logged_in']) && $_SESSION["logged_in"] === TRUE) {
    $loggedIn = TRUE;
  }

  // Internal Testing
  if (strpos(__DIR__, "/home/jacobsp/WebDev/") !== false) {
    // $loggedIn = TRUE;
    ini_set('display_errors', 1);
    error_reporting(E_ERROR | E_WARNING | E_PARSE);
  }

  $tempLoggedIn = FALSE;

  // Check if Temporarily Logged In
  if (isset($_SESSION['temp_logged_in']) && $_SESSION["temp_logged_in"] === TRUE) {
    $tempLoggedIn = TRUE;
  }

  $Identity_Session = "Default";

  if (isset($_SESSION['identity'])) {
    $Identity_Session = $_SESSION['identity'];
  }

?>
