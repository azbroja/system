
<div class="container">
    <div class="row justify-content-center">
        <div class="">
            <div class="card">
                <div class="card-header">Menu</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <ul> 
                    @foreach ($links as $link) 
                        
                            <a href="{{ $link['url'] }}">{{ $link['label']}}</a>
                        
                    @endforeach
                        </ul>


                </div>
            </div>
        </div>
    </div>
</div>
