<!DOCTYPE html>
<html>
    <?php
        include_once 'head.php'
    ?>
    <body>
        <div>
        <?php
            include_once 'header.php'
        ?> 
        <div class="gesamtbox">

            <div class='eingabebox'>
                <h1>Erstellen Sie ihren Auftrag :</h1>
                <form action="/">
                        <label for="name">Name</label> <input type="text" name="name" id="name" required placeholder="Max Mustermann"> <br>
                        <label for="address">Address</label> <input type="text" name="address" id="address" required placeholder="31, rue des Prés"> <br>
                        <label for="auftrag">Auftrag</label> <input type="text" name="auftrag" id="auftrag" required placeholder="Karotten kaufen"> <br>
                        <label for="dauer">Dauer</label> <input type="number" name="dauer" id="dauer" min=1 max=4 required value=1> Std.  <br>
                        <button type="submit">Senden</button> <input type="hidden" name="mode" value="save">
                </form>
        <?php

        if(isset($_GET['mode']) && $_GET['mode'] == 'save'){
            $name = $_GET['name'];
            $address = $_GET['address'];
            $auftrag = $_GET['auftrag'];
            $dauer = $_GET['dauer'];
            
            $control = SafeAnnonce($name, $address, $auftrag, $dauer);
            if($control == 1) {
                echo "<p>Sie haben vergessen ihren Namen einzugeben.</p>";
            } else if($control == 2){
                echo "<p>Sie haben vergessen ihre Adresse einzugeben.</p>";
            } else if($control ==3){
                echo "<p>Sie haben vergessen einen Auftrag aufzugeben.</p>";
            } else if($control ==4 ){
                echo "<p>Sie haben vergessen einen Dauer für den Auftrag aufzugeben.</p>";
            } else if($control == "FALSE") {
                echo "<p>Fehler beim Speichervorgang.</p>";
            }
        }
        ?>
            </div>
        <?php
       if(!is_file('annonces.txt')){
            file_put_contents('annonces.txt','');
       }
       $annonces=file_get_contents ('annonces.txt');
        $now = time();

       echo '<div class="annonces"><h1>Momentane Aufträge :</h1>';
       
        foreach(preg_split("/((\r?\n)|(\r\n?))/", $annonces) as $line){
            $annonce = preg_split("/\^/", $line);
            if($annonce[0]=='ANNONCE' && $annonce[4] >= $now){
                echo '<div class="annonce"><span class="name">'.$annonce[1].'</span>
                    <span class="address">'.$annonce[2].'</span><span class="auftrag">'.$annonce[3].'</span>
                    <span class="dauer">Noch gültig für '.floor(($annonce[4]-$now)/60).' Minuten.</span></div>';
                  
            } 
              
        }
        echo '</div>';

        function SafeAnnonce($name, $address, $auftrag, $dauer){
            if($name==""){
                return 1;
            } else if($address==""){
                return 2;
            } else if($auftrag==""){
                return 3;
            } else if($dauer==""){
                return 4;
            }

            $endzeit = time() +($dauer*60*60);
            $rand = rand(1000, 9999);
            $annonce = "\nANNONCE^".$name.'^'.$address.'^'.$auftrag.'^'.$endzeit.'^'.$rand.'^';
               // echo '<span id="code">Noch vor Ablauf der Gültgkeit ihres Auftrags, können Sie mit diesem Code ihren Auftrag löschen : '.$rand.'</span>';
            return file_put_contents('annonces.txt',$annonce,FILE_APPEND);
        }
          
        ?>
            </div>
        </div>
    </body>
</html>
