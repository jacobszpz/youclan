<?php if (!$loggedIn) { ?>
  <form class="login-form" action="login/index.php" method="post"> <?php } ?>
    <div class="main-header">
      <div class="header-logo">
        <img src="assets/youclan_v2.png" alt="Welcome to youclan" class="main-logo">
      </div>
    <?php if (!$loggedIn) { ?>
      <div class="header-login">
        <div class="header-username">
          <h4>Username</h4>
          <input type="text" name="username" class="header-input" value="" placeholder="">
        </div>
        <div class="header-password">
          <h4>Password</h4>
          <input type="password" name="password" class="header-input" value="" placeholder="">
        </div>
        <input type="submit" class="header-submit" name="" value="Log In">
      </div>
    <?php } ?>
    </div>
<?php if (!$loggedIn) { ?>
  </form>
<?php } else { ?>
<?php } ?>
