<div class="top-actions">
	[+topActions+]
</div>

<div id="docs" style="width:100%;"></div>

<div id="pager" class="ec_pager"></div>

<script>
	var getDocsUrl = '[+getDocsUrl+]';





    function applyMassActionRequest(request){



    	if(!request['actionType']){
    		webix.alert('Выберите тип операции');
    		return;
		}
		if(!request['actionType']){
			webix.alert('Выберите поле');
			return;
		}


		$.post('[+moduleurl+]action=massUpdate',request,function (response) {
			if(response.status){
				webix.alert('Обновлены '+response.count);

				datatable.clearAll();
				datatable.load(getDocsUrl);
				datatable.filterByAll();


			}
			else {
				webix.alert('Ошибка')
			}


		})

	}
    function applyMassActionSelected(request){
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

		request.type = 'selected';
		request.documents = ids;


		applyMassActionRequest(request);
	}
	function applyMassActionAll(request){



		webix.confirm({
			title: "<h3>Вы уверены?</h3> <span style='color: red;font-size: 12px'>рекомендуем делать <a target='_blank' href='/manager/index.php?a=93'>бекап</a> перед применением массовых действий во избежания непредвиденных ситуаций</span>",
			input: false,
			ok: "Ок",
			cancel: "Отмена",
			callback: function(result) {
				if(result === true){

					var filter = {};

					datatable.eachColumn(function(columnId){
						var value = datatable.getFilter(columnId);
						value = $(value).val();
						if(value){
							filter[columnId] = value;
						}
					})
					request.type = 'all';
					request.filter = filter;


					applyMassActionRequest(request);

				}
			}

		});



	}



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
