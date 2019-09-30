<?php
$pluginData = $modx->db->getRow($modx->db->query("select * from ".$modx->getFullTableName("site_plugins")." where `name` = 'collections'"));
$properties = json_decode($pluginData['properties'],true);

$controllerConfig = json_decode($properties['controllerConfig'][0]["value"],true);
$doc = $modx->getDocument($parent);

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


return '
<iframe id="collections-frame" 
  src="' . MODX_MANAGER_URL . 'index.php?a=112&from=doc&id=' . $module_id . '&parent=' . $parent . '&controller=' . $controller . '" 
  style="width:100%;height:410px;" 
  scrolling="auto" 
  frameborder="0"></iframe>
<script>
    var height = jQuery(window).height();
    var $obj = jQuery("#collections-frame");
    height = height - 140;
    $obj.height(height)
    $obj.attr("src",$obj.attr("data-src"))
</script>';
