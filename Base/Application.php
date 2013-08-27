<?php

class Base_Application
{
    /** @var Base_Context */
    private $context;
    /** @var App_Controller_Base */
    private $controller;

    public function __construct($config){
        $this->context = Base_Context::getInstance();
        $this->context->setConfig($config);
    }

    public function run(){
        try {
            $this->context->setRequest(new Base_Request());

            $config = $this->context->getConfig();
            $dbSourceClass = $config->getConfigSection(Base_Config::TYPE_DB_SOURCE);
            if (!class_exists($dbSourceClass)) {
                 throw new Exception('db source class not exists');
            }
            $this->context->setDbDriver(new $dbSourceClass($config));

            $this->controller = $this->getController($this->context);
            $this->controller->setView(new Base_View());
            $this->controller->init();
            if (!$this->isExistsActionMethod()) {
                $this->context->getRequest()->setAction('error404');
            }

            $this->controller->{$this->getActionMethodName()}();

            $content = $this->controller->view->render($this->controller->getTpl());

            $this->controller->afterRun();
            $bottomContent = $this->controller->getBottomContent();
        } catch (Base_Exception_Redirect $e) {
            header('location: '.$e->getUrl());
            return true;
        } catch (Exception $e) {
            trigger_error($e->getMessage(), E_USER_WARNING);
            header('HTTP/1.0 500 Internal Server Error');
            $content = 'Произошла внутренняя ошибка.';
        }

        $this->render($content);
        if (!empty($bottomContent)) {
            $this->render($bottomContent);
        }
        return true;
    }

    private function getController($context){
        return new App_Controller_Index($context);
    }

    private function render($content){
        print $content;
        return true;
    }

    private function isExistsActionMethod(){
        return method_exists($this->controller, $this->getActionMethodName());
    }

    private function getActionMethodName(){
        return $this->context->getRequest()->getAction().'Action';
    }
}
