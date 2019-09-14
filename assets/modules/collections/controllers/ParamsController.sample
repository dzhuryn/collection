<?php
namespace collections;
include_once 'BaseController.php';

class ParamsController extends BaseController{


    protected $fields = [
        'id'=>[
            'caption'=>'Ид',
            'type'=>'id'
        ],
        'pagetitle'=>[
            'caption'=>'Название',
            'type'=>'title'
        ],

        'edit'=>[
            'type'=>'edit'
        ],

    ];

    protected $newDocParams = [
        'hidemenu' => 1,
        'template' => 11
    ];
    public function __construct($modx, $parent)
    {
        parent::__construct($modx, $parent);
        $this->DLParams = array_merge($this->DLParams,[
            'depth'=>0
        ]);
    }

}