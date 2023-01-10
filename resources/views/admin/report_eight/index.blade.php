@php
    use App\Helper\EnglishToBanglaDate;
    use App\Helper\NumberToBanglaWord;
    use Rakibhstu\Banglanumber\NumberToBangla;
    $numto = new NumberToBangla();
@endphp

@php
  $edit = false;
  use App\Models\Admin;
  $authUser = Auth::guard('admin')->user()->load(['userType']);
@endphp

@extends('admin.layouts.master')
@section('title')

  @if (count($parameters)>0)
    {{-- {{__('admin.report_eight.from_date')}} : {{(app()->getLocale() == 'en') ? date('d-m-Y', strtotime($parameters['from_date'])) : EnglishToBanglaDate::dateFormatEnglishToBangla(date('d-m-Y', strtotime($parameters['from_date'])))}} || {{__('admin.report_eight.to_date')}} : {{(app()->getLocale() == 'en') ? date('d-m-Y', strtotime($parameters['to_date'])) : EnglishToBanglaDate::dateFormatEnglishToBangla(date('d-m-Y', strtotime($parameters['to_date'])))}} --}}
    {{ @$parameters['forest_division_'. app()->getLocale()] }} 
    {!! __('admin.report_eight.view') !!} - 
    {{(app()->getLocale() == 'en') ? $parameters['title_date_view'] : EnglishToBanglaDate::dateFormatEnglishToBangla($parameters['title_date_view'])}}
  
  
  @else
    {{__('admin.menu.site')}} :: {{__('admin.menu.dashboard')}}
  @endif

@endsection




@section('styles')

<link rel="stylesheet" href="{{ asset('assets/plugins/daterangepicker/daterangepicker.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/datepicker/bootstrap-datepicker.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">

<link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/bs-stepper/css/bs-stepper.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/dropzone/min/dropzone.min.css') }}">

<link rel="stylesheet" href="{{ asset('assets/plugins/sweetalert2/sweetalert2.css') }}">

<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

@endsection

@section('breadcrumb')
  @include('../admin.layouts.partials.breadcrumb', ['path1' => __('admin.menu.sale'), 'route1' => route('admin.sale') ])
@endsection


