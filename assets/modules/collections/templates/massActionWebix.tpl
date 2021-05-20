<div id="massActionButton">
    <div class="group">
        <a id="Button4" class="btn btn-success add-item ml-2" href="javascript:;" onclick="$$('massActionsWebix').show()">
            Mass action
        </a>
    </div>
</div>


<script>
    var massActionField = '[+massActionField+]';



    function getMassActionValuesFromWebix() {
        var increase = $$('increase_percent').getValue();
        var decrease = $$('decrease_percent').getValue();

        var plus = $$('plus_value').getValue();
        var minus = $$('minus_value').getValue();


        var type,value;
        if(increase){
            type = "increase";
            value = increase;
        }
        else if(decrease){
            type = "decrease";
            value = decrease;
        }
        else if(plus){
            type = "plus";
            value = plus;
        }
        else if(minus) {
            type = "minus";
            value = minus;
        }
        else {
            webix.alert("Nothing not select");
            return false;
        }
        return {
            actionType:type,
            actionField:massActionField,
            actionFieldValue:value,
        };
    }

    webix.ui({
        view:"window",
        id:"massActionsWebix",
        position:"center",
        head:"Mass actions",
        close:true,
        body:{
            rows:[
                {

                    cols: [
                        { template:"",autoheight:true},
                        { template:"%",autoheight:true},
                        { template:"$",autoheight:true},
                    ]},
                {

                    cols:[
                        { template: "increase",autoheight:true },
                        { view:"text", id:"increase_percent", autoheight:true },
                        { view:"text", id:"plus_value", autoheight:true },
                    ]},
                {
                    cols:[
                        { template: "decrease",autoheight:true },
                        { view:"text", id:"decrease_percent", autoheight:true },
                        { view:"text", id:"minus_value", autoheight:true },
                    ]},
                {
                    cols:[
                        { template: "",autoheight:true },
                        { view:"button", label:"Apply", autoheight:true, click:function () {
                                var request = getMassActionValuesFromWebix();

                                if(!request){
                                    return false;
                                }

                                applyMassActionSelected(request);
                            }},
                        { view:"button", label:"Apply to all", autoheight:true, click:function () {
                                var request = getMassActionValuesFromWebix();
                                if(!request){
                                    return false;
                                }

                                applyMassActionAll(request);
                            } },
                    ]}
            ]
        },
    });



</script>