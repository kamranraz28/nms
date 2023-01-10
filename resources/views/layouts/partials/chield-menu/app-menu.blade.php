
@foreach ($project['allChildren'] as $key => $project)
    <ul class="dropdown-menu" role="menu">
        
        @if ($project['allChildren'])
            <li class="dropdown-submenu">
                <a href="#!" class="dropdown-toggle" data-toggle="dropdown">{{$project->name}}</a>
                @include('layouts.partials.chield-menu.app-menu-1', $project)
                @if ($project->last)
                    <ul class="dropdown-menu">
                        @foreach ($project['content'] as $item)
                            <li class="{{(substr(url()->current(), (strrpos(url()->current(),'/'))+1) == $item->slug) ? 'active' : ''}}">
                                <a href="{{route('site.content',['slug'=>$item->slug])}}">{{ $item->name }}</a></li>
                        @endforeach
                    </ul> 
                @endif
            </li>
        @endif

    </ul>
@endforeach



