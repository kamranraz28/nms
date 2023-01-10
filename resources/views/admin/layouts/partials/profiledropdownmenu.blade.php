
<style>
  .profile-img-size-50 {
    width: 40px;
    margin-top: -10px;
    border: 3px solid #f4f6f9;

 
}

p.text-default {
    border: 1px solid #ffa523;
    background-color: #ffa523;
    padding: 5px;
    color: white;
    box-shadow: rgb(249 249 249 / 20%) 0px 7px 29px 0px;
}
</style>  

  <li class="nav-item">
    <p class="text-default" style="margin: 5px 15px; font-weight: 500;">{{authGetLocWiseInfo()}}</p>
  </li>
  <li class="nav-item dropdown">
    <a class="nav-link" data-toggle="dropdown" href="#">
      {{-- <i class="far fa-user"></i>
      <span class="badge badge-danger navbar-badge">3</span> --}}
      @if (Auth::guard('admin')->user()->thumb)
        <img src="{{asset('storage/'.Auth::guard('admin')->user()->thumb)}}" alt="User Avatar" class="profile-img-size-50 mr-3 img-circle">   
      @elseif(Auth::user()->thumb)
        <img src="{{asset('storage/'.Auth::user()->thumb)}}" alt="User Avatar" class="profile-img-size-50 mr-3 img-circle">    
      @else
        <img src="{{ asset('assets/dist/img/avatar.png') }}" alt="User Avatar" class="profile-img-size-50 mr-3 img-circle"> 
      @endif
      
    </a>
    
    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
      <a href="#" class="dropdown-item">
        <div class="media">
          
          <div class="media-body">
            <p class="text-dark" style="margin: 4% 6%; border-bottom: 1px solid;">
              <span><i class="fab fa-critical-role"></i></span>
              <span>
                {{ strtoupper( Auth::guard('admin')->user()->role->{'title_'. app()->getLocale()} )}}
              </span>
            </p>
            <p class="text-info"><i style="margin-top: 4px; margin-right: 30px;" class="far fa-user"></i> {{ Auth::guard('admin')->user()->{'title_'. app()->getLocale()} }} </p>
          </div>
        </div>
      </a>
      <div class="dropdown-divider"></div>
      
      <div class="col-12">
        
        @can('change_password', app('App\Models\Admin'))
        <a class="lead change-password" style="float:left;background: repeating-radial-gradient(black, transparent 100px);color: aliceblue;font-weight: 600;font-size: 12px; padding: 5px 10px" class="dropdown-item dropdown-footer" href="{{ route('admin.change.password', Auth::guard('admin')->user()->id ) }}"
          onclick="event.preventDefault();" data-toggle="modal" data-target="#myModalPasswordChange">
          {{ __('admin.common.change_password') }}
        </a>
        @endcan

        <a class="lead" style="float:right;background: repeating-radial-gradient(black, transparent 100px);color: aliceblue;font-weight: 600;font-size: 12px; padding: 5px 10px" class="dropdown-item dropdown-footer" href="{{ route('admin.logout') }}"
          onclick="event.preventDefault(); document.getElementById('admin-logout-form').submit();">
          {{ __('admin.menu.logout') }}
        </a>
      </div>
      

      <form id="admin-logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
          @csrf
      </form>
    </div>
  </li>

  

  {{-- <li class="nav-item dropdown" style="font-weight: bolder;">
    <a style="background: #f4f6f9;font-weight: bolder;color: tomato;" class="dropdown-item dropdown-footer" href="{{ route('admin.logout') }}"
                                    onclick="event.preventDefault();
                                                  document.getElementById('admin-logout-form').submit();">
                                    {{ __('admin.menu.logout') }}
                                </a>
  </li> --}}

  {{--  <li class="nav-item dropdown">
    <a href="#">
     {{ Config::get('languages')[App::getLocale()]['display'] }}
    </a>

    <ul>
        @foreach (Config::get('languages') as $lang => $language)
            <li>
                @if ($lang != App::getLocale())
                    <a  href="{{ route('lang.switch', $lang) }}">{{$language['display']}}</a>
                @endif
            </li>
        @endforeach
    </ul>
  </li>  --}}


  <li class="nav-item dropdown" style="font-weight: bolder;">
    <a class="nav-link" data-toggle="dropdown" href="#">
      {{ Config::get('languages')[App::getLocale()]['display'] }}
    </a>
    <div class="dropdown-menu" style="border:none">
      @foreach (Config::get('languages') as $lang => $language)  
        @if ($lang != App::getLocale())
          <a style="padding : 0% 35%" href="{{ route('lang.switch', $lang) }}">
            {{$language['display']}}
          </a>
          <div class="dropdown-divider"></div>
        @endif
      @endforeach
    </div>
  </li>

