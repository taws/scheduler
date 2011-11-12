<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of myUtils
 *
 * @author Allan
 */
class myUtils {
    //put your code here
    public static function getHtmlContent(sfActions $action, $layout = false)
    {
        sfConfig::set('sf_web_debug', false);
        $action->setLayout($layout);
        $context = $action->getContext();
        //clear http headers
        $context->getResponse()->clearHttpHeaders();

        /*get the view, execute n render with variables*/
        $view = $context->getController()->getView($action->getModuleName(), $action->getActionName(), 'Success');
        $view->execute();
        $view->getAttributeHolder()->add($action->getVarHolder()->getAll());
        $content = $view->render();
        return $content;
    }
}
?>
