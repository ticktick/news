<?php

class App_Model_News extends App_Model_Base {

    protected $name = 'news';
    protected $mandatoryProperties = array(
        'title',
        'text',
    );

    public function create($data){
        $data['date_created'] = date('Y-m-d H:i:s', TIME);
        $data['date_modified'] = date('Y-m-d H:i:s', TIME);
        return parent::create($data);
    }

    public function save(){
        if ($this->changedProperties) {
            $this->date_modified = date('Y-m-d H:i:s', TIME);
        }
        parent::save();
    }
}