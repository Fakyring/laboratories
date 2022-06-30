<div style="color: white" class="d-flex flex-column flex-md-row align-items-center p-2 px-md-4 mb-3 bg-dark">
    <h3 class="my-0 mr-md-auto font-weight-normal">MPT Labs</h3>
    <nav class="my-2 my-md-0 mr-md-3">
        <a class="p-2 text-light" style="font-size: 23px" href="{{route('home')}}">Лаборатории</a>
        <a class="p-2 text-light" style="font-size: 23px" href="{{route('equipments')}}">Оборудование</a>
        <a class="p-2 text-light" style="font-size: 23px" href="{{route('passports')}}">Паспорта</a>
        <a class="p-2 text-light" style="font-size: 23px" href="{{route('archive')}}">Архив</a>
    </nav>
    <div class="btn-group">
        <a href="{{route('profile')}}"><button type="button" class="btn btn-secondary">Профиль</button></a>
        <button type="button" class="btn btn-secondary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="sr-only">Toggle Dropdown</span>
        </button>
        <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 48px, 0px); top: 0px; left: 0px; will-change: transform;">
            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                Выйти
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="post">
                @csrf
            </form>
        </div>
    </div>
</div>
