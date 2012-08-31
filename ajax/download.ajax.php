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
include '../libs/ffmpeg.lib.php';

// Start session
session_start();

// Get filename
$file = $_GET['file'];

if(file_exists('../cache/' . $_SESSION['id'] . '/' . basename($file) . '.flv'))
{
  $data = json_encode(array(
    'file' => basename($file) . '.flv'
  ));
  
  print $data;

  exit();
}


//
// Kill the connection but continue loading
//
ob_end_flush(); 
header('Connection: close'); 
set_time_limit(0);
ignore_user_abort(true); 

// Start buffer
ob_start(); 
header('Content-Length: 0'); 
ob_end_flush(); 
flush();

// Reenable when jobs coded!

session_write_close();


// Start downloading
dvr_save_file($file, '../cache/' . $_SESSION['id'] . '/' . 
  basename($file) . '.flv');
  
$data = json_encode(array(
  'file' => basename($file) . '.flv'
));

print $data;