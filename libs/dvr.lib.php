<?php
/**
 * H.264 Network DVR Web Interface
 * © Damien Walsh & Transcend Solutions 2012
 *
 * @author Damien Walsh <me@damow.net>
 * @version 1.0
 */

/**
 * Convert an array of data into a binstring
 * 
 * @param array $data The data.
 * @param integer $pad Optionally a padding value to pad to with NUL bytes.
 * @return string
 */
function get_chrs($data, $pad = -1)
{
  $result = '';
  $bCount = 0;
  
  foreach($data as $d)
  {
    $result .= chr($d);
    $bCount ++;
  }
  
  // Pad?
  if($pad)
  {
    for(; $bCount < $pad; $bCount ++)
    {
      $result .= chr(0x00);
    }
  }
  
  return $result;
}

/**
 * Get a string of NULs of a specified length
 * 
 * @param integer $nuls Number of NULs to get.
 * @return string
 */
function get_nuls($nuls)
{
  $result = '';
  
  for($c = 0; $c < $nuls; $c ++)
  {
    $result .= chr(0x00);
  }
  
  return $result;
}

/**
 * Connect to the DVR and download the file list for a specified date
 * Data will be stored in the current cache directory.
 *
 * @param integer $year The year to search for.
 * @param integer $month The month to search for.
 * @param integer $day The day of the month to search for.
 * @param integer $chanNumber Optionally the channel number.  Default 1.
 *
 * @return boolean
 */
function dvr_get_files($year, $month, $day, $chanNumber = 1)
{
  // Convert to bytes
  $yearByte = intval(substr($year, 2, 2));
  $monthByte = intval($month);
  $dayByte = intval($day);
  $chanNumber = intval($chanNumber);
 
  // Connect to socket
  $dvr = fsockopen($_SESSION['ip'], intval($_SESSION['port']),
    $errno, $errstr, 5);
  
  if(!$dvr)
  {
    return false;
  }
  else
  {
    fwrite($dvr, get_chrs(array(
      0x00, 0x00, 0x00, 0x01, 0x00, 0x00, 0x00, 0x09,
      0x01, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 
      0x00, 0x00, 0x00, 0x28, 0x03, 0x00, 0x00, 0x00,
      $chanNumber, 0x00, 0x00, 0x00, $yearByte, $monthByte,
      $dayByte, 0x00, 0x00, 0x17, 0x3b
    ), 500));
 
    $data = '';
 
    while(!feof($dvr))
    {
      $data .= fgets($dvr, 128); 
    }
    
    fclose($dvr);
    
    error_log('Got ' . strlen($data) . ' bytes of data from DVR');
    
    $matches = array();
    $mCount = preg_match_all('/\/stm[a-z0-9-\/]*\.nvr/', $data, $matches);
    $matches = $matches[0];
    
    return $matches;
  }
}

/**
 * Connect to the DVR and download the specified file.
 * The file will be saved in $target
 *
 * @param string $file The file to download as presented by dvr_get_files()
 * @param string $target The place to save the file on this machine.
 * @return boolean
 */
function dvr_save_file($file, $target)
{
  // Connect to socket
  $dvr = fsockopen($_SESSION['ip'], intval($_SESSION['port']),
    $errno, $errstr, 5);
  
  if(!$dvr)
  {
    return false;
  }
  else
  {
    // Open target file for writing
    $tfile = fopen($target . '.h264', 'w');
  
    fwrite($dvr, get_chrs(array(
      0x00, 0x00, 0x00, 0x01, 0x00, 0x00, 0x00, 0x07,
      0x0b, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 
      0x00, 0x00, 0x00, 0xac, 0x00, 0x00, 0x00, 0x01,
      0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 
      0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 
      0x00, 0x00, 0x00, 0x00)) . $file . get_nuls(376));
  
    // Read bytes and write them to the target file
    $chunk = null;
    while(!feof($dvr))
    {
      $chunk = fgets($dvr, 1024); 
      fwrite($tfile, $chunk);
    }
  }
  
  fclose($dvr);
  fclose($tfile);
  
  // Convert the file
  $output = '';
  exec('./ffmpeg.sh ' . $target, $output);
  
  // Delete the old file
  exec('rm ' . $target . '.h264');
  
  // Return OK!
  return true;
}

?>