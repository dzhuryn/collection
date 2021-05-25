<?php
/** @var $defaultController string */
/** @var $defaultParent string */
/** @var $controller \collections\BaseController */
/**

,2,,
 *
 *
 */

//создаем модуль и вставляем строку: include_once(MODX_BASE_PATH.'assets/modules/collections/collections.module.php');
if (IN_MANAGER_MODE != "true" || empty($modx) || !($modx instanceof DocumentParser)) {
    die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");
}
if (!$modx->hasPermission('exec_module')) {
    header("location: " . $modx->getManagerPath() . "?a=106");
}

//Подключаем обработку шаблонов через DocLister
include_once(MODX_BASE_PATH.'assets/snippets/DocLister/lib/DLTemplate.class.php');
include_once(MODX_BASE_PATH . 'assets/lib/MODxAPI/modResource.php');

$tpl = DLTemplate::getInstance($modx);

//Выводим список параметров которые нужны в шаблоне
$moduleurl = 'index.php?a=112&id='.$_GET['id'].'&parent='.$_GET['parent'].'&';
$action = isset($_GET['action']) ? $_GET['action'] : 'home';
$outTpl = null;
$outData = [];


$inModule = !empty($_GET['from']) && $_GET['from'] == 'doc'?false:true;
$controllerName = !empty($defaultController)?$defaultController:'base';
$defaultParent = isset($defaultParent)?$defaultParent:0;
$parent = !empty($_GET['parent'])?intval($_GET['parent']):$defaultParent;
$title = empty($parent)?$modx->getConfig('site_name'):$modx->runSnippet('DocInfo', array('docid'=>$parent));


$data = array (
    'moduleurl'=>$moduleurl,
    'manager_theme'=>$modx->config['manager_theme'],
    'title'=>$title,
    'parent'=>$parent
);

if(!empty($_GET['controller']) && !preg_match('/[^a-z]/', $_GET['controller'])){
    $controllerName = $_GET['controller'];
}

$filename = MODX_BASE_PATH.'assets/modules/collections/controllers/'.ucfirst($controllerName).'Controller.php';

if(file_exists($filename)){
    include_once $filename;
    $className = '\collections\\' . ucfirst($controllerName) . 'Controller';
}
else{
    include_once 'controllers/BaseController.php';
    $className = '\collections\\' . ucfirst($defaultController) . 'Controller';
}
$controller = new $className($modx,$parent,$moduleurl);


//выполнение действий
switch ($action) {
    case 'home'://главная страничка вывод и шаблон
        $ownerTpl = $inModule ? 'productsOwner' : 'tabOwner';

        $topActions = '';

        $topActions .= $tpl->parseChunk('@CODE:' . file_get_contents(dirname(__FILE__) . '/templates/massAction.tpl'),[
            'massActionFields'=>json_encode($controller->getMassActionFields(),JSON_UNESCAPED_UNICODE),
            'massActions'=>json_encode($controller->getMassActions(),JSON_UNESCAPED_UNICODE ),

            'massActionField' => $controller->massActionField
        ]);


        $data = array_merge($data,[
            'datatable'=>json_encode($controller->renderDataTableOptions(),JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),

            'getDocsUrl'=>$controller->getGetDocsUrl(),
            'topActions' => $topActions,



            'parentTab'=>!$inModule?'parent.':'',
            'controller'=>$controllerName,
            'display'=>$controller->display

        ]);



//        $data['bottom-buttons'] = $tpl->parseChunk('@CODE:' . file_get_contents(dirname(__FILE__) . '/templates/bottom-buttons.tpl'), $data);
//        $data['owner'] = $tpl->parseChunk('@CODE:' . file_get_contents(dirname(__FILE__) . '/templates/' . $ownerTpl . '.tpl'), $data);

        $data['template'] = $tpl->parseChunk('@CODE:' . file_get_contents(dirname(__FILE__) . '/templates/template.tpl'), $data);
        $outTpl = $tpl->parseChunk('@CODE:' . file_get_contents(dirname(__FILE__) . '/templates/' . $ownerTpl . '.tpl'), $data);

        break;

    case 'massUpdate'://получение всех документов
        $outData = $controller->massUpdate();
        break;

    case 'getDocs'://получение всех документов
        $outData = $controller->getData();
        break;
    case 'sortable':
        $outData = $controller->sortable($_POST['ids']);
        break;

    case 'getThumb':
        $outData = $modx->runSnippet('phpthumb',['input'=>$_GET['image'],'options'=>'w=30,h=30,zc=C']);

        break;

    case 'saveDoc'://сохранени со списка документов
        $outData = $controller->saveAction();
        break;
}

// Вывод результата или шаблон или Ajax 
if(!is_null($outTpl)){
    $headerTpl = '@CODE:'.file_get_contents(dirname(__FILE__).'/templates/header.tpl');
    $footerTpl = '@CODE:'.file_get_contents(dirname(__FILE__).'/templates/footer.tpl');
    $output = $tpl->parseChunk($headerTpl,$data) . $outTpl . $tpl->parseChunk($footerTpl,$data);

}else{
  header('Content-type: application/json');
  $output = json_encode($outData, JSON_UNESCAPED_UNICODE);  
}
echo $output;
?>