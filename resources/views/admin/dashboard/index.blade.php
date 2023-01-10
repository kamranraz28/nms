@php
    use App\Helper\EnglishToBanglaDate;
    use App\Helper\NumberToBanglaWord;
    use Rakibhstu\Banglanumber\NumberToBangla;
    $numto = new NumberToBangla();
@endphp

@php
  use App\Models\Admin;
  $authUser = Auth::guard('admin')->user()->load(['userType']);
      
@endphp


@extends('admin.layouts.master')
@section('title')
  {{ $sitesetting->{'title_'. app()->getLocale()} }} :: {{__('admin.menu.dashboard')}}
@endsection




@section('styles')
<link rel="stylesheet" href="{{ asset('assets/plugins/daterangepicker/daterangepicker.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/bs-stepper/css/bs-stepper.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/dropzone/min/dropzone.min.css') }}">

@endsection

@section('breadcrumb')
  @include('../admin.layouts.partials.breadcrumb')
@endsection


@section('content')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    

    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          
          {{-- <div class="col-sm-2">
            <a href="{{ route('admin.district.create') }}" class="btn btn-info form-control btn-add-new">
                <i class="fas fa-plus"></i> <span>{{ __('admin.common.add') }}</span>
            </a>
          </div> --}}

          {{-- <div class="col-sm-10">
            <h1 class="text-info">
              <i class="fas fa-bookmark"></i> {{ __('admin.menu.dashboard') }}
            </h1>
          </div> --}}


        </div>
      </div>
    </section>

    @if (count($errors) || Session::has('success'))

      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-0">
            <div class="col-md-12">
                @if(count($errors))
                  <div class="alert alert-danger alert-dismissible">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong>{{__('admin.common.error_whoops')}}</strong> {{__('admin.common.error_heading')}}
                    <br/>
                    <ul>
                      @foreach($errors->all() as $error)
                      <li>{{ $error }}</li>
                      @endforeach
                    </ul>
                  </div>
                @endif

                @if(Session::has('success'))
                    <div class="alert alert-success alert-dismissible">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <strong>{{__('admin.common.success_heading')}}</strong> {{Session::get('success')}}
                    </div>
                @endif
                <br>
            </div>
          </div>
        </div>
      </section>
    @endif


    <!-- Main content -->
    <section class="content">
        <div class="row">
    <div class="col-sm-8">  
        @if ($authUser->userType->default_role == Admin::DEFAULT_ROLE_LIST[6])
<a href="{{ route('admin.purchase.create') }}"<button type="button" class="btn btn-success btn-lg"> {{ __('admin.menu.purchase') }}</button></a>

 @can('create', app('App\Models\Sale'))
<a href="{{ route('admin.sale.create') }}"><button type="button" class="btn btn-warning btn-lg">{{ __('admin.menu.sale') }}</button></a>
  @endcan
