<?php
/**
 * Created by PhpStorm.
 * User: gautier
 * Date: 14/07/2014
 * Time: 10:45
 */

$sql = "CREATE TABLE $table_name (
  id mediumint(9) NOT NULL AUTO_INCREMENT,
  service tinytext NOT NULL,
  base_url text NOT NULL,
  catable tinytext NOT NULL,
  mode tinytext,
  query text,
  time timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  result blob,
  UNIQUE KEY id (id)
);";