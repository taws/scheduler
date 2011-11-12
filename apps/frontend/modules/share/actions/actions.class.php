<?php

/**
 * share actions.
 *
 * @package    scheduler
 * @subpackage share
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class shareActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
      $matricula = base64_decode($request->getParameter('code'));
      $zm = $request->getParameter('zm');

      if(strcmp(substr($matricula, 4, strlen($matricula)-1), $zm) == 0) {
          $this->getRequest()->setParameter('shared', true);
          $this->forward('viewer', 'index');
      } else {
          //Devolver un error a mostrar por pantalla
      }
      
  }

  public function executeLink(sfWebRequest $request)
  {
      $handler = new WSDLHandler();
      $handler->initDirectorioEspolHandler();

      $user = $request->getParameter('user');
      $password = $request->getParameter('password');

      $results = $handler->userData($user, $password);

      $doc = new DOMDocument('1.0', 'utf-8');
      $doc->loadXML($results);
      $elements = $doc->getElementsByTagName("DATOS_USUARIO");

      if($elements->length > 0) {

          $node = $elements->item(0);
          $matricula = $node->getElementsByTagName("MATRICULA")->length!=0 ? $node->getElementsByTagName("MATRICULA")->item(0)->nodeValue : "";

          $message = array();
          $message["code"] = base64_encode($matricula);
          $message["zm"] = substr($matricula, 4, strlen($matricula)-1);

          $this->getResponse()->setHttpHeader('Content-type', 'application/json');
          return $this->renderText(json_encode($message));
          
      }
      
  }

  public function executeRq(sfWebRequest $request)
  {
       $matricula = base64_decode($request->getParameter('code'));

       $this->getRequest()->setParameter('matricula', $matricula);
       $this->getRequest()->setParameter('internal', true);
       $this->forward('viewer', 'scheduler');
  }

}
