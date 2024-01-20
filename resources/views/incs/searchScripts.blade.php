<script>
    function search(element) {
        let filter = element.value;
        let i = 0;
        //Labs
        if (element.getAttribute('view') === 'labs') {
            let labs = document.getElementsByClassName('labs');
            if (filter !== '') {
                let name = '';
                let type = '';
                let subType = '';
                let desc = '';
                for (i = 0; i < labs.length; i++) {
                    name = labs[i].getAttribute('name');
                    type = labs[i].getAttribute('type');
                    subType = labs[i].getAttribute('subType');
                    desc = labs[i].getAttribute('desc');
                    if (!name.includes(filter) && !type.includes(filter) && !subType.includes(filter) && !desc.includes(filter)) {
                        labs[i].classList.remove("d-inline-block");
                        labs[i].style.display = 'none';
                    } else {
                        labs[i].classList.add("d-inline-block");
                        labs[i].style.display = '';
                    }
                }
            } else {
                for (i = 0; i < labs.length; i++) {
                    labs[i].classList.add("d-inline-block");
                    labs[i].style.display = '';
                }
            }
        }
        //Eqs
        if (element.getAttribute('view') === 'eqs') {
            let eqs = document.getElementsByClassName('eqs');
            if (filter !== '') {
                let name = '';
                for (i = 0; i < eqs.length; i++) {
                    name = eqs[i].getAttribute('name');
                    if (!name.includes(filter)) {
                        eqs[i].style.display = 'none';
                    } else {
                        eqs[i].style.display = '';
                    }
                }
            } else {
                for (i = 0; i < eqs.length; i++) {
                    eqs[i].style.display = '';
                }
            }
        }
        //Archive
        if (element.getAttribute('view') === 'archive') {
            let labs = document.getElementsByClassName('labs');
            let eqs = document.getElementsByClassName('eqs');
            let passports = document.getElementsByClassName('passports');
            let users = document.getElementsByClassName('users');
            if (filter !== '') {
                let name = '';
                let email = '';
                for (i = 0; i < labs.length; i++) {
                    name = labs[i].getAttribute('name');
                    if (!name.includes(filter)) {
                        labs[i].classList.remove("d-block");
                        labs[i].style.display = 'none';
                    } else {
                        labs[i].classList.add("d-block");
                        labs[i].style.display = '';
                    }
                }
                for (i = 0; i < eqs.length; i++) {
                    name = eqs[i].getAttribute('name');
                    if (!name.includes(filter)) {
                        eqs[i].classList.remove("d-block");
                        eqs[i].style.display = 'none';
                    } else {
                        eqs[i].classList.add("d-block");
                        eqs[i].style.display = '';
                    }
                }
                for (i = 0; i < passports.length; i++) {
                    name = passports[i].getAttribute('name');
                    if (!name.includes(filter)) {
                        passports[i].classList.remove("d-block");
                        passports[i].style.display = 'none';
                    } else {
                        passports[i].classList.add("d-block");
                        passports[i].style.display = '';
                    }
                }
                for (i = 0; i < users.length; i++) {
                    email = users[i].getAttribute('email');
                    if (!email.includes(filter)) {
                        users[i].classList.remove("d-block");
                        users[i].style.display = 'none';
                    } else {
                        users[i].classList.add("d-block");
                        users[i].style.display = '';
                    }
                }
            } else {
                for (i = 0; i < labs.length; i++) {
                    labs[i].classList.add("d-block");
                    labs[i].style.display = '';
                }
                for (i = 0; i < eqs.length; i++) {
                    eqs[i].classList.add("d-block");
                    eqs[i].style.display = '';
                }
                for (i = 0; i < passports.length; i++) {
                    passports[i].classList.add("d-block");
                    passports[i].style.display = '';
                }
                for (i = 0; i < users.length; i++) {
                    users[i].classList.add("d-block");
                    users[i].style.display = '';
                }
            }
        }
        //Passports
        if (element.getAttribute('view') === 'passports'){
            let passports = document.getElementsByClassName('passports');
            if (filter !== '') {
                let file = '';
                let name = '';
                for (i = 0; i < passports.length; i++) {
                    name = passports[i].getAttribute('name');
                    file = passports[i].getAttribute('file');
                    if (!name.includes(filter) && !file.includes(filter)) {
                        passports[i].style.display = 'none';
                    } else {
                        passports[i].style.display = '';
                    }
                }
            } else {
                for (i = 0; i < passports.length; i++) {
                    passports[i].style.display = '';
                }
            }
        }
    }

    function truncateAll(){
        $.ajax({
            url: '{{route('truncateAll')}}',
            type: "GET",
        });
    }
</script>
