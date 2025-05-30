
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discover DZ - Partagez vos expériences de voyage !</title>
    <link rel="stylesheet" type="text/css" href="styleaccueil.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20,200,1,-25">
    <link rel="stylesheet" href="icones/all.css">
</head>
<body>

    <header>
        <div class="mydiv">
            <img src="image/logo3.jpg" alt="Discover DZ Logo">
            <a href="accueil.php" style="text-decoration: none;"><h1>DISCOVER DZ</h1></a>
        </div>
        <nav class="navv">
            <ul class="navbar">
               <li><a href="explore.php">EXPLORE</a></li> 
               <li> <nav class="menu-container">
                <ul class="menu">
                    <li class="menu-item">
                        <a href="accueil.php" id="destination">DESTINATIONS</a>
                        <div class="submenu">
                            <ul class="submenu-column">
                                <li><strong>Nord</strong></li>
                                <li><a href="#">Alger</a></li>
                                <li><a href="#">Blida</a></li>
                                <li><a href="#">Boumerdès</a></li>
                                <li><a href="#">Tipaza</a></li>
                                <li><a href="#">Tizi Ouzou</a></li>
                                <li><a href="#">Béjaïa</a></li>
                                <li><a href="#">Jijel</a></li>
                                <li><a href="#">Skikda</a></li>
                            </ul>
                            
                            <ul class="submenu-column">
                                <li><strong>Est</strong></li>
                                <li><a href="#">Constantine</a></li>
                                <li><a href="annaba.php">Annaba</a></li>
                                <li><a href="#">Guelma</a></li>
                                <li><a href="#">Oum El Bouaghi</a></li>
                                <li><a href="#">Tébessa</a></li>
                                <li><a href="#">Souk Ahras</a></li>
                                <li><a href="#">Khenchela</a></li>
                                <li><a href="#">Batna</a></li>
                            </ul>
        
                            <ul class="submenu-column">
                                <li><strong>Ouest</strong></li>
                                <li><a href="#">Oran</a></li>
                                <li><a href="#">Tlemcen</a></li>
                                <li><a href="#">Aïn Témouchent</a></li>
                                <li><a href="#">Mostaganem</a></li>
                                <li><a href="#">Relizane</a></li>
                                <li><a href="#">Mascara</a></li>
                                <li><a href="#">Sidi Bel Abbès</a></li>
                                <li><a href="#">Saïda</a></li>
                            </ul>
        
                            <ul class="submenu-column">
                                <li><strong>Sud</strong></li>
                                <li><a href="#">Adrar</a></li>
                                <li><a href="#">Tamanrasset</a></li>
                                <li><a href="#">Illizi</a></li>
                                <li><a href="#">Djanet</a></li>
                                <li><a href="#">Bordj Badji Mokhtar</a></li>
                                <li><a href="#">Timimoun</a></li>
                                <li><a href="#">Tindouf</a></li>
                                <li><a href="#">In Salah</a></li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </nav>


