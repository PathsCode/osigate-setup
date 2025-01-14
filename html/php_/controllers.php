<?php
include ('session.php');
include('menu.php');
?>

   <br />

     <fieldset>
       <legend><b>Controllers Status: </b></legend><br>
       <?php
       if ($file=fopen("/srv/data/osiRele", "r")) {
           echo "<table border=1>";
           // apri connessione a db
           $connDB = mysqli_connect("localhost", "root", "root");
           if (!$connDB) {
                     exit('Errore di connessione alla basedati ('
                           . mysqli_connect_errno() . ') ' . mysqli_connect_error());
           }
           mysqli_set_charset($connDB, 'utf-8');
           mysqli_select_db($connDB, "TECNOQ");
           echo "<thead><tr><th>Controller ID</th><th>Switch No 1</th><th>Switch No 2</th>
                                                  <th>Switch No 3</th><th>Switch No 4</th>
                                                  <th>Switch No 5</th><th>Switch No 6</th>
                                                  <th>Switch No 7</th><th>Switch No 8</th>
                                                  <th>Last seen</th></tr></thead><tbody>";
           while (($id=fgets($file)) != false) {
                    $idHex='';
                    for ($i=0; $i<6; $i++){
                          $idHex .= dechex(ord($id[$i]));
                    }
//                    $contrStatus = mysqli_query($connDB, "SELECT status, time FROM OSIRE_STATUS WHERE status LIKE '%" . trim($id) . "%'"
                    $contrStatus = mysqli_query($connDB, "SELECT status, time FROM OSIRE_STATUS WHERE status LIKE '%" . $idHex . "%'"
                                                . " ORDER BY time DESC LIMIT 1" );
                    $riga = mysqli_fetch_array($contrStatus);
                    echo "<tr><td>" . $id . "</td><td>" . substr($riga[0], 16, 2) . "</td><td>" .
                                                          substr($riga[0], 22, 2) . "</td><td>" .
                                                          substr($riga[0], 28, 2) . "</td><td>" .
                                                          substr($riga[0], 34, 2) . "</td><td>" .
                                                          substr($riga[0], 40, 2) . "</td><td>" .
                                                          substr($riga[0], 46, 2) . "</td><td>" .
                                                          substr($riga[0], 52, 2) . "</td><td>" .
                                                          substr($riga[0], 58, 2) . "</td><td>" .
                                                          $riga[1] . "</td></tr>";
                    mysqli_free_result($contrStatus);
                 }
           // chiudi connessione db
           mysqli_close($connDB);
           fclose($file);
           echo "</tbody></table><br>";
           echo "FF = ON, 00 = OFF";
       }
       else { echo "NO CONTROLLERS CONFIGURED"; }
       ?>

     </fieldset>

    <br/>

    <div id="login">
      <form action="subphp_/script.php" method="post">
      <b>Add Controller: </b>
        <input id="contr-add" name="contr-add" placeholder="Type Controller ID (Es: R00123)" type="text">
        <input name="submit" type="submit" value=" Add ">
      </form>
    </div>

    <div id="login">
      <form action="subphp_/script.php" method="post">
      <b>Remove Controller: </b>
        <input id="contr-del" name="contr-del" placeholder="Type Controller ID (Es: R00123)" type="text">
        <input name="submit" type="submit" value="Remove">
      </form>
    </div>
    <br/>
    <br/>


