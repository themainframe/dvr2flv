<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Web DVR Playback Interface</title>
    <link rel="stylesheet" type="text/css" href="static/default.css" />
		<link type="text/css" href="css/smoothness/jquery-ui-1.8.22.custom.css" rel="stylesheet" />
		<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui-1.8.22.custom.min.js"></script>
  </head>
  <body>
    <table class="layout">
      <tr>
        <td>
          <div class="middleiface">
            <h1>Connect to DVR</h1>
            
            <form action="./?act=connect" method="post">
            
            <table>
              <tr class="item">
                <td class="label">IP</td>
                <td class="value"><input name="ip" value="192.168.0.109" type="text" /></td>
              </tr>
              <tr class="item">
                <td class="label">Port</td>
                <td class="value"><input name="port" type="text" style="width: 50px;" value="9000" /></td>
              </tr>    
              <tr class="item">
                <td class="label">Username</td>
                <td class="value"><input name="uname" type="text" value="admin" /></td>
              </tr>  
              <tr class="item">
                <td class="label">Password</td>
                <td class="value"><input name="passwd" type="password" /></td>
              </tr>                  
            </table>

            <hr />
            
            <input type="submit" value="Connect" class="button" />
            
            </form>
            
          </div>
        </td>
      </tr>
    </table>
  </body>
</html>