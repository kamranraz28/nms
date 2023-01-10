
    @foreach ($project['allChildren'] as $key => $project)
        
        <ul class="dropdown-menu">
            @if ($project['allChildren'])
                <li class="dropdown-submenu">
                    <a href="#!" class="dropdown-toggle">{{$project->name}}</a>
                    
                    @if ($project->last)
                    <ul class="dropdown-menu">
                        @foreach ($project['content'] as $item)
                        <li class="{{(substr(url()->current(), (strrpos(url()->current(),'/'))+1) == $item->slug) ? 'active' : ''}}">
                            <a href="{{route('site.content',['slug'=>$item->slug])}}">{{ $item->name }}</a>
                        </li>
                        @endforeach
                    </ul>
                    @endif
                    @include('layouts.partials.chield-menu.app-menu-1', $project)
                </li>
            
            @endif
        </ul>

    @endforeach