@section('content')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    

    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">

          <div class="col-sm-12">
            <h1 class="text-info">
              <i class="fas fa-bookmark"></i> {{ __('admin.report_eight.view') }}
            </h1>
          </div>

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

    <section class="content-header">
      <div class="container-fluid">
        @can('create', app('App\Models\ReportEight'))
          <form class="form-edit-add" sale="form" id="sale_entry_form"
            action="{{route('admin.report_eight.store')}}"
            method="POST" enctype="multipart/form-data" autocomplete="off">
            {{ csrf_field() }}
            
            <div class="row">


              <div class="col-md-4">
                <div class="form-group">
                  <label for="stock_type_id">{{ __('admin.report_eight.stock_type') }} <span style="color: red"> * </span></label>
                  <select class="form-control select2" name="stock_type_id" id="stock_type_id" required>
                    <option value="all">{{ __('admin.common.all') }}</option>
                    @foreach ($stock_types as $key => $item)
                      <option value="{{ $item->id }}" {{(@$parameters['stock_type_id'] == $item->id) ? 'selected' : ''}} >{{ $item->{'title_'. app()->getLocale()} }}</option>
                    @endforeach
                  </select>
                </div>
              </div>

              
              <div class="col-md-4">
                <div class="form-group">
                  <label for="budget_id">{{ __('admin.report_eight.budget') }}</label>
                  <select class="form-control select2" name="budget_id" id="budget_id" required>
                    <option value="all">{{ __('admin.common.all') }}</option>
                    @foreach ($budgets as $key => $item)
                      <option value="{{ $item->id }}" {{(@$parameters['budget_id'] == $item->id) ? 'selected' : ''}} >{{ $item->{'title_'. app()->getLocale()} }}</option>
                    @endforeach
                  </select>
                </div>
              </div>

              
              {{-- <div class="col-md-4">
                <div class="form-group">
                  <label for="financial_year_id">{{ __('admin.report_eight.financial_year') }}</label>
                  <select class="form-control select2" name="financial_year_id" id="financial_year_id" required>
                    <option value="">{{ __('admin.common.select') }}</option>
                    @foreach ($financial_years as $key => $item)
                      <option value="{{ $item->id }}" {{(@$parameters['financial_year_id'] == $item->id) ? 'selected' : ''}} >{{ $item->{'title_'. app()->getLocale()} }}</option>
                    @endforeach
                  </select>
                </div>
              </div> --}}

              <div class="col-md-4">
                <div class="form-group">
                  <label for="f_date">{{ __('admin.report_eight.f_date') }}</label>
                  <input type="" name="f_date"
                  id="f_date" placeholder="{{ __('admin.report_eight.f_date') }}" 
                  value="{{(@$parameters['f_date']) ? date_format(date_create($parameters['f_date']),"Y-M") : ''}}"
                  class="form-control date_picker" required>
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group">
                  <label for="forest_division_id">{{ __('admin.report_eight.forest_division') }}</label>
                  <select onfocusout="check()" required class="form-control select2" name="forest_division_id" id="forest_division_id">
                    <option value="">{{ __('admin.common.select') }}</option>
                    @foreach ($forest_divisions as $key => $item)
                      <option value="{{ $item->id }}" {{(@$parameters['forest_division_id'] == $item->id) ? 'selected' : ''}} >{{ $item->{'title_'. app()->getLocale()} }} - {{ $item->bbs_code }}</option>
                    @endforeach
                  </select>
                </div>
              </div>

              {{-- <div class="col-md-4">
                <div class="form-group">
                  <label for="forest_range_id">{{ __('admin.report_eight.forest_range') }} <!--<span style="color: red"> * </span>--></label>
                  <select class="form-control select2" name="forest_range_id" id="forest_range_id">
                    <option value="all">{{ __('admin.common.all') }}</option>
                  </select>
                </div>
              </div> --}}

              <div class="col-md-4">
                <div class="form-group">
                  <label for="forest_beat_id">{{ __('admin.report_eight.forest_beat') }} <!--<span style="color: red"> * </span>--></label>
                  <select class="form-control select2" name="forest_beat_id" id="forest_beat_id">
                    <option value="all">{{ __('admin.common.all') }}</option>
                  </select>
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group">
                  <label for="submit" style="visibility: hidden">Search</label>
                  <button type="submit" id="submit" class="btn btn-info btn-sm form-control save"> 
                    <i class="fas fa-save"></i> {{ __('admin.common.search') }}
                  </button>
                </div>
              </div>




            </div>
            <div class="row">
              <div class="col-md-12">
                <hr>
                <br>
                @can('print', app('App\Models\ReportEight'))
                  @if (count($report_eights)>0)
                    <div class="col-12">
                      <a style="float: right" href="" id="print" rel="noopener" target="_blank" class="btn btn-default"><i class="fas fa-print"></i> Print</a>
                    </div>
                  @endif
                @endcan
                
              </div>
            </div>
            
        
          </form>
        @endcan
      </div>
    </section>


    

    @if (count($report_eights)>0)
      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">

                <!-- Table row -->
                <div class="row">
                  <div class="col-12 table-responsive">
                    <table class="table table-striped" id="example1">
                      <thead>
                      <tr>
                        <th colspan="7"> 
                          <p style="text-align: center;">
                            {{ @$parameters['forest_division_'. app()->getLocale()] }} 
                            <br> 
                            {!! __('admin.report_eight.title') !!}
                            <br>
                            {{(app()->getLocale() == 'en') ? $parameters['title_date_view'] : EnglishToBanglaDate::dateFormatEnglishToBangla($parameters['title_date_view'])}}
                          </p> 
                        </th>
                      </tr>
                      
                      <tr>
                        <th>{{__('admin.report_eight.district')}}</th>
                        <th>{{__('admin.report_eight.upazila')}}</th>
                        <th>{{__('admin.report_eight.forest_beat')}}</th>
                        <th>
                          {{(app()->getLocale() == 'en') ? $parameters['from_date_pre_view'] : EnglishToBanglaDate::dateFormatEnglishToBangla($parameters['from_date_pre_view'])}} <br>
                          {!! __('admin.report_eight.pre_stock') !!}
                        </th>
                        <th>
                          {{(app()->getLocale() == 'en') ? $parameters['from_date'] : EnglishToBanglaDate::dateFormatEnglishToBangla($parameters['from_date'])}} - <br>
                          {{(app()->getLocale() == 'en') ? $parameters['to_date'] : EnglishToBanglaDate::dateFormatEnglishToBangla($parameters['to_date'])}} <br>
                          {!! __('admin.report_eight.current_total_stock_in1') !!} 
                        </th>
                        <th>
                          {{(app()->getLocale() == 'en') ? $parameters['till_date_view'] : EnglishToBanglaDate::dateFormatEnglishToBangla($parameters['till_date_view'])}} <br>
                          {!! __('admin.report_eight.current_total_stock_out') !!}
                        </th>
                        <th>
                          {{(app()->getLocale() == 'en') ? $parameters['to_date_view'] : EnglishToBanglaDate::dateFormatEnglishToBangla($parameters['to_date_view'])}} <br>
                          {!! __('admin.report_eight.current_total_stock') !!}
                        </th>
                      </tr>
                      </thead>
                      <tbody>
                        @foreach ($report_eights as $key => $item)
                        <tr>
                          <td>{{ $item['district_'. app()->getLocale()] }}</td>
                          <td>{{ $item['upazila_'. app()->getLocale()] }}</td>
                          <td>{{ $item['forest_beat_'. app()->getLocale()] }}</td>
                          <td>{{(app()->getLocale() == 'en') ? $item['pre_stock'] : NumberToBanglaWord::engToBn($item['pre_stock'])}}</td>
                          <td>{{(app()->getLocale() == 'en') ? $item['current_total_stock_in'] : NumberToBanglaWord::engToBn($item['current_total_stock_in'])}}</td>
                          <td>{{(app()->getLocale() == 'en') ? $item['current_total_stock_out'] : NumberToBanglaWord::engToBn($item['current_total_stock_out'])}}</td>
                          <td>{{(app()->getLocale() == 'en') ? $item['current_total_stock'] : NumberToBanglaWord::engToBn($item['current_total_stock'])}}</td>
                        </tr>
                        @endforeach
                        
                        @if ($authUser->userType->default_role != Admin::DEFAULT_ROLE_LIST[6])
                          @foreach ($forest_district_data as $key => $item)
                            <tr style="font-weight: 600!important;">
                              <td></td>
                              <td> {!! __('admin.report_eight.total_stock_division_wise') !!} </td>
                              <td>{{ $item['forest_division_'. app()->getLocale()] }}</td>
                              <td>{{(app()->getLocale() == 'en') ? $item['pre_stock_arr_total'] : NumberToBanglaWord::engToBn($item['pre_stock_arr_total'])}}</td>
                              <td>{{(app()->getLocale() == 'en') ? $item['current_total_stock_in_arr'] : NumberToBanglaWord::engToBn($item['current_total_stock_in_arr'])}}</td>
                              <td>{{(app()->getLocale() == 'en') ? $item['current_total_stock_out_arr'] : NumberToBanglaWord::engToBn($item['current_total_stock_out_arr'])}}</td>
                              <td>{{(app()->getLocale() == 'en') ? $item['current_total_stock_arr'] : NumberToBanglaWord::engToBn($item['current_total_stock_arr'])}}</td>
                            </tr>
                          @endforeach
                        @endif
                      </tbody>
                    </table>
                  </div>
                  <!-- /.col -->
                </div>
                <!-- /.row -->

                <!-- this row will not appear when printing -->
                {{-- <div class="row no-print">
                  <div class="col-12">
                    <a href="" id="print" rel="noopener" target="_blank" class="btn btn-default"><i class="fas fa-print"></i> Print</a>
                  </div>
                </div> --}}
              </div>
              <!-- /.invoice -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </section>
      <!-- /.content -->
    @endif

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

