<div id="docs" style="width:100%;"></div>
<div class="bottom-actions">
	<div class="group">
		<select id="mass-actions">
			<option value="">Действия</option>
			<option value="del">Удалить</option>
			<option value="restore">Восстановить</option>
			<option value="unpub">Снять с публикации</option>
			<option value="pub">Опубликовать</option>
		</select>
		<a  class="btn btn-secondary" href="javascript:;" id="user-mass-actions">
			<span class="fa-check webix_icon"></span> Применить
		</a>

		<a id="Button4" class="btn btn-success add-item ml-2" href="javascript:;">
			<span class="fa-file-o webix_icon"></span> Добавить ресурс
		</a>
	</div>
</div>
<div id="pager" class="ec_pager"></div>

<script>
	//массовые действия
	$(document).on('click','#user-mass-actions',function () {
		var $select = $('#mass-actions');
		var action = $select.val();

		if(action === '') return;

		var actionTitle,actionField,actionNewValue;
		switch (action) {
			case 'del':
				actionTitle = 'удалить';
				actionField = 'deleted';
				actionNewValue = '1';
				break;
			case 'restore':
				actionTitle = 'восстановить';
				actionField = 'deleted';
				actionNewValue = '0';
				break;
			case 'unpub':
				actionTitle = 'снять с публикации';
				actionField = 'published';
				actionNewValue = '0';
				break;
			case 'pub':
				actionTitle = 'опубликовать';
				actionField = 'published';
				actionNewValue = '1';
				break;
		}


		var resources = datatable.getSelectedItem();

		if(resources === undefined){
			webix.alert({
				title: "Ошибка",
				text: "Ничего не выбрано",
			});
			return ;
		}

		var ids = [];
		if(resources.length === undefined){
			ids.push(resources.id);
		}
		else{
			for(var i=0;i<resources.length;i++){
				ids.push(resources[i].id);
			}
		}


		webix.confirm({
			title: "Удалить заказы",// the text of the box header
			text: "Вы уверенны что хотите "+actionTitle+" следующие ресурсы "+ids.join(','),
			callback: function(result) {
				if (result) {
					ids.forEach(function (id) {
						var item = datatable.getItem(id);
						item[actionField] = actionNewValue;
						datatable.updateItem(id,item);
					})
				}
			}
		});



	});
	var datatable;
	var docOnPage = [+display+];

    function status(value, obj){
        var css = '';
        if(obj.published == 0){
            css = 'un-publish';
        }
        if(obj.deleted == 1 ){
            css = 'deleted';
        }
        return css;
    }



    webix.ready(function(){
        datatable = webix.ui(
			[+datatable+]
        );

		datatable.attachEvent("onAfterDrop", function (response, id, object) {

			var items = [];
			datatable.eachRow(
					function (row) {
						var item = datatable.getItem(row);
						items.push(item.id);
					}
			)
			$.post('[+moduleurl+]action=sortable',{
				ids:items.join(','),
			})
		});


		webix.dp($$("table")).attachEvent('onAfterSaveError', function (id, status, response, details) {
		    webix.alert("Произошла ошибка");
		});

		$$("table").attachEvent("onDataUpdate", function(id, data, old){
			var record = $$('table').getItem(id);
			record.statusImage = '/assets/modules/collections/media/image/loader.gif';
			$$('table').refresh();


		});

		webix.dp($$("table")).attachEvent("onAfterSave", function(response, id, object){


			if(response === null){
				webix.alert("Произошла ошибка");
			}
		    else if(response.status !== 'success'){
		        webix.alert("Произошла ошибка");
		    }
		    else{
				var record = $$('table').getItem(id);
				record.statusImage = '/assets/modules/collections/media/image/ok.jpg';
				$$('table').refresh();

				setTimeout(function () {
					var record = $$('table').getItem(id);
					record.statusImage = '';
					$$('table').refresh();
				},2500)
			}



		});

    });


	webix.event(window, "resize", function(){
		$$("table").resize();
	});
    var addImageToId,addImageToField;
	function OpenServerBrowser(url, width, height ) {
		var iLeft = (screen.width  - width) / 2 ;
		var iTop  = (screen.height - height) / 2 ;

		var sOptions = 'toolbar=no,status=no,resizable=yes,dependent=yes' ;
		sOptions += ',width=' + width ;
		sOptions += ',height=' + height ;
		sOptions += ',left=' + iLeft ;
		sOptions += ',top=' + iTop ;

		var oWindow = window.open( url, 'FCKBrowseWindow', sOptions ) ;
	}

	function BrowseServer() {
		var w = screen.width * 0.5;
		var h = screen.height * 0.5;
		OpenServerBrowser('/manager/media/browser/mcpuk/browser.php?Type=images', w, h);
	}
	function SetUrl(url, width, height, alt) {


		$.get('[+moduleurl+]action=getThumb&controller=[+controller+]',{
			image:url
		},function (thumb) {


			var item = datatable.getItem(addImageToId);
			item[addImageToField] = url;
			item[addImageToField+'_thumb'] = thumb;


			datatable.updateItem(addImageToId,item);

		});
	}


	$(document)

			.on('click', '.add-item', function (e) {
				e.preventDefault()
				datatable.add({});

			})
			.on('click', '.edit', function (e) {
				e.preventDefault()

				var obj = {
					url: $(this).attr('href'),
					title: $(this).attr('data-title'),
				};
				[+parentTab+]parent.modx.tabs(obj)

			})
			.on('click', '.js-add-image', function () {
				addImageToId = $(this).attr('data-id');
				addImageToField = $(this).attr('data-field');

				BrowseServer();


			})

</script>
