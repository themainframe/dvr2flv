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

function parse_percent($value)
{
  if($value > 100)
  {
    return '99.00';
  }
  
  return number_format($value, 2);
}

// Start session
session_start();

// Check a file state and respond with a percentage (approximated)
$file = $_GET['file'];
$flvFile = '../cache/' . $_SESSION['id'] . '/' . basename($file) . '.flv';
$nvrFile = '../cache/' . $_SESSION['id'] . '/' . basename($file) . '.flv.h264';

if(file_exists($flvFile))
{
  // Return the percentage of the current file

  if(file_exists($nvrFile))
  {
    // Fudge factor.
    print json_encode(array('file' => basename($file) . '.flv', 'progress' => parse_percent((filesize($flvFile) * 3.7) / filesize($nvrFile) * 100), 'flv' => $flvFile, 'nvr' => $nvrFile, 'fs' => filesize($flvFile), 'fs2' => filesize($nvrFile)));
  }
  else
  {
    print json_encode(array('file' => basename($file) . '.flv', 'progress' => 100, 'flv' => $flvFile, 'nvr' => $nvrFile, 'fs' => filesize($flvFile), 'fs2' => filesize($nvrFile)));
  }

  exit();
}

print json_encode(array('file' => basename($file) . '.flv', 'progress' => -1, 'flv' => $flvFile, 'nvr' => $nvrFile, 'fs' => filesize($flvFile), 'fs2' => filesize($nvrFile), 'mbs' => number_format(filesize($nvrFile) / 1024 / 1024, 2)));