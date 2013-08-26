<?php

class App_Controller_Index extends App_Controller_Base {

    public function indexAction(){
        $this->view->pageTitle = 'Новости';

        $news = new App_Model_News();
        $this->view->news = $news->getAll(50);
    }

    public function addAction(){
        $this->view->pageTitle = 'Добавление новости';

        $title = $this->p('title');
        $text = $this->p('text');

        if ($title && $text) {
            $news = new App_Model_News();
            $data = array(
                'title' => $title,
                'text' => $text,
            );
            $news->create($data);
            $this->redirect('/');
        }
    }

    public function editAction(){
        $this->view->pageTitle = 'Добавление новости';

        if ($this->p('edit')) {
            $id = (int)$this->p('edit');
            $title = $this->p('title');
            $text = $this->p('text');

            if ($id && ($title || $text)) {
                $news = new App_Model_News($id);
                foreach(array('title' => $title, 'text' => $text) as $field => $value) {
                    $news->$field = $value;
                }
                $news->save();
                $this->redirect('/');
            }
        }

        $id = $this->p('id');
        if ($id) {
            $news = new App_Model_News($id);
            if ($news->isExists()) {
                $this->view->news = $news;
            } else {
                $this->error404Action();
            }
        }
    }

    public function deleteAction(){
        $id = $this->p('id');
        $news = new App_Model_News($id);
        if ($news->isExists()) {
            $news->delete();
            $this->redirect('/');
        } else {
            $this->error404Action();
        }
    }
}