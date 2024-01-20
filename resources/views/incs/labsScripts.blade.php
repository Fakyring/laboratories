<script>
    let i = 0;
    let timer = setTimeout(hideMessage, 1);
    let constIds = document.getElementsByClassName('eqs').length; //Existing equipments amount to add new eqs
    let selectedPos = 0;
    window.onload = hidePanels();
    function validateMainInfo(){
        let name = document.getElementById('labName').value;
        let type = document.getElementById('labType').value;
        let subType = document.getElementById('labSubType').value;
        let message = '';
        if (name==='')
            message+='Необходимо наименование<br>';
        else if (name.length < 4)
            message+='Длина наименования должна быть больше 4<br>';
        else if (name.length>20)
            message+='Длина наименования должна быть меньше или равна 20<br>';
        if (type==='')
            message+='Необходим тип лаборатории<br>';
        else if (type.length < 2)
            message+='Длина типа должна быть больше 2<br>';
        else if (type.length>70)
            message+='Длина типа должна быть меньше или равно 70<br>';
        if (subType==='')
            message+='Необходим подтип лаборатории<br>';
        else if (subType.length < 2)
            message+='Длина подтипа должна быть больше 2<br>';
        else if (subType.length>70)
            message+='Длина подтипа должна быть меньше или равно 70<br>';
        if (message === '') {
            if (confirm('Вы закончили создание лаборатории?')) {
                return true;
            } else {
                return false;
            }
        }
        else {
            document.getElementById('errorMessage').innerHTML = message;
            message = false;
        }
        return message;
    }

    //Обновить подтипы
    function loadSubTypes() {
        let type = document.getElementById('labType');
        let id = $('#typeList').find('option[value="' + type.value + '"]').attr('id');
        if (id != null) {
            document.getElementById('labSubType').value = '';
            hideMessage();
            clearTimeout(timer);
            $.ajax({
                url: '{{route('updateSubTypes')}}',
                type: "POST",
                data: {
                    id: id
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="CSRF"]').attr('content')
                },
                success: function (response) {
                    document.getElementById('subTypeList').innerHTML = response.message;
                },
            });
        } else document.getElementById('subTypeList').innerHTML = '';
    }

    //Обновить версии софта
    function loadVersions(element) {
        let position = element.getAttribute('position');
        let id = $('#softList' + position).find('option[value="' + element.value + '"]').attr('id');
        if (id != null) {
            document.getElementById('version' + position).value = '';
            hideMessage();
            clearTimeout(timer);
            $.ajax({
                url: '{{route('loadVersions')}}',
                type: "POST",
                data: {
                    id: id
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="CSRF"]').attr('content')
                },
                success: function (response) {
                    document.getElementById('versionList' + position).innerHTML = response.message;
                },
            });
        } else {
            document.getElementById('versionList' + position).innerHTML = "";
        }
        addSoft(element);
    }
    //Добавление нового оборудования
    function copyEq(element) {
        let elementPos = element.getAttribute('position');
        let selectedEq = document.getElementById('selectedEq'+elementPos).value;
        let createNumber = document.getElementById('amount'+elementPos).value;
        let tmpAttVals = document.getElementsByClassName('val' + elementPos);
        let attVals = [];
        for (i = 0; i < tmpAttVals.length; i++)
            attVals.push(tmpAttVals[i].value);
        if (selectedEq !== '-1') {
            if (createNumber > 0) {
                constIds += Number(createNumber);
                document.getElementById('amount'+elementPos).value = '1';
                $.ajax({
                    url: '{{route('addNewEqs')}}',
                    type: "POST",
                    data: {
                        id: selectedEq,
                        amount: createNumber,
                        allEqAmount: constIds,
                        values: attVals
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="CSRF"]').attr('content')
                    },
                    success: function (response) {
                        if (response.status=='success') {
                            document.getElementById('insertHere').insertAdjacentHTML('beforebegin', response.eqs);
                            document.getElementById('insertAttsHere').insertAdjacentHTML('beforebegin', response.atts);
                        }
                    },
                });
            } else {
                alert('Количество оборудований не может быть отрицательными');
            }
        }
    }
    //Смена оборудования
    function changeEq(element) {
        hidePanels();
        let elementPos = element.getAttribute('position');
        selectedPos = elementPos;
        let selectedEq = document.getElementById('selectedEq' + elementPos).value;
        if (selectedEq !== '-1') {
            document.getElementById('amount' + elementPos).value = '1';
            $.ajax({
                url: '{{route('changeEq')}}',
                type: "POST",
                data: {
                    id: selectedEq,
                    position: elementPos
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="CSRF"]').attr('content')
                },
                success: function (response) {
                    if (response.status == 'success') {
                        let prevPanel = document.getElementById('panel' + elementPos);
                        if (prevPanel != null)
                            prevPanel.remove();
                        document.getElementById('insertAttsHere').insertAdjacentHTML('beforebegin', response.atts);
                        document.getElementById('attPanelsName').innerHTML='Атрибуты ' + elementPos+' элемента';
                    }
                },
            });
        } else {
            let prevPanel = document.getElementById('panel' + elementPos);
            if (prevPanel != null) {
                document.getElementById('attPanelsName').innerHTML='';
                prevPanel.remove();
            }
        }
    }
    //Показать атрибуты
    function showAtts(element) {
        let elementPos = element.getAttribute('position');
        let selectedEq = document.getElementById('selectedEq' + elementPos).value;
        if (selectedPos != elementPos)
            hidePanels();
        selectedPos = elementPos;
        if (selectedEq !== '-1') {
            let attPan = document.getElementById('panel' + elementPos);
            if (attPan.style.display === 'none') {
                attPan.style.display = '';
                document.getElementById('attPanelsName').innerHTML='Атрибуты ' + elementPos+' элемента';
            } else {
                attPan.style.display = 'none';
                document.getElementById('attPanelsName').innerHTML='';
            }
        } else {
            hidePanels();
        }
    }
    //Удалить оборудование
    function deleteEq(element) {
        let elementPos = element.getAttribute('position');
        if (!!document.getElementById('panel'+elementPos)) {
            if (document.getElementById('panel' + elementPos).style.display === '')
                document.getElementById('attPanelsName').innerHTML = '';
            document.getElementById('panel' + elementPos).remove();
        }
        document.getElementById('eq' + elementPos).remove();
    }
    //Добавление софта
    function addSoft(element) {
        let elementPos = element.getAttribute('position');
        let parent = element.parentNode;
        let softs = document.getElementsByClassName('soft');
        if (document.getElementById('software' + elementPos).value === '' && document.getElementById('version' + elementPos).value === '') {
            if (elementPos !== softs[softs.length - 1].getAttribute('position')) {
                document.getElementById('softwareDiv' + elementPos).remove();
            }
        } else if (elementPos === softs[softs.length - 1].getAttribute('position') && document.getElementById('version' + elementPos).value !== '' && document.getElementById('software' + elementPos).value !== '') {
            elementPos = Number(elementPos) + 1;
            $.ajax({
                url: '{{route('addSoft')}}',
                type: "POST",
                data: {
                    id: elementPos,
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="CSRF"]').attr('content')
                },
                success: function (response) {
                    if (response.status === 'success') {
                        let parent = element.parentNode;
                        parent.insertAdjacentHTML('afterend', response.soft);
                    }
                },
            });
        }
    }
    //let array = new Array();
    //Добавление ответственных
    function addResp(element) {
        let elementPos = element.getAttribute('position'); //Id of element
        let parent = element.parentNode;
        let resps = document.getElementsByClassName('resps');
        // let options = document.getElementsByClassName('options');
        // let i = 0;
        // let j = 0;
        // let elementValue = element.value; //Value of element
        if (elementPos !== resps[resps.length - 1].getAttribute('position') && document.getElementById('responsible' + elementPos).value === '-1') {
            if (elementPos !== resps[resps.length - 1].getAttribute('position')) {
                // for (i = 0; i < array.length; i++) {
                //     if (array[i] === elementValue)
                //         array.splice(i, 1);
                // }
                document.getElementById('resp' + elementPos).remove();
            }
        } else if (elementPos === resps[resps.length - 1].getAttribute('position') && document.getElementById('responsible' + elementPos).value !== '-1') {
            //array.push(element.value);
            elementPos = Number(elementPos) + 1;
            $.ajax({
                url: '{{route('addResp')}}',
                type: "POST",
                data: {
                    id: elementPos
                    //included: array
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="CSRF"]').attr('content')
                },
                success: function (response) {
                    if (response.status === 'success') {
                        let parent = element.parentNode;
                        parent.insertAdjacentHTML('afterend', response.resp);
                    }
                },
            });
        }
        // let exists = true;
        // for (i = 0; i < options.length; i++) {
        //     exists = true;
        //     for (j = 0; j < array.length; j++) {
        //         if (options[i].value === array[j]) {
        //             exists = false;
        //             break;
        //         }
        //     }
        //     if (exists === false) {
        //         if (document.getElementById('responsible'+options[i].getAttribute('position')).value!==options[i].value){
        //             options[i].remove();
        //         }
        //     } else {
        //
        //     }
        // }
        // alert(array);
    }
    //Видимость панелей
    function hidePanels(){
        hideMessage();
        var panels = document.getElementsByName('panel');
        for (i=0;i<panels.length;i++){
            panels[i].style.display='none';
        }
        document.getElementById('attPanelsName').innerHTML='';
    }
    //Скрыть сообщения
    function hideMessage(){
        document.getElementById('errorMessage').innerHTML='';
    }
    //Добавление фото
    function loadImage(file){
        if (file.files && file.files[0]) {

            var reader = new FileReader();
            reader.onload = function (e) {
                $('#image')
                    .attr('src', e.target.result);
            };
            reader.readAsDataURL(file.files[0]);
        }
    }
</script>