@endif
</div>
    </div>
    
    <br>
  <div class="row">
    <div class="col-sm-4">
      <div class="form-group">
        <label for="financial_year_id">{{ __('admin.report_five.financial_year') }}</label>
        <select class="form-control select2" name="financial_year_id" id="financial_year_id" required>
          <option value="">{{ __('admin.common.select') }}</option>
          @foreach ($financial_years as $key => $item)
            <option value="{{ $item->id }}" {{(@$yearl_total_stock['id'] == $item->id) ? 'selected' : ''}} >{{ $item->{'title_'. app()->getLocale()} }}</option>
          @endforeach
        </select>
      </div>
    </div>
  
   <div class="col-sm-8">
       </div>

 </div>



  <div class="row">

    <div class="col-sm">  
      <div class="small-box bg-warning">
        <div class="inner">
          <h3 id="pre_stock_arr_total">
            {{(app()->getLocale() == 'en') ? $yearl_total_stock['pre_stock_arr_total'] : NumberToBanglaWord::engToBn($yearl_total_stock['pre_stock_arr_total'])}}
          </h3>
          <p> <span id="to_date_pre"></span> {{__('admin.dashboard.stock_in')}}</p>
        </div>
        <div class="icon">
          <i class="ion ion-bag"></i>
        </div>
      </div>
    </div>

    <div class="col-sm"> 
      <div class="small-box bg-info">
        <div class="inner">
        <h6>{{__('admin.dashboard.stockin_count')}}: <span id="current_total_stock_in_arr">
          {{(app()->getLocale() == 'en') ? $yearl_total_stock['current_total_stock_in_arr'] : NumberToBanglaWord::engToBn($yearl_total_stock['current_total_stock_in_arr'])}}</span> </h6>
        <h6>{{__('admin.dashboard.stockout_count')}}: <span id="current_total_stock_out_arr">
          {{(app()->getLocale() == 'en') ? $yearl_total_stock['current_total_stock_out_arr'] : NumberToBanglaWord::engToBn($yearl_total_stock['current_total_stock_out_arr'])}}</span></h6>

          <p>{{__('admin.dashboard.stock_out')}}</p>
        </div>
        <div class="icon">
          <i class="ion ion-bag"></i>
        </div>
      </div>
    </div>

    <div class="col-sm"> 
      <div class="small-box bg-success">
        <div class="inner">
          <h3><span id="current_total_stock_arr">
            {{(app()->getLocale() == 'en') ? $yearl_total_stock['current_total_stock_arr'] : NumberToBanglaWord::engToBn($yearl_total_stock['current_total_stock_arr'])}}</span></h3>

          <p><span id="to_date"></span> {{__('admin.dashboard.stock')}}</p>
        </div>
        <div class="icon">
          <i class="ion ion-bag"></i>
        </div>
      </div>
    </div>

  </div>

  </section>

  @if ($authUser->userType->default_role <= Admin::DEFAULT_ROLE_LIST[6])
  <section class="content">
  <div class="row">
      <div class="col-sm-4">
        <center>
            <h6>{{ __('admin.dashboard.catergorywise_stock')}} ( {{ EnglishToBanglaDate::dateFormatEnglishToBangla (date('d-m-Y'))}}){{ __('admin.dashboard.till_stock')}}</h6>
        </center>

        <table id="example1" class="table table-bordered table-hover">

            <thead>
                <tr>

                    <th scope="col">{{ __('admin.dashboard.content_category') }}</th>
                    <th scope="col">{{ __('admin.dashboard.stock_quantity') }}</th>
                </tr>
            </thead>
            <tbody>
              @foreach ($yearly_stock_category_wise_datas as $item)
              <tr>
                <td> @if ($authUser->userType->default_role <= Admin::DEFAULT_ROLE_LIST[3]) <a href="{{route('admin.dashboard.view-category',$item['id']  )}}">{{$item['title_'. app()->getLocale()]}}</a> @else {{$item['title_'. app()->getLocale()]}} @endif </td>
                <td>{{(app()->getLocale() == 'en') ? $item['stock'] : NumberToBanglaWord::engToBn($item['stock'])}}</td>
              </tr>
              @endforeach
            </tbody>
        </table>
      </div>


    <div class="col-sm-4">
      <center>
        <h6>{{ __('admin.dashboard.seedlingwise_stock') }}( {{ EnglishToBanglaDate::dateFormatEnglishToBangla (date('d-m-Y'))}}){{ __('admin.dashboard.till_stock')}}
          <a href="{{route('admin.dashboard.view')}}" class="btn btn-success btn-xs"> {{ __('admin.dashboard.view_more') }} </a>
        </h6>
      </center>
      
      <table id="example2" class="table table-bordered table-hover">
          <thead>
              <tr>
                  <th scope="col">{{ __('admin.dashboard.content_category') }}</th>
                  <th scope="col">{{ __('admin.dashboard.content_seedling') }}</th>
                  <th scope="col">{{ __('admin.dashboard.stock_quantity') }}</th>
              </tr>
          </thead>
          <tbody>
            @foreach ($yearly_stock_seedlink_wise_datas as $item)
            <tr>
              <td>{{$item['category_'. app()->getLocale()]}}</td>
              <td>{{$item['title_'. app()->getLocale()]}}</td>
              <td>{{(app()->getLocale() == 'en') ? $item['stock'] : NumberToBanglaWord::engToBn($item['stock'])}}</td>
            </tr>
            @endforeach
          </tbody>
      </table>
    </div>
    
    <div class="col-sm-4">
      <center>
        <h6>
          {{__('admin.dashboard.app_status')}}
        </h6>
      </center>
      
      <table id="example3" class="table table-success table-striped">
        <thead>
          <tr>
            <th scope="col">{{ __('admin.dashboard.user') }}</th>
            <th scope="col">{{ __('admin.dashboard.pending_raising') }}</th>
            <th scope="col">{{ __('admin.dashboard.pending_stockout') }}</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>{{__('admin.dashboard.range')}} </td>
            <td><a href="{{route('admin.purchase')}}" class="btn btn-danger btn-sm">{{(app()->getLocale() == 'en') ? $app_status_data['range_purchase'] : NumberToBanglaWord::engToBn($app_status_data['range_purchase'] )}}</a></td>
            <td><a href="{{route('admin.sale')}}" class="btn btn-danger btn-sm">{{(app()->getLocale() == 'en') ? $app_status_data['range_sale'] : NumberToBanglaWord::engToBn($app_status_data['range_sale'] )}}</a></td>
          </tr>
          <tr>
            <td>{{__('admin.dashboard.acf')}}</td>
            <td><a href="{{route('admin.purchase')}}" class="btn btn-warning btn-sm">{{(app()->getLocale() == 'en') ? $app_status_data['acf_purchase'] : NumberToBanglaWord::engToBn($app_status_data['acf_purchase'] )}}</a></td>
            <td><a href="{{route('admin.sale')}}" class="btn btn-warning btn-sm">{{(app()->getLocale() == 'en') ? $app_status_data['acf_sale'] : NumberToBanglaWord::engToBn($app_status_data['acf_sale'] )}}</a></td>
          </tr>
          <tr>
            <td>{{__('admin.dashboard.dfo')}}</td>
            <td><a href="{{route('admin.purchase')}}" class="btn btn-primary btn-sm">{{(app()->getLocale() == 'en') ? $app_status_data['dfo_purchase'] : NumberToBanglaWord::engToBn($app_status_data['dfo_purchase'] )}}</a></td>
            <td><a href="{{route('admin.sale')}}" class="btn btn-primary btn-sm">{{(app()->getLocale() == 'en') ? $app_status_data['dfo_sale'] : NumberToBanglaWord::engToBn($app_status_data['dfo_sale'] )}}</a></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  </section>    

  <!-- /.content -->
  
  
    <section class="content">
  <div class="row">
      <div class="col-sm-4">
        <center>
            <h6></h6>
        </center>
 <div id="piechart" style="width: 100%; height: 500px;"></div>
     
      </div>
         <div class="col-sm-4">
        <center>
            <h6></h6>
        </center>
 <div id="donutchart" style="width: 100%; height: 500px;"></div>
     
      </div>
      
      <div class="col-sm-4">
        <center>
            <h6>{{ __('admin.dashboard.monthlywise_stock') }}</h6>
        </center>

        <table id="example1" class="table table-bordered table-hover">

            <thead>
                <tr>

                    <th scope="col">{{ __('admin.dashboard.content_category') }}</th>
                    <th scope="col">{{ __('admin.dashboard.sale_quantity') }}</th>
                </tr>
            </thead>
            <tbody>
              @foreach ($montlhy_sales_category_wise_datas as $item)
              <tr>
                <td>{{$item['title_'. app()->getLocale()]}}</td>
                <td>{{(app()->getLocale() == 'en') ? $item['stock'] : NumberToBanglaWord::engToBn($item['stock'])}}</td>
              </tr>
              @endforeach
            </tbody>
        </table>
      </div>
