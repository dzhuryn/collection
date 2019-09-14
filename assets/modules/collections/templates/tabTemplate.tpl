<div id="collections" class="tab-page">
    <h2 class="tab" id="collections-tab">[+tab_name+]</h2>

    <iframe id="collections-frame" src="[+src+]" style="width:100%;height:410px;" scrolling="auto" frameborder="0"></iframe>

    <script>
        var height = jQuery(window).height();
        var $obj = jQuery("#collections-frame");
        height = height - 140;
        $obj.height(height)
        $obj.attr("src",$obj.attr("data-src"))
    </script>
</div>