
<?php
                    require("../common/config.php");

                    $sql = mysql_query("SELECT songlist.interpret, songlist.title, queue.singer, queue.id FROM songlist, queue WHERE queue.songid = songlist.id AND queue.played = 0 ORDER BY timestamp ASC;");
                    $i = 1;
                    while($row = mysql_fetch_assoc($sql)):
                    ?>
                    <tr>
                        <td><?php echo $i++ ?></td>
                        <td><?php echo $row['interpret']." - ".$row['title'] ?></td>
                        <td><?php echo $row['singer'] ?></td>
                    </tr>
<?php endwhile; ?>
