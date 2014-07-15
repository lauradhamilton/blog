<?php
/*
Copyright 2014 Google Inc. All Rights Reserved.

This file is part of the Google Publisher Plugin.

The Google Publisher Plugin is free software:
you can redistribute it and/or modify it under the terms of the
GNU General Public License as published by the Free Software Foundation,
either version 2 of the License, or (at your option) any later version.

The Google Publisher Plugin is distributed in the hope that it
will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty
of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General
Public License for more details.

You should have received a copy of the GNU General Public License
along with the Google Publisher Plugin.
If not, see <http://www.gnu.org/licenses/>.
*/

if(!defined('ABSPATH')) {
  exit;
}

require_once 'ClassAutoloader.php';

/**
 * A singleton class that shows a notification.
 */
class GooglePublisherPluginNotifier {

  /**
   * A list of names of admin pages in which notifications will be shown.
   */
  protected static $ADMIN_PAGE_NAME_WHITELIST = array(
    'index.php' => '',
    'plugins.php' => '',
  );

  const NO_NOTIFICATION = 'none';

  private $configuration;

  public function __construct($configuration) {
    $this->configuration = $configuration;
  }

  public function notify() {
    global $pagenow;
    if (array_key_exists($pagenow, self::$ADMIN_PAGE_NAME_WHITELIST)) {
      $notification = $this->configuration->getNotification();
      if (isset($notification) &&
          $notification !== self::NO_NOTIFICATION) {
        add_action('admin_notices', array($this, 'displayNotice'));
      }
    }
  }

  public function displayNotice() {
    include 'NoticeTemplate.php';
  }
}
