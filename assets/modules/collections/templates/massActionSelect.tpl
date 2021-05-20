
    <div class="group">
        <select name="action" id="mass-actions" >
            <option value="">Действия</option>
            <option value="multiplication">Умножить</option>
            <option value="division">Поделить</option>
            <option value="plus">Увеличить</option>
            <option value="minus">Уменьшить</option>
            <option value="set">Установить</option>
        </select>

        <select name="action" id="mass-action-field" style="display: none">

        </select>

        <div id="mass-action-field-value-owner" class="mass-action-field-value-owner">

        </div>


        <a  class="btn btn-secondary" href="javascript:;" onclick="applyMassActionSelected(getMassActionValuesFromSelect())" id="user-mass-actions">
            <span class="fa fa-check "></span> Применить
        </a>

        <a  class="btn btn-secondary" href="javascript:;" onclick="applyMassActionAll(getMassActionValuesFromSelect())" id="use-mass-action-all">
            <span class="fa fa-check-double "></span> Применить ко всем
        </a>

        <a id="Button4" class="btn btn-success add-item ml-2" href="javascript:;">
            <span class="fa-file-o webix_icon"></span> Добавить ресурс
        </a>
    </div>



<script>

    var massActionFields = JSON.parse('[+massActionFields+]');
    var massActions = JSON.parse('[+massActions+]');


    $(document).on('change','#mass-actions',function () {

        $('#mass-action-field-value-owner').html('').hide('')

        var $massActionFieldSelector = $('#mass-action-field');
        $massActionFieldSelector.hide().html($('<option value="">Поле</option>'));
        var action = $(this).val();
        if(!massActions[action]){
            return;
        }
        var actionSupportFields = massActions[action];

        actionSupportFields.forEach(function (fieldName) {

            var fieldConfig = massActionFields[fieldName];

            $massActionFieldSelector.append($('<option></option>').val(fieldName).text(fieldConfig.caption));

        })

        $massActionFieldSelector.show();
    });


    $('#mass-action-field').change(function () {
        var fieldName = $(this).val();

        var massAction = $('#mass-actions').val();


        var $owner = $('#mass-action-field-value-owner');
        $owner.hide().html('');


        if (!massActionFields[fieldName]) {
            return;
        }

        var fieldConfig = massActionFields[fieldName];


        var valueFieldType = massAction === 'set'?fieldConfig.type:'number';

        var $valueField = '';
        switch (valueFieldType) {
            case 'number':
                $valueField = $('<input name="action-field-value" />').attr('type', 'number');
                break;
            case 'text':
                $valueField = $('<input name="action-field-value" />').attr('type', 'number');
                break;

            case  'select':
                var elements = fieldConfig.elements;
                $valueField = $('<select name="action-field-value" />');
                for (var elementValue in elements) {
                    $valueField.append($('<option></option>').val(elementValue).text(elements[elementValue]));
                }
                break;
        }


        $owner.append($valueField);
        $owner.show()

    });

    function getMassActionValuesFromSelect() {

        return {
            actionType:$('#mass-actions').val(),
            actionField:$('#mass-action-field').val(),
            actionFieldValue:$('[name="action-field-value"]').val(),
        };

    }
</script>