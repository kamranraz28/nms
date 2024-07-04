@php
  $edit = false;
  if(!empty($purchase)){
     if($purchase->id !=''){
         $edit=true;
     }
  }

  use App\Models\Admin;
@endphp


@extends('admin.layouts.master')
@section('title')
  {{__('admin.menu.site')}} :: {{__('admin.menu.dashboard')}}
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

{{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css" 
rel="stylesheet" type="text/css" /> --}}

<style>
  .select2 {
    width: 100%!important;
  }

  .swal2-cancel{
    display: none!important;
  }


</style>
@endsection

@section('breadcrumb')
  @include('../admin.layouts.partials.breadcrumb', ['path1' => __('admin.menu.purchase'), 'route1' => route('admin.purchase') ])
@endsection

@section('content')

  <div class="content-wrapper">
    
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          
          <div class="col-sm-2">
            <a href="{{ route('admin.purchase') }}" class="btn btn-info form-control btn-add-new">
                <i class="fas fa-backward"></i> <span>{{ __('admin.common.back') }}</span>
            </a>
          </div>

          <div class="col-sm-10">
            <h1 class="text-info">
              @if ($edit)
                <i class="fas fa-bookmark"></i>  {{ __('admin.menu.purchase') }} {{ __('admin.common.info') }}
              @else
                <i class="fas fa-bookmark"></i> {{ __('admin.menu.purchase') }} {{ __('admin.common.info') }} {{ __('admin.common.add') }} 
              @endif
              
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


<form class="form-edit-add" purchase="form" id="purchase_entry_form"
              action="{{!$edit ? route('admin.purchase.store') : route('admin.purchase.update', $purchase->id)}}"
              method="POST" enctype="multipart/form-data" autocomplete="off">
              
