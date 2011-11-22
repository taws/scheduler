<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Scheduler
 *
 * @author Alejandro
 */
class Scheduler {
    
    public static function getUserPhoto($usuario){
        
          try {
                $header = null;
                if($usuario==null)
                {
                    return "/images/sinfoto.jpg";
                }
                $header = get_headers('http://www.academico.espol.edu.ec/imgEstudiante/'.$usuario->getMatricula().'.jpg');
                if($header[0]!='HTTP/1.1 200 OK' || $header==null){
                    if($usuario->getFoto()){                    
                        return $usuario->getFoto();
                    }else {                        
                        if($usuario->getGenero()=='M')
                            return "/images/user/man.gif";
                        else if($usuario->getGenero()=='F')
                            return "/images/user/woman.gif";
                        else
                            return "/images/user/question-mark.png";
                    }
                }else {
                    return "http://www.academico.espol.edu.ec/imgEstudiante/".$usuario->getMatricula().".jpg";
                }
            } catch (Exception $exc) {
                return "/images/user/question-mark.png";                
            } 
      }
      
      static public function slugify($text)
      {
              // replace non letter or digits by -
              $text = preg_replace('#[^\\pL\d]+#u', '-', $text);

              // trim
              $text = trim($text, '-');

              // transliterate
              if (function_exists('iconv'))
              {
                $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
              }

              // lowercase
              $text = strtolower($text);

              // remove unwanted characters
              $text = preg_replace('#[^-\w]+#', '', $text);

              if (empty($text))
              {
                return 'n-a';
              }

              return $text;

      }
}

?>
