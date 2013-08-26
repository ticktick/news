<?php

abstract class App_Controller_Base {

    private $context;
    /** @var Base_View */
    public $view;
    private $tpl;
    private $bottomContent;

    public function __construct(Base_Context $context){
        $this->context = $context;
    }

    public function init(){
        $this->setTpl($this->context->getRequest()->getAction().'.phtml');
        $config = $this->context->getConfig();
        $this->view->setLayout($config->getConfigSection(Base_Config::TYPE_DEFAULT_LAYOUT));
        $this->view->setPath($config->getConfigSection(Base_Config::TYPE_TPL_PATH));
    }

    public function afterRun(){
        $this->view->profilerLogs = Base_Profiler::getLogs();
        $this->bottomContent = $this->view->render('profiler.phtml', false);
        return true;
    }

    public function getBottomContent(){
        return $this->bottomContent;
    }

    public function getContext(){
        return $this->context;
    }

    public function setView(Base_View $view){
        $this->view = $view;
    }

    public function p($param){
        return $this->context->getRequest()->p($param);
    }

    /**
     * @return Base_View
     */
    public function getView(){
        return $this->view;
    }

    public function setTpl($tpl){
        $this->tpl = $tpl;
    }

    public function getTpl(){
        return $this->tpl;
    }

    public function redirect($url){
        throw new Base_Exception_Redirect($url);
    }

    public function error404Action(){
        $this->tpl = 'error404.phtml';
    }
}