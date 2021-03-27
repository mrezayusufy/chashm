<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Notification_model extends CI_Model {

  function __construct() {
    parent::__construct();
  }
  function send_notification($message = "", $sender = "") {

  }
}