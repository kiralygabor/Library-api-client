@extends('layout')
 
@section('content')
<h1>Szerzők</h1>
<div>
    <!-- Happiness is not something readymade. It comes from your own actions. - Dalai Lama -->
 
    <ul>
        <table>
        <a href="{{ route(name: 'authors.create') }}" title="Új">Új hozzáadása</a>
        @foreach($authors as $author)
            <li class="row {{ $loop->iteration % 2 == 0 ? 'even' : 'odd' }}">
                <div class="col id">{{ $author->id }}</div>
                <div class="col">
    <a href="{{ route('authors.show', $author->id) }}">
        {{ $author->name }}
    </a>
</div>

                <div class="right">
                    <div class="col">
{{--                        <a href="{{ route('authors.show', $author->id) }}"><button><i class="fa fa-binoculars" title="Mutat"></i></button></a></div>--}}
                       
                    </div>
 
                    
                        <div class="col">
                            <a href="{{ route('authors.edit', $author->id) }}"><button>Módosít</button></a>
                        </div>
                        <div class="col">
                               <form action="{{ route('authors.destroy', $author->id) }}" method="POST" 
      onsubmit="return confirm('Biztos törlöd?');" 
      style="display:inline; margin:0; padding:0;">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn">Töröl</button>
</form>
                        </div>
                    
                </div>
 
            </li>
        @endforeach
        </table>
    </ul>
    @isset($abc)
        <div class="paginator">
            {{ $subjects
                ->appends([
                    'sort_by' => request('sort_by'),
                    'sort_dir' => request('sort_dir'),
                ])
                ->links()
 
            }}
        </div>
    @endisset
</div>
@endsection