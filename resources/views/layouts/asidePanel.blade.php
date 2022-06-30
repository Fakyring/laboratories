@include('incs.profileActions')
<div class="sidenav">
    <a id="changeData" onclick="changeData()" href="#">Изменить личные данные</a>
    @if($auth->role=='1')
        <a id="adminPanel" onclick="adminPanel()" href="#">Панель администрации</a>
    @else
        <a class="badge-danger" style="color: white" id="deleteAccount" onclick="deleteAccount()" href="#">Удалить аккаунт</a>
    @endif
</div>
<style>
    .sidenav {
        width: 200px;
        position: absolute;
        z-index: 1;
        top: 3em;
        left: 0px;
        background: #343A40;
        overflow-x: hidden;
        padding: 8px 0;
    }

    .sidenav a {
        padding: 6px 8px 6px 16px;
        text-decoration: none;
        font-size: 20px;
        color: #2196F3;
        display: block;
    }
    .sidenav a:hover {
        color: #064579;
    }
    .main {
        margin-left: 220px; /* Same width as the sidebar + left position in px */
        font-size: 28px; /* Increased text to enable scrolling */
        padding: 0px 10px;
    }
    @media screen and (max-height: 450px) {
        .sidenav {padding-top: 15px;}
        .sidenav a {font-size: 18px;}
    }
</style>
