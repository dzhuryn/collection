<?php
/** @var $tab_parents string */
/** @var $tab_ids string */
/** @var $tab_templates string */
/** @var $module_id string */
/** @var $tab_name string */
/** @var $controllerConfig string */
/** @var $tabConfig string */
/** @var $treeOnClickModuleConfig string */
/** @var $treeConfig string */

if(!function_exists('checkCfg')){
    function checkCfg($configuration,$doc){
        $status = false;
        $configuration = json_decode($configuration,true);
        if(is_array($configuration)){
            foreach ($configuration as $fieldName => $fieldValue) {
                if(!isset($fieldValue) || $fieldValue  == '')  continue;
                if (in_array($doc[$fieldName], explode(',', $fieldValue))) {
                    $status = true;
                }
            }
        }
        return $status;
    }
}



$e = $modx->event;
$render = DLTemplate::getInstance($modx);

$controller = 'base';


$doc = [];
if($e->name == 'OnDocFormRender' && !empty($params['id'])){
    $doc = $modx->getDocument(intval($params['id']), 'id,parent,template', 'all', 'all');
}
if($e->name == 'OnManagerNodePrerender'){
    $doc = $params['ph'];
}
$controllerConfig = json_decode($controllerConfig, true);
if (is_array($controllerConfig)) {
    foreach ($controllerConfig as $controllerName => $cfg) {
        foreach ($cfg as $fieldName => $fieldValue) {
            if(empty($fieldValue))  continue;
            if (in_array($doc[$fieldName], explode(',', $fieldValue))) {
                $controller = $controllerName;
            }
        }
    }
}

switch ($e->name) {
    case 'OnDocFormRender':
        $showTab = checkCfg($tabConfig, $doc);

        if ($showTab) {
            $src = $modx->config['site_url'] . MGR_DIR . '/index.php?a=112&from=doc&id=' . $module_id . '&parent=' . $doc['id'] . '&controller=' . $controller;
            $content = $render->parseChunk('@CODE:' . file_get_contents(MODX_BASE_PATH . 'assets/modules/collections/templates/tabTemplate.tpl'), [
                'tab_name' => $tab_name,
                'src' => $src,
            ]);

            $e->output($content);
        }
        break;

    case 'OnManagerNodePrerender':
        $updateNode = checkCfg($treeConfig, $doc);

        $showModuleOnClick = checkCfg($treeOnClickModuleConfig, $doc);

        if ($showModuleOnClick) {
            $ph['tree_page_click'] = 'index.php?a=112&id=' . $module_id . '&parent=' . $ph['id']. '&controller=' . $controller;;
        }
        if ($updateNode) {
            $ph['icon'] = '<i class="fa fa-list-alt"></i>';
            $ph['icon_folder_open'] = "<i class='fa fa-list-alt'></i>";
            $ph['icon_folder_close'] = "<i class='fa fa-list-alt'></i>";
            $ph['showChildren'] = '0';
        }

        $e->output(serialize($ph));

        break;
}

?>