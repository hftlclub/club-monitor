<tr>
                        <th>#</th>
                        <th>Bereits gewünschte Songs</th>
                    </tr>
<?php
                    require("../common/config.php");

                    $sql = mysql_query("SELECT id, interpret, song, album, href, timestamp, played FROM wishlist WHERE played = 0 ORDER BY timestamp ASC;");
                    $i = 1;
                    while($row = mysql_fetch_assoc($sql)):
                    ?>
                    <tr>
                        <td><?php echo $i++ ?></td>
                        <td><?php echo $row['song']." - ".$row['interpret'] ?></td>
                    </tr>
<?php endwhile; ?>