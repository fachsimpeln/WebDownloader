<?php
     $file = $_GET['file'];
     $re = '/download\/file_\d+_\d+\.key/m';

     $token = hash_hmac('sha512', 'xOg1u3asdJH&AJkHJ(&Tj1lj8' . $file . 'AFnCOO60UAJkHATj1l/JT/Usjh&j8NxwdbunYokgvo', 'KLAIhkjsUTZOAK 312HLm3x8uscN');

     if ($token !== $_GET['token']) {
          print 'no_auth';
          die();
     }

     preg_match_all($re, $file, $matches, PREG_SET_ORDER, 0);

     //var_dump($matches);
     //die();
     if (count($matches) > 0) {
          unlink($matches[0][0]);
          header('Location: /');
          print 'file_deleted';
          die();
     } else {
          print 'no_auth';
          die();
     }

?>