{{-- <form class="form-edit-add" purchase="form" id="purchase_entry_form"
              action="{{route('admin.purchase.store')}}"
              method="POST" enctype="multipart/form-data" autocomplete="off"> --}}

            <!-- PUT Method if we are editing -->
            @if($edit)
                <input type="hidden" name="id" value="{{$purchase->id}}">
                {{ method_field("PUT") }}
            @endif
            {{ csrf_field() }}

    <section class="content" style="margin-top: -10px;">
      <div class="container-fluid">
          <div class="row">
            <!-- col start -->
            <div class="col-12">
              <!-- card start -->
              <div class="card" style="background-color: #c1c1c1;">
                
                <div class="card-header">
                  <!--<h3 class="card-title">DataTable with minimal features & hover style</h3>-->
                </div>

                <div class="card-body">
                 
                  <div class="row">
                    <div class="col-6">
                      

                      @if (Auth::guard('admin')->user()->userType->default_role <= Admin::DEFAULT_ROLE_LIST[5])
                        @if ($edit)
                          <div class="form-group">
                            <label for="forest_beat_id">{{ __('admin.purchase.forest_beat') }} <span style="color: red"> * </span></label>
                            <select class="form-control select2" name="forest_beat_id" id="forest_beat_id" required>
                              <option value="">{{ __('admin.common.select') }}</option>
                              @foreach ($forest_beats as $key => $item)
                                <option value="{{ $item->id }}" {{($purchase->forest_beat_id == $item->id) ? 'selected' : ''}} >{{ $item->{'title_'. app()->getLocale()} }} - {{ $item->bbs_code }}</option>
                              @endforeach
                            </select>
                          </div>    
                        @else
                          <div class="form-group">
                            <label for="forest_beat_id">{{ __('admin.purchase.forest_beat') }} <span style="color: red"> * </span></label>
                            <select class="form-control select2" name="forest_beat_id" id="forest_beat_id" required>
                              <option value="">{{ __('admin.common.select') }}</option>
                              @foreach ($forest_beats as $key => $item)
                                <option value="{{ $item->id }}" {{(old('forest_beat_id') == $item->id) ? 'selected' : ''}} >{{ $item->{'title_'. app()->getLocale()} }} - {{ $item->bbs_code }}</option>
                              @endforeach
                            </select>
                          </div>
                        @endif
                      @endif
                      
                      @if ($edit)
                        <div class="form-group">
                          <label for="stock_type_id">{{ __('admin.purchase.stock_type') }} <span style="color: red"> * </span></label>
                          <select class="form-control select2" name="stock_type_id" id="stock_type_id" required>
                            <option value="">{{ __('admin.common.select') }}</option>
                            @foreach ($stock_types as $key => $item)
                              <option value="{{ $item->id }}" {{($purchase->stock_type_id == $item->id) ? 'selected' : ''}} >{{ $item->{'title_'. app()->getLocale()} }}</option>
                            @endforeach
                          </select>
                        </div>    
                      @else
                        <div class="form-group">
                          <label for="stock_type_id">{{ __('admin.purchase.stock_type') }} <span style="color: red"> * </span></label>
                          <select class="form-control select2" name="stock_type_id" id="stock_type_id" required>
                            <option value="">{{ __('admin.common.select') }}</option>
                            @foreach ($stock_types as $key => $item)
                              <option value="{{ $item->id }}" {{(old('stock_type_id') == $item->id) ? 'selected' : ''}} >{{ $item->{'title_'. app()->getLocale()} }}</option>
                            @endforeach
                          </select>
                        </div>
                      @endif
    
                      
    
    
                      @if ($edit)
                        <div class="form-group">
                          <label for="budget_id">{{ __('admin.purchase.budget') }} <span style="color: red"> * </span></label>
                          <select class="form-control select2" name="budget_id" id="budget_id" required>
                            <option value="">{{ __('admin.common.select') }}</option>
                            @foreach ($budgets as $key => $item)
                              <option value="{{ $item->id }}" {{($purchase->budget_id == $item->id) ? 'selected' : ''}} >{{ $item->{'title_'. app()->getLocale()} }}</option>
                            @endforeach
                          </select>
                        </div>    
                      @else
                        <div class="form-group">
                          <label for="budget_id">{{ __('admin.purchase.budget') }} <span style="color: red"> * </span></label>
                          <select class="form-control select2" name="budget_id" id="budget_id" required>
                            <option value="">{{ __('admin.common.select') }}</option>
                            @foreach ($budgets as $key => $item)
                              <option value="{{ $item->id }}" {{(old('budget_id') == $item->id) ? 'selected' : ''}} >{{ $item->{'title_'. app()->getLocale()} }}</option>
                            @endforeach
                          </select>
                        </div>
                      @endif
                    </div>
                    
                    <div class="col-6">
                      <div class="form-group">
                        <label for="vch_date">{{ __('admin.purchase.vch_date') }} <span style="color: red"> * </span> </label>
                        <input type="text" name="vch_date"
                         id="{{$edit?'':'vch_date'}}" placeholder="{{ __('admin.purchase.vch_date') }}" 
                         value="{{ $edit? date_format(date_create($purchase->vch_date),"Y-m-d"):old('vch_date') }}"
                         class="form-control" required>
                      </div>
    
                      <div class="form-group">
                        <label for="details_en">{{ __('admin.purchase.details_en') }}</label>
                        <input type="text" name="details_en"
                         id="details_en" placeholder="{{ __('admin.purchase.details_en') }}" 
                         value="{{ $edit?$purchase->details_en:old('details_en') }}"
                         class="form-control" >
                      </div>
    
                      <div class="form-group">
                        <label for="details_bn">{{ __('admin.purchase.details_bn') }}</label>
                        <input type="text" name="details_bn"
                         id="details_bn" placeholder="{{ __('admin.purchase.details_bn') }}" 
                         value="{{ $edit?$purchase->details_bn:old('details_bn') }}"
                         class="form-control">
                      </div>
                    </div>
                  </div>

                  

                </div>

              </div>
              <!-- card start -->
            </div>
            <!-- col end -->

            @if ($edit == false)
            <!-- col start -->
            <div class="col-12">
              <!-- card start -->
              <div class="card">
                
                <div class="card-header">
                  <!--<h3 class="card-title">DataTable with minimal features & hover style</h3>-->
                </div>

                <div class="card-body">

                <div class="row">
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="category_id1">{{ __('admin.purchase.category') }} <span style="color: red"> * </span></label>
                    <select class="form-control select2" name="arraydata[stock1][category_id]" id="category_id1" required>
                      <option value="">{{ __('admin.common.select') }}</option>
                      @foreach ($categories as $key => $item)
                        <option value="{{ $item->id }}" {{(old('category_id') == $item->id) ? 'selected' : ''}} >{{ $item->{'title_'. app()->getLocale()} }}</option>
                      @endforeach
                    </select>
                  </div>
                  </div>
                  <div class="col-md-3">
                  <div class="form-group">
                    <label for="product_id1">{{ __('admin.purchase.product') }} <span style="color: red"> * </span></label>
                    <select class="form-control select2" name="arraydata[stock1][product_id]" id="product_id1" required>
                      <option value="">{{ __('admin.common.select') }}</option>
                    </select>
                  </div>
                  </div>

                  {{-- <div class="form-group">
                    <label for="unit_id1">{{ __('admin.purchase.unit') }}</label>
                    <select disabled class="form-control select2" name="arraydata[stock1][unit_id]" id="unit_id1">
                      <option value="">{{ __('admin.common.select') }}</option>
                    </select>
                  </div> --}}


                  {{-- <div class="form-group">
                    <label for="price1">{{ __('admin.purchase.price') }}</label> <span class="text-info">({{__('admin.common.en_lang_use')}}) </span>
                    <input disabled type="number" name="arraydata[stock1][price]"
                     id="price1" placeholder="{{ __('admin.purchase.price') }}" 
                     value=""
                     class="form-control" >
                  </div> --}}

                  <div class="col-md-3">

                  <div class="form-group">
                    <label for="price_type_id1">{{ __('admin.purchase.price_type') }} <span style="color: red"> * </span> </label>
                    <select class="form-control select2" name="arraydata[stock1][price_type_id]" id="price_type_id1" required>
                      @foreach ($price_types as $key => $item)
                        <option value="{{ $item->id }}" {{(old('price_type_id') == $item->id) ? 'selected' : ''}} >{{ $item->{'title_'. app()->getLocale()} }}</option>
                      @endforeach
                    </select>
                  </div>
                  </div>
                  <div class="col-md-3">

                  <div class="form-group">
                    <label for="quentity1">{{ __('admin.purchase.quantity') }}</label> <span style="color: red"> * </span> <span class="text-info">({{__('admin.common.en_lang_use')}}) </span>
                    <input type="number" name="arraydata[stock1][quantity]"
                     id="quentity1" placeholder="{{ __('admin.purchase.quantity') }}" 
                     value="0" min="1" style="background-color: #51915f !important; color: #ffffff !important; font-size: 2rem !important;"
                     class="form-control quantity" required>
                  </div>
                  </div>
                  </div>
                  <div>

                
              </div>
              <!-- card start -->
            </div>
            <!-- col end -->    
            @else

              @if (count($purchase_etails) > 0 )
                @foreach ($purchase_etails as $index => $value)

                <input type="hidden" name="arraydata[stock{{$index}}][id]" value="{{$value->id}}">
                <!-- col start -->
                <div class="col-12">
                  <!-- card start -->
                  <div class="card">
                    
                    <div class="card-header">
                      <!--<h3 class="card-title">DataTable with minimal features & hover style</h3>-->
                    </div>

                    <div class="card-body">
                    <div class="row">
                <div class="col-md-3">
                      <div class="form-group">
                        <label for="category_id{{$index}}">{{ __('admin.purchase.category') }} <span style="color: red"> * </span> </label>
                        <select class="form-control select2" name="arraydata[stock{{$index}}][category_id]" id="category_id{{$index}}" required>
                          <option value="">{{ __('admin.common.select') }}</option>
                          @foreach ($categories as $key => $item)
                            <option value="{{ $item->id }}" {{($value->category_id == $item->id) ? 'selected' : ''}} >{{ $item->{'title_'. app()->getLocale()} }}</option>
                          @endforeach
                        </select>
                      </div>
                  </div>
                  <div class="col-md-3">
                      <div class="form-group">
                        <label for="product_id{{$index}}">{{ __('admin.purchase.product') }} <span style="color: red"> * </span></label>
                        <select class="form-control select2" name="arraydata[stock{{$index}}][product_id]" id="product_id{{$index}}" required>
                          <option value="">{{ __('admin.common.select') }}</option>
                          @foreach ($products as $key => $item)
                            <option value="{{ $item->id }}" {{($value->product_id == $item->id) ? 'selected' : ''}} >{{ $item->{'title_'. app()->getLocale()} }}</option>
                          @endforeach
                        </select>
                      </div>
                  </div>
                      {{-- <div class="form-group">
                        <label for="unit_id{{$index}}">{{ __('admin.purchase.unit') }}</label>
                        <select disabled class="form-control select2" name="arraydata[stock{{$index}}][unit_id]" id="unit_id{{$index}}">
                          <option value="">{{ __('admin.common.select') }}</option>
                          <option selected value="{{$value->unit->id}}">{{ $value->unit->{'title_'. app()->getLocale()} }}</option>
                        </select>
                      </div> --}}


                      {{-- <div class="form-group">
                        <label for="price{{$index}}">{{ __('admin.purchase.price') }}</label> <span class="text-info">({{__('admin.common.en_lang_use')}}) </span>
                        <input disabled type="number" name="arraydata[stock{{$index}}][price]" id="price{{$index}}" placeholder="{{ __('admin.purchase.price') }}" value="{{$value->price}}" class="form-control">
                      </div> --}}

                      <div class="col-md-3">

                      <div class="form-group">
                        <label for="price_type_id{{$index}}">{{ __('admin.purchase.price_type') }} <span style="color: red"> * </span></label>
                        <select class="form-control select2" name="arraydata[stock{{$index}}][price_type_id]" id="price_type_id{{$index}}" required>
                          <option value="">{{ __('admin.common.select') }}</option>
                          @foreach ($price_types as $key => $item)
                            <option value="{{ $item->id }}" {{($value->price_type_id == $item->id) ? 'selected' : ''}} >{{ $item->{'title_'. app()->getLocale()} }}</option>
                          @endforeach
                        </select>
                      </div>
                      </div>
                      <div class="col-md-3">

                      <div class="form-group">
                        <label for="quantity{{$index}}">{{ __('admin.purchase.quantity') }}</label> <span class="text-info">({{__('admin.common.en_lang_use')}}) <span style="color: red"> * </span></span>
                        <input type="number" name="arraydata[stock{{$index}}][quantity]" id="quantity{{$index}}" placeholder="{{ __('admin.purchase.quantity') }}" value="{{$value->quantity}}" style="background-color: #51915f !important; color: #ffffff !important; font-size: 2rem !important;" class="form-control quantity" required>
                      </div> 
                      @can('delete_purchase_details', app('App\Models\Purchase'))
                        
                          <div class="col-md-12">
                              <a style="margin-left: 95%;" title="Delete This Section" class="btn btn-danger btn-sm delete1" href="{{route('admin.purchase_details.delete',[$value->id])}}"> <i class="fa fa-trash" aria-hidden="true"></i> </a>
                          </div>
                       
                      @endcan
                      

                      </div>
                     

                    </div>
                      </div>
                  </div>
                  <!-- card start -->
                </div>
                <!-- col end -->  
                @endforeach  
                
              @endif
                
            @endif
            


            
            <!-- col start -->
            <div class="col-12">
              <!-- card start -->
              
                
            

                  <div class="row col-md-12 container1"></div>


                  <div class="row">

                      <div class="col-md-10"></div>
                      <div class="col-md-2">
                          <button class="btn btn-info add_form_field" style="width: 100%">{{__('admin.common.add_more')}} <i class="fa fa-plus" aria-hidden="true"></i></button>
                      </div>
                  </div>

            
              
              <!-- card start -->
            </div>
            <!-- col end -->

            
            <!-- col start -->
            <div class="col-12">
              <!-- card start -->
              <div class="card">
                
                <div class="card-header">
                  <!--<h3 class="card-title">DataTable with minimal features & hover style</h3>-->
                </div>

                <div class="card-body">

                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-2">
                        <div class="form-group">
                          <label for="total_quantity">{{ __('admin.sale.total_quantity') }}</label>
                          <input style="background-color: #51915f; color: white; height: 53px; font-size: 40px;" disabled type="number" name="total_quantity"
                           id="total_quantity" placeholder="{{ __('admin.sale.total_quantity') }}" value="0" class="form-control total_quantity">
                        </div>
                      </div>
                    </div>
                    



                    <button type="submit" class="btn btn-info btn-sm form-control save"> 
                      <i class="fas fa-save"></i> {{ __('admin.common.save') }}
                    </button>
                  </div>


                </div>
              </div>
              <!-- card start -->
            </div>
            <!-- col end -->



          </div>
      </div>
    </section>
  </form>

  </div>




