<?php

require_once('modules/KReports/Plugins/prototypes/kreportintegrationplugin.php');

class ksnapshot extends kreportintegrationplugin {

   public function __construct() {
      $this->pluginName = 'Snapshots';
   }

   public function getMenuItem() {
      
      return array(
          'jsFile' => 'modules/KReports/Plugins/Integration/ksnapshots/ksnapshot.js',
          'menuItem' => array(
              'icon' => $this->wrapText('modules/KReports/images/snapshot.png'),
              'text' => $this->wrapText($this->pluginName),
              'menu' => array(
                  // 'K.kreports.ksnapshot.snapshotCombo',
                  array(
                      'text' => $this->wrapText('take Snapshot'),
                      'icon' => $this->wrapText('modules/KReports/images/snapshot.png'),
                      'handler' => $this->wrapFunction('K.kreports.ksnapshot.takeSnapshot')
                  ),
                  'K.kreports.ksnapshot.snapshotCombo'
              )
      ));
   }

}