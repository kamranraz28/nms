
<ul>
    @foreach ($project['allChildren'] as $key => $project)
        <li>
             {{$project->name}} 

             @if ($project->last)
                 <ul>
                    @foreach ($project['content'] as $item)
                        <li> 
                            <a href="{{route('site.content',['slug'=>$item->slug])}}" class="{{(substr(url()->current(), (strrpos(url()->current(),'/'))+1) == $item->slug) ? 'active' : ''}}">{{ $item->name }}
                            </a>
                        </li>
                    @endforeach
                 </ul>
             @else
                @if ($project['allChildren'])
                    @include('layouts.partials.chield-menu.content', $project)
                @endif
             @endif
        </li>
    @endforeach
</ul>



