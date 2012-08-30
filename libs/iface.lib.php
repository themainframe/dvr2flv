<?php
/**
 * H.264 Network DVR Web Interface
 * © Damien Walsh & Transcend Solutions 2012
 *
 * @author Damien Walsh <me@damow.net>
 * @version 1.0
 */
 
/**
 * Redirect to a URL using JS Injection
 * @param string $URL The URL to go to
 * @return boolean
 */
function redirect($URL)
{
  print '<script type="text/javascript">window.location="' . $URL . '";</script>';
}

 
/**
 * Load a page into the interface
 * @param string $page The name of the page file to load.
 * @return boolean
 */
function load_page($page)
{
  $page = str_replace('.', '', $page);
  
  include 'pages/' . $page . '.php';
}

/**
 * Prevent the interface blocking
 * @return boolean
 */
function non_blocking()
{
  flush();
  
  return true;
}