<?php
namespace collections;
include_once 'BaseController.php';

class ParamlistController extends BaseController{


    protected $fields = [
        'id'=>[
            'caption'=>'Ид',
            'fillspace' => 1,
            "editor" => false,
        ],
        'pagetitle'=>[
            'caption'=>'Название',
            'type'=>'title'
        ],
        'alias'=>[
            'caption'=>'Alias',
            'type'=>'text'
        ],
        'longtitle'=>[
            'caption'=>'Склонение',
            'type'=>'title'
        ],
        'deleted'=>[
            'caption'=>'Удален',
            'type'=>'checkbox',
        ]
    ];
    protected $newDocParams = [
        'template'=>12
    ];
    public function __construct($modx, $parent)
    {
        parent::__construct($modx, $parent);
        $this->DLParams = array_merge($this->DLParams,[
            'depth'=>0
        ]);
    }
}