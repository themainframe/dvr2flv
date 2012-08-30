<?php
/**
 * H.264 Network DVR Web Interface
 * © Damien Walsh & Transcend Solutions 2012
 *
 * @author Damien Walsh <me@damow.net>
 * @version 1.0
 */
 
//
// includes
//
include '../libs/dvr.lib.php';

// Start session
session_start();

// Find files
$result = dvr_get_files(
  date($_GET['year']),   // Year
  date($_GET['month']),   // Month
  date($_GET['day'])    // Day
);

error_log("Getting {$_GET['year']}, {$_GET['month']}, {$_GET['day']}, $result");

$fList = array();
foreach($result as $file)
{
  $exists = 0;

  if(file_exists('../cache/' . $_SESSION['id'] . '/' . basename($file) . '.flv'))
  {
    $exists = '1';
  }
  
  error_log(file_exists('../cache/' . $_SESSION['id'] . '/' . basename($file) . '.flv'));
  
  $fList[] = array(
    'name' => $file,
    'exists' => $exists
  );
}

$data = json_encode($fList);

print $data;
