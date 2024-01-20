<script>
    var firstLoad = true;
    var i = 0;
    var timer = setTimeout(hideMessage, 1);
    window.onload = hidePanels();
    //Update personal data
    $('#updPers').on('submit', function (e) {
        e.preventDefault();
        hideMessage();
        clearTimeout(timer);
        var id = $("input[name=id]").val();
        var surname = $("input[name=updSurname]").val();
        var name = $("input[name=updName]").val();
        var patronymic = $("input[name=updPatronymic]").val();
        var message = validateUser(true, surname, name, patronymic);
        if (message === true) {
            $.ajax({
                url: '{{route('updateUser')}}',
                type: "POST",
                data: {
                    id: id,
                    surname: surname,
                    name: name,
                    patronymic: patronymic
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="updPersCsrf"]').attr('content')
                },
                success: function (response) {
                    if (response.status === 'success')
                        document.getElementById('messageSuccess').innerHTML = response.message;
                    else
                        document.getElementById('messageError').innerHTML = response.message;
                    timer = setTimeout(hideMessage, 5000);
                },
            });
        } else {
            document.getElementById('messageWarning').innerHTML = message;
        }
    })
    //Update password
    $('#updPass').on('submit', function (e) {
        e.preventDefault();
        hideMessage();
        clearTimeout(timer);
        var id = $("input[name=id]").val();
        var oldPass = $("input[name=oldPassword]").val();
        var newPass = $("input[name=newPassword]").val();
        var newPassAgain = $("input[name=newPasswordAgain]").val();
        var message = validation(oldPass, newPass, newPassAgain);
        if (message === 'true') {
            $.ajax({
                url: '{{route('updateUser')}}',
                type: "POST",
                data: {
                    id: id,
                    oldPass: oldPass,
                    newPass: newPass
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="updPassCsrf"]').attr('content')
                },
                success: function (response) {
                    if (response.status === 'success')
                        document.getElementById('messageSuccess').innerHTML = response.message;
                    else
                        document.getElementById('messageError').innerHTML = response.message;
                    timer = setTimeout(hideMessage, 5000);
                },
            });
        } else {
            document.getElementById('messageWarning').innerHTML = message;
        }
    })
    //Change admin
    $('#changeAdm').on('submit', function (e) {
        e.preventDefault();
        hideMessage();
        clearTimeout(timer);
        var id = $("input[name=id]").val();
        var adminPassword = $("input[name=adminPassword]").val();
        var newAdmin = document.getElementById('newAdmin').value;
        $.ajax({
            url: '{{route('changeAdmin')}}',
            type: "POST",
            data: {
                id: id,
                adminPassword: adminPassword,
                newAdmId: newAdmin
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="changeAdmCsrf"]').attr('content')
            },
            success: function (response) {
                if (response.status === 'success')
                    location.href = "{{route('profile')}}"
                else
                    document.getElementById('messageError').innerHTML = response.message;

                timer = setTimeout(hideMessage, 5000);
            },
        });
    })

    //Delete users
    function deleteUser(element) {
        hideMessage();
        clearTimeout(timer);
        var id = $("input[name=id]").val();
        var adminPassword = document.getElementById('adminPassword').value;
        var userId = element.getAttribute('userId');
        $.ajax({
            url: '{{route('deleteUser')}}',
            type: "POST",
            data: {
                id: id,
                adminPassword: adminPassword,
                userId: userId
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="deleteUserCsrf"]').attr('content')
            },
            success: function (response) {
                if (response.status === 'success') {
                    document.getElementById('delete' + userId).remove();
                    let newAdm = document.getElementsByClassName('newAdmins');
                    for (let i = 0; i < newAdm.length; i++) {
                        if (newAdm[i].value == userId)
                            newAdm[i].remove();
                    }
                } else
                    document.getElementById('messageError').innerHTML = response.message;
                timer = setTimeout(hideMessage, 5000);
            },
        });
    }

    //Add users
    function addUser() {
        hideMessage();
        clearTimeout(timer);
        var id = $("input[name=id]").val();
        var adminPassword = document.getElementById('adminPassword').value;
        var email = document.getElementById('addEmail').value;
        var surname = document.getElementById('addSurname').value;
        var name = document.getElementById('addName').value;
        var patronymic = document.getElementById('addPatronymic').value;
        var message = validateUser(email, surname, name, patronymic);
        if (message === true) {
            $.ajax({
                url: '{{route('addUser')}}',
                type: "POST",
                data: {
                    id: id,
                    adminPassword: adminPassword,
                    email: email,
                    surname: surname,
                    name: name,
                    patronymic: patronymic,
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="addUserCsrf"]').attr('content')
                },
                success: function (response) {
                    if (response.status === 'success') {
                        document.getElementById('addEmail').value = '';
                        document.getElementById('addSurname').value = '';
                        document.getElementById('addName').value = '';
                        document.getElementById('addPatronymic').value = '';
                        document.getElementById('messageSuccess').innerHTML = response.message
                    } else
                        document.getElementById('messageError').innerHTML = response.message;
                    timer = setTimeout(hideMessage, 5000);
                },
            });
        } else {
            document.getElementById('messageWarning').innerHTML = message;
        }
    }

    //Validate passwords
    function validation(oldPass, newPass, newPassAgain) {
        var message = '';
        if (oldPass === '')
            message = 'Поле старого пароля должно быть заполнено' + "<br>";
        if (newPass === '')
            message += 'Поле нового пароля должно быть заполнено' + "<br>";
        if (newPass.length < 8 || newPass.length > 20)
            message += 'Новый пароль должно быть больше 8 и меньше 20' + "<br>";
        if (newPass.includes(' '))
            message += 'Новый пароль не должен содержать пробелы' + "<br>";
        if (newPass !== newPassAgain)
            message += 'Поля пароля и повтора пароля должны совпадать' + "<br>";
        if (message === '')
            message = 'true';
        return message;
    }

    //Validate new user
    function validateUser(email, surname, name, patronymic) {
        var message = '';
        if (email != true) {
            if (email === '')
                message = 'Поле почты должно быть заполнено' + "<br>";
            // if (!email.includes('@gmail.com') && !email.includes('@mpt.ru')) {
            //     message += 'Доменом должен быть gmail.com или mpt.com' + "<br>";
            // }
        }
        if (surname.length < 3 || surname.length > 30)
            message += 'Длина фамилии должно быть больше 2 и меньше 30' + "<br>";
        if (name.length < 2 || name.length > 20)
            message += 'Длина имени должно быть больше 1 и меньше 20' + "<br>";
        if (patronymic.length != 0)
            if (patronymic.length < 3 || patronymic.length > 50)
                message += 'Длина отчества должна быть больше 2 и меньше 50' + "<br>";
        if (message === '')
            message = true;
        return message;
    }

    //Panels visibility
    function hidePanels() {
        hideMessage();
        var panels = document.getElementsByName('panel');
        for (i = 0; i < panels.length; i++) {
            panels[i].style.display = 'none';
        }
        if (firstLoad === true) {
            document.getElementById('changeDataPanel').style.display = '';
            firstLoad = false;
        }
    }

    function changeData() {
        document.getElementById('oldPassword').value = '';
        document.getElementById('newPassword').value = '';
        document.getElementById('newPasswordAgain').value = '';
        var dataPanel = document.getElementById('changeDataPanel');
        if (dataPanel.style.display === 'none') {
            hidePanels();
            dataPanel.style.display = '';
        } else {
            hideMessage();
            dataPanel.style.display = 'none';
        }
    }

    function adminPanel() {
        document.getElementById('adminPassword').value = '';
        var dataPanel = document.getElementById('adminPanel');
        if (dataPanel.style.display === 'none') {
            hidePanels();
            dataPanel.style.display = '';
        } else {
            hideMessage();
            dataPanel.style.display = 'none';
        }
    }

    function deleteAccount() {
        if (confirm('Вы уверены, что хотите удалить свой аккаунт?')) {
            window.location.replace('{{route('deleteAccount')}}');
        }
    }

    //Show password
    function showPassword(id) {
        var pass = document.getElementById(id);
        if (pass.type === "password") {
            pass.type = "text";
        } else {
            pass.type = "password";
        }
        pass.classList.toggle('visible');
    }

    //Hide message after ajax
    function hideMessage() {
        document.getElementById('messageSuccess').innerHTML = "";
        document.getElementById('messageWarning').innerHTML = "";
        document.getElementById('messageError').innerHTML = "";
    }

    function printUsers(){
        $.ajax({
            url: '{{route('printUsers')}}',
            type: "GET",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="printUsers"]').attr('content')
            },
        });
    }
</script>
