<script type="text/javascript">
    var att = {{$lastAttId}};
    var attVal = {{$lastValId}};
    var csrf = 0;
    window.addEventListener("focusout", createAttVal(this));
    window.addEventListener("focusout", createAtt(this));
    function validation() {
        var errorName = document.getElementById('errorName');
        var name = document.getElementById('eqName');
        if (name.value === "") {
            errorName.innerHTML = 'Поле наименование должно быть заполнено.';
            return false;
        }
        else if (name.value.length < 2 || name.value.length > 100) {
            errorName.innerHTML = 'Поле наименование должно быть не меньше 2 и не больше 100 символов.';
            return false;
        }
        else {
            errorName.innerHTML = "";
            if (confirm('Вы закончили создание оборудования?')) {
                return true;
            } else {
                return false;
            }
        }
    }

    function changeType(element){
        att = element.getAttribute('tag');
        var parent = element.parentNode;
        if (element.value=='1') {
            document.getElementById('attributes' + att).remove();
        }
        else {
            attVal = 0;
            parent.insertAdjacentHTML('afterend', "<div id=\"attributes" + att + "\" class=\"rounded border \" style=\"overflow: auto; width: fit-content; max-height: 8em \">\n" +
                "<input class=\"form-control rounded d-block w-100 val" + att + "\" attval=\"" + attVal + "\" att=\"" + att + "\" name=\"val"+att+"."+attVal+"\" type=\"text\" id=\"" + attVal + "\" onfocusout=\"createAttVal(this)\" placeholder=\"Значение\">\n" +
                "</div>");
        }
    }

    function createAttVal(element){
        att = element.getAttribute('att');
        attVal = element.getAttribute('attval');
        var elements = document.getElementsByClassName('val'+att);
        var elId = -1;
        for (var i=0; i<elements.length;i++){
            if (element.id==elements[i].id){
                elId=i;
                break;
            }
        }
        if (element.value!="" && element.value!=null){
            if ((elId+1)==elements.length) {
                attVal = Number(attVal)+1;
                element.insertAdjacentHTML('afterend', "<input class=\"form-control rounded d-block w-100 val"+att+"\" attval=\""+attVal+"\" att=\""+att+"\" name=\"val"+att+"."+attVal+"\" type=\"text\" id=\""+att+"."+attVal+"\" onfocusout=\"createAttVal(this)\" placeholder=\"Значение\">");
                document.getElementById(att+"."+attVal).focus();
            }
        } else {
            if ((elId+1)!=elements.length) {
                element.remove();
            }
        }
    }

    function createAtt(element){
        att = element.getAttribute('tag');
        attVal = 0;
        var elements = document.getElementsByClassName('atts');
        var elId = -1;
        for (var i=0; i<elements.length;i++){
            if (element.id==elements[i].id){
                elId=i;
                break;
            }
        }
        var parent = element.parentNode.parentNode;
        if (element.value!="" && element.value!=null){
            if ((elId+1)==elements.length) {
                att = Number(att)+1;
                parent.insertAdjacentHTML('afterend', "<div tag=\""+att+"\" class=\"w-100 row mx-auto mt-2 border-bottom\" style=\"height: 8em\">\n" +
                    "<div class=\"border rounded\" style=\"width: fit-content\">\n" +
                    "<label class=\"d-block  mt-1\" for=\"att"+att+"\">Атрибут</label>\n" +
                    "<input class=\"form-control rounded atts d-inline-block\" style=\"width: fit-content\" type=\"text\" name=\"att"+att+"\" tag=\""+att+"\" placeholder=\"Атрибут\" onfocusout=\"createAtt(this)\" id=\"att"+att+"\">\n" +
                    "<select class=\"combobox rounded mt-1 d-inline-block\" onchange=\"changeType(this)\" tag=\""+att+"\" name=\"type"+att+"\" id=\"type"+att+"\" style=\"padding: 2% 0%; width: fit-content\">\n" +
                    "<option value=\"1\">Текст</option>\n" +
                    "<option value=\"2\">Лист</option>\n" +
                    "</select>\n" +
                    "</div>\n" +
                    "</div>")
            }
        } else {
            if ((elId+1)!=elements.length) {
                if (confirm('Вы действительно хотите удалить данный атрибут?')) {
                    parent.remove();
                } else {

                }
            }
        }
    }
</script>
