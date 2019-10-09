

@guest

<li class="nav-item">
    <a class="nav-link" href="{{ route('login') }}">{{ __('Zaloguj się') }}</a>
</li>

@else
@can('create_customers')

<div class="search">
<form method="GET" action="/search">
<button type="submit" class="fas fa-search"></button>
<input class="search-input" onfocus="select()" name="q" value="{{$needle ?? ''}}" placeholder="Szukaj Klienta">
</form>
</div>

@endcan
<div class="nav-item">

                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">

                                 <a class="dropdown-item" href="{{ route('user') }}"> Ustawienia
                                </a>
                                <br>

                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                    {{ __('Wyloguj się') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>


                            </div>
</div>

                    @endguest

<script>
    const todayEventsToDo = {!! $todayEventsToDo !!};
    const oldEventsToDo = {!! $oldEventsToDo !!};
</script>
