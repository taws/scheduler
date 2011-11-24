<?php
class sfCASRequiredFilter extends sfBasicSecurityFilter
{
  public function execute ($filterChain)
  {

    if ($this->isFirstCall()) {
        require_once('phpCAS/CAS.php');
        
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            phpCAS::setDebug($filename = 'c:\phpCAS.log');
            //Windows
        }else {
            //phpCAS::setDebug($filename = '/var/www/html/SIDWeb/phpCAS.log');
            phpCAS::setDebug($filename = '/tmp/sidweb/cas/phpCAS.log');
            //Linux
        }
        
        phpCAS::client(CAS_VERSION_2_0,$this->getParameter('server_domain'), $this->getParameter('server_port'), '', false);
        // no SSL validation for the CAS server
        phpCAS::setNoCasServerValidation();

        

        if (!$this->getContext()->getUser()->isAuthenticated()){

            phpCAS::forceAuthentication();
            $this->getContext()->getUser()->clearAuth();
            $this->getContext()->getUser()->setAttribute('auth','cas');
            $this->getContext()->getUser()->setAuthenticated(true);
            $this->getContext()->getUser()->setAttribute('usuario', phpCAS::getUser());
            
            

        }

    }

    # if not initially authorized, sfBasicSecurityFilter sets $controller->forward(sfConfig::get('sf_login_module'), sfConfig::get('sf_login_action'));
    # so we re-dispatch since we are already authorized
    # copied from sfFrontWebController's dispatch()
    $this->getContext()->getLogger()->debug('{sfCASRequiredFilter} configs are ' . sfConfig::get('sf_login_module') . '/' . sfConfig::get('sf_login_action'));
    if ($this->getContext()->getModuleName() == sfConfig::get('sf_login_module')
            && $this->getContext()->getActionName() == sfConfig::get('sf_login_action')) {

        $request    = $this->getContext()->getRequest();
        $moduleName = $request->getParameter('module');
        $actionName = $request->getParameter('action');
        $this->getContext()->getLogger()->debug('{sfCASRequiredFilter} forwarding to ' . $moduleName . '/' . $actionName);
        $this->getContext()->getController()->forward($moduleName, $actionName);
    }

    // Execute next filter in the chain
    $filterChain->execute();
  }
}
?>
