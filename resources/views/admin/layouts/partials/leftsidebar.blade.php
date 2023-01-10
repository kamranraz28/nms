<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    
    <a href="{{route('admin.dashboard')}}" class="brand-link">
      <img src="{{ ($sitesetting->logo) ?  asset('storage/'.$sitesetting->logo) : asset('site/assets/images/logo.png') }}" alt="{{( $sitesetting->{'title_'. app()->getLocale()} )? $sitesetting->{'title_'. app()->getLocale()} :__('admin.menu.site_short') }}" class="brand-image img-circle elevation-3" style="opacity:1">
      {{-- <span class="brand-text font-weight-light" style="font-weight: bold!important;">{{( $sitesetting->{'title_'. app()->getLocale()} )? $sitesetting->{'title_'. app()->getLocale()} :__('admin.menu.site_short') }}</span>  --}}
    </a>

    

    <!-- Sidebar -->
    <div class="sidebar">

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          
          <li class="nav-item">
            <a href="{{route('admin.dashboard')}}" class="menu-loader nav-link {{$retVal = (Route::current()->getName() == 'admin.dashboard') ? 'active' : '' ;}}">
              <i class="nav-icon fas fa-th"></i>
              <p>
                {{__('admin.menu.dashboard')}}
              </p>
            </a>
          </li>
        
          <li class="nav-item has-child {{$retVal = (request()->route()->getPrefix() == 'admin/role-permissions') ? 'menu-open' : ''}}">
            <a href="#" class="nav-link">
              <i class="fab fa-critical-role"></i>
              <p>
                {{__('admin.menu.role_parent')}}
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              
              @can('parent', app('App\Models\SiteSetting'))
              <li class="nav-item">
                <a href="{{route('admin.site_setting')}}" class="menu-loader nav-link {{$retVal = (Route::current()->getName() == 'admin.site_setting') ? 'active' : '' ;}}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>{{__('admin.menu.site_setting')}} </p>
                </a>
              </li>
              @endcan
              
              
              
              @can('parent', app('App\Models\Role'))
              <li class="nav-item">
                <a href="{{route('admin.role')}}" class="menu-loader nav-link {{$retVal = (Route::current()->getName() == 'admin.role') ? 'active' : '' ;}}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>{{__('admin.menu.role')}} </p>
                </a>
              </li>
              @endcan
              
              @can('parent', app('App\Models\UserType'))
              <li class="nav-item">
                <a href="{{route('admin.user_type')}}" class="menu-loader nav-link {{$retVal = (Route::current()->getName() == 'admin.user_type') ? 'active' : '' ;}}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>{{__('admin.menu.user_type')}} </p>
                </a>
              </li>
              @endcan

              @can('parent', app('App\Models\Lang'))
              <li class="nav-item">
                <a href="{{route('admin.lang')}}" class="menu-loader nav-link {{$retVal = (Route::current()->getName() == 'admin.lang') ? 'active' : '' ;}}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>{{__('admin.menu.lang')}} </p>
                </a>
              </li>
              @endcan

            </ul>
          </li>

          <li class="nav-item has-child {{$retVal = (request()->route()->getPrefix() == 'admin/locations') ? 'menu-open' : ''}}">
            <a href="#" class="nav-link">
              <i class="fa fa-map-marker" aria-hidden="true"></i>
              <p>
                {{__('admin.menu.location_parent')}}
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              {{-- @can('parent', app('App\Models\State'))
              <li class="nav-item">
                <a href="{{route('admin.state')}}" class="menu-loader nav-link {{$retVal = (Route::current()->getName() == 'admin.state') ? 'active' : '' ;}}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>{{__('admin.menu.state')}} </p>
                </a>
              </li>
              @endcan --}}

              @can('parent', app('App\Models\Division'))
              <li class="nav-item">
                <a href="{{route('admin.division')}}" class="menu-loader nav-link {{$retVal = (Route::current()->getName() == 'admin.division') ? 'active' : '' ;}}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>{{__('admin.menu.division')}} </p>
                </a>
              </li>
              @endcan

              @can('parent', app('App\Models\District'))
              <li class="nav-item">
                <a href="{{route('admin.district')}}" class="menu-loader nav-link {{$retVal = (Route::current()->getName() == 'admin.district') ? 'active' : '' ;}}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>{{__('admin.menu.district')}} </p>
                </a>
              </li>
              @endcan

              @can('parent', app('App\Models\Upazila'))
              <li class="nav-item">
                <a href="{{route('admin.upazila')}}" class="menu-loader nav-link {{$retVal = (Route::current()->getName() == 'admin.upazila') ? 'active' : '' ;}}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>{{__('admin.menu.upazila')}} </p>
                </a>
              </li>
              @endcan

            </ul>
          </li>

          <li class="nav-item has-child {{$retVal = (request()->route()->getPrefix() == 'admin/offices') ? 'menu-open' : ''}}">
            <a href="#" class="nav-link">
              <i class="fa fa-building" aria-hidden="true"></i>
              <p>
                {{__('admin.menu.office_parent')}}
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              @can('parent', app('App\Models\ForestState'))
              <li class="nav-item">
                <a href="{{route('admin.forest_state')}}" class="menu-loader nav-link {{$retVal = (Route::current()->getName() == 'admin.forest_state') ? 'active' : '' ;}}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>{{__('admin.menu.forest_state')}} </p>
                </a>
              </li>
              @endcan

              @can('parent', app('App\Models\ForestDivision'))
              <li class="nav-item">
                <a href="{{route('admin.forest_division')}}" class="menu-loader nav-link {{$retVal = (Route::current()->getName() == 'admin.forest_division') ? 'active' : '' ;}}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>{{__('admin.menu.forest_division')}} </p>
                </a>
              </li>
              @endcan

              @can('parent', app('App\Models\ForestRange'))
              <li class="nav-item">
                <a href="{{route('admin.forest_range')}}" class="menu-loader nav-link {{$retVal = (Route::current()->getName() == 'admin.forest_range') ? 'active' : '' ;}}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>{{__('admin.menu.forest_range')}} </p>
                </a>
              </li>
              @endcan

              @can('parent', app('App\Models\ForestBeat'))
              <li class="nav-item">
                <a href="{{route('admin.forest_beat')}}" class="menu-loader nav-link {{$retVal = (Route::current()->getName() == 'admin.forest_beat') ? 'active' : '' ;}}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>{{__('admin.menu.forest_beat')}} </p>
                </a>
              </li>
              @endcan

            </ul>
          </li>
          

          <li class="nav-item has-child {{$retVal = (request()->route()->getPrefix() == 'admin/users') ? 'menu-open' : ''}}">
            <a href="#" class="nav-link">
              {{-- <i class="fa fa-building" aria-hidden="true"></i> --}}
              <i class="fa fa-users" aria-hidden="true"></i>
              <p>
                {{__('admin.menu.user_parent')}}
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              
              @can('parent', app('App\Models\Admin'))
              <li class="nav-item">
                <a href="{{route('admin.admin')}}" class="menu-loader nav-link {{$retVal = (Route::current()->getName() == 'admin.admin') ? 'active' : '' ;}}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>{{__('admin.menu.admin')}} </p>
                </a>
              </li>
              @endcan
              
              @can('parent', app('App\Models\RangeOffice'))
              <li class="nav-item">
                <a href="{{route('admin.range_office')}}" class="menu-loader nav-link {{$retVal = (Route::current()->getName() == 'admin.range_office') ? 'active' : '' ;}}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>{{__('admin.menu.range_office')}} </p>
                </a>
              </li>
              @endcan
              
              @can('parent', app('App\Models\BeatOffice'))
              <li class="nav-item">
                <a href="{{route('admin.beat_office')}}" class="menu-loader nav-link {{$retVal = (Route::current()->getName() == 'admin.beat_office') ? 'active' : '' ;}}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>{{__('admin.menu.beat_office')}} </p>
                </a>
              </li>
              @endcan
              
              {{-- @can('parent', app('App\Models\Nursery'))
              <li class="nav-item">
                <a href="{{route('admin.nursery')}}" class="menu-loader nav-link {{$retVal = (Route::current()->getName() == 'admin.nursery') ? 'active' : '' ;}}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>{{__('admin.menu.nursery')}} </p>
                </a>
              </li>
              @endcan --}}

              @can('parent', app('App\Models\User'))
              <li class="nav-item">
                <a href="{{route('admin.user')}}" class="menu-loader nav-link {{$retVal = (Route::current()->getName() == 'admin.user') ? 'active' : '' ;}}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>{{__('admin.menu.user')}} </p>
                </a>
              </li>
              @endcan

              
            </ul>
          </li>

          <li class="nav-item has-child {{$retVal = (request()->route()->getPrefix() == 'admin/products') ? 'menu-open' : ''}}">
            <a href="#" class="nav-link">
              <i class="fa fa-tree" aria-hidden="true"></i>
              <p>
                {{__('admin.menu.color_parent')}}
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              
              @can('parent', app('App\Models\Category'))
              <li class="nav-item">
                <a href="{{route('admin.category')}}" class="menu-loader nav-link {{$retVal = (Route::current()->getName() == 'admin.category') ? 'active' : '' ;}}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>{{__('admin.menu.category')}} </p>
                </a>
              </li>
              @endcan

              @can('parent', app('App\Models\Budget'))
              <li class="nav-item">
                <a href="{{route('admin.budget')}}" class="menu-loader nav-link {{$retVal = (Route::current()->getName() == 'admin.budget') ? 'active' : '' ;}}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>{{__('admin.menu.budget')}} </p>
                </a>
              </li>
              @endcan

              @can('parent', app('App\Models\FinancialYear'))
              <li class="nav-item">
                <a href="{{route('admin.financial_year')}}" class="menu-loader nav-link {{$retVal = (Route::current()->getName() == 'admin.financial_year') ? 'active' : '' ;}}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>{{__('admin.menu.financial_year')}} </p>
                </a>
              </li>
              @endcan

              @can('parent', app('App\Models\Unit'))
              <li class="nav-item">
                <a href="{{route('admin.unit')}}" class="menu-loader nav-link {{$retVal = (Route::current()->getName() == 'admin.unit') ? 'active' : '' ;}}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>{{__('admin.menu.unit')}} </p>
                </a>
              </li>
              @endcan

              @can('parent', app('App\Models\Size'))
              <li class="nav-item">
                <a href="{{route('admin.size')}}" class="menu-loader nav-link {{$retVal = (Route::current()->getName() == 'admin.size') ? 'active' : '' ;}}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>{{__('admin.menu.size')}} </p>
                </a>
              </li>
              @endcan
              
              @can('parent', app('App\Models\Age'))
              <li class="nav-item">
                <a href="{{route('admin.age')}}" class="menu-loader nav-link {{$retVal = (Route::current()->getName() == 'admin.age') ? 'active' : '' ;}}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>{{__('admin.menu.age')}} </p>
                </a>
              </li>
              @endcan

              @can('parent', app('App\Models\Color'))
              <li class="nav-item">
                <a href="{{route('admin.color')}}" class="menu-loader nav-link {{$retVal = (Route::current()->getName() == 'admin.color') ? 'active' : '' ;}}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>{{__('admin.menu.color')}} </p>
                </a>
              </li>
              @endcan

              @can('parent', app('App\Models\StockType'))
              <li class="nav-item">
                <a href="{{route('admin.stock_type')}}" class="menu-loader nav-link {{$retVal = (Route::current()->getName() == 'admin.stock_type') ? 'active' : '' ;}}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>{{__('admin.menu.stock_type')}} </p>
                </a>
              </li>
              @endcan
              
              @can('parent', app('App\Models\Product'))
              <li class="nav-item">
                <a href="{{route('admin.product')}}" class="menu-loader nav-link {{$retVal = (Route::current()->getName() == 'admin.product') ? 'active' : '' ;}}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>{{__('admin.menu.product')}} </p>
                </a>
              </li>
              @endcan
              
            </ul>

            
{{-- 
          <li class="nav-item has-child {{$retVal = (request()->route()->getPrefix() == 'admin/pages') ? 'menu-open' : ''}}">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-book"></i>
              <p>
                {{__('admin.menu.page_parent')}}
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              @can('parent', app('App\Models\ContentCategory'))
              <li class="nav-item">
                <a href="{{route('admin.content_category')}}" class="menu-loader nav-link {{$retVal = (Route::current()->getName() == 'admin.content_category') ? 'active' : '' ;}}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>{{__('admin.menu.content_category')}} </p>
                </a>
              </li>
              @endcan

              @can('parent', app('App\Models\Version'))
              <li class="nav-item">
                <a href="{{route('admin.version')}}" class="menu-loader nav-link {{$retVal = (Route::current()->getName() == 'admin.version') ? 'active' : '' ;}}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>{{__('admin.menu.version')}} </p>
                </a>
              </li>
              @endcan
              
              @can('parent', app('App\Models\Content'))
              <li class="nav-item">
                <a href="{{route('admin.content')}}" class="menu-loader nav-link {{$retVal = (Route::current()->getName() == 'admin.content') ? 'active' : '' ;}}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>{{__('admin.menu.content')}} </p>
                </a>
              </li>
              @endcan
              
            </ul> --}}
          </li>

          <li class="nav-item has-child {{$retVal = (request()->route()->getPrefix() == 'admin/inventory') ? 'menu-open' : ''}}">
            <a href="#" class="nav-link">
              <i class="fa fa-briefcase" aria-hidden="true"></i>
              <p>
                {{__('admin.menu.inventory_parent')}}
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              
              @can('parent', app('App\Models\Purchase'))
              <li class="nav-item">
                <a href="{{route('admin.purchase')}}" class="menu-loader nav-link {{$retVal = (Route::current()->getName() == 'admin.purchase') ? 'active' : '' ;}}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>{{__('admin.menu.purchase')}} </p>
                </a>
              </li>
              @endcan
              
              @can('parent', app('App\Models\Sale'))
              <li class="nav-item">
                <a href="{{route('admin.sale')}}" class="menu-loader nav-link {{$retVal = (Route::current()->getName() == 'admin.sale') ? 'active' : '' ;}}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>{{__('admin.menu.sale')}} </p>
                </a>
              </li>
              @endcan
              
            </ul>
          </li>



          <li class="nav-item has-child {{$retVal = (request()->route()->getPrefix() == 'admin/reports') ? 'menu-open' : ''}}">
            <a href="#" class="nav-link">
              <i class="fa fa-flag" aria-hidden="true"></i>
              <p>
                {{__('admin.menu.report_parent')}}
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              
              @can('parent', app('App\Models\ReportOne'))
              <li class="nav-item">
                <a href="{{route('admin.report_one')}}" class="menu-loader nav-link {{$retVal = (Route::current()->getName() == 'admin.report_one') ? 'active' : '' ;}}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>{{__('admin.menu.report_one')}} </p>
                </a>
              </li>
              @endcan
              
              @can('parent', app('App\Models\ReportTwo'))
              <li class="nav-item">
                <a href="{{route('admin.report_two')}}" class="menu-loader nav-link {{$retVal = (Route::current()->getName() == 'admin.report_two') ? 'active' : '' ;}}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>{{__('admin.menu.report_two')}} </p>
                </a>
              </li>
              @endcan
              
              @can('parent', app('App\Models\ReportThree'))
              <li class="nav-item">
                <a href="{{route('admin.report_three')}}" class="menu-loader nav-link {{$retVal = (Route::current()->getName() == 'admin.report_three') ? 'active' : '' ;}}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>{{__('admin.menu.report_three')}} </p>
                </a>
              </li>
              @endcan
              
              {{-- @can('parent', app('App\Models\ReportFour'))
              <li class="nav-item">
                <a href="{{route('admin.report_four')}}" class="menu-loader nav-link {{$retVal = (Route::current()->getName() == 'admin.report_four') ? 'active' : '' ;}}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>{{__('admin.menu.report_four')}} </p>
                </a>
              </li>
              @endcan --}}
              
              @can('parent', app('App\Models\ReportFive'))
              <li class="nav-item">
                <a href="{{route('admin.report_five')}}" class="menu-loader nav-link {{$retVal = (Route::current()->getName() == 'admin.report_five') ? 'active' : '' ;}}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>{{__('admin.menu.report_five')}} </p>
                </a>
              </li>
              @endcan
              
              @can('parent', app('App\Models\ReportSix'))
              <li class="nav-item">
                <a href="{{route('admin.report_six')}}" class="menu-loader nav-link {{$retVal = (Route::current()->getName() == 'admin.report_six') ? 'active' : '' ;}}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>{{__('admin.menu.report_six')}} </p>
                </a>
              </li>
              @endcan
              
              @can('parent', app('App\Models\ReportSeven'))
              <li class="nav-item">
                <a href="{{route('admin.report_seven')}}" class="menu-loader nav-link {{$retVal = (Route::current()->getName() == 'admin.report_seven') ? 'active' : '' ;}}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>{{__('admin.menu.report_seven')}} </p>
                </a>
              </li>
              @endcan
              
              @can('parent', app('App\Models\ReportEight'))
              <li class="nav-item">
                <a href="{{route('admin.report_eight')}}" class="menu-loader nav-link {{$retVal = (Route::current()->getName() == 'admin.report_eight') ? 'active' : '' ;}}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>{{__('admin.menu.report_eight')}} </p>
                </a>
              </li>
              @endcan
              
              @can('parent', app('App\Models\ReportNine'))
              <li class="nav-item">
                <a href="{{route('admin.report_nine')}}" class="menu-loader nav-link {{$retVal = (Route::current()->getName() == 'admin.report_nine') ? 'active' : '' ;}}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>{{__('admin.menu.report_nine')}} </p>
                </a>
              </li>
              @endcan
              
            </ul>
          </li>

        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>