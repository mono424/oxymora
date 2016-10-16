<?php namespace KFall\oxymora\addons;

interface iAddon{

  // ========================================
  //  EVENTS
  // ========================================

  // Start/Stop Events
  public function onInstallation();
  public function onEnable();
  public function onDisable();

  // Dashboard
  public function onOpen();
  public function onTabChange($tab);

  // Page
  public function onPageOpen($page);

  // ========================================
  //  FUNCTIONS
  // ========================================

  // Settings Page
  public function getPage();

}
