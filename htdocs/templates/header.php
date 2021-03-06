<?php if ($loginHeader) { ?>
  <form class="login-form" action="/login/" method="post"> <?php } ?>
    <div class="main-header">
      <div class="header-logo">
        <a href="/">
          <img src="/assets/youclan_v3.png" alt="<?php echo $main_strings['logo_caption']; ?>" class="main-logo">
        </a>
      </div>
    <?php if ($loginHeader) { ?>
      <div class="header-login">
        <div class="header-username">
          <h4><?php echo $main_strings['login_user']; ?></h4>
          <input type="text" name="username" class="header-input" value="" placeholder="">
        </div>
        <div class="header-password">
          <h4><?php echo $main_strings['login_pass']; ?></h4>
          <input type="password" name="password" class="header-input" value="" placeholder="" >
        </div>
        <input type="submit" class="header-submit" name="" value="Log In">
      </div>
    <?php } else if ($registerHeader) { ?>
      <a href="/account/registration.php" class="header-registration-link">
        <div class="header-registration">
          Create an account
        </div>
      </a>
    <?php } else if ($loggedIn) { ?>
      <div class="header-account">
        <h2>Hello, <?php echo $Name_Session; ?></h2>
        <img id="header-account-pp" src="<?php echo "/" . $Picture_Session; ?>" alt="">
        <img src="/assets/icons/arrow_dropdown.svg" class="dropdown-arrow">
        <ul class="header-account-dd">
          <li><a href="/account/setup.php">- Settings</a></li>
          <li><a href="/account/logout.php">- Logout</a></li>
        </ul>
      </div>
    <?php } ?>
    </div>
<?php if ($loginHeader) { ?>
  </form>
<?php } else { ?>
<?php } ?>
