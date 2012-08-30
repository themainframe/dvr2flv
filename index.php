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
include 'libs/dvr.lib.php';
include 'libs/ffmpeg.lib.php';
include 'libs/iface.lib.php';

//
// session manaagement
//
session_start();

//
// main control
//
switch($_GET['act'])
{

  case 'logout':
  
    session_destroy();
    load_page('connect_form');
  
    break;

  case 'dvr':
  
    load_page('dvr');
    
    break;
  
  case 'connect':
  
    // Create a DVR session
    $_SESSION['id'] = $_POST['ip'];
    $_SESSION['ip'] = $_POST['ip'];
    $_SESSION['uname'] = $_POST['uname'];
    $_SESSION['passwd'] = $_POST['passwd'];
    $_SESSION['port'] = $_POST['port'];
    
    // Create a directory for the cache
    mkdir('cache/' . $_SESSION['id']);
    
    load_page('initial_connect');
    non_blocking();
    
    // Attempt to get files for yesterday
    $result = dvr_get_files(
      date('Y', time() - 24 * 60 * 60 + 1),   // Year
      date('n', time() - 24 * 60 * 60 + 1),   // Month
      date('j', time() - 24 * 60 * 60 + 1)    // Day
    );
    
    
    
    if($result === false)
    {
      redirect('./?act=error');
    }
    else
    {
      // Ok!
      sleep(1);
      redirect('./?act=dvr');
    }
    
    break;
    
  case 'error':
  
    session_destroy();
    load_page('connect_error');
  
    break;
}

// does DVR session exist?
if(!$_SESSION['id'] || !file_exists('cache/' . $_SESSION['id']))
{
  // No.
  load_page('connect_form');
  exit();
}

?>