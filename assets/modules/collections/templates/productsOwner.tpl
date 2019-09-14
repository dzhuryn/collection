<h1><i class="fa fa-list"></i>[+title+]<small>([+parent+])</small></h1>

<div id="actions">
    <ul class="actionButtons">
        <li id="Button1" class="primary">
            <a href="javascript:;" onclick="document.location.href='index.php?a=27&id=[+parent+]';">
                <i class="fa fa-edit"></i>Редактировать документ</a>
        </li>
    </ul>
</div>

<div class="sectionBody">
    <div id="modulePane" class="dynamic-tab-pane-control tab-pane">
        <div class="tab-row">
            <h2 class="tab [+selected.home+]"><a href="[+moduleurl+]action=home"><span>Просмотр дочерних ресурсов</span></a></h2>
        </div>
        <div id="tab-page1" class="tab-page [+action+]-page" style="display:block;">
            [+template+]
        </div>
    </div>
</div>