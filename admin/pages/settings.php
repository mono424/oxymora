<?php
// core stuff
use KFall\oxymora\config\Config;
use KFall\oxymora\pageBuilder\CurrentTemplate;
use KFall\oxymora\database\modals\DBGroups;
use KFall\oxymora\database\modals\DBStatic;
use KFall\oxymora\memberSystem\MemberSystem;
use KFall\oxymora\addons\AddonManager;
use KFall\oxymora\permissions\UserPermissionSystem;
require_once '../php/admin.php';
require_once '../php/htmlComponents.php';

// Check Login
loginCheck();

// Check Permissions
if(!UserPermissionSystem::checkPermission("oxymora_settings")) die(html_error("You do not have the required rights to continue!"));

// Tab Change event
AddonManager::triggerEvent(ADDON_EVENT_TABCHANGE, 'settings');

$vars = DBStatic::getVars();
$config = Config::get();
?>
<!-- <div class="headerbox flat-box">
<h1>Settings</h1>
<h3>Settings all over the place, but be careful!</h3>
</div> -->
<div class="settings">

  <div class="tabContainer">
    <ul>
      <li><a data-tab="system">System</a></li>
      <li><a data-tab="database">Database</a></li>
      <li><a data-tab="template">Template</a></li>
      <li><a data-tab="account">Account</a></li>
      <li><a data-tab="reset">Reset</a></li>
    </ul>
    <div class="tabContent">

      <div class="tab" data-tab="system">
        <div class="dataContainer">
          <div class="info">
            <img src="img/oxy.svg">
            <span class="oxy-h1">XYMORA</span><br>
            <span class="oxy-h2"> VERSION <?php echo OXY_VERSION; ?></span>
          </div>
        </div>
      </div>

      <div class="tab" data-tab="database">
        <div class="dataContainer">

          <form class="oxform settings database" action="" method="post">

            <label><i class="fa fa-server" aria-hidden="true"></i> Host</label>
            <input data-initial="<?php echo $config['database']['host']; ?>" name="host" class="oxinput" type="text" value="<?php echo $config['database']['host']; ?>">
            <label><i class="fa fa-user" aria-hidden="true"></i> User</label>
            <input data-initial="<?php echo $config['database']['user']; ?>" name="user" class="oxinput" type="text" value="<?php echo $config['database']['user']; ?>">
            <label><i class="fa fa-unlock" aria-hidden="true"></i> Password</label>
            <input data-initial="<?php echo $config['database']['pass']; ?>" name="pass" class="oxinput" type="text" value="<?php echo $config['database']['pass']; ?>">
            <label><i class="fa fa-database" aria-hidden="true"></i> Database</label>
            <input data-initial="<?php echo $config['database']['db']; ?>" name="db" class="oxinput" type="text" value="<?php echo $config['database']['db']; ?>">

            <div class="user-actions">
              <button class="templateDiscard" type="button">Discard</button>
              <button class="templateSave" type="submit">Save</button>
            </div>
          </form>

        </div>
      </div>

      <div class="tab" data-tab="template">
        <div class="dataContainer">
          <form class="oxform settings template" action="index.html" method="post">
            <?php
            $settings = CurrentTemplate::getStaticSettings();
            foreach($settings as $setting){
              ?>
              <label><?php echo $setting['displayname'] ?></label>
              <input data-initial="<?php echo $setting['value'] ?>" name="<?php echo $setting['key'] ?>" class="oxinput" type="text" value="<?php echo $setting['value'] ?>">
              <?php
            }
            ?>
            <div class="user-actions">
              <button class="templateDiscard" type="button">Discard</button>
              <button class="templateSave" type="submit">Save</button>
            </div>
          </form>
        </div>
      </div>

      <div class="tab" data-tab="account">
        <div class="dataContainer">
          <div class="user">
            <div class="userimg">
              <img src="<?php echo MemberSystem::init()->member->image; ?>" alt="">
            </div>
            <div class="userinfo">
              <label>Username</label>
              <input readonly class="oxinput" type="text" value="<?php echo MemberSystem::init()->member->username; ?>">
              <label>Email</label>
              <input readonly class="oxinput" type="text" value="<?php echo MemberSystem::init()->member->email; ?>">
              <label>Group</label>
              <input readonly class="oxinput" type="text" value="<?php echo DBGroups::getGroupInfo(MemberSystem::init()->member->groupid)['name']; ?>">
            </div>
          </div>
          <div class="user-actions">
            <button class="changePw" type="button"><i class="fa fa-cog" aria-hidden="true"></i> Change Password</button>
            <button class="deleteAcc"type="button"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete Account</button>
          </div>
        </div>
      </div>

      <div class="tab" data-tab="reset">
        <div class="dataContainer">
          Are you sure you want to reset all changes and data? This includes:
          <ol>
            <li>Widgets</li>
            <li>Pages</li>
            <li>Navigation Entries</li>
            <li>Files</li>
            <li>Members</li>
            <li>Addon & Widget stored Data</li>
            <li>Settings(Databaser & Template)</li>
          </ol>
        </div>
      </div>



    </div>
  </div>


</div>

<script type="text/javascript">
let allforms = $('form.settings');
let databaseForm = $('form.settings.database');
let templateForm = $('form.settings.template');

// DISCARD
allforms.each(function(){
  let form = $(this);
  form.find('.templateDiscard').on('click', function(){
    form.find('input').each(function(){
      $(this).val($(this).data('initial'));
    });
  });
});

// SUBMIT DATABASE
databaseForm.on('submit', function(e){
  e.preventDefault();
  let form = $(this);
  let formdata = form.serialize();
  $.post("php/ajax_settings.php?a=database", formdata, function(data){
    setNewInitial();
  });
});

// SUBMIT TEMPLATE
templateForm.on('submit', function(e){
  e.preventDefault();
  let formdata = $(this).serialize();
  $.post("php/ajax_settings.php?a=template", formdata, function(data){
    setNewInitial();
  });
});

// DISCARD FUNCTION
function setNewInitial(){
  allforms.find('input').each(function(){
    $(this).data('initial', $(this).val())
  });
}
</script>
