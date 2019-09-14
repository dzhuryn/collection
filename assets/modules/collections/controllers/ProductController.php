<?php
namespace collections;
include_once 'BaseController.php';

class ProductController extends BaseController{


    protected $fields = [
        'id'=>[
            'caption'=>'Ид',
            'fillspace' => 1,
            "editor" => false,
        ],
        'pagetitle'=>[
            'caption'=>'Заголовок',
            'type'=>'title'
        ],

        'sg_image'=>[
            'caption'=>'Фото',
            'type'=>'thumb',
        ],


        'published'=>[
            'caption'=>'Опубликован',
            'type'=>'checkbox'
        ],
        'deleted'=>[
            'caption'=>'Удален',
            'type'=>'checkbox'
        ],

        'edit'=>[
            'type'=>'edit'
        ],

    ];

    public function __construct($modx, $parent)
    {
        parent::__construct($modx, $parent);

        $this->DLParams = array_merge($this->DLParams,[
            'controller' => 'sg_site_content',
            'dir'=> 'assets/snippets/simplegallery/controller/',

//            'sgOrderBy'=>'sg_index DESC',
        ]);
    }

        public $display = 50;
    protected function prepareData($data)
    {
        $data =  parent::prepareData($data);

        $data['sg_image'] = $data['images'][0]['sg_image'];
        $data['sg_image_thumb'] = $this->modx->runSnippet('phpthumb',['input'=>$data['sg_image'],'options'=>'w=30,h=30,zc=C']);
        unset($data['images']);

        return $data;
    }

}