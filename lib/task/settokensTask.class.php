<?php

class settokensTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
      new sfCommandOption('idstart',null,sfCommandOption::PARAMETER_REQUIRED, 'The position to start the task', '1') 
      // add your own options here
    ));
    $this->namespace        = '';
    $this->name             = 'set-tokens';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [set-tokens|INFO] task does things.
Call it with:

  [php symfony set-tokens|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    ini_set('memory_limit', '1024M');
    
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
 
       
    $usuarios=Doctrine_Core::getTable('Usuario')
                ->createQuery('u')
                ->where('u.id>='.$options['idstart'])
                ->execute();
    $i=1;
    foreach($usuarios as $usuario){
        $usuario->save();
        echo "[INFO ".$i."]".$usuario->getNombreUsuario()." Seteado\n";
        $i++;
    }
  }
}
