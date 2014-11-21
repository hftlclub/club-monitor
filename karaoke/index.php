<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- used to start like an app with google chrome on android -->
    <meta name="mobile-web-app-capable" content="yes">

    <!-- <meta http-equiv="refresh" content="3; URL=<?php echo $_SERVER['REQUEST_URI'] ?>"> -->
    <title>Karaokemanager - Überblick</title>

    <!-- Bootstrap -->
    <link href="../common/css/bootstrap.min.css" rel="stylesheet">
    <link href="../common/css/bootstrap.cyborg.min.css" rel="stylesheet">
    <link href="../common/css/style.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="../common/js/bootstrap.min.js"></script>

    <!-- Disable mark text -->
    <script src="../common/js/disable_mark_text.js"></script>

    <script type="text/javascript"> 
   		function refresh() {
   			$('#refresh').load('refresh.php?'+ 1*new Date());
            $('#loading_state').fadeOut();
            $('#song_table').fadeIn();
            $('#song_table').css('visibility','visible').fadeIn().removeClass('hidden');
  		}
   		var auto_refresh = setInterval(function() { refresh() }, 1000);
   		refresh();
   		
   		
	</script>
    
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="page-header">
                <h1>Karaoke-Playlist</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-8">
                <h2 id="loading_state" class="text-center">... loading ...</h2>
                <table class="table table-striped invisible" id="song_table">
                    <thead>
                        <th>#</th>
                        <th>nächster Song</th>
                        <th>Sänger</th>
                    </thead>
                    <tbody id="refresh"></tbody>
                	<!-- script calls table in refresh.php -->
              
                </table>
            </div>
            <div class="col-xs-12 col-md-4">
                <div class="center-block" style="text-align:center;">
                    <button type="button" class="btn btn-default btn-lg" onclick="window.location.href='./input.html'">
                        <span class="glyphicon glyphicon-plus largeicon"></span><br />Song reservieren
                    </button>
                </div>
            </div>
        </div>
    </div>
      
</body>
</html>