<?php
/* code moved to sensorc2.php

    <h3 align="left" style="color:blue">TIME RANGE SETTINGS (Default 00:00 - 23:59)</h3>

    <div id="timeRange">
      <form action="subphp_/script.php" method="post">
        <h4 align="center" style="color:#e65c00">Switch no.1 (RA1)</h4>
        <b>Start time: <br><i><?php $output=shell_exec("/var/www/html/script_/MANAGE_tr showTRON 1"); print_r($output); ?></i>
        <input id="tron1" name="tron1" placeholder="HH:MM" type="text">
        <br>
        <b>Stop time: <br><i><?php $output=shell_exec("/var/www/html/script_/MANAGE_tr showTROFF 1"); print_r($output); ?></i>
        <input id="troff1" name="troff1" placeholder="HH:MM" type="text">
        <input name="submit" type="submit" value="Submit">
      </form>
    </div>

    <div id="timeRange">
      <form action="subphp_/script.php" method="post">
        <h4 align="center" style="color:#e65c00">Switch no.2 (RA2)</h4>
        <b>Start time: <br><i><?php $output=shell_exec("/var/www/html/script_/MANAGE_tr showTRON 2"); print_r($output); ?></i>
        <input id="tron2" name="tron2" placeholder="HH:MM" type="text">
        <br>
        <b>Stop time: <br><i><?php $output=shell_exec("/var/www/html/script_/MANAGE_tr showTROFF 2"); print_r($output); ?></i>
        <input id="troff2" name="troff2" placeholder="HH:MM" type="text">
        <input name="submit" type="submit" value="Submit">
      </form>
    </div>

    <div id="timeRange">
      <form action="subphp_/script.php" method="post">
        <h4 align="center" style="color:#e65c00">Switch no.3 (RB1)</h4>
        <b>Start time: <br><i><?php $output=shell_exec("/var/www/html/script_/MANAGE_tr showTRON 3"); print_r($output); ?></i>
        <input id="tron3" name="tron3" placeholder="HH:MM" type="text">
        <br>
        <b>Stop time: <br><i><?php $output=shell_exec("/var/www/html/script_/MANAGE_tr showTROFF 3"); print_r($output); ?></i>
        <input id="troff3" name="troff3" placeholder="HH:MM" type="text">
        <input name="submit" type="submit" value="Submit">
      </form>
    </div>

    <div id="timeRange">
      <form action="subphp_/script.php" method="post">
        <h4 align="center" style="color:#e65c00">Switch no.4 (RB2)</h4>
        <b>Start time: <br><i><?php $output=shell_exec("/var/www/html/script_/MANAGE_tr showTRON 4"); print_r($output); ?></i>
        <input id="tron4" name="tron4" placeholder="HH:MM" type="text">
        <br>
        <b>Stop time: <br><i><?php $output=shell_exec("/var/www/html/script_/MANAGE_tr showTROFF 4"); print_r($output); ?></i>
        <input id="troff4" name="troff4" placeholder="HH:MM" type="text">
        <input name="submit" type="submit" value="Submit">
      </form>
    </div>
    </br>

    <div id="timeRange">
      <form action="subphp_/script.php" method="post">
        <h4 align="center" style="color:#e65c00">Switch no.5 (RC1)</h4>
        <b>Start time: <br><i><?php $output=shell_exec("/var/www/html/script_/MANAGE_tr showTRON 5"); print_r($output); ?></i>
        <input id="tron5" name="tron5" placeholder="HH:MM" type="text">
        <br>
        <b>Stop time: <br><i><?php $output=shell_exec("/var/www/html/script_/MANAGE_tr showTROFF 5"); print_r($output); ?></i>
        <input id="troff5" name="troff5" placeholder="HH:MM" type="text">
        <input name="submit" type="submit" value="Submit">
      </form>
    </div>

    <div id="timeRange">
      <form action="subphp_/script.php" method="post">
        <h4 align="center" style="color:#e65c00">Switch no.6 (RC2)</h4>
        <b>Start time: <br><i><?php $output=shell_exec("/var/www/html/script_/MANAGE_tr showTRON 6"); print_r($output); ?></i>
        <input id="tron6" name="tron6" placeholder="HH:MM" type="text">
        <br>
        <b>Stop time: <br><i><?php $output=shell_exec("/var/www/html/script_/MANAGE_tr showTROFF 6"); print_r($output); ?></i>
        <input id="troff6" name="troff6" placeholder="HH:MM" type="text">
        <input name="submit" type="submit" value="Submit">
      </form>
    </div>

    <div id="timeRange">
      <form action="subphp_/script.php" method="post">
        <h4 align="center" style="color:#e65c00">Switch no.7 (RD1)</h4>
        <b>Start time: <br><i><?php $output=shell_exec("/var/www/html/script_/MANAGE_tr showTRON 7"); print_r($output); ?></i>
        <input id="tron7" name="tron7" placeholder="HH:MM" type="text">
        <br>
        <b>Stop time: <br><i><?php $output=shell_exec("/var/www/html/script_/MANAGE_tr showTROFF 7"); print_r($output); ?></i>
        <input id="troff7" name="troff7" placeholder="HH:MM" type="text">
        <input name="submit" type="submit" value="Submit">
      </form>
    </div>

    <div id="timeRange">
      <form action="subphp_/script.php" method="post">
        <h4 align="center" style="color:#e65c00">Switch no.8 (RD2)</h4>
        <b>Start time: <br><i><?php $output=shell_exec("/var/www/html/script_/MANAGE_tr showTRON 8"); print_r($output); ?></i>
        <input id="tron8" name="tron8" placeholder="HH:MM" type="text">
        <br>
        <b>Stop time: <br><i><?php $output=shell_exec("/var/www/html/script_/MANAGE_tr showTROFF 8"); print_r($output); ?></i>
        <input id="troff8" name="troff8" placeholder="HH:MM" type="text">
        <input name="submit" type="submit" value="Submit">
      </form>
    </div>
    <br/>
*/
?>

    <br/>

    <h3 align="left" style="color:blue">MTTQ CHANNEL SETTINGS</h3>

    <div id="login">
      <form action="subphp_/script.php" method="post">
      <b>Exchange host IP address: <br><i><?php $output=shell_exec("/var/www/html/script_/MANAGE_exchange"); print_r($output); ?></i></b>
        <input id="exch-ip" name="exch-ip" placeholder="Insert new IP address for the exchange" type="text">
        <input name="submit" type="submit" value="Change">
      </form>
    </div>

    <div id="login">
      <form action="subphp_/script.php" method="post">
      <b>Queue name: <br><i><?php $output=shell_exec("/var/www/html/script_/MANAGE_queue"); print_r($output); ?></i></b>
        <input id="queue_name" name="queue_name" placeholder="Insert new queue name" type="text">
        <input name="submit" type="submit" value="Change">
      </form>
    </div>



  </body>
</html>

