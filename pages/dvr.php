<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Web DVR Playback Interface</title>
    <link rel="stylesheet" type="text/css" href="static/default.css" />
		<link type="text/css" href="css/smoothness/jquery-ui-1.8.22.custom.css" rel="stylesheet" />
		<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui-1.8.22.custom.min.js"></script>
		<script type="text/javascript" src="js/flowplayer-3.2.11.min.js"></script>
		<script type="text/javascript">
		
		  $(function() {
		  
		  var session = '<?php print $_SESSION['id']; ?>';
		  
		    $('.time').live('click', function() {
		    
		      // Mark as loading
		      $(this).addClass('loading');
		      var clickItm = this;
		      $('#status').html('Getting & transcoding ' + $(this).html() + ' - this could take a while...');
		    
		      // Request to remux and display
		      $.getJSON('ajax/download.ajax.php', {'file' : $(this).attr('file') }, function(data) {
            
            // Set file
            flowplayer("player", "../dvr/js/flowplayer-3.2.14.swf", 
              './cache/' + session + '/' + data.file);
            
            $(clickItm).removeClass('loading');
            $(clickItm).addClass('ok');
            
            $('#status').html('Finished downloading & transcoding ' + $(clickItm).html());
            
		      });
		    
		    });
		  
		    $('#date').datepicker({
		      'dateFormat' : 'd/m/yy',
		      'onSelect': function(dateText, inst) {
		        
		        doSelect(dateText);
		        
		      }
		    
		    });
		    
		    // Init
		    $('#date').datepicker('setDate', '+0');
		    var today = new Date();
		    doSelect(today.getDate().toString() + '/' + (today.getMonth() + 1).toString() + '/' + today.getFullYear().toString());
		  
		  });
		
		  function doSelect(dateText)
		  {
		    console.log(dateText);
        dateData = dateText.split('/');
		 
        day = dateData[0];
        month = dateData[1];
        year = dateData[2];
        
        $('#status').html('Getting file list...');
        
        // Make the request
        $.getJSON('ajax/getfiles.ajax.php', {
          'day': day, 'month': month, 'year': year
        }, function(data) {
        
          $('#status').html('Got file list.');
        
          // Clear items
          $('.time').remove();
        
          $.each(data, function(i, k) {
          
            var newTime = $('<div />').addClass('time');
                                      
            // Add full filename
            $(newTime).attr('file', k.name);
            
            // Parse a "nice" name
            var hour = k.name.substr(50, 2);
            var minute = k.name.substr(52, 2);
            var second = k.name.substr(54, 2);
            
            var time = hour.toString() + ':' + minute.toString() + ':' + second.toString();
                                  
            $(newTime).html(time); 
            
            if(k.name.indexOf('p101') != -1)
            {
              // MD happened.
              $(newTime).addClass('alarm');
            }
            
            if(k.exists == '1')
            {
              // File already got
              $(newTime).addClass('ok');
            }
                                      
            $('#times').append(newTime);
          
          });
        
        });
		  }
		
		</script>
  </head>
  <body>
    <table class="layout">
      <tr>
        <td>
          <div class="middleiface" style="width: 840px">
            <h1>Browsing <?php print strip_tags($_SESSION['ip']); ?></h1>
            
            <table style="width: 100%; height: 480px">
              
              <tr>
              
                <td style="vertical-align: top; width: 190px">
                  
                  <strong>Date:</strong> <br /><br />
                  <input type="text" style="width: 130px" id="date" />
                  
                  <br /><br />
                  
                  <strong>Time:</strong> <br /><br />
                  
                  <div style="width: 175px; height: 350px" id="times">
                    
                  </div>
                  
                  
                </td>
              
                <td>
                  <div class="flashplayer" style="width: 640px; height: 480px; background: #dfdfdf">
                  
		<a  
			 style="display:block;width:640px;height:480px"  
			 id="player"> 
		</a> 
		
		<script>
			flowplayer("player", "../dvr/js/flowplayer-3.2.14.swf", '');
		</script>
                  
                  </div>
                </td>
              
              </tr>
            
            </table>
            
            <hr />
            
            <span class="status" style="float: left" id="status"></span>
            <a href="./?act=logout" style="float: right">Exit</a>
            
            
            <br />
            
            
          </div>
        </td>
      </tr>
    </table>
  </body>
</html>