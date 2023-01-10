{{-- <x-site.contents.footer></x-site.contents.footer> --}}

<footer class="footer">
    <div class="container">
        <div class="row justify-content-between">
            <div class="col-4">
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
            </div>
            <div class="col-7">
                <div class="footer-nav">
                    <p>
                        {{-- <a href="#">About Us</a>
                        <a href="#">Contact Us</a>
                        <a href="#">FAQ</a>
                        <a href="#">Feedback</a> --}}

                        @if ($content_categories)
                            @foreach ($content_categories as $key => $content_category)
                                @if ($content_category->position == 'footer')
                                    @foreach ($content_category->content as $item)
                                        <a href="{{route('site.content',['slug'=>$item->slug])}}" class="{{(substr(url()->current(), (strrpos(url()->current(),'/'))+1) == $item->slug) ? 'active' : ''}}">{{ $item->name }}</a>
                                    @endforeach
                                @endif
                            @endforeach
                        @endif
                    </p>
                </div>
                <div class="footer-btm">
                    {{-- <p>Copyright © {{date('Y')}} {{ __('site.common.site') }}. All rights reserved.</p> --}}

                    <p>{!! $sitesetting->{'footer_'. app()->getLocale()} !!}</p>

                    
                    
                </div>
            </div>
        </div>
    </div>
    </div>

</footer>
