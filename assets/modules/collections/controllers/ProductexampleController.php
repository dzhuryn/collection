<?php
namespace collections;
include_once 'BaseController.php';

class ProductexampleController extends BaseController{


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
        'price'=>[
            'caption'=>'Цена',
        ],
        'old_price'=>[
            'caption'=>'Старая цена.',
        ],
        'sg_image'=>[
            'caption'=>'Фото',
            'type'=>'thumb',
        ],

        'imageformain'=>[
            'caption'=>'Фото для главной',
            'type'=>'image',
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

    protected function prepareData($data)
    {
        $data =  parent::prepareData($data);

        $data['sg_image'] = $data['images'][0]['sg_image'];
        $data['sg_image_thumb'] = $this->modx->runSnippet('phpthumb',['input'=>$data['sg_image'],'options'=>'w=30,h=30,zc=C']);
        unset($data['images']);

        return $data;
    }

}