@endsection


@section('scripts')
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
    $(document).ready(function () {
      $('.select2').select2();
      const month = [1,2,3,4,5,6,7,8,9,10,11,12];
      let date = new Date();
      let getPerviousYear = date.getFullYear() - 1;
      let getCurrentYear = date.getFullYear();
      let getCurrentMonth = date.getMonth() + 1;
      //let getMonthJuly = month[date.getMonth()];

      let firstDay = '';
      let lastDay = '';
      
//       @if($edit)
//       getCurrentMonth = "date_format(date_create($purchase->vch_date),'m')";
//       // getCurrentMonth = "7";
//       if (getCurrentMonth <= 6) {
//         firstDay = new Date(date.getFullYear(), 5, 1);
//         lastDay = new Date(date.getFullYear() -1, 5, 30);
//       }else{
//       firstDay = new Date(date.getFullYear() -1, 5, 1);
//         lastDay = new Date(date.getFullYear(), 5, 30);
//       }
//       @else
//   getCurrentMonth = "7";
//       if (getCurrentMonth <= 6) {
//          firstDay = new Date(date.getFullYear() -1, 5, 1);
//         lastDay = new Date(date.getFullYear(), 5, 30);
//       }else{
          
//       firstDay = new Date(date.getFullYear(),  5, 1);
//         lastDay = new Date(date.getFullYear(), 1, 5, 30);
//       }
//       @endif
//      console.log(getCurrentMonth + '-' + lastDay);
      $("input[name='vch_date']").datepicker({ 
        autoclose: true, 
        todayHighlight: true,
        format: 'yyyy-mm-dd',
        startDate: firstDay,
        endDate: lastDay
        //startDate: '-3d'

      }).datepicker(@if(!$edit) 'update', new Date() @endif);
      // {{$edit? date_format(date_create($purchase->vch_date),'Y-m-d'): ''}}
      @if($edit)
        @foreach ($purchase_etails as $index => $value)
        
          $('#category_id{{$index}}').on('change', function(e){
            var category_id = e.target.value;
            var route = "{{route('get.products')}}/"+category_id;
            //console.log(category_id);
            $.get(route, function(data) {
              //console.log(data);
              $('#product_id{{$index}}').empty();
              $('#product_id{{$index}}').append('<option value="">{{ __('admin.common.select') }}</option>');
              $.each(data, function(index,data){
                $('#product_id{{$index}}').append('<option value="' + data.id + '">' + data.title_{{app()->getLocale()}} + ' - ' + data.code +  '</option>');
              });
            });
          });


          $('#product_id{{$index}}').on('change', function(e){
            var product_id = e.target.value;
            var route = "{{route('get.product')}}/"+product_id;
            //console.log(product_id);
            $.get(route, function(data) {
              //console.log(data);
              $('#unit_id{{$index}}').empty();
              //$('#unit_id{{$index}}').append('<option value="">{{ __('admin.common.select') }}</option>');
              $.each(data, function(index,data){
                $('#unit_id{{$index}}').append('<option value="' + data.unit.id + '">' + data.unit.title_{{app()->getLocale()}} +  '</option>');
                $('#price{{$index}}').val(Math.max(data.price, data.price_bag, data.price_10, data.price_12));
              });
            });
          });
        @endforeach
      @endif

      


    });
  </script>



  <script>
    $(document).ready(function(){
      let max_fields      = 70;
      let wrapper         = $(".container1");
      let add_button      = $(".add_form_field"); 

      @if ( $edit && count($purchase_etails) > 0)
        let x = {{count($purchase_etails)}}; 
      @else
        let x = 1;
      @endif

      
      $(add_button).click(function (event) {
          event.preventDefault();
          if(x < max_fields){
              x++;

              $(wrapper).append(
                '<div class="col-md-12 col-sm-offset-3 academic-qualification-jsc mb-2">'+
                  
                        '<div class="jsc_collapse hide">'+
                        '<div class="row">'+
                        '<div class="col-md-3">'+
                          '<div class="form-group">'+
                            '<label for="category_id'+ x +'">{{ __('admin.purchase.category') }} <span style="color: red"> * </span></label>'+
                            '<select class="form-control select2" name="arraydata[stock'+ x +'][category_id]" id="category_id'+ x +'" required>'+
                              '<option value="">{{ __('admin.common.select') }}</option>'+
                              @foreach ($categories as $key => $item)
                                '<option value="{{ $item->id }}" {{(old('category_id') == $item->id) ? 'selected' : ''}} >{{ $item->{'title_'. app()->getLocale()} }}</option>'+
                              @endforeach
                            '</select>'+
                          '</div>'+
                          '</div>'+
                          '<div class="col-md-3">'+
                          '<div class="form-group">'+
                            '<label for="product_id'+ x +'">{{ __('admin.purchase.product') }} <span style="color: red"> * </span></label>'+
                            '<select class="form-control select2" name="arraydata[stock'+ x +'][product_id]" id="product_id'+ x +'" required>'+
                              '<option value="">{{ __('admin.common.select') }}</option>'+
                            '</select>'+
                          '</div>'+
                          '</div>'+

                          // '<div class="form-group">'+
                          //   '<label for="unit_id'+ x +'">{{ __('admin.purchase.unit') }}</label>'+
                          //   '<select disabled class="form-control select2" name="arraydata[stock'+ x +'][unit_id]" id="unit_id'+ x +'">'+
                          //     '<option value="">{{ __('admin.common.select') }}</option>'+
                          //   '</select>'+
                          // '</div>'+


                          // '<div class="form-group">'+
                          //   '<label for="price'+ x +'">{{ __('admin.purchase.price') }}</label> <span class="text-info">({{__('admin.common.en_lang_use')}}) </span>'+
                          //   '<input disabled type="number" name="arraydata[stock'+ x +'][price]" id="price'+ x +'" placeholder="{{ __('admin.purchase.price') }}" value="" class="form-control">'+
                          // '</div>'+
                          '<div class="col-md-3">'+
                          '<div class="form-group">'+
                            '<label for="price_type_id'+ x +'">{{ __('admin.purchase.price_type') }} <span style="color: red"> * </span></label>'+
                            '<select class="form-control select2" name="arraydata[stock'+ x +'][price_type_id]" id="price_type_id'+ x +'" required>'+
                              //'<option value="">{{ __('admin.common.select') }}</option>'+
                              @foreach ($price_types as $key => $item)
                                '<option value="{{ $item->id }}" {{(old('price_type_id') == $item->id) ? 'selected' : ''}} >{{ $item->{'title_'. app()->getLocale()} }}</option>'+
                              @endforeach
                            '</select>'+
                          '</div>'+
                          '</div>'+
                          '<div class="col-md-3">'+
                          '<div class="form-group">'+
                            '<label for="quantity'+ x +'">{{ __('admin.purchase.quantity') }}</label> <span style="color: red"> * </span> <span class="text-info">({{__('admin.common.en_lang_use')}}) </span>'+
                            '<input type="number" min="1" required name="arraydata[stock'+ x +'][quantity]" id="quantity'+ x +'" placeholder="{{ __('admin.purchase.quantity') }}" value="0" style="background-color: #51915f !important; color: #ffffff !important; font-size: 2rem !important;" class="form-control quantity">'+
                          '</div>'+

                        '</div>'+
                        '<button title="Remove This Section" id="delete'+ x +'"class="delete btn btn-warning btn-sm" style="margin-left: 97%;"> <i class="fa fa-minus-circle" aria-hidden="true"></i></button>'+
                    '</div>'+
                    '</div>'+
                    '</div>'
               
              );

              // ajax code
              $('.select2').select2();
              $('#category_id'+x).on('change', function(e){
                var category_id = e.target.value;
                var route = "{{route('get.products')}}/"+category_id;
                //console.log('category x value ' + x);
                $.get(route, function(data) {
                  //console.log(data);
                  $('#product_id'+x).empty();
                  $('#product_id'+x).append('<option value="">{{ __('admin.common.select') }}</option>');
                  $.each(data, function(index,data){
                    $('#product_id'+x).append('<option value="' + data.id + '">' + data.title_{{app()->getLocale()}}  +  '</option>');
                  });
                });
              });
              
              $('#product_id'+x).on('change', function(e){
                var product_id = e.target.value;
                var route = "{{route('get.product')}}/"+product_id;
                //console.log('product x value ' + x);
                $.get(route, function(data) {
                  //console.log(data);
                  $('#price'+x).val(0);
                  $('#unit_id'+x).empty();
                  $.each(data, function(index,data){
                    $('#unit_id'+x).append('<option value="' + data.unit.id + '">' + data.unit.title_{{app()->getLocale()}} +  '</option>');
                    //$('#price'+x).val(Math.max(data.price, data.price_bag, data.price_10, data.price_12));
                  });
                });
              });
              // ajax code

              //getTotalQuentity();
              totalQuentityKeyup();

          }else{
              alert('You Reached the limits')
          }
      });

      $(wrapper).on("click",".delete", function(e){ 
        e.preventDefault(); 
        $(this).parent('div').parent('div').remove();x--;

        //getTotalQuentity();
        totalQuentityKeyup();
      });


      $('#category_id'+x).on('change', function(e){
        var category_id = e.target.value;
        var route = "{{route('get.products')}}/"+category_id;
        //console.log(category_id);
        $.get(route, function(data) {
          //console.log(data);
          $('#product_id'+x).empty();
          $('#product_id'+x).append('<option value="">{{ __('admin.common.select') }}</option>');
          $.each(data, function(index,data){
            $('#product_id'+x).append('<option value="' + data.id + '">' + data.title_{{app()->getLocale()}} + '</option>');
          });
        });
      });
      


      $('#product_id'+x).on('change', function(e){
        var product_id = e.target.value;
        var route = "{{route('get.product')}}/"+product_id;
        //console.log(product_id);
        $.get(route, function(data) {
          //console.log(data);
          $('#price'+x).val(0);
          $('#unit_id'+x).empty();
          $.each(data, function(index,data){
            $('#unit_id'+x).append('<option value="' + data.unit.id + '">' + data.unit.title_{{app()->getLocale()}} +  '</option>');
            //$('#price'+x).val(Math.max(data.price, data.price_bag, data.price_10, data.price_12));
          });
        });
      });

    
      $(document).on('click', '.delete1', function (e) {
          e.preventDefault();
          //console.log($(this).attr('href'))
          var route = $(this).attr('href');
          Swal.fire({
            title: "{{__('admin.common.confirm_msg')}}",
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: "{{__('admin.common.delete')}}",
            denyButtonText: "{{__('admin.common.cancel')}}",
            confirmButtonColor: 'info',
          }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
              window.location.href = route;
              //console.log(route);
            } else if (result.isDenied) {
              //Swal.fire("{{__('admin.common.not_save1')}}", '', 'info')
            }
          })
      });


      getTotalQuentity();
      function getTotalQuentity() {
        total_quantity = 0;
        $(document).find(".quantity").each(function() {
          total_quantity += parseFloat($(this).val());
        });
        $("#total_quantity").val(total_quantity);
      }

      totalQuentityKeyup();
      function totalQuentityKeyup() {
        $(document).on("keyup",".quantity", function(e){ 
          getTotalQuentity();
        });
      }


      //Form Submit Confirmation
      $('#purchase_entry_form').submit(function(event) {
        event.preventDefault();
        var form = $(this)[0];
        Swal.fire({
          title: "{{__('admin.common.submit_confirm_msg')}}",
          showDenyButton: true,
          showCancelButton: true,
          confirmButtonText: "{{__('admin.common.save')}}",
          denyButtonText: "{{__('admin.common.not_save')}}",
        }).then((result) => {
          /* Read more about isConfirmed, isDenied below */
          if (result.isConfirmed) {
            $(".se-pre-con").fadeIn().fadeOut();
            form.submit();
          } else if (result.isDenied) {
            Swal.fire("{{__('admin.common.not_save1')}}", '', 'info')
          }
        })

      });


    }); 
  </script>

  

@endsection


