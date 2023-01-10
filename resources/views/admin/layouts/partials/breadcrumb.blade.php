
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="{{route('admin.dashboard')}}" class="nav-link">{{__('admin.menu.dashboard')}}</a>
      </li>
      @if (isset($path1))
        <li class="nav-item d-none d-sm-inline-block">
          <a href="" class="nav-link">/</a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
          <a href="{{$route1}}" class="nav-link">{{$path1}}</a>
        </li>    
      @endif
      
    </ul>