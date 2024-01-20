<script>
    function openFile(id) {
        let file = document.getElementById(id).value;
        if (file!=-1) {
            let url = window.location.protocol + '//' + window.location.host + '/files/' + file;
            window.open("https://docs.google.com/gview?url=" + url + "&embedded=true");
        }
    }
    function validate() {
        let name = document.getElementById('namePassport').value;
        let file = document.getElementById('file').value;
        let fileList = document.getElementById('fileList').value;
        let checkedLocal = document.getElementById('checkLocal').checked;
        let checkedOnline = document.getElementById('checkOnline').checked;
        let message = '';
        if (name==='')
            message += 'Поле наименования должно быть заполнено<br>';
        else if (name.length<4)
            message+= 'Наименование должно вмещать больше 4 символом<br>';
        if (name.length>50)
            message+= 'Наименование должно вмещать меньше 50 символом<br>';
        if (checkedLocal && file==='')
            message += 'Выберите файл<br>';
        else if (checkedOnline && fileList==='-1')
            message += 'Выберите файл<br>';
        document.getElementById('error').innerHTML=message;
        if (message == '')
            message = true;
        else
            message = false;
        return message;
    }
    function downloadFile(element) {
        let id = element.getAttribute('id');
        let file = document.getElementById('file' + id).text;
        let lab = document.getElementById('lab' + id).value;
        if (lab !== '-1') {
            $.ajax({
                url: '{{route('downloadFile')}}',
                type: "POST",
                data: {
                    id: id,
                    lab: lab
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="CSRF"]').attr('content')
                },
                success: function (response) {
                    let url = window.location.protocol + '//' + window.location.host + '/files/tmp/' + file;
                    window.open(url);
                },
            });
        }
    }
</script>
