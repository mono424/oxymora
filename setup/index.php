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
    <div class="wrapper">
      <header>
        <a class="backbutton" href="#"><i class="fa fa-chevron-left" aria-hidden="true"></i></a>
        <h1>Getting started</h1>
      </header>

      <section data-page="start" data-title="Getting started">
        <p>Welcome to Oxymora Setup, follow few easy steps to set me up!</p>
        <p>First of all, do you have a Backup which you want to load, or do you want to setup Oxymora for the first time?</p>
        <button class="link" type="button" data-url="setup-database">Setup Oxymora from the scratch!</button>
        <button class="link" type="button" data-url="backup">I got a Backup</button>
      </section>

      <section data-page="backup" data-title="Restore Backup">
        <p>Please upload your Backup-File now. Just drop your file to the area below.
        <br><br>If you have set a password for your Backup-Container, type it in before you upload the container!</p>
        <input class="backupPassword" type="password" placeholder="Password (Optional)"><br>
        <div class="dropzone"></div>
        <div class="backupInfos"></div>

        <button class="backupContinueButton" type="button" disabled>Continue</button>
      </section>


      <section data-page="setup-database" data-title="Database">
        <p>First setup your Database connection for Oxymora.</p>
        <form class="oxform settings database" action="" method="post">
            <label><i class="fa fa-server" aria-hidden="true"></i> Host</label>
            <input name="host" type="text" placeholder="localhost">
            <label><i class="fa fa-user" aria-hidden="true"></i> User</label>
            <input name="user" type="text" value="pripcyoy_oxymora" placeholder="root">
            <label><i class="fa fa-unlock" aria-hidden="true"></i> Password</label>
            <input name="pass" type="password" placeholder="">
            <label><i class="fa fa-database" aria-hidden="true"></i> Database</label>
            <input name="db" type="text" placeholder="oxymora">
            <button class="databaseSave" type="submit">Weiter</button>
          </form>
      </section>

      <section data-page="setup-backup-database" data-title="Database">
        <p class="info">Your Backup does not have any configuration data. Please setup your Database now</p>
        <div class="checkbox">
          <input class="checkbox" style="display:inline;" id="useBackupConfig" type="checkbox"><label class="checkbox" for="useBackupConfig">Use Config from Backup-Container</label>
        </div>
        <form class="oxform settings backupConfigOverwrite" action="" method="post">
            <label><i class="fa fa-server" aria-hidden="true"></i> Host</label>
            <input name="host" type="text" placeholder="localhost">
            <label><i class="fa fa-user" aria-hidden="true"></i> User</label>
            <input name="user" type="text" value="pripcyoy_oxymora" placeholder="root">
            <label><i class="fa fa-unlock" aria-hidden="true"></i> Password</label>
            <input name="pass" type="password" placeholder="">
            <label><i class="fa fa-database" aria-hidden="true"></i> Database</label>
            <input name="db" type="text" placeholder="oxymora">
            <button class="link" type="submit" data-url="install-backup">Weiter</button>
          </form>
      </section>

      <section data-page="install-backup" data-title="Database">
        <p>Ok one click and Oxymora installs everthing from Backup.</p>
        <button class="databseSave" type="submit">Install now</button>
      </section>


      <section data-page="setup-account">

      </section>

    </div>

    <script src="js/main.js" charset="utf-8"></script>
  </body>
</html>
