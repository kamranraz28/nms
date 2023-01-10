{{-- <x-site.contents.header></x-site.contents.header> --}}


<header class="header" id="home">
    <nav class="navbar navbar-common">
        <div class="container">
           
            {{-- <a class="navbar-brand" href="{{route('site.home')}}">
                DP<span>4</span>PM
            </a> --}}
            @if ($sitesetting->logo)
            <div class="navbar-logo">
                <a href="{{route('site.home')}}">
                    <img src="{{ asset('storage/'.$sitesetting->logo) }}" alt="">
                </a>
            </div>
            @else
            <div class="navbar-logo1">
                <a href="{{route('site.home')}}">
                    <img src="{{ asset('site/assets/images/favicon.png') }}" alt="">
                </a>
            </div>
            @endif

            
            <button class="navbar-toggler">
                <span class="navbar-icon"></span>
                <span class="navbar-icon"></span>
                <span class="navbar-icon"></span>
            </button>

            <div class="navbar-collapse">
                {{-- <div class="navbar-logo">
                    <img src="{{ asset('site/assets/images/favicon.ico') }}" alt="">
                </div> --}}
                {{-- <ul class="navbar-nav">
                    @if ($content_categories)
                        @foreach ($content_categories as $key => $content_category)
                            @if ($content_category->position == 'header')
                                @foreach ($content_category->content as $item)
                                    <li><a href="{{route('site.content',['slug'=>$item->slug])}}" class="{{(substr(url()->current(), (strrpos(url()->current(),'/'))+1) == $item->slug) ? 'active' : ''}}">{{ $item->name }}
                                    </a></li>
                                @endforeach
                            @endif
                        @endforeach
                    @endif
                </ul> --}}



                {{-- <div class="dropdown">
                    <button class="dropbtn">Dropdown</button>
                    <div class="dropdown-content">
                      <a href="#">Link 1</a>
                      <a href="#">Link 2</a>
                      <a href="#">Link 3</a>
                    </div>
                </div> --}}


                {{-- @if ($content_categories)
                    @foreach ($content_categories as $key => $content_category)
                    <div class="dropdown">
                        @if ($content_category->position == 'header')
                        <ul class="dropbtn">{{$content_category->name}}</ul>
                        <div class="dropdown-content">
                            @foreach ($content_category->content as $item)
                            <li>
                                <a href="{{route('site.content',['slug'=>$item->slug])}}" class="{{(substr(url()->current(), (strrpos(url()->current(),'/'))+1) == $item->slug) ? 'active' : ''}}">{{ $item->name }}
                                </a>
                            </li>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    @endforeach
                @endif --}}

                {{-- <ul class="tree">
                    <li>
                        Top Menu 1
                            <ul>
                                <li>
                                    Top Menu 1.1

                                    <ul>
                                        <li>Top Menu 1.1.1</li>
                                    </ul>
                                </li>
                            </ul>
                    </li>
                </ul> --}}

                @if ($content_categories)
                    <ul class="tree">
                        @foreach ($content_categories as $key => $project)
                            @if ($project->position == 'header')
                                <li>
                                    {{$project->name}}
                                    @if ($project['allChildren'])
                                        @include('layouts.partials.chield-menu.content', $project)
                                    @endif
                                </li>
                            @endif
                        @endforeach
                    </ul>
                @endif 
            </div>
        </div>
    </nav>
</header>
