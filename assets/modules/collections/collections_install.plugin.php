<?php
//require MODX_BASE_PATH.'assets/modules/collections/collections_install.plugin.php';

require_once MODX_BASE_PATH.'assets/lib/MODxAPI/modTV.php';
require_once MODX_BASE_PATH.'assets/lib/MODxAPI/modPlugin.php';
$moduleId = $modx->db->getValue($modx->db->query("select id from ".$modx->getFullTableName("site_modules")." where `name` = 'collections'"));
if(empty($moduleId)) return true;
$tvId = $modx->db->getValue($modx->db->query("select id from ".$modx->getFullTableName("site_tmplvars")." where `name` = 'collection_module'"));
$pluginData = $modx->db->getRow($modx->db->query("select * from ".$modx->getFullTableName("site_plugins")." where `name` = 'collections'"));
$installPluginId = $modx->db->getValue($modx->db->query("select `id` from ".$modx->getFullTableName("site_plugins")." where `name` = 'collections_install'"));

if(empty($tvId)){
    $obj = new modTV($modx);
    $obj->create([
        'type'=>'custom_tv',
        'name'=>'collection_module',
        'caption'=>'collection_module',
        'elements'=>'@EVAL return !empty($content["id"]) ? $modx->runSnippet("collections", array("module_id" => "'.$moduleId.'", "controller" => "", "parent" => $content["id"])) : "";',
    ]);
    $obj->save(false,false);
}

if(!empty($pluginData)){
    $properties = json_decode($pluginData['properties'],true);
    if(!empty($properties) && is_array($properties['module_id'][0]) && empty($properties['module_id'][0]['value'])){
        $properties['module_id'][0]['value'] = $moduleId;

        $obj = new modPlugin($modx);
        $obj->edit($pluginData['id']);
        $obj->set('properties',json_encode($properties));
        $obj->save();
    }
}
if(!empty($installPluginId)){
    $modx->db->delete($modx->getFullTableName("site_plugins"),'id = '.$installPluginId);
    $modx->db->delete($modx->getFullTableName("site_plugin_events"),'pluginid = '.$installPluginId);
}