</section>  


<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

        var data = google.visualization.arrayToDataTable([
             ['{{ __('admin.dashboard.content_category') }}', '{{ __('admin.dashboard.stock_quantity') }}'],
             @foreach ($yearly_stock_category_wise_datas as $item)
            ['{{$item['title_'. app()->getLocale()]}}', {{(app()->getLocale() == 'en') ? $item['stock'] : ($item['stock'])}}],
             @endforeach
        
        ])

        var options = {
          title: '{{ __('admin.dashboard.catergorywise_stock') }}( {{ EnglishToBanglaDate::dateFormatEnglishToBangla (date('d-m-Y'))}}){{ __('admin.dashboard.till_stock')}}'
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
      }
    </script>
    
    
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

        var data = google.visualization.arrayToDataTable([
             ['{{ __('admin.dashboard.content_category') }}', '{{ __('admin.dashboard.stock_quantity') }}'],
             @foreach ($yearly_stock_purpose_wise_datas as $item)
            ['{{$item['title_'. app()->getLocale()]}}', {{(app()->getLocale() == 'en') ? $item['stock'] : ($item['stock'])}}],
             @endforeach
        
        ])

        var options = {
          title: '{{ __('admin.dashboard.purpose_stock') }}( {{ EnglishToBanglaDate::dateFormatEnglishToBangla (date('d-m-Y'))}}){{ __('admin.dashboard.till_stock')}}',
          pieHole: 0.4,
        };

        var chart = new google.visualization.PieChart(document.getElementById('donutchart'));

        chart.draw(data, options);
      }
    </script>
  @endif 
    <!-- Main content -->
    {{-- <section class="content">
        <div class="row">
          <div class="col-sm-6">
            <div id="piechart" style="width: 100%; height: 380px;"></div>
          </div>
          
          <div class="col-sm-6">
            <table id="example3" class="table table-success table-striped">
              <thead>
                <tr>
                  <th scope="col">{{ __('admin.dashboard.user') }}</th>
                  <th scope="col">{{ __('admin.dashboard.pending_raising') }}</th>
                  <th scope="col">{{ __('admin.dashboard.pending_stockout') }}</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>{{__('admin.dashboard.range')}} </td>
                  <td><button type="button" class="btn btn-success btn-sm">{{(app()->getLocale() == 'en') ? $app_status_data['range_purchase'] : NumberToBanglaWord::engToBn($app_status_data['range_purchase'] )}}</button></td>
                  <td><button type="button" class="btn btn-success btn-sm">{{(app()->getLocale() == 'en') ? $app_status_data['range_sale'] : NumberToBanglaWord::engToBn($app_status_data['range_sale'] )}}</button></td>
                </tr>
                <tr>
                  <td>{{__('admin.dashboard.acf')}}</td>
                  <td><button type="button" class="btn btn-success btn-sm">{{(app()->getLocale() == 'en') ? $app_status_data['acf_purchase'] : NumberToBanglaWord::engToBn($app_status_data['acf_purchase'] )}}</button></td>
                  <td><button type="button" class="btn btn-success btn-sm">{{(app()->getLocale() == 'en') ? $app_status_data['acf_sale'] : NumberToBanglaWord::engToBn($app_status_data['acf_sale'] )}}</button></td>
                </tr>
                <tr>
                  <td>{{__('admin.dashboard.dfo')}}</td>
                  <td><button type="button" class="btn btn-success btn-sm">{{(app()->getLocale() == 'en') ? $app_status_data['dfo_purchase'] : NumberToBanglaWord::engToBn($app_status_data['dfo_purchase'] )}}</button></td>
                  <td><button type="button" class="btn btn-success btn-sm">{{(app()->getLocale() == 'en') ? $app_status_data['dfo_sale'] : NumberToBanglaWord::engToBn($app_status_data['dfo_sale'] )}}</button></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
    </section> --}}



  </div>




