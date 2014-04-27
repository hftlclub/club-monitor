<?php
require("../../common/config.php");

$mode = $_GET['mode'];
$id   = $_GET['id'];


//GET mode unregister
if($mode == "unregister" && !empty($id)){
	$sql = "UPDATE queue SET played = 1 WHERE id = '".$id."';";
	mysql_query($sql);

	header("Location: index.php");
	exit();
}


//GET without parameters
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="refresh" content="3; URL=<?php echo $_SERVER['REQUEST_URI'] ?>">
    <title>Karaokemanager - Management</title>

    <!-- Bootstrap -->
    <link href="../../common/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../common/css/bootstrap.cyborg.min.css" rel="stylesheet">
    <link href="../../common/css/style.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="page-header">
                <h1>Karaoke Manager</h1>
            </div>
        </div>
        <div class="row">
            <div>
                <table class="table table-striped">
                    <tr>
                        <th>#</th>
                        <th>Interpret</th>
                        <th>Titel</th>
                        <th>eingetragen von</th>
                        <th>&nbsp;</th>
                    </tr>

                    <?php

                    $sql = mysql_query("SELECT songlist.interpret, songlist.title, queue.singer, queue.id FROM songlist, queue WHERE queue.songid = songlist.id AND queue.played = 0 ORDER BY timestamp ASC;");
                    $i = 1;
                    while($row = mysql_fetch_assoc($sql)){
                    echo "
                    <tr>
                        \n";
                        echo "
                        <td>".$i++."</td>\n";
                        echo "
                        <td>".$row['interpret']."</td>\n";
                        echo "
                        <td>".$row['title']."</td>\n";
                        echo "
                        <td>".$row['singer']."</td>\n";
                        echo "
                        <td><a href=\"index.php?mode=unregister&id=" . $row['id'] . " \">Entfernen</a></td>\n";
                        echo "
                    </tr>\n";
                    }

                    ?>
                </table>
            </div>
        </div>
    </div>


    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="../../common/js/bootstrap.min.js"></script>
</body>
</html>