<script src="{{ asset('assets/plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('assets/plugins/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script> 



<script src="{{asset('assets/plugins/sweetalert2/sweetalert2.js')}}"></script>
<script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('assets/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js') }}"></script>
<script src="{{ asset('assets/plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('assets/plugins/inputmask/jquery.inputmask.min.js') }}"></script>

<script src="{{ asset('assets/plugins/daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ asset('assets/plugins/datepicker/bootstrap-datepicker.js') }}"></script>
<script src="{{ asset('assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>
<script src="{{ asset('assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>

<script src="{{ asset('assets/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
<script src="{{ asset('assets/plugins/bs-stepper/js/bs-stepper.min.js') }}"></script>
<script src="{{ asset('assets/plugins/dropzone/min/dropzone.min.js') }}"></script>



<script>
  $(function () {
    $("#example1").DataTable({
      "responsive": false, "lengthChange": false, "autoWidth": false,"searching": true,"ordering": false,"info": false,"paging": false,
      "lengthMenu": [
          [-1],
          ['All'],
        ],
      @can('print', app('App\Models\ReportEight'))
      "buttons": ["excel"]
      @endcan
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

  function check(){
    var x = document.getElementById("forest_division_id").selectedOptions[0].label;
      if(x == "{{__('admin.common.select')}}"){
        alert("{{(app()->getLocale() == 'en') ? 'Please Select Forest Division' : 'অনুগ্রহ করে বন বিভাগ নির্বাচন করুন'}}");
      }
  }
  $(document).ready(function () {
    $('.select2').select2();
    $(".date_picker").datepicker({ 
      autoclose: true, 
      todayHighlight: true,
      format: 'yyyy-M',
      startView: "months", 
       minViewMode: "months"
      //startDate: '-3d'
    }).datepicker(@if(!@$parameters['from_date']) 'update', new Date() @endif);
  });
</script>

<script>
  $('#print').click(function (e) { 
    e.preventDefault();
    $(".buttons-excel").css("display","none");
    $(".dataTables_info").css("display","none");
    $(".dataTables_paginate").css("display","none");
    $(".content").css("min-height","650px");
    $("#example1_filter").css("display","none");
    window.addEventListener("load", window.print());
  });
</script>

<script>
      
      // $('#forest_state_id').on('change', function(e){
      //   var forest_state_id = e.target.value;
      //   var route = "{{route('get.forest_division')}}/"+forest_state_id;
      //   //console.log(forest_state_id);
      //   $.get(route, function(data) {
      //     //console.log(data);
      //     $('#forest_division_id').empty();
      //     $('#forest_division_id').append('<option value="">{{ __('admin.common.select') }}</option>');
      //     $.each(data, function(index,data){
      //       $('#forest_division_id').append('<option value="' + data.id + '">' + data.title_{{app()->getLocale()}} + ' - ' + data.bbs_code +  '</option>');
      //     });
      //   });
      // });

      //forest division to beat
      $('#forest_division_id').on('change', function(e){
        var forest_division_id = e.target.value;
        var route = "{{route('get.forest_beat_from_division')}}/"+forest_division_id;
        //console.log(forest_division_id);
        $.get(route, function(data) {
          //console.log(data);
          $('#forest_beat_id').empty();
          $('#forest_beat_id').append('<option value="all">{{ __('admin.common.all') }}</option>');
          $.each(data, function(index,data){
            $('#forest_beat_id').append('<option value="' + data.id + '">' + data.title_{{app()->getLocale()}} + ' - ' + data.bbs_code +  '</option>');
          });
        });
      });
      //forest division to beat
      
      // $('#forest_division_id').on('change', function(e){
      //   var forest_division_id = e.target.value;
      //   var route = "{{route('get.forest_range')}}/"+forest_division_id;
      //   //console.log(forest_division_id);
      //   $.get(route, function(data) {
      //     //console.log(data);
      //     $('#forest_range_id').empty();
      //     $('#forest_range_id').append('<option value="all">{{ __('admin.common.all') }}</option>');
      //     $.each(data, function(index,data){
      //       $('#forest_range_id').append('<option value="' + data.id + '">' + data.title_{{app()->getLocale()}} + ' - ' + data.bbs_code +  '</option>');
      //     });
      //   });
      // });


      // $('#forest_range_id').on('change', function(e){
      //   var forest_range_id = e.target.value;
      //   var route = "{{route('get.forest_beat')}}/"+forest_range_id;
      //   //console.log(forest_range_id);
      //   $.get(route, function(data) {
      //     //console.log(data);
      //     $('#forest_beat_id').empty();
      //     $('#forest_beat_id').append('<option value="all">{{ __('admin.common.all') }}</option>');
      //     $.each(data, function(index,data){
      //       $('#forest_beat_id').append('<option value="' + data.id + '">' + data.title_{{app()->getLocale()}} + ' - ' + data.bbs_code +  '</option>');
      //     });
      //   });
      // });


      // $('#forest_beat_id').on('change', function(e){
      //   var forest_beat_id = e.target.value;
      //   var route = "{{route('get.nursery1')}}/"+forest_beat_id;
      //   //console.log(forest_beat_id);
      //   $.get(route, function(data) {
      //     //console.log(data);
      //     $('#nursery_id').empty();
      //     $('#nursery_id').append('<option value="all">{{ __('admin.common.all') }}</option>');
      //     $.each(data, function(index,data){
      //       $('#nursery_id').append('<option value="' + data.id + '">' + data.title_{{app()->getLocale()}} + ' - ' + data.code +  '</option>');
      //     });
      //   });
      // });
</script>

@php
  Session::forget(['from_date','to_date','from_date_pre','to_date_pre','forest_division_id','budget_id','stock_type_id','forest_beat_id','financial_year']);
@endphp
@endsection


