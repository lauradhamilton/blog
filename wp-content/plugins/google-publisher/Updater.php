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
 * A singleton class that that fetches a notification status from Google.
 */
class GooglePublisherPluginUpdater {

  const SITE_DATA_SERVER_ENDPOINT = 'https://www.googleapis.com';
  const SITE_DATA_GET_API_TEMPLATE = '/publisherplugin/v1/sitedata/%s/get';

  const UPDATER_STATUS_PREFIX = 'GooglePublisherPluginUpdaterStatus::';
  const UPDATER_STATUS_HTTP_HEADER = 'X-Google-Publisher-Plugin-Updater-Status';

  const HTTP_BLOCKED_MESSAGE = 'User has blocked requests through HTTP.';

  // The query parameter name for passing in site id.
  const SITE_ID_PARAMETER = 'siteId';

  private $configuration;

  public function __construct($configuration) {
    $this->configuration = $configuration;
  }

  public function doUpdate() {
    $status = $this->update();
    $code = GooglePublisherPluginUpdaterStatus::getResponseCode($status);
    $status = self::UPDATER_STATUS_PREFIX . $status;
    header(self::UPDATER_STATUS_HTTP_HEADER . ': ' . $status);
    wp_die($status, '', array('response' => $code));
  }

  /**
   * Fetches and stores the latest site configuration from Google servers.
   *
   * @return string A status value from GooglePublisherPluginUpdaterStatus.
   */
  public function update() {
    if (!array_key_exists(self::SITE_ID_PARAMETER, $_REQUEST)) {
      return GooglePublisherPluginUpdaterStatus::MISSING_SITE_ID;
    }

    $siteId = filter_var(
        $_REQUEST[self::SITE_ID_PARAMETER], FILTER_SANITIZE_STRING);
    if ($this->configuration->getSiteId() !== $siteId) {
      return GooglePublisherPluginUpdaterStatus::INVALID_SITE_ID;
    }

    $url = self::SITE_DATA_SERVER_ENDPOINT .
        sprintf(self::SITE_DATA_GET_API_TEMPLATE, $siteId);
    $response = wp_remote_get($url, array('timeout' => 30));
    if (is_wp_error($response)) {
      $errorMessage = $response->get_error_message();
      if ($errorMessage === __(self::HTTP_BLOCKED_MESSAGE)) {
        return GooglePublisherPluginUpdaterStatus::HTTP_BLOCKED . ':' .
            $errorMessage;
      } else {
        return GooglePublisherPluginUpdaterStatus::CONNECTION_FAILURE . ':' .
            $errorMessage;
      }
    }
    $decoded = json_decode(wp_remote_retrieve_body($response), true);
    if (!isset($decoded)) {
      return GooglePublisherPluginUpdaterStatus::INVALID_JSON;
    }
    if (!is_array($decoded)) {
      return GooglePublisherPluginUpdaterStatus::INVALID_DATA .
          ':the response is not an array';
    }
    if (array_key_exists('error', $decoded)) {
      if (array_key_exists('message', $decoded['error'])) {
        return GooglePublisherPluginUpdaterStatus::SERVER_ERROR . ':' .
            $decoded['error']['message'];
      } else {
        return GooglePublisherPluginUpdaterStatus::SERVER_ERROR;
      }
    }
    $httpStatus = wp_remote_retrieve_response_code($response);
    // wp_remote_retrieve_response_code may return either a number or a string.
    if ($httpStatus !== '200' && $httpStatus !== 200) {
      return GooglePublisherPluginUpdaterStatus::HTTP_ERROR . ':' . $httpStatus;
    }
    return $this->parseAndSaveNotification($decoded);
  }

  /**
   * Parses and saves notification.
   *
   * @return OK on success, an error on failure.
   */
  private function parseAndSaveNotification($decoded) {
    if (!array_key_exists('notification', $decoded)) {
      return GooglePublisherPluginUpdaterStatus::OK;
    }
    $notification = $decoded['notification'];
    if (!is_array($notification)) {
      return GooglePublisherPluginUpdaterStatus::INVALID_NOTIFICATION .
          ':unexpected notification received (array expected)';
    }
    if (array_key_exists('status', $notification)) {
      $status = $notification['status'];
    } else {
      return GooglePublisherPluginUpdaterStatus::INVALID_NOTIFICATION .
          ':missing status';
    }
    if ($this->configuration->writeNotification($status)) {
      return GooglePublisherPluginUpdaterStatus::OK;
    } else {
      return GooglePublisherPluginUpdaterStatus::WORDPRESS_ERROR;
    }
  }
}
