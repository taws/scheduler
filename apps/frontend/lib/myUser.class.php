<?php

class myUser extends sfBasicSecurityUser
{
    
    
    public function getFullName(){

        if($this->getAttribute('usuario')=="")
                return 'Anónimo';

         $usuario = $this->getUserDB();

        if($usuario)
        return $usuario->getNombres()." ".$usuario->getApellidos();
        return $this->getAttribute('usuario') . " <Usuario desconocido>";
    }
    
    public function getShortName($usuario){
        
        if($this->getAttribute('usuario')=="")
                return 'Anónimo';

         //$usuario = $this->getUserDB();

        if($usuario)
            return Utility::FNameFLast ($usuario->getNombres(), $usuario->getApellidos());
        return $this->getAttribute('usuario') . " <Usuario desconocido>";
    }

    public function getCorreo(){

        if($this->getAttribute('usuario')=="")
                return 'taws@espol.edu.ec';

         $usuario = $this->getUserDB();

        return trim($usuario->getCorreo());

    }


    public function getIdUsuario(){
          $usuario = $this->getUserDB();
          

        if($usuario)
        return $usuario->getId();
        return null;
    }
    
    public function getUserDB(){

          $usuario = Doctrine_Core::getTable('Usuario')
            ->createQuery('u')
            ->where('u.nombre_usuario= ?', $this->getAttribute('usuario'))
            ->andWhere('u.activo = true')
            ->fetchOne();

        return $usuario;

    }
    
    public function  clearAuth(){

        $this->setAttribute('auth', null);
        $this->setAttribute('usuario', null);
        $this->clearCredentials();
        $this->UserLogged=null;
        $this->setAuthenticated(false);
        
    }
    public function getLogoutRoute(){
        

        return '@cas_logout';
        
    }
    public function CASLogout(){

       $this->clearAuth();
       phpCAS::logout();
        
    }
    
}