</li>
               <li><a href="offre.php">OFFRE</a></li>
               <li><a href="ajouter_votre_etablissement.php">AJOUTER VOTRE ETABLISSEMENT</a></li>
               <li><a href="admin.php" class="admin-btn"><i class="fa-solid fa-lock"></i> ADMIN</a></li>
            </ul>
        </nav>
        <div class="myi">
            <a href="https://www.facebook.com/"><i class="fa-brands fa-facebook-f icon-btn"></i></a>
            <a href="https://www.instagram.com/"><i class="fa-brands fa-instagram icon-btn"></i></a>
        </div>
    </header>

   
    
   


    <div class="div1">
        <nav class="nav1">
            <ul class="navbar1">
                <li><a href="creer-carnet.php">ÉCRIRE SON CARNET DE VOYAGE</a></li> 
                <li><a href="creer-experience.php">ÉCRIRE SON EXPÉRIENCE</a></li>
                <div class="posi" >
                    <div class="posi" >
                        <button onclick="getLocation()">TROUVER VOTRE POSITION</button>
                          <p id="position"></p>
                      </div>
                </div>
            </ul>
        </nav>
        <nav class="nav2">
            <ul class="navbar2">
                <li><a href="connecter.php">SE CONNECTER</a></li> 
                <li><a href="s'inscrire.php">S'INSCRIRE</a></li>
            </ul>
        </nav>
    </div>

    <div class="img">
        <div class="sousimg " >
           <h1 ><span>CRÉEZ VOTRE</span><br>CARNET DE VOYAGE</h1> 
           <p>En quelques clics, partagez gratuitement votre blog de voyage</p> 
        </div>
      </div>

    <div class="div2">
      <img src="image/logo2.jpeg" alt="logo">
        <div class="carte">
            <p>
                Discover DZ est une plateforme sur le thème du voyage.<br>
                Elle vous permet de créer des <a href="creer-experience.php"><b>expériences</b></a> et des <a href="creer-carnet.php"><b>carnets de voyage</b></a> gratuitement, et de les 
                partager avec vos amis et votre famille.<br>
                Elle vise aussi à vous aider dans votre voyage en proposant des itinéraires adaptés à votre localisation et à votre destination.
            </p>
        </div>
        <img src="image/logo2.jpeg" alt="logo">
    </div>

    <div class="container">
        <div class="item">
            <div class="icon"><i class="fa-solid fa-pencil"></i></div>
            <h2>Racontez</h2>
            <p>Racontez vos aventures de voyage et faites rêver vos proches avec vos plus beaux souvenirs !</p>
            </div>
        <div class="item">
            <div class="icon"><i class="fa-solid fa-comment-dots"></i></div>
            <h2>Partagez</h2>
            <p>Partagez votre blog de voyage en toute simplicité avec vos proches et faites-les voyager à travers vos récits.</p>
        </div>
        <div class="item">
            <div class="icon"><i class="fa-solid fa-cloud-arrow-down"></i></div>
            <h2>Conservez</h2>
            <p>Conservez précieusement vos souvenirs de voyage et vos itinéraires pour revivre à tout moment vos meilleurs instants !</p>
        </div>
    </div>

  <div class="bloc">
        <div class="containerleft">
          <i  class="fa-solid fa-pencil" style="color: darkblue;margin-left: 50px;font-size:large;"></i><h1>Créer mon blog de voyage</h1>
          <img src="image/acc.jpeg" class="acc"><br>
          <span class="spin">De façon intuitive, enrichissez vos récits de voyage en intégrant :</span>
          <ul class="cont">
            <li>Des <b>textes</b></li>
            <li>Des <b>phots</b></li>
            <li>Des <b>vidéos</b></li>
            <li>Des <b>conseils/infos pratiques</b></li>
            <li>Des <b>cartes</b></li>
          </ul>
          <p class="cont1"><b>Géolocalisez</b>chaque étape pour créer une carte interactive mettant en valeur les lieux incontournables de chaque wilaya d’Algérie.<br>
            Indiquez vos<b> moyens de transport</b> pour affiner l’itinéraire et aider les voyageurs à mieux organiser leurs déplacements.</p>
    
        </div>

<div class="containerright">
      <i class="fa-solid fa-earth-africa" style="color: darkblue;margin-left: 50px;font-size:large;"></i><h1>Trouver votre position</h1>
      <img src="image/img8.jpg"><br>
      <span class="spin">Trouver votre position en quelques minutes</span><br><br>
      <button class="butn" onclick="getLocation()">TROUVER VOTRE POSITION</button>
      <p id="position"></p>
      <br><br>
     ________________________________________ <b>OU</b>_____________________________________
     <h3>Sélectionnez votre destination</h3>
     <div class="para">
     <SELECT NAME="param">
      <OPTION VALUE="1">Adrar</OPTION>
      <OPTION VALUE="2" >Chlef</OPTION>
      <OPTION VALUE="3">Laghouat</OPTION>
      <OPTION VALUE="4">D'oum el bouaghi</OPTION>
      <OPTION VALUE="5">Batna</OPTION>
      <OPTION VALUE="6">Béjaia</OPTION>
      <OPTION VALUE="7">Biskra</OPTION>
      <OPTION VALUE="8">Béchar</OPTION>
      <OPTION VALUE="9">Blida</OPTION>
      <OPTION VALUE="10">Bouira</OPTION>
      <OPTION VALUE="11">Tamanrasset</OPTION>
      <OPTION VALUE="12">Tébessa</OPTION>
      <OPTION VALUE="13">Tlemcen</OPTION>
      <OPTION VALUE="14">Tiaret</OPTION>
      <OPTION VALUE="15">Tizi ouzou</OPTION>
      <OPTION VALUE="16">Alger</OPTION>
      <OPTION VALUE="17">Djelfa</OPTION>
      <OPTION VALUE="18" >Jijel</OPTION>
      <OPTION VALUE="19">Sétif</OPTION>
      <OPTION VALUE="20">Saida</OPTION>
      <OPTION VALUE="21">Skikda</OPTION>
      <OPTION VALUE="22">Sidi bel abbès</OPTION>
      <OPTION VALUE="23">Annaba</OPTION>
      <OPTION VALUE="24">Guelma</OPTION>
      <OPTION VALUE="25">Constanti e</OPTION>
      <OPTION VALUE="26">Médéa</OPTION>
      <OPTION VALUE="27">Mostaganem</OPTION>
      <OPTION VALUE="28">M'Sila</OPTION>
      <OPTION VALUE="29">Mascara</OPTION>
      <OPTION VALUE="30">Ouargla</OPTION>
      <OPTION VALUE="31">Oran</OPTION>
      <OPTION VALUE="32">Bayadh</OPTION>
      <OPTION VALUE="33">Illizi</OPTION>
      <OPTION VALUE="34" >Bordj bou arreridj</OPTION>
      <OPTION VALUE="35">Boumerdès</OPTION>
      <OPTION VALUE="36">Taref</OPTION>
      <OPTION VALUE="37">Tindouf</OPTION>
      <OPTION VALUE="38">Tissemsilt</OPTION>
      <OPTION VALUE="39">Oued</OPTION>
      <OPTION VALUE="40">Khenchela</OPTION>
      <OPTION VALUE="41">Souk ahras</OPTION>
      <OPTION VALUE="42" >Tipaza</OPTION>
      <OPTION VALUE="43">Mila</OPTION>
      <OPTION VALUE="44">Ain defla</OPTION>
      <OPTION VALUE="45">Naama</OPTION>
      <OPTION VALUE="46">Témouchent</OPTION>
      <OPTION VALUE="47">Ghardaia</OPTION>
      <OPTION VALUE="48">Relizane</OPTION>
      <OPTION VALUE="49">Timimoun</OPTION>
      <OPTION VALUE="50" >Bordj badji mokhtar</OPTION>
      <OPTION VALUE="51">Ouled djellal</OPTION>
      <OPTION VALUE="52">Béni abbaès</OPTION>
      <OPTION VALUE="53">In salah</OPTION>
      <OPTION VALUE="54">In guezzam</OPTION>
      <OPTION VALUE="55">Touggourt</OPTION>
      <OPTION VALUE="56">Djanet</OPTION>
      <OPTION VALUE="57">M'ghair</OPTION>
      <OPTION VALUE="58" >Meniaa</OPTION>
      </SELECT>
      <INPUT TYPE="SUBMIT" NAME="bouton" value="Valider">
    </div>
      
    </div>
  </div>


