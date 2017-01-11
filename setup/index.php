<?php
require '../core/statics.php';

function configExists(){
  return file_exists(ROOT_DIR.'config.json');
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Oxymora Setup</title>
  <link rel="stylesheet" href="css/master.css">
  <link rel="stylesheet" href="css/dropzone.css">
  <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="js/dropzone.js"></script>
</head>
<body>

  <?php if(configExists()){ ?>

    <div class="wrapper">
      <header>
        <a class="backbutton" href="#"><i class="fa fa-chevron-left" aria-hidden="true"></i></a>
        <h1>Already setup!</h1>
      </header>
      <section style="display:block;">
        <p>Hey, Oxymora looks like already setup!</p>
        <p>If you want to setup Oxymora again or restore a Backup, please make sure to reset it first from Dashboard > Settings.</p>
        <a href="../admin/settings.html">Go to Settings</a>
      </section>
    </div>

    <?php }else{ ?>

      <div class="wrapper">
        <header>
          <a class="backbutton" href="#"><i class="fa fa-chevron-left" aria-hidden="true"></i></a>
          <h1>Getting started</h1>
        </header>

        <!-- ============================================== -->
        <!--                 FROM SCRATCH                   -->
        <!-- ============================================== -->

        <section data-page="start" data-title="Getting started">
          <p>Welcome to Oxymora Setup, follow few easy steps to set me up!</p>
          <p>First of all, do you have a Backup which you want to load, or do you want to setup Oxymora for the first time?</p>
          <button class="link" type="button" data-url="setup-database">Setup Oxymora from the scratch!</button>
          <button class="link" type="button" data-url="backup">Restore Data from Backup</button>
        </section>

        <section data-page="setup-database" data-title="Database">
          <p>First setup your Database connection for Oxymora.</p>
          <form id="setup_db" class="oxform settings database" action="" method="post">
            <label><i class="fa fa-server" aria-hidden="true"></i> Host</label>
            <input name="host" type="text" placeholder="localhost">
            <label><i class="fa fa-user" aria-hidden="true"></i> User</label>
            <input name="user" type="text" placeholder="root">
            <label><i class="fa fa-unlock" aria-hidden="true"></i> Password</label>
            <input name="pass" type="password" placeholder="">
            <label><i class="fa fa-database" aria-hidden="true"></i> Database</label>
            <input name="db" type="text" placeholder="oxymora">
            <label><i class="fa fa-table" aria-hidden="true"></i> Table-Prefix (Optional)</label>
            <input name="prefix" type="text" placeholder="oxymora_">
            <button class="link" type="button" data-condition="setupDatabaseCheck" data-url="setup-account">Continue</button>
          </form>
        </section>

        <section data-page="setup-account" data-title="Account">
          <p>Setup your Admin-Account.</p>
          <form id="setup_account" class="oxform settings database" action="" method="post">
            <label><i class="fa fa-user-o" aria-hidden="true"></i> User</label>
            <input name="user" type="text" placeholder="admin" required>
            <label><i class="fa fa-envelope-o" aria-hidden="true"></i> Email (Optional)</label>
            <input name="email" type="email" placeholder="admin@gmail.com">
            <label><i class="fa fa-unlock-alt" aria-hidden="true"></i> Password</label>
            <input name="pass" type="password" placeholder="" required>
            <label><i class="fa fa-unlock-alt" aria-hidden="true"></i> Confirm Password</label>
            <input name="cpass" type="password" placeholder="" required>
            <button class="link" type="button" data-condition="setupAccountCheck" data-url="setup-install">Continue</button>
          </form>
        </section>

        <section data-page="setup-install" data-title="Install">
          <p>Ok one click and Oxymora will install and setup everything for you.</p>
          <ul>
            <li id="setup_indicator_createConfig"><i class="fa fa-check" aria-hidden="true"></i> Create Config</li>
            <li id="setup_indicator_setupDB"><i class="fa fa-check" aria-hidden="true"></i> Setup Database</li>
            <li id="setup_indicator_registerPermissions"><i class="fa fa-check" aria-hidden="true"></i> Register Permissions</li>
            <li id="setup_indicator_registerUser"><i class="fa fa-check" aria-hidden="true"></i> Register User</li>
            <li id="setup_indicator_installAddons"><i class="fa fa-check" aria-hidden="true"></i> Install Addons</li>
          </ul>
          <button class="link" type="button" data-condition="setupInstall" data-url="setup-success">Install</button>
        </section>

        <section data-page="setup-success">
          <p>Installation was successful!</p>
        </section>

        <!-- ============================================== -->
        <!--                RESTORE BACKUP                  -->
        <!-- ============================================== -->

        <section data-page="backup" data-title="Restore Backup">
          <p>Please upload your Backup-File now. Just drop your file to the area below.
            <br><br>If you have set a password for your Backup-Container, type it in before you upload the container!</p>
            <input class="backupPassword" type="password" placeholder="Password (Optional)"><br>
            <div class="dropzone"></div>
            <div class="backupInfos"></div>

            <button class="backupContinueButton" type="button" disabled>Continue</button>
          </section>

          <section id="setup_db" data-page="backup-database" data-title="Database">
            <p class="info">Your Backup does not have any configuration data. Please setup your Database now</p>
            <div class="checkbox">
              <input class="checkbox" style="display:inline;" id="useBackupConfig" type="checkbox"><label class="checkbox" for="useBackupConfig">Use Config from Backup-Container</label>
            </div>
            <form class="oxform settings backupConfigOverwrite" action="" method="post">
              <label><i class="fa fa-server" aria-hidden="true"></i> Host</label>
              <input name="host" type="text" placeholder="localhost">
              <label><i class="fa fa-user" aria-hidden="true"></i> User</label>
              <input name="user" type="text" placeholder="root">
              <label><i class="fa fa-unlock" aria-hidden="true"></i> Password</label>
              <input name="pass" type="password" placeholder="">
              <label><i class="fa fa-database" aria-hidden="true"></i> Database</label>
              <input name="db" type="text" placeholder="oxymora">
              <label><i class="fa fa-table" aria-hidden="true"></i> Table-Prefix (Optional)</label>
              <input name="prefix" type="text" placeholder="oxymora_">
              <button type="button" class="link" data-condition="backupDatabaseCheck" data-url="backup-install">Weiter</button>
            </form>
          </section>

          <section data-page="backup-install" data-title="Restore">
            <p>Ok, one click and Oxymora restore everthing from Backup-Container.</p>
            <ul>
              <li><i class="fa fa-check" aria-hidden="true"></i> Prepare</li>
              <li><i class="fa fa-check" aria-hidden="true"></i> Restore Config</li>
              <li><i class="fa fa-check" aria-hidden="true"></i> Restore Database</li>
              <li><i class="fa fa-check" aria-hidden="true"></i> Restore Addons</li>
              <li><i class="fa fa-check" aria-hidden="true"></i> Restore Files</li>
              <li><i class="fa fa-check" aria-hidden="true"></i> Restore Profile-Pictures</li>
            </ul>
            <button type="button" class="link" data-url="backup-success">Restore</button>
          </section>


          <section data-page="backup-success">
            <p>Restoring was successful!</p>
          </section>

        </div>
        <script src="js/main.js" charset="utf-8"></script>

        <?php } ?>

      </body>
      </html>
