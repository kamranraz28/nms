@php
    use App\Helper\EnglishToBanglaDate;
    use App\Helper\NumberToBanglaWord;
    use Rakibhstu\Banglanumber\NumberToBangla;
    $numto = new NumberToBangla();
@endphp

@php
  $edit = false;
@endphp

@extends('admin.layouts.master')
@section('title')

  @if (count($parameters)>0)
  {{(app()->getLocale() == 'en') ? date('d-m-Y', strtotime($parameters['from_date'])) : EnglishToBanglaDate::dateFormatEnglishToBangla(date('d-m-Y', strtotime($parameters['from_date'])))}} - {{(app()->getLocale() == 'en') ? date('d-m-Y', strtotime($parameters['to_date'])) : EnglishToBanglaDate::dateFormatEnglishToBangla(date('d-m-Y', strtotime($parameters['to_date'])))}} 
    {{__('admin.report_three.title')}}
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

{{-- <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}"> --}}


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
              <i class="fas fa-bookmark"></i> {{ __('admin.report_three.view') }}
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
        @can('create', app('App\Models\ReportThree'))
          <form class="form-edit-add" sale="form" id="sale_entry_form"
            action="{{route('admin.report_three.store')}}"
            method="POST" enctype="multipart/form-data" autocomplete="off">
            {{ csrf_field() }}
            
            <div class="row">

              <div class="col-md-4">
                <div class="form-group">
                  <label for="forest_state_id">{{ __('admin.report_three.forest_state') }}</label>
                  <select class="form-control select2" name="forest_state_id" id="forest_state_id">
                    <option value="all">{{ __('admin.common.all') }}</option>
                    @foreach ($forest_states as $key => $item)
                      <option value="{{ $item->id }}" {{(@$parameters['forest_state_id'] == $item->id) ? 'selected' : ''}} >{{ $item->{'title_'. app()->getLocale()} }} - {{ $item->bbs_code }}</option>
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group">
                  <label for="forest_division_id">{{ __('admin.report_three.forest_division') }}</label>
                  <select class="form-control select2" name="forest_division_id" id="forest_division_id">
                    <option value="all">{{ __('admin.common.all') }}</option>
                  </select>
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group">
                  <label for="forest_range_id">{{ __('admin.report_three.forest_range') }} <!--<span style="color: red"> * </span>--></label>
                  <select class="form-control select2" name="forest_range_id" id="forest_range_id">
                    <option value="all">{{ __('admin.common.all') }}</option>
                  </select>
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group">
                  <label for="forest_beat_id">{{ __('admin.report_three.forest_beat') }} <!--<span style="color: red"> * </span>--></label>
                  <select class="form-control select2" name="forest_beat_id" id="forest_beat_id">
                    <option value="all">{{ __('admin.common.all') }}</option>
                  </select>
                </div>
              </div>

              {{-- <div class="col-md-4">
                <div class="form-group">
                  <label for="nursery_id">{{ __('admin.report_three.nursery') }} <!--<span style="color: red"> * </span>--></label>
                  <select class="form-control select2" name="nursery_id" id="nursery_id">
                    <option value="all">{{ __('admin.common.all') }}</option>
                  </select>
                </div>
              </div> --}}




              <div class="col-md-4">
                <div class="form-group">
                  <label for="category_id">{{ __('admin.report_three.category') }}</label>
                  <select class="form-control select2" name="category_id" id="category_id">
                    <option value="all">{{ __('admin.common.all') }}</option>
                    @foreach ($categories as $key => $item)
                      <option value="{{ $item->id }}" {{(@$parameters['category_id'] == $item->id) ? 'selected' : ''}} >{{ $item->{'title_'. app()->getLocale()} }}</option>
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group">
                  <label for="plant_id">{{ __('admin.report_one.plant_name') }} <!--<span style="color: red"> * </span>--></label>
                  <select class="form-control select2" name="plant_id" id="plant_id">
                    <option value="all">{{ __('admin.common.all') }}</option>
                  </select>
                </div>
              </div>


              <!-- <div class="col-md-4">
                <div class="form-group">
                  <label for="from_date">{{ __('admin.report_three.from_date') }}</label>
                  <input type="text" name="from_date"
                  id="from_date" placeholder="{{ __('admin.report_three.from_date') }}" 
                  value="{{(@$parameters['from_date']) ? $parameters['from_date'] : ''}}"
                  class="form-control date_picker" required>
                </div>
              </div> -->

              <div class="col-md-4">
                <div class="form-group">
                  <label for="to_date">{{ __('admin.report_three.to_date') }}</label>
                  <input type="text" name="to_date"
                  id="to_date" placeholder="{{ __('admin.report_three.to_date') }}" 
                  value="{{(@$parameters['to_date']) ? $parameters['to_date'] : ''}}"
                  class="form-control date_picker" required>
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
                @can('print', app('App\Models\ReportOne'))
                  @if (count($report_threes)>0)
                    <div class="col-12">
                      <a style="float: right" href="" id="print" rel="noopener" target="_blank" class="btn btn-default"><i class="fas fa-print"></i> Print</a>
                    </div>
                  @endif
                @endcan

                @can('print', app('App\Models\ReportOne'))
                  @if (count($report_threes)>0)
                  <div class="col-12">
                    <a target="_blank" style="float: right" href="{{route('admin.report_three.download')}}" rel="noopener" target="_blank" class="btn btn-default"><i class="fas fa-download"></i> 
                        {{(app()->getLocale() == 'en') ? 'Download' : 'ডাউনলোড করুন'}}
                      </a>
                    </div>
                  @endif
                @endcan

                


                
              </div>
            </div>
            
        
          </form>
        @endcan
      </div>
    </section>


    

    @if (count($report_threes)>0)
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
                          <th style="text-align: center;" colspan="{{(count($categories) + count($categories) + count($categories)) + 3 + 3}}"> 
                            <br>
                            {!! __('admin.report_three.title') !!}
                            <br>
                           <br>
                           <br>
                            
                          </th>
                        </tr>
                      <tr>
                        <th class="fremove"></th>
                        <th colspan="1" style="min-width: 150px;"></th>
                        <th colspan="3">
                        
                        </th>
                        <th colspan="1" style="min-width: 150px;">{{__('admin.report_three.to_date')}} : </th>
                        <th colspan="3">{{(app()->getLocale() == 'en') ? date('d-m-Y', strtotime($parameters['to_date'])) : EnglishToBanglaDate::dateFormatEnglishToBangla(date('d-m-Y', strtotime($parameters['to_date']))) }}
                      </tr>
                      <tr>
                        {{-- <th>#</th> --}}
                        <th class="fremove">{{__('admin.report_three.forest_state')}}</th>
                        <th>{!! __('admin.report_three.forest_division') !!}</th>
                        <th>{!! __('admin.report_three.forest_range') !!}</th>
                        <th>{!! __('admin.report_three.forest_beat') !!}</th>
                        <th>{!! __('admin.report_three.category') !!}</th>
                        <th>{!! __('admin.report_three.product') !!}</th>
                        <th>{!! __('admin.report_three.stock_in') !!}</th>
                        <th>{!! __('admin.report_three.stock_out')!!}</th>
                        <th>{!! __('admin.report_three.stock') !!}</th>
                      </tr>
                      </thead>
                      <tbody>
                      @php
                          usort($report_threes, function($a, $b) {
                          return strcmp($a['forest_state_'. app()->getLocale()], $b['forest_state_'. app()->getLocale()]);
                          });
                        @endphp
                      @foreach ($report_threes as $key => $item)
                      <tr>
                        <td>
                          @if ($previousState && $item['forest_state_'. app()->getLocale()] === $previousState)
            	            @else
                            {{ $item['forest_state_'. app()->getLocale()] }}
                          @endif
                        </td>
            
                        <td>
                          @if ($previousDivision && $item['forest_division_'. app()->getLocale()] === $previousDivision)
            	            @else
                            {{ $item['forest_division_'. app()->getLocale()] }}
                          @endif
                        </td>
                  
                        <td>
                          @if ($previousRange && $item['forest_range_'. app()->getLocale()] === $previousRange)
            	            @else
                            {{ $item['forest_range_'. app()->getLocale()] }}
                          @endif
                        </td>

                        <td>
                          @if ($previousBeat && $item['forest_beat_'. app()->getLocale()] === $previousBeat)
            	            @else
                            {{ $item['forest_beat_'. app()->getLocale()] }}
                          @endif
                        </td>
            
                        <td>
                          @if ($previousCategory && $item['category_'. app()->getLocale()] === $previousCategory)
            	            @else
                            {{ $item['category_'. app()->getLocale()] }}
                          @endif
                        </td>
                        
                        <td>
                          @if ($previousProduct && $item['product_'. app()->getLocale()] === $previousProduct)
            	            @else
                            {{ $item['product_'. app()->getLocale()] }}
                          @endif
                        </td>
                        <td>{{(app()->getLocale() == 'en') ? $item['stock_in'] : NumberToBanglaWord::engToBn($item['stock_in'])}}</td>
                        <td>{{(app()->getLocale() == 'en') ? $item['stock_out'] : NumberToBanglaWord::engToBn($item['stock_out'])}}</td>
                        <td>{{(app()->getLocale() == 'en') ? $item['stock'] : NumberToBanglaWord::engToBn($item['stock'])}}</td>
                      
                      @php
                        $previousState = $item['forest_state_' . app()->getLocale()]; //Current State storing for comparison
                        $previousDivision = $item['forest_division_' . app()->getLocale()];
                        $previousRange = $item['forest_range_' . app()->getLocale()];
                        $previousBeat = $item['forest_beat_' . app()->getLocale()];
                        $previousCategory = $item['category_' . app()->getLocale()];
                        $previousProduct = $item['product_' . app()->getLocale()];
                      @endphp
                      @endforeach
                      
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
      "responsive": true, "lengthChange": false, "autoWidth": true,"searching": true,"ordering": false,"info": false,"paging": false,
      "lengthMenu": [
          [-1],
          ['All'],
        ],
      @can('print', app('App\Models\ReportOne'))
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
  $(document).ready(function () {
    $('.select2').select2();
    $(".date_picker").datepicker({ 
      autoclose: true, 
      todayHighlight: true,
      format: 'yyyy-mm-dd',
      //startDate: '-3d'
    }).datepicker(@if(!@$parameters['from_date']) 'update', new Date() @endif);
  });
</script>

<script>
  $('#print').click(function (e) { 
    $(".fremove").css("display","none");
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


        $('#category_id').on('change', function(e){
        var category_id = e.target.value;
        var route = "{{route('get.plant2')}}/"+category_id;
        //console.log(category_id);
        $.get(route, function(data) {
          //console.log(data);
          $('#plant_id').empty();
          $('#plant_id').append('<option value="">{{ __('admin.common.select') }}</option>');
          $.each(data, function(index,data){
            $('#plant_id').append('<option value="' + data.id + '">' + data.title_{{app()->getLocale()}} +  '</option>');
          });
        });
      });
      
      $('#forest_state_id').on('change', function(e){
        var forest_state_id = e.target.value;
        var route = "{{route('get.forest_division')}}/"+forest_state_id;
        //console.log(forest_state_id);
        $.get(route, function(data) {
          //console.log(data);
          $('#forest_division_id').empty();
          $('#forest_division_id').append('<option value="">{{ __('admin.common.select') }}</option>');
          $.each(data, function(index,data){
            $('#forest_division_id').append('<option value="' + data.id + '">' + data.title_{{app()->getLocale()}} + ' - ' + data.bbs_code +  '</option>');
          });
        });
      });
      
      $('#forest_division_id').on('change', function(e){
        var forest_division_id = e.target.value;
        var route = "{{route('get.forest_range')}}/"+forest_division_id;
        //console.log(forest_division_id);
        $.get(route, function(data) {
          //console.log(data);
          $('#forest_range_id').empty();
          $('#forest_range_id').append('<option value="all">{{ __('admin.common.all') }}</option>');
          $.each(data, function(index,data){
            $('#forest_range_id').append('<option value="' + data.id + '">' + data.title_{{app()->getLocale()}} + ' - ' + data.bbs_code +  '</option>');
          });
        });
      });


      $('#forest_range_id').on('change', function(e){
        var forest_range_id = e.target.value;
        var route = "{{route('get.forest_beat')}}/"+forest_range_id;
        //console.log(forest_range_id);
        $.get(route, function(data) {
          //console.log(data);
          $('#forest_beat_id').empty();
          $('#forest_beat_id').append('<option value="all">{{ __('admin.common.all') }}</option>');
          $.each(data, function(index,data){
            $('#forest_beat_id').append('<option value="' + data.id + '">' + data.title_{{app()->getLocale()}} + ' - ' + data.bbs_code +  '</option>');
          });
        });
      });


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
  Session::forget(['from_date','to_date','forest_state_id','forest_division_id','forest_range_id','forest_beat_id','category_id','plant_id']);
@endphp
@endsection


