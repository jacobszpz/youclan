<?php
  $current_page = "send_mail";

  $path_parts = explode('htdocs', __DIR__);
  $path_deep = substr_count($path_parts[1], "/");
  $file_root = "";

  for ($i=0; $i < $path_deep; $i++) {
    $file_root .= "../";
  }

  function sendTokenMail($name, $user, $token) {
    global $file_root, $lang, $main_strings;

    $email = "$user@uclan.ac.uk";

    $user_mail_body = file_get_contents($file_root . 'templates/mail.php');
    $user_mail_body = str_replace('MAIN_TEXT', file_get_contents($file_root . 'texts/mail/' . $lang . '_verify.txt'), $user_mail_body);
    $user_mail_body = str_replace('DISCLAIMER_TEXT', file_get_contents($file_root . 'texts/mail/' . $lang .'_disclaimer.txt'), $user_mail_body);
    $user_mail_body = str_replace('NAME', $name, $user_mail_body);
    $user_mail_body = str_replace('MAIL', $email, $user_mail_body);
    $user_mail_body = str_replace('TOKEN', "$token", $user_mail_body);


    $user_mail_reply_to = 'no-reply@youclan.uk';
    $user_mail_sender = 'no-reply@youclan.uk';

    $user_mail_subject = $main_strings['mail_token_subject'];

    // To send HTML mail, the Content-type header must be set
    $mail_headers  = 'MIME-Version: 1.0' . "\r\n";
    $mail_headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

    // Create email headers
    $mail_headers .= 'From: youclan <' . $user_mail_sender . "> \r\n" . 'Reply-To: ' . $user_mail_reply_to . "\r\n";
    $mail_headers .= 'X-Mailer: PHP/' . phpversion();

    $mailOP = mail($email, $user_mail_subject, $user_mail_body, $mail_headers);
    return $mailOP;
  }

  sendTokenMail($Name_Session, $Username_Session, $Verify_Token);

  sleep(5);

  header("location: {$file_root}account/verify.php");
  exit;
?>
