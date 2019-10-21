<?php
     set_time_limit(0);

     function filename_from_uri ($uri) {
          if (is_array($uri)) return "false";
          $parts = explode('/', $uri);
          return array_pop($parts);
     }

     function downloadDistantFile($url, $dest) {
          $options = array(
               CURLOPT_FILE => is_resource($dest) ? $dest : fopen($dest, 'w'),
               CURLOPT_FOLLOWLOCATION => true,
               CURLOPT_URL => $url,
               CURLOPT_FAILONERROR => true, // HTTP code > 400 will throw curl error
          );

          $ch = curl_init();
          curl_setopt_array($ch, $options);
          $return = curl_exec($ch);

          if ($return === false) {
               return curl_error($ch);
          } else {
               return true;
          }
     }

     if (isset($_POST['url'])) {
          $url = $_POST['url'];
          $filename = filename_from_uri($url);

          $file = "download/file_" . rand(0, 9999999) . "_" . rand(0, 9999999) . ".key";

          $error = downloadDistantFile($url, $file);
          if ($error) {


               if ($_POST['bigfile'] == true) {
                    $base64 = '';
               } else {
                    $res = base64_encode(file_get_contents( $file ) );
                    $base64 = trim("!§§($%§%(§%%$%))" . $res . "§(%%$$(%$$");
               }


          } else {
               print 'ERROR';
               die();
          }


     }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>WebDownloader</title>

    <link rel="stylesheet" href="css/main.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500" rel="stylesheet">
    <link rel="icon" type="image/png" href="favicon.png" sizes="512x512">

    <script text="text/javascript">

          window.downloadFile = function downloadFile() {

               var base64 = document.getElementById('base64').innerText;

               base64 = base64.replace("!§§($%§%(§%%$%))", "");
               base64 = base64.replace("§(%%$$(%$$", "");

               base64 = base64.trim();

               var dlnk = document.getElementById('dwnldLnk');
               dlnk.href = "data:application/octet-stream;base64," + encodeURIComponent(base64);
               <?php
                    if ($_POST['bigfile'] == true) {
                         print 'dlnk.href = "https://<URL>/' . $file . '";';
                    }
               ?>
               dlnk.click();
          }

    </script>
</head>
<body>
     <div class="topbar"></div>
     <div class="secondtop">
          <div class="no-padding">
               <h1>WebDownloader</h1>
               <h4>a fachsimpeln service</h4>
          </div>
     </div>


     <form action="" method="post">
          <span>URL:</span><br />
          <div class="textbox">
               <input type="text" autocomplete="off" name="url">
          </div>
          <br />

          <div class="toggle-container">
               <span id="sw">Große Datei</span><input type="checkbox" id="switch" name="bigfile"><label for="switch">Große Datei</label>
          </div>

          <input type="submit" value="Datei über Server herunterladen">
     </form>
     <br />

     <?php
          if (isset($base64)) {

               if ($_POST['bigfile'] == false) {
                    print '<div class="success-suround">
                         <div class="success">
                              <span>Datei <i>' . htmlspecialchars($filename, ENT_QUOTES) . '</i> erfolgreich heruntergeladen</span>
                         </div>
                    </div>';
               } else {
                    $token = hash_hmac('sha512', 'xOg1u3asdJH&AJkHJ(&Tj1lj8' . $file . 'AFnCOO60UAJkHATj1l/JT/Usjh&j8NxwdbunYokgvo', 'KLAIhkjsUTZOAK 312HLm3x8uscN');

                    print '<div class="success-suround">
                         <div class="success">
                              <span>Datei <i>' . htmlspecialchars($filename, ENT_QUOTES) . '</i> erfolgreich heruntergeladen</span><br />
                              <a href="remove.php?token=' . $token . '&file=' . $file . '" style="color: white;">Lösche Datei vom Server</a>
                         </div>
                    </div>';
               }

          }
     ?>

     <pre id="base64" style="display: none;">
          <?php
               if (isset($base64)) {
                    print $base64;
               }
          ?>
     </pre>

     <a id='dwnldLnk' download='<?php print htmlspecialchars($filename, ENT_QUOTES); ?>' style="display:none;"></a>


     <div class="footer" style="z-index: -1;">
          <div class="balken balken-1 hell"></div>
          <div class="balken balken-2 dunkel"></div>
          <div class="balken balken-3 hell"></div>
          <div class="balken balken-4 hell"></div>
          <div class="balken balken-5 dunkel"></div>
          <div class="balken balken-6 hell"></div>
          <div class="balken balken-7 dunkel"></div>
     </div>

     <script type="text/javascript">
          <?php
               if (isset($_POST['url'])) {
                    print 'window.onload = function () {
                              downloadFile();
                         }';
                    if ($_POST['bigfile'] == false) {
                         unlink($file);
                    }
               }
          ?>
     </script>
</body>
</html>