@endsection


@section('scripts')
<script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/plugins/jszip/jszip.min.js') }}"></script>

<script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

{{-- <script>
  google.charts.load('current', {'packages':['corechart']});
  google.charts.setOnLoadCallback(drawChart1);

  function drawChart1() {

    var data = google.visualization.arrayToDataTable([
      ['Task', "{{__('admin.common.chart_1_title')}}" ],
      
      
      ['Eat',      2],
      ['Commute',  2],
      ['Watch TV', 2],
      ['Sleep',    7]
    ]);

    var options = {
      title: "{{__('admin.common.chart_1_title')}}"
    };

    var chart = new google.visualization.PieChart(document.getElementById('piechart'));

    chart.draw(data, options);
  }
</script> --}}



<script>
  // function check(){
  //   var x = document.getElementById("forest_division_id").selectedOptions[0].label;
  //     if(x == "{{__('admin.common.select')}}"){
  //       alert("{{(app()->getLocale() == 'en') ? 'Please Select Forest Division' : 'অনুগ্রহ করে বন বিভাগ নির্বাচন করুন'}}");
  //     }
  // }
  $(function () {
    $("#example1").DataTable({
      "responsive": false, "lengthChange": false, "autoWidth": false,"searching": false,"ordering": false,"info": false,"paging": false,
      "lengthMenu": [
          [-1],
          ['All'],
        ],
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    $('#example2').DataTable({
      "lengthMenu": [
          [1000, 2000, 3000, -1],
          [1000, 2000, 3000, 'All'],
        ],
      "paging": false,
      "lengthChange": false,
      "searching": false,
      "ordering": false,
      "info": false,
      "autoWidth": false,
      "responsive": true,
      
    });
  });
</script>
<script>
  $(document).ready(function () {
    $('.select2').select2();
  });
</script>
<script>
  $('#financial_year_id').on('change', function(e){
    var financial_year_id = e.target.value;
    var route = "{{route('admin.yearl_total_stock')}}/"+financial_year_id;
    //console.log(financial_year_id);
    if (financial_year_id != '') {
      $(".se-pre-con").fadeIn();
    }
    $.get(route, function(data) {
      $(".se-pre-con").fadeOut();
      //console.log(data);
      //console.log(data.pre_stock_arr_total);
      $('#pre_stock_arr_total').text(data.pre_stock_arr_total);
      $('#to_date_pre').text(data.to_date_pre);
      $('#current_total_stock_in_arr').text(data.current_total_stock_in_arr);
      $('#current_total_stock_out_arr').text(data.current_total_stock_out_arr);
      $('#current_total_stock_arr').text(data.current_total_stock_arr);
      //$('#to_date').text(data.to_date);
    });
  });
</script>
@endsection
