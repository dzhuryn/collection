<?php
namespace collections;


class BaseController
{

    /** @var $modx \DocumentParser */
    protected $modx;
    protected $render;

    /** Параметры для нового документу **/
    protected $newDocParams = [];

    /** Направление сортировки по умолчанию */
    protected $orderBy = 'id asc';

    /**  Количество документов на странице /*/
    public $display = 50;

    /**  Список обезательных тв параметров **/
    protected $tvList = '';

    /**  Ширина и высота превю **/
    protected $previewWidth = 30;
    protected $previewHeight = 30;

    /**
     * Параметры выборки
     * @var array
     */
    protected $DLParams = [
        'showNoPublish' => '1',
        'depth' => '6',
        'api' => '1',
        'paginate'=>'pages',
        'JSONformat' => 'simple',

        'tvPrefix'=>'',

    ];



    protected $defaultParams = [
        'id' => [
            "id" =>"[+id+]",
            'header' => ["[+caption+]", ['content' => "serverFilter"]],
            'sort' => "server",
            'fillspace' => 1,
        ],
        'text' => [
            "id" =>"[+id+]",
            'header' => ["[+caption+]", ['content' => "serverFilter"]],
            'sort' => "server",
            'editor' => "text",
            'fillspace' => 2,
        ],
        'title' => [
            "id" =>"[+id+]",
            'header' => ["[+caption+]", ['content' => "serverFilter"]],
            'sort' => "server",
            'editor' => "text",
            'fillspace' => 8,
            'cssFormat' => 'status',
        ],
        'thumb' => [
            'id' => "image",
            'header' => ["[+caption+]"],
            'template' => '<img src="/#[+id+]_thumb#" class="image-thumb">',
            'width' => 60,
            'tooltip' => '<img src="/#[+id+]#" class="image-tooltip" >',
            'css'=>'image-cell',
        ],
        'image' => [
            'id' => "image",
            'header' => ["[+caption+]"],
            'template' => '<img src="/#[+id+]_thumb#" class="image-thumb js-add-image" data-id="#id#" data-field="[+id+]">',
            'width' => 60,
            'tooltip' => '<img src="/#[+id+]#" class="image-tooltip" >',
            'css'=>'image-cell',
        ],
        'checkbox' => [
            'id' => "[+id+]",
            'header' => ["[+caption+]", ['content' => "serverFilter"]],
            'sort' => "server",
            'editor' => "text",
            'width' => 70,
            'checkValue' => '1',
            'uncheckValue' => '0',
            'template' => "{common.checkbox()}"
        ],
        'select' => [
            'id' => "[+id+]",
            'header' => ["[+caption+]", ['content' => "serverSelectFilter"]],
            'sort' => "server",
            'editor' => "select",
        ],
        'edit'=>[
            'id'=>"edit",
            'header' => [""],
            'template' => "<a class='edit btn btn-info' href='index.php?a=27&id=#id#' title='редактировать' data-title='#pagetitle#'><span class='webix_icon fa-edit'></span></a>",
            'width'=>50
        ]
    ];

