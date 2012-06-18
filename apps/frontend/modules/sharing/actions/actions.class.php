<?php

/**
 * sharing actions.
 *
 * @package    scheduler
 * @subpackage sharing
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class sharingActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
      $this->comparten=$this->getUser()->getUserDB()->getComparten();
    
  }
  public function executeChecksharing(sfWebRequest $request)
  {
      $userByToken=Doctrine_Core::getTable('Usuario')->getUserByToken($request->getParameter('tkn'));
      $currentUser=$this->getUser()->getUserDB();
      
      $userCompartidos=$currentUser->getCompartidos();
      
      $band=false;
      
      foreach ($userCompartidos as $user){
          if($user->getId()==$userByToken->getId()){
              $band=true;
              break;
          }
      }
      
      $return['isSharing'] = $band;
      $return['name'] = Utility::FName($userByToken->getNombres());
      $this->getResponse()->setHttpHeader('Content-type', 'application/json');
      return $this->renderText(json_encode($return));
      
  }
  public function executeShareback(sfWebRequest $request)
  {
      
      $userByToken=Doctrine_Core::getTable('Usuario')->getUserByToken($request->getParameter('tkn'));
      $currentUser=$this->getUser()->getUserDB();
      
      $compartir=Doctrine_Core::getTable('Compartir')->getByIds($currentUser->getId(),$userByToken->getId());
      
      if($compartir==null){
        $compartir=new Compartir();
        $compartir->setComparteId($currentUser->getId());
        $compartir->setCompartidoId($userByToken->getId());
        try{
            $compartir->save();
            $return['ok']=true;
            $return['msj']='Compartido Satisfactoriamente';
        }catch(Exception $e){
            $return['ok']=false;
            $return['msj']='No se pudo compartir con '.Utility::FName($userByToken->getNombres());
        }
        
      }else{
          $return['ok']=false;
          $return['msj']='Usted ya ha compartido su horario con '.Utility::FName($userByToken->getNombres());
      }
      $return['name']=Utility::FName($userByToken->getNombres());
      $this->getResponse()->setHttpHeader('Content-type', 'application/json');
      return $this->renderText(json_encode($return));
        
  }
  
  public function executeUnshare(sfWebRequest $request)
  {
      $userByToken=Doctrine_Core::getTable('Usuario')->getUserByToken($request->getParameter('tkn'));
      $currentUser=$this->getUser()->getUserDB();
      $compartir=Doctrine_Core::getTable('Compartir')->getByIds($currentUser->getId(),$userByToken->getId());
      
      if($compartir->delete()){
          $return['ok']=true;
          $return['msj']='Eliminado Satisfactoriamente';
      }else{
          $return['ok']=false;
          $return['msj']='No se ha podido dejar de compartir con '.Utility::FName($userByToken->getNombres());
      }
      $return['name']=Utility::FName($userByToken->getNombres());
      $this->getResponse()->setHttpHeader('Content-type', 'application/json');
      return $this->renderText(json_encode($return));
  }
  
  public function executeJsonusuarios(sfWebRequest $request){
      $tag=$request->getParameter("keyword");
      $tag=strtolower($tag);
      $tag=str_replace(" ", "%", $tag);
      $user_id_logged=$this->getUser()->getUserDB()->getId();
      $users_shared=$this->getUser()->getUserDB()->getCompartidos();
      
      $resultados=  Usuario::usuariosByTag($tag,$user_id_logged,$users_shared);
      
      $this->getResponse()->setHttpHeader('Content-type', 'application/json');
      return $this->renderText(json_encode($resultados));
      sfView::NONE;
  }
  
  public function executeShare(sfWebRequest $request){
      $ppl=$request->getParameter("ppl");
      $internal = $request->getParameter('internal');
      if(isset($internal) && $internal) {
          if (empty($ppl)) {
                    $return['error'] = true;
                    $return['msg'] = 'No hay nadie para compartir';
            }
            else {
                $arrayppl=explode(",", $ppl);
                $strppl="";
                $i=0;
                foreach($arrayppl as $persontkn){
                    $user=Doctrine_Core::getTable('Usuario')->getUserBySearchToken($persontkn);
                    if(isset ($user)){
                        if(sizeof($arrayppl)==1 || $i==0)
                            $strppl=Utility::FName($user->getNombres())." ".$user->getApellidos();
                        else
                            $strppl=$strppl.", ".Utility::FName($user->getNombres())." ".$user->getApellidos();
                        $compartir=new Compartir();
                        $compartir->setComparteId($this->getUser()->getUserDB()->getId());
                        $compartir->setCompartidoId($user->getId());
                        $compartir->save();
                    }
                    $i++;
                }
                $return['error'] = false;
                if(sizeof($arrayppl)==0)
                    $return['msg'] = 'No hay nadie para compartir';
                else
                    $return['msg'] = 'Ha compartido su horario satisfactoriamente con '.$strppl;
            }
          $this->getResponse()->setHttpHeader('Content-type', 'application/json');
          return $this->renderText(json_encode($return));

      }
      else {
          $message = array();
          $message["error"]=true;
          $message["msg"] = "Acceso inválido";
          $this->getResponse()->setHttpHeader('Content-type', 'application/json');
          return $this->renderText(json_encode($message));
      }
      sfView::NONE;
  }
}