<div class="ville">
        <h1>Destinations phares</h1>
        <div class="villes">
            <div class="alger image-container">
                <img src="image/img1.jpg" alt="Alger">
                <div class="city-info" id="alg">
                    <a href="alger.html" style="text-decoration: none;color:white;"><span>ALGER</span></a>
                   
                    
                </div>
            </div>
            <div class="vill">
                <div class="annaba">
                   <div class="image-container">
                        <img src="image/img6.jpg" alt="Annaba">
                        <div class="city-info" >
                            <a href="annaba.html" style="text-decoration: none;color:white;"><span>ANNABA</span></a>
                        
                            
                        </div>
                    </div>
                    <div class="image-container">
                        <img src="image/img7.jpg" alt="Béjaia">
                        <div class="city-info" > 
                            <a href="bejaia.html" style="text-decoration: none;color:white;"><span>BÉJAIA</span></a>
                          
                            
                        </div>
                    </div>
                </div>
                <div class="oran image-container">
                    <img src="image/img2.jpg" alt="Oran">
                    <div class="city-info" id="or">
                        <a href="oran.html" style="text-decoration: none;color:white;"><span>ORAN</span></a>
                        
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    


    <footer class="footer">
        <div class="footer-container">
          <!-- Contact -->
          <div class="footer-column">
              <h3>Contactez-nous</h3>
              <div class="footer-social">
                <a href="https://www.facebook.com/">  <i class="fa-brands fa-facebook-f" style="color:  darkblue;"></i></a>
                <a href="https://www.instagram.com/">  <i class="fa-brands fa-instagram"  style="color:  darkblue;"></i></a>
                <a href="https://www.gmail.com/"> <i class="fa-solid fa-envelope"  style="color:  darkblue;"></i></a>
          </div>
          </div>
  
  
  
        
            <div class="footer-section">
              <h2>Liens utiles</h2>
              <ul>
                <li><a href="#">Trouver votre localisation</a></li>
                <li><a href="offre.php">Offre</a></li>
              </ul>
            </div>
        
            <div class="footer-section">
              <h2>À propos</h2>
              <p>DiscoverDZ vous permet de raconter vos aventures et vous aide à les partager avec vos amis et d'autres passionnés de voyages.</p>
            </div>
        
            <div class="footer-section">
              <h2>Wilayas à découvrir</h2>
              <ul>
                <li><a href="alger.php">Alger</a></li>
                <li><a href="annaba.php">Annaba</a></li>
                <li><a href="constantine.php">Constantine</a></li>
                <li><a href="mostaganem.php">Mostaganem</a></li>
                <li><a href="#">...</a></li>
    
              </ul>
            </div>
          </div>
        </footer>
        <script src="icones/all.js"></script>
        <script src="script1.js"></script>
    
      </body>
      </html>