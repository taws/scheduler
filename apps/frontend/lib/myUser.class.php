<?php

class myUser extends sfBasicSecurityUser
{
    
    public function getNombre(){

//        if($this->getAttribute('usuario')=="")
//                return 'Anónimo';

         $usuario = Doctrine_Core::getTable('Usuario')
            ->createQuery('u')
            //->where('u.nombre_usuario = ?', $this->getAttribute('usuario'))
            ->where('u.nombre_usuario = "aavendan"')
            ->fetchOne();

        if($usuario)
        return $usuario->getNombres()." ".$usuario->getApellidos();
        //return $this->getAttribute('usuario') . " <Usuario desconocido>";
    }

    public function getCorreo(){

//        if($this->getAttribute('usuario')=="")
//                return 'sidweb@cti.espol.edu.ec';

         $estudiante = Doctrine_Core::getTable('Usuario')
            ->createQuery('u')
            //->where('u.nombre_usuario= ?', $this->getAttribute('usuario'))
            ->where('u.nombre_usuario= "aavendan"')
            ->fetchOne();

        return trim($estudiante->getCorreo());

    }


    public function getIdUsuario(){

          $usuario = Doctrine_Core::getTable('Usuario')
            ->createQuery('u')
            //->where('u.nombre_usuario = ?', $this->getAttribute('usuario'))
            ->where('u.nombre_usuario = "aavendan"')
            ->andWhere('u.activo = true')
            ->fetchOne();

        if($usuario)
        return $usuario->getId();
        return null;
    }
    
    public function getUsuario(){

          $estudiante = Doctrine_Core::getTable('Usuario')
            ->createQuery('u')
            //->where('u.nombre_usuario= ?', $this->getAttribute('usuario'))
            ->where('u.nombre_usuario= "aavendan"')
            ->fetchOne();

        return $estudiante;

    }
}
