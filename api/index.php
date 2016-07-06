<?php

require(dirname(__DIR__) . '/vendor/autoload.php');

use ChangelogParser\Manager\ChangelogManager;

header('Content-Type: application/json');

if (isset($_SERVER['HTTP_ORIGIN'])) {
  header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
  header('Access-Control-Allow-Credentials: true');
  header('Access-Control-Max-Age: 86400');    // cache for 1 day
}
// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
  if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
      header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

  if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
      header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
}
else {

  $manager = new ChangelogManager();

  // Set the cache validity time to one day unless forcing a refresh
  if (isset($_GET['force']) && $_GET['force'] === 'true') {
    $manager->getCacheManager()->setCacheTime(0);
  }
  else {
    $manager->getCacheManager()->setCacheTime(60*60*1);
  }

  if (empty($_GET['source'])) {
    $source_url = 'https://bitbucket.org/agaveapi/agave/raw/develop/CHANGELOG.md';
  }
  else {
    $source_url = $_GET['source'];
  }

  if (isset($_GET['latest']) && $_GET['latest'] === 'true') {
    $response = $manager->getLastVersion($source_url);
  }
  else {
    $response = $manager->getAllVersions($source_url);
  }

  echo $response;
}
