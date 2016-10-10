<?php namespace KFall\oxymora\plugins;

interface iPlugin{

  // ========================================
  //  EVENTS
  // ========================================

  // Start/Stop Events
  public function onInstallation();
  public function onEnable();
  public function onDisable();

  // Dashboard
  public function onDashboardOpen();
  public function onDashboardTabChange($tab);

  // Page
  public function onPageOpen($page, $get, $post);

  // ========================================
  //  FUNCTIONS
  // ========================================

  // Plugin Dashbaord Page
  public function getPage();

}