    protected $fields = [
        'id'=>[
            'caption'=>'Ид',
            'fillspace' => 1,
            'type'=>'id'
        ],
        'pagetitle'=>[
            'caption'=>'Заголовок',
            'type'=>'title'
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
    protected $datatableOptions = [
            'container'=>'docs',
			'columns'=>'[+columns+]',
			//'url'=>"[+moduleurl+]action=getDocs&controller=[+controller+]&docOnPage="+docOnPage,
			'save'=>[
			//	'url'=>'"[+moduleurl+]action=saveDoc&controller=[+controller+]",
				'undoOnError'=>true,
				'updateFromResponse'=>true

			],
            'checkboxRefresh'=>true,
			'autoConfig'=> false,
			'width'=>'90%',
			'view'=> 'datatable',
			'editable'=>true,
			'borderless'=>false,
			'drag'=>false,
			'navigation'=>true,
			'select'=>true,

			'multiselect'=>true,
			'autoheight'=>true,
			'scroll'=>false,

			'pager'=>[
         //   'size'=>$this->display,
			'container' =>'pager',
			'group'=>6,
			'template'=>'{common.first()} {common.pages()}  {common.last()}',

			],
			'fixedRowHeight'=>false,
			'rowLineHeight'=>40,
			'rowHeight'=>40,
			'resizeColumn'=>true,
			'hover'=>'myhover',
			'id'=>'table',
			'tooltip'=>true
    ];


    protected $parent;
    protected $actionUrl;
    protected $controllerName;

    public function __construct($modx,$parent,$actionUrl)
    {
        $this->modx = $modx;
        $this->parent = $parent;
        $this->render = \DLTemplate::getInstance($modx);


        $className = get_class($this);
        $this->controllerName = strtolower(str_replace(['collections\\','Controller'],'',$className));
        $this->actionUrl = $actionUrl;


    }


    /**
     * Генерируем колонки для webix
     * @return false|string
     */
    public function renderColumns(){

        $output = [];
        foreach ($this->fields as $id => $fieldParams) {

            $type = isset($fieldParams['type'])?$fieldParams['type']:'text';
            $defaultParams = !empty($this->defaultParams[$type])?$this->defaultParams[$type]:[];

            $params = array_merge($defaultParams,$fieldParams);

            $parseData = [
                'id'=> $id,
                'caption'=> $fieldParams['caption'],
            ];
            $params = $this->parseParams($params,$parseData);
            if($params['editor'] === 'select' && !empty($params['options']) && is_string($params['options'])){
                $params['options'] = $this->parseOptionsSelect($params['options']);
            }

            $output[] = $params;
        }

        return $output;
    }
    public function renderDataTableOptions()
    {
        $columns = $this->renderColumns();
        $getDocsUrl = $this->actionUrl."action=getDocs&controller=$this->controllerName&docOnPage=$this->display";
        $saveDocsUrl = $this->actionUrl."action=saveDoc&controller=$this->controllerName";



        $options = $this->datatableOptions;
        $options = array_merge($options,[
            'url'=>$getDocsUrl,
            'columns'=>$columns,
        ]);
        $options['save']['url'] = $saveDocsUrl;
	$options['pager']['size'] = $this->display;


        return $options;

    }
    protected function isDefaultField($filterName){
        $default_field = array(
            'id', 'type', 'contentType', 'pagetitle', 'longtitle', 'description', 'alias', 'link_attributes', 'published', 'pub_date',
            'unpub_date', 'parent', 'isfolder', 'introtext', 'content', 'richtext', 'template', 'menuindex', 'searchable',
            'cacheable', 'createdon', 'createdby', 'editedon', 'editedby', 'deleted', 'deletedon', 'deletedby', 'publishedon',
            'publishedby', 'menutitle', 'donthit', 'privateweb', 'privatemgr', 'content_dispo', 'hidemenu', 'alias_visible'
        );
        return in_array($filterName,$default_field);
    }

    /**
     * Формируем фильтра для параметра filters DocLister
     * @return array|string
     */
    protected function getDLFilters(){
        $DLFilters = [];

        if (!empty($_GET['filter'])) {
            $filters = $_GET['filter'];

            foreach ($filters as $filterName => $filterValue) {

                if ($filterValue === '') continue;
                $filterName = $this->modx->db->escape($filterName);
                $filterValue = $this->modx->db->escape($filterValue);

                $filterType = 'tvd';
                if ($this->isDefaultField($filterName)) {
                    $filterType = 'content';
                    $filterName = 'c.' . $filterName;
                }
                $DLFilters[] = $filterType . ':' . $filterName . ':%:' . $filterValue;
            }
        }

        if (!empty($DLFilters)) {
            $DLFilters = 'AND(' . implode(';', $DLFilters) . ')';
        } else {
            $DLFilters = '';
        }
        return $DLFilters;
    }

    /**
     * Формируем мисив параметорв для DocLister для получения данных
     * @return array
     */
    protected function getDLParams(){
        //тащим инфу о фильтрах
        $params = $this->DLParams;

        $params['filters'] = $this->getDLFilters();
        $params['orderBy'] = $this->getOrderBy();
        $params['tvList'] = $this->getTVList();

        //ставим parents
        if(!isset($params['parents']) && empty($params['idType'])){
            $params['parents'] = $this->parent;
        }
        //ставим количество документов на странице
        if(!isset($params['display'])){
            $params['display'] = $this->display;
        }

        if(!isset($params['selectFields'])){
            $params['selectFields'] = $this->getSelectFields();
        }

        return $params;

    }

    /**
     * Получаем данные из базы
     * @return array
     */
    //выводит инфу об ресурсах
    public function getData()
    {
        //пагинация
        $start = 0;
        if(!empty($_GET['start'])){
            $start = intval($_GET['start']);
            $_GET['page'] = $start / $this->display + 1;

        }

        $DLParams = $this->getDLParams();


        $resource = $this->modx->runSnippet('DocLister', $DLParams);
        $resource = json_decode($resource, true);


        foreach ($resource as $key => $res) {
            $resource[$key] = $this->prepareData($res);
        }

        $outData = [
            'data' => $resource,
            'pos' => $start,
        ];
        if (empty($_GET['start'])) { //первая страница
            $outData['total_count'] = $this->modx->getPlaceholder('count');
        }
        return $outData;

    }

    /**
     * Подготавляваем данные перед выводом
     * @param $res
     * @return mixed
     */
    protected function prepareData($res)
    {
        $res['statusImage'] = '';

        foreach ($this->fields as $fieldName => $fieldData) {
            if(!empty($fieldData['type']) && in_array($fieldData['type'],['thumb','image'])){
                $src = !empty($res[$fieldName])?$res[$fieldName]:'';

            $res[$fieldName.'_thumb'] = $this->modx->runSnippet('phpthumb',['input'=>$src,'options'=>'w='.$this->previewWidth.',h='.$this->previewHeight.',zc=C']);
            }
        }

        foreach ($res as $key => $value) {
            if (is_string($value) && $value !== 'url') {
                $res[$key] = strip_tags(stripcslashes($value));
            }
        }

        return $res;
    }

    /**
     * @param array $params
     * @param array $parseData
     * @return array
     */
    protected function parseParams(array $params, array $parseData)
    {
        foreach ($params as $name => $value) {
            if (is_array($value)) {
                $params[$name] = $this->parseParams($value, $parseData);
            } else {
                if(strpos($value,'[+') !== false){
                $params[$name] = $this->render->parseChunk('@CODE:' . $value, $parseData);
                }
            }
        }
        return $params;


    }

    /**
     * Формируем список необходимых тв полей
     * @return string
     */
    protected function getTVList()
    {
        $tvList = [];
       if(!empty($this->tvList)){
           $tvList = explode(',',$this->tvList);
       }
        foreach ($this->fields as $name => $value) {
            if(!$this->isDefaultField($name)){
                $tvList[] = $name;
            }
        }
        return implode(',',$tvList);
    }

    /**
     * Определяем поля для сортировки
     * @return array|string
     */
    protected function getOrderBy()
    {
        if(empty($_GET['sort'])){
            return $this->orderBy;
        }

        $sortBy = key($_GET['sort']);
        $sortOrder = $_GET['sort'][$sortBy];

        $orderBy = $this->modx->db->escape($sortBy.' '.$sortOrder);
        return $orderBy;


    }


    /**
     * Метод испольняется для сохранения данных
     * @return array
     */
    public function saveAction(){
        $req = $this->modx->db->escape($_REQUEST);
        $outData = ['status' => 'error'];

        //если у нас нет заголовка ставим фейковый id
        if(empty($req['pagetitle'])){
            return array('newid' => 'noid_'.microtime(), 'status' => 'success');
        }

        switch ($_REQUEST['webix_operation']) {
            case 'insert':
            case 'update':
                $doc = new \modResource($this->modx);
                if (is_numeric($req['id'])) {
                    $doc->edit($req['id']);
                } else {
                    $doc->create($this->getNewDocParams());
                }
                $doc->fromArray($req);
                $result = $doc->save(true, false);


                if ($result) {
                    $outData = array(
                        'newid' => $result,
                        'alias'=>$doc->get('alias'),
                        'status' => 'success'
                    );
                } else {
                    $outData = array('status' => 'error');
                }
                break;
            case 'delete':
                //
                break;
        }
        return $outData;
    }

    /**
     * Подготавляваем масив данных для создания документа
     * @return array
     */
    protected function getNewDocParams()
    {
        $params = $this->newDocParams;
        if(!isset($params['parent'])){
            $params['parent'] = $this->parent;
        }

        return $params;
    }

    /**
     * Формируем список полея которые необходимо вытащить, идет в параметр selectFields DocLister
     * @return string
     */
    protected function getSelectFields()
    {
        $selectFields = [
            'id','pagetitle','published','deleted'
        ];

        foreach ($this->fields as $fieldName => $options) {
            if($this->isDefaultField($fieldName) && !in_array($fieldName,$selectFields)){
                $selectFields[] = $fieldName;
            }
        }

        //добавляем c.
        foreach ($selectFields as $key => $field) {
            $selectFields[$key] = 'c.'.$field;
        }
        return implode(',',$selectFields);


    }

    private function parseOptionsSelect($value)
    {


        $options = [
            ['id'=>'','value'=>'']
        ];
        if (stristr($value, "@EVAL")) {
            $value = trim(substr($value, 6));
            $value = str_replace("\$modx->", "\$this->modx->", $value);
            $value = eval($value);

            foreach (explode('||',$value) as $group) {
                $resp = explode('==',$group);
                $options[] = ['id'=>$resp[1],'value'=>$resp[0]];
            }
        }
        else if(stristr($value, "@SELECT")){
            $sql = str_replace(['[+PREFIX+]','@SELECT'],[$this->modx->db->config['table_prefix'],'SELECT'],$value);

            $data = $this->modx->db->makeArray($this->modx->db->query($sql));
            foreach ($data as $el) {
                $resp = array_values($el);
                $options[] = ['id'=>$resp[1],'value'=>$resp[0]];
            }
        }
        else{
            $options = $value;
        }
        return $options;
    }

    public function sortable($ids)
    {
        $docs = explode(',',$ids);

        foreach ($docs as $key=> $docId) {
            $obj = new \modResource($this->modx);
            $obj->edit($docId);
            $obj->set('menuindex',$key);
            $obj->save(false,false);
        }
        return ['status'=>true];
        
    }
}
