<?php

/**
 * viewer actions.
 *
 * @package    scheduler
 * @subpackage viewer
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class viewerActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */ 
 public function executeIndex(sfWebRequest $request)
 {
     //Forwarded from Share,Index (module,action)
     $this->shared = $request->hasParameter('shared')?$request->getParameter('shared'):false;
     $this->code = $request->hasParameter('code')?$request->getParameter('code'):false;
     /*if($this->code) {
        $this->jsVar = $this->getJson(base64_decode($this->code),true);
     }*/
     //An extra piece of javascript-code will load schedule
 }

 public function executeAuthentication(sfWebRequest $request)
 {
     
      $handler = new WSDLHandler();
      $handler->initDirectorioEspolHandler();

      $user = $request->getParameter('user');
      $password = $request->getParameter('password');

      if(isset($user) && isset($password)) {

          $exists = $handler->authenticate($user, $password);

          if(isset($exists) && $exists) {
              $results = $handler->userData($user, $password);

              $doc = new DOMDocument('1.0', 'utf-8');
              $doc->loadXML($results);
              $elements = $doc->getElementsByTagName("DATOS_USUARIO");

              if($elements->length > 0) {
                  $node = $elements->item(0);
                  $matricula = $node->getElementsByTagName("MATRICULA")->length!=0 ? $node->getElementsByTagName("MATRICULA")->item(0)->nodeValue : "";

                  $this->getRequest()->setParameter('matricula', $matricula);
                  $this->getRequest()->setParameter('internal', true);
                  $this->forward('viewer', 'scheduler');
              }

          } else {
              $message = array();
              $message["error"] = "El usuario o la contraseña introducidos no son correctos";
              $this->getResponse()->setHttpHeader('Content-type', 'application/json');
              return $this->renderText(json_encode($message));
          }
          
      } else {
          $message = array();
          $message["error"] = "Petición sin usuario y contraseña";
          $this->getResponse()->setHttpHeader('Content-type', 'application/json');
          return $this->renderText(json_encode($message));
      }


      
 }

 /**
  * Executes scheduler action
  *
  * @param sfRequest $request A request object
  */
  public function executeScheduler(sfWebRequest $request)
  {
      $handler = new WSDLHandler();
      $handler->initWSSAACHandler();

      $matricula = $request->getParameter('matricula');
      $internal = $request->getParameter('internal');
      
      if(isset($internal) && $internal) {
          
          $results = $handler->scheduler($matricula);

          $doc = new DOMDocument('1.0', 'utf-8');
          $doc->loadXML($results);
          $elements = $doc->getElementsByTagName("V_HORARIO_CLASES");
          $elements2 = $doc->getElementsByTagName("V_MAT_REGISTRADAS");

          $materias = array();

          for ($i = 0; $i < $elements->length; $i++) {

              try {

                $paralelo = new Paralelo();
                $node = $elements->item($i);
                $paralelo->codigoparalelo = $node->getElementsByTagName("IDCURSO")->length!=0 ? $node->getElementsByTagName("IDCURSO")->item(0)->nodeValue : "";
                $paralelo->dia = $this->convertDay($node->getElementsByTagName("DIA")->length!=0 ? $node->getElementsByTagName("DIA")->item(0)->nodeValue : "");
                $paralelo->horaini = substr($node->getElementsByTagName("HORAINICIO")->length!=0 ? $node->getElementsByTagName("HORAINICIO")->item(0)->nodeValue : "",0,2);
                $paralelo->minutoini = substr($node->getElementsByTagName("HORAINICIO")->length!=0 ? $node->getElementsByTagName("HORAINICIO")->item(0)->nodeValue : "",3,2);
                $paralelo->horafin = substr($node->getElementsByTagName("HORAFIN")->length!=0 ? $node->getElementsByTagName("HORAFIN")->item(0)->nodeValue : "",0,2);
                $paralelo->minutofin = substr($node->getElementsByTagName("HORAFIN")->length!=0 ? $node->getElementsByTagName("HORAFIN")->item(0)->nodeValue : "",3,2);
                $paralelo->aula = $node->getElementsByTagName("AULA")->length!=0 ? $node->getElementsByTagName("AULA")->item(0)->nodeValue : "";
                $paralelo->ubicacion = ($node->getElementsByTagName("BLOQUE")->length!=0 ? $node->getElementsByTagName("BLOQUE")->item(0)->nodeValue : "")." - ".($node->getElementsByTagName("CAMPUS")->length!=0 ? $node->getElementsByTagName("CAMPUS")->item(0)->nodeValue : "");
                $this->getExtraData($elements2,$paralelo);

                array_push($materias, $paralelo->__toString());

              }catch(Exception $ee){

              }
          }

          $results = str_replace("\\", "", json_encode($materias));
          $results = str_replace("\"{", "{", $results);
          $results = str_replace("}\"", "}", $results);
          $results = str_replace("u00d1", "Ñ", $results);
          $results = str_replace("u00c1", "Á", $results);
          $results = str_replace("u00c9", "É", $results);
          $results = str_replace("u00cd", "Í", $results);
          $results = str_replace("u00d3", "Ó", $results);
          $results = str_replace("u00da", "Ú", $results);

          $this->getResponse()->setHttpHeader('Content-type', 'application/json');
          return $this->renderText($results);
          
      } else {
          $message = array();
          $message["error"] = "Acceso inválido";
          $this->getResponse()->setHttpHeader('Content-type', 'application/json');
          return $this->renderText(json_encode($message));
      }
  }

 
  public function getExtraData($elements2, $paralelo) {
        for ($i = 0; $i < $elements2->length; $i++) {
            $node = $elements2->item($i);
            $idcurso = $node->getElementsByTagName("IDCURSO")->length!=0 ? $node->getElementsByTagName("IDCURSO")->item(0)->nodeValue : "";
            if($idcurso == $paralelo->codigoparalelo) {
                $paralelo->profesor = $node->getElementsByTagName("PROFESOR")->length!=0 ? $node->getElementsByTagName("PROFESOR")->item(0)->nodeValue : "";
                $paralelo->materia = $node->getElementsByTagName("NOMBREMATERIA")->length!=0 ? $node->getElementsByTagName("NOMBREMATERIA")->item(0)->nodeValue : "";
                $paralelo->paralelo = $node->getElementsByTagName("PARALELO")->length!=0 ? $node->getElementsByTagName("PARALELO")->item(0)->nodeValue : "";

            }
        }
  }

  public function convertDay($nday) {
      switch($nday) {
          case "1": return "lunes";
          case "2": return "martes";
          case "3": return "miercoles";
          case "4": return "jueves";
          case "5": return "viernes";
          case "6": return "sabado";
          default: return "domingo";
      }
  }
  
}
