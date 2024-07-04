@php
    use App\Helper\EnglishToBanglaDate;
    use App\Helper\NumberToBanglaWord;
    use Rakibhstu\Banglanumber\NumberToBangla;
    $numto = new NumberToBangla();
    
@endphp
@php
  $edit = false;
  if(!empty($sale)){
     if($sale->id !=''){
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
  .card{
    box-shadow:none !important;
  }
  .col-12{
    background-color:#ffff !important;
  }
  #price1 {
      background-color: #51915f !important;
      color: #ffffff !important;
      font-size: 2rem !important;
  }
  #quantity1{
    background-color: #51915f !important;
    color: #ffffff !important;
    font-size: 2rem !important;
  }

  .swal2-cancel{
    display: none!important;
  }
</style>
@endsection

@section('breadcrumb')
  @include('../admin.layouts.partials.breadcrumb', ['path1' => __('admin.menu.sale'), 'route1' => route('admin.sale') ])
@endsection

@section('content')

  <div class="content-wrapper">
    
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          
          <div class="col-sm-2">
            <a href="{{ route('admin.sale') }}" class="btn btn-info form-control btn-add-new">
                <i class="fas fa-backward"></i> <span>{{ __('admin.common.back') }}</span>
            </a>
          </div>

          <div class="col-sm-10">
            <h1 class="text-info">
              @if ($edit)
                <i class="fas fa-bookmark"></i> {{ __('admin.menu.sale') }} {{ __('admin.common.info') }} {{ __('admin.common.update') }}
              @else
                <i class="fas fa-bookmark"></i>  {{ __('admin.menu.sale') }} {{ __('admin.common.info') }}
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


<form class="form-edit-add" sale="form" id="sale_entry_form"
              action="{{!$edit ? route('admin.sale.store') : route('admin.sale.update', $sale->id)}}"
              method="POST" enctype="multipart/form-data" autocomplete="off">
              
{{-- <form class="form-edit-add" sale="form" id="sale_entry_form"
              action="{{route('admin.sale.store')}}"
              method="POST" enctype="multipart/form-data" autocomplete="off"> --}}

            <!-- PUT Method if we are editing -->
            @if($edit)
                <input type="hidden" name="id" value="{{$sale->id}}">
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
                            <label for="forest_beat_id">{{ __('admin.sale.forest_beat') }} <span style="color: red"> * </span></label>
                            <select class="form-control select2" name="forest_beat_id" id="forest_beat_id" required>
                              <option value="">{{ __('admin.common.select') }}</option>
                              @foreach ($forest_beats as $key => $item)
                                <option value="{{ $item->id }}" {{($sale->forest_beat_id == $item->id) ? 'selected' : ''}} >{{ $item->{'title_'. app()->getLocale()} }} - {{ $item->bbs_code }}</option>
                              @endforeach
                            </select>
                          </div>    
                        @else
                          <div class="form-group">
                            <label for="forest_beat_id">{{ __('admin.sale.forest_beat') }} <span style="color: red"> * </span></label>
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
                          <label for="stock_type_id">{{ __('admin.sale.stock_type') }} <span style="color: red"> * </span></label>
                          <select class="form-control select2" name="stock_type_id" id="stock_type_id" required>
                            <option value="">{{ __('admin.common.select') }}</option>
                            @foreach ($stock_types as $key => $item)
                              <option value="{{ $item->id }}" {{($sale->stock_type_id == $item->id) ? 'selected' : ''}} >{{ $item->{'title_'. app()->getLocale()} }}</option>
                            @endforeach
                          </select>
                        </div>    
                      @else
                        <div class="form-group">
                          <label for="stock_type_id">{{ __('admin.sale.stock_type') }} <span style="color: red"> * </span></label>
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
                          <label for="budget_id">{{ __('admin.sale.budget') }} <span style="color: red"> * </span></label>
                          <select class="form-control select2" name="budget_id" id="budget_id" required>
                            <option value="">{{ __('admin.common.select') }}</option>
                            @foreach ($budgets as $key => $item)
                              <option value="{{ $item->id }}" {{($sale->budget_id == $item->id) ? 'selected' : ''}} >{{ $item->{'title_'. app()->getLocale()} }}</option>
                            @endforeach
                          </select>
                        </div>    
                      @else
                        <div class="form-group">
                          <label for="budget_id">{{ __('admin.sale.budget') }} <span style="color: red"> * </span></label>
                          <select class="form-control select2" name="budget_id" id="budget_id" required>
                            <option value="">{{ __('admin.common.select') }}</option>
                            @foreach ($budgets as $key => $item)
                              <option value="{{ $item->id }}" {{(old('budget_id') == $item->id) ? 'selected' : ''}} >{{ $item->{'title_'. app()->getLocale()} }}</option>
                            @endforeach
                          </select>
                        </div>
                      @endif

                      {{-- @if ($edit)
                        <div class="form-group">
                          <label for="nursery_id">{{ __('admin.sale.nursery') }} <span style="color: red"> * </span></label>
                          <select class="form-control select2" name="nursery_id" id="nursery_id" required>
                            <option value="">{{ __('admin.common.select') }}</option>
                            @foreach ($nurseries as $key => $item)
                              <option value="{{ $item->id }}" {{($sale->nursery_id == $item->id) ? 'selected' : ''}} >{{ $item->{'office_'. app()->getLocale()} }}</option>
                            @endforeach
                          </select>
                        </div>    
                      @else
                        <div class="form-group">
                          <label for="nursery_id">{{ __('admin.sale.nursery') }} <span style="color: red"> * </span></label>
                          <select class="form-control select2" name="nursery_id" id="nursery_id" required>
                            <option value="">{{ __('admin.common.select') }}</option>
                            @foreach ($nurseries as $key => $item)
                              <option value="{{ $item->id }}" {{(old('nursery_id') == $item->id) ? 'selected' : ''}} >{{ $item->{'office_'. app()->getLocale()} }}</option>
                            @endforeach
                          </select>
                        </div>
                      @endif --}}

                      

                      <div class="row">
                        <div class="col-md-8">
                          @if ($edit)
                            <div class="form-group">
                              <label for="user_id">{{ __('admin.sale.user') }} <span style="color: red"> * </span></label>
                              <select class="form-control select2" name="user_id" id="user_id_edit" required>
                                <!-- <option value="">{{ __('admin.common.select') }}</option> -->
                                @foreach ($users as $key => $item)
                                  <option value="{{ $item->id }}" {{($sale->user_id == $item->id) ? 'selected' : ''}}>{{ @$item->{'title_'. app()->getLocale()} }} - {{(app()->getLocale() == 'en') ? @$item->contact : NumberToBanglaWord::engToBn(@$item->contact)}}</option>
                                @endforeach
                              </select>
                            </div>    
                          @else
                            <div class="form-group" style="visibility: hidden">
                              <label for="user_id">{{ __('admin.sale.user') }} <span style="color: red"> * </span></label>
                              <select class="form-control select2" name="user_id" id="user_id" required>
                                <!-- <option value="">{{ __('admin.common.select') }}</option> -->
                                {{-- @foreach ($users as $key => $item)
                                  <option value="{{ $item->id }}" {{(old('user_id') == $item->id) ? 'selected' : ''}} >{{ @$item->{'title_'. app()->getLocale()} }} - {{(app()->getLocale() == 'en') ? @$item->contact : NumberToBanglaWord::engToBn(@$item->contact)}}</option>
                                @endforeach --}}
                              </select>
                            </div>
                          @endif
                        </div>
                        <div class="col-md-4" style="visibility: hidden">
                          <label for="" >{{ __('admin.sale.user') }}</label>
                          <a href="#" class="btn btn-info form-control" data-toggle="modal" data-target="#myModal">
                            {{ (app()->getLocale() == 'en') ? 'Add Customer' : 'ক্রেতা যোগ করুন' }}
                          </a>
                        </div>
                      </div> 
                    </div>

                    <div class="col-6">
                      <div class="form-group">
                        <label for="vch_date">{{ __('admin.sale.vch_date') }} <span style="color: red"> * </span></label>
                        <input type="text" name="vch_date"
                        id="{{$edit?'':'vch_date'}}" placeholder="{{ __('admin.sale.vch_date') }}" 
                        value="{{ $edit?$sale->vch_date:old('vch_date') }}"
                        class="form-control" required>
                      </div>

                      <div class="form-group">
                        <label for="details_en">{{ __('admin.sale.details_en') }}</label>
                        <input type="text" name="details_en"
                        id="details_en" placeholder="{{ __('admin.sale.details_en') }}" 
                        value="{{ $edit?$sale->details_en:old('details_en') }}"
                        class="form-control" >
                      </div>

                      <div class="form-group">
                        <label for="details_bn">{{ __('admin.sale.details_bn') }}</label>
                        <input type="text" name="details_bn"
                        id="details_bn" placeholder="{{ __('admin.sale.details_bn') }}" 
                        value="{{ $edit?$sale->details_bn:old('details_bn') }}"
                        class="form-control">
                      </div>

                      @if ($edit)
                      <div class="form-group" style="visibility: hidden">
                        <label for="free">{{ __('admin.sale.free') }}  </label><br>
                        <div class="icheck-primary d-inline">
                          <input type="radio" id="free1" value="1" name="free" 
                          {{ ($sale->free == '1') ? 'checked' : '' }}>
                          <label for="free1">
                            {{(app()->getLocale() == 'en') ? App\Models\Status::EN1[1] : App\Models\Status::BN1[1]}}
                          </label>
                        </div>
                        <div class="icheck-danger d-inline">
                          <input type="radio" id="free2" value="0" name="free"
                          {{ ($sale->free == '0') ? 'checked' : '' }}>
                          <label for="free2">
                            {{(app()->getLocale() == 'en') ? App\Models\Status::EN1[0] : App\Models\Status::BN1[0]}}
                          </label>
                        </div>
                      </div>    
                      @else
                      <div class="form-group" style="visibility: hidden">
                        <label for="free">{{ __('admin.sale.free') }}  </label><br>
                        <div class="icheck-primary d-inline">
                          <input type="radio" id="free1" value="1" name="free" 
                          {{ (old('free') == '1') ? 'checked' : '' }}>
                          <label for="free1">
                            {{(app()->getLocale() == 'en') ? App\Models\Status::EN1[1] : App\Models\Status::BN1[1]}}
                          </label>
                        </div>
                        <div class="icheck-danger d-inline">
                          <input type="radio" id="free2" value="0" name="free"
                          {{ (old('free') == '0') ? 'checked' : '' }} checked>
                          <label for="free2">
                            {{(app()->getLocale() == 'en') ? App\Models\Status::EN1[0] : App\Models\Status::BN1[0]}}
                          </label>
                        </div>
                      </div>    
                      @endif
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
                <div class="col-md-2">
                  <div class="form-group">
                    <label for="category_id1">{{ __('admin.sale.category') }} <span style="color: red"> * </span></label>
                    <select class="form-control select2" name="arraydata[stock1][category_id]" id="category_id1" required>
                      <option value="">{{ __('admin.common.select') }}</option>
                      @foreach ($categories as $key => $item)
                        <option value="{{ $item->id }}" {{(old('category_id') == $item->id) ? 'selected' : ''}} >{{ $item->{'title_'. app()->getLocale()} }}</option>
                      @endforeach
                    </select>
                  </div>
                  </div>

                  <div class="col-md-2">
                  <div class="form-group">
                    <label for="product_id1">{{ __('admin.sale.product') }} <span style="color: red"> * </span></label>
                    <select class="form-control select2" name="arraydata[stock1][product_id]" id="product_id1" required>
                      <option value="">{{ __('admin.common.select') }}</option>
                    </select>
                  </div>
                  </div>

                  {{-- <div class="form-group">
                    <label for="unit_id1">{{ __('admin.sale.unit') }}</label>
                    <select disabled class="form-control select2" name="arraydata[stock1][unit_id]" id="unit_id1">
                      <option value="">{{ __('admin.common.select') }}</option>
                    </select>
                  </div> --}}
                  <div class="col-md-2">
                  <div class="form-group">
                    <label for="price_type_id1">{{ __('admin.sale.price_type') }} <span style="color: red"> * </span></label>
                    <select class="form-control select2 price_type_id" name="arraydata[stock1][price_type_id]" id="price_type_id1" {{(@$forest_division->price_type == true)? 'disabled':'required'}} >
                      <option value="">{{ __('admin.common.select') }}</option>
                      @foreach ($price_types as $key => $item)
                        <option value="{{ $item->id }}" {{(old('price_type_id') == $item->id) ? 'selected' : ''}} >{{ $item->{'title_'. app()->getLocale()} }}</option>
                      @endforeach
                    </select>
                  </div>
                  </div>
                  <div class="col-md-3">

                  <div class="form-group">
                    <label for="price1">{{ __('admin.sale.price') }}</label> <span style="color: red"> * </span> <span class="text-info"> ({{__('admin.common.en_lang_use')}}) </span>
                    <input required type="number" name="arraydata[stock1][price]"
                     id="price1" placeholder="{{ __('admin.sale.price') }}" value="0" class="form-control price">
                  </div>
                  </div>
                  <div class="col-md-3">
                  <div class="form-group">
                    <label for="quantity1">{{ __('admin.sale.quantity') }}</label> <span style="color: red"> * </span> <span class="text-info">({{__('admin.common.en_lang_use')}}) </span>
                    <input type="number" name="arraydata[stock1][quantity]"
                     id="quantity1" placeholder="{{ __('admin.sale.quantity') }}" value="0" min="1" class="form-control quantity" required>
                  </div>
                  </div>
                  <input type="hidden" value="0" id="totalPrice1" class="totalPrice">

                  </div>
                  </div>
                  </div>
                  <div>

                
              </div>
              <!-- card start -->
            </div>
            <!-- col end -->    
            @else

              @if (count($sale_details) > 0 )
                @foreach ($sale_details as $index => $value)

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
                <div class="col-md-2">
                      <div class="form-group">
                        <label for="category_id{{$index}}">{{ __('admin.sale.category') }} <span style="color: red"> * </span></label>
                        <select class="form-control select2" name="arraydata[stock{{$index}}][category_id]" id="category_id{{$index}}" required>
                          <option value="">{{ __('admin.common.select') }}</option>
                          @foreach ($categories as $key => $item)
                            <option value="{{ $item->id }}" {{($value->category_id == $item->id) ? 'selected' : ''}} >{{ $item->{'title_'. app()->getLocale()} }}</option>
                          @endforeach
                        </select>
                      </div>
                  </div>
                  <div class="col-md-2">
                      <div class="form-group">
                        <label for="product_id{{$index}}">{{ __('admin.sale.product') }} <span style="color: red"> * </span></label>
                        <select class="form-control select2" name="arraydata[stock{{$index}}][product_id]" id="product_id{{$index}}" required>
                          <option value="">{{ __('admin.common.select') }}</option>
                          @foreach ($products as $key => $item)
                            <option value="{{ $item->id }}" {{($value->product_id == $item->id) ? 'selected' : ''}} >{{ $item->{'title_'. app()->getLocale()} }}</option>
                          @endforeach
                        </select>
                      </div>
                  </div>
                      {{-- <div class="form-group">
                        <label for="unit_id{{$index}}">{{ __('admin.sale.unit') }}</label>
                        <select disabled class="form-control select2" name="arraydata[stock{{$index}}][unit_id]" id="unit_id{{$index}}">
                          <option selected value="{{$value->unit->id}}">{{ $value->unit->{'title_'. app()->getLocale()} }}</option>
                        </select>
                      </div> --}}


                      <div class="col-md-2">
                      <div class="form-group">
                        <label for="price_type_id{{$index}}">{{ __('admin.sale.price_type') }} <span style="color: red"> * </span></label>
                        <select class="form-control select2 price_type_id" name="arraydata[stock{{$index}}][price_type_id]" 
                        id="price_type_id{{$index}}" {{(@$forest_division->price_type == true)? 'disabled':'required'}} >
                          <option value="">{{ __('admin.common.select') }}</option>
                          @foreach ($price_types as $key => $item)
                            <option value="{{ $item->id }}" {{($value->price_type_id == $item->id) ? 'selected' : ''}} >{{ $item->{'title_'. app()->getLocale()} }}</option>
                          @endforeach
                        </select>
                      </div>
                      </div>

                      
                      <div class="col-md-3">
                      <div class="form-group">
                        <label for="price{{$index}}">{{ __('admin.sale.price') }}</label> <span style="color: red"> * </span> <span class="text-info">({{__('admin.common.en_lang_use')}}) </span>
                        <input required type="number" name="arraydata[stock{{$index}}][price]" id="price{{$index}}" placeholder="{{ __('admin.sale.price') }}" value="{{$value->price}}" style="background-color: #51915f !important; color: #ffffff !important; font-size: 2rem !important;" class="form-control price">
                      </div>
                      </div>
                      <div class="col-md-3">
                      <div class="form-group">
                        <label for="quantity{{$index}}">{{ __('admin.sale.quantity') }}</label> <span style="color: red"> * </span> <span class="text-info">({{__('admin.common.en_lang_use')}}) </span>
                        <input type="number" name="arraydata[stock{{$index}}][quantity]" id="quantity{{$index}}" placeholder="{{ __('admin.sale.quantity') }}" value="{{$value->quantity}}" style="background-color: #51915f !important; color: #ffffff !important; font-size: 2rem !important;" class="form-control quantity" required>
                      </div>   
                      <input type="hidden" value="{{$value->quantity*$value->price}}" id="totalPrice{{$index}}" class="totalPrice">
                      
                      @can('delete_sale_details', app('App\Models\Sale'))
                        <div class="form-row form-group mt-0">
                        
                          <div class="col-md-12">
                              <a style="margin-left: 94%;" title="Delete This Section" class="btn btn-danger btn-sm delete1" href="{{route('admin.sale_details.delete',[$value->id])}}"> <i class="fa fa-trash" aria-hidden="true"></i> </a>
                          </div>
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
                  <div class="row">
                    
                    <!-- <div class="col-md-2">
                      <div class="form-group">
                        <label for="total_price">{{ __('admin.sale.total_price') }}</label>
                        <input disabled type="number" name="total_price"
                         id="total_price" placeholder="{{ __('admin.sale.total_price') }}" value="0" class="form-control total_price">
                      </div>
                    </div> -->
                    
                    <div class="col-md-2">
                      <div class="form-group">
                        <label for="total_quantity">{{ __('admin.sale.total_quantity') }}</label>
                        <input style="background-color: #51915f; color: white; height: 53px; font-size: 40px;" disabled type="number" name="total_quantity"
                         id="total_quantity" placeholder="{{ __('admin.sale.total_quantity') }}" value="0" class="form-control total_quantity">
                      </div>
                    </div>

                   

                    <!-- <div class="col-md-2">
                      <div class="form-group">
                        <label for="due_amount">{{ __('admin.sale.due_amount') }}</label>
                        <input style="background-color: #51915f; color: white; height: 53px; font-size: 40px;" disabled type="number" name="due_amount"
                         id="due_amount" placeholder="{{ __('admin.sale.due_amount') }}" value="0" class="form-control due_amount">
                      </div>
                    </div> -->

                    <div class="col-md-2">
                      <div class="form-group">
                        <label for="paid_amount">{{ __('admin.sale.paid_amount') }}</label>
                        <input style="background-color: #51915f; color: white; height: 53px; font-size: 40px;" disabled type="number" name="paid_amount"
                         id="paid_amount" placeholder="{{ __('admin.sale.paid_amount') }}" value="0" class="form-control paid_amount">
                      </div>
                    </div>

                    <div class="col-md-2" style="visibility: hidden;">
                      <div class="form-group">
                        <label for="discount">{{ __('admin.sale.discount') }}</label>
                        <input  style="height: 53px; font-size: 40px;" type="number" name="discount"
                         id="discount" placeholder="{{ __('admin.sale.discount') }}" value="{{($edit)?$sale->discount : '0'}}" class="form-control discount">
                      </div>
                    </div>

                    <div class="col-md-2" style="visibility: hidden;">>
                      <div class="form-group">
                        
                        <div class="form-check" style="left: 40%">
                          <label for="percent" style="visibility: hidden;">{{ __('admin.sale.percent') }}</label><br>
                          <label class="checkbox-inline"><input id="percent" class="percent" type="checkbox" value="1" name="percent" {{($edit && $sale->percent) ? 'checked' : ''}}  >%</label>
                        </div>
                      </div>
                    </div>


                  </div>
                  


                  <div class="form-group">
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


<!-- Modal -->
<div class="modal fade" id="myModal" role="dialog">
  <div class="modal-dialog">
  
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        
      </div>
      <div class="modal-body">
        <form class="form-edit-add" role="form" id="customer-form"
  action="{{ route('admin.user.store') }}"
  method="POST" enctype="multipart/form-data" autocomplete="off">
        <!-- PUT Method if we are editing -->
        <input type="hidden" name="where" id="where" value="admin.sale.create">
        
        {{ csrf_field() }}


        <div class="form-group">
          <label for="title_en">{{ __('admin.user.title_en') }}</label>
          <input type="text" name="title_en"
           id="title_en" placeholder="{{ __('admin.user.title_en') }}" 
           value="{{ old('title_en')}}"
           class="form-control">
        </div>

        <div class="form-group">
          <label for="title_bn">{{ __('admin.user.title_bn') }}</label>
          <input type="text" name="title_bn"
           id="title_bn" placeholder="{{ __('admin.user.title_bn') }}" 
           value="{{ old('title_bn')}}"
           class="form-control">
        </div>

        <div class="form-group">
          <label for="contact">{{ __('admin.user.contact') }}</label>
          <input type="text" name="contact"
           id="contact" placeholder="{{ __('admin.user.contact') }}" 
           value="{{ old('contact') }}" {{$edit?'required':'required'}}
           class="form-control" required>
        </div>

        <div class="form-group">
          <button type="submit" class="btn btn-info btn-sm form-control save" id="user-form-submit"> 
            <i class="fas fa-save"></i> {{ __('admin.common.save') }}
          </button>
        </div>


        </form>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
    
  </div>
</div>
<!-- Modal -->

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

      var date = new Date();
      var firstDay = new Date(date.getFullYear(), date.getMonth(), 1);
      var lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0);
      $("input[name='vch_date']").datepicker({ 
        autoclose: true, 
        todayHighlight: true,
        format: 'yyyy-mm-dd',
        startDate: firstDay,
        endDate: lastDay
        //startDate: '-3d'

      }).datepicker(@if(!$edit) 'update', new Date() @endif);

      @if($edit)
        @foreach ($sale_details as $index => $value)
        
          $('#category_id{{$index}}').on('change', function(e){
            var category_id = e.target.value;
            var route = "{{route('get.products')}}/"+category_id;
            ////console.log(category_id);
            $.get(route, function(data) {
              ////console.log(data);
              $('#product_id{{$index}}').empty();
              $('#product_id{{$index}}').append('<option value="">{{ __('admin.common.select') }}</option>');
              $.each(data, function(index,data){
                $('#product_id{{$index}}').append('<option value="' + data.id + '">' + data.title_{{app()->getLocale()}} + '</option>');
              });
            });
          });


          $('#product_id{{$index}}').on('change', function(e){
            var product_id = e.target.value;
            var route = "{{route('get.product')}}/"+product_id;
            ////console.log(product_id);
            $.get(route, function(data) {
              ////console.log(data);
              $('#unit_id{{$index}}').empty();
              //$('#unit_id{{$index}}').append('<option value="">{{ __('admin.common.select') }}</option>');
              $.each(data, function(index,data){
                $('#unit_id{{$index}}').append('<option value="' + data.unit.id + '">' + data.unit.title_{{app()->getLocale()}} +  '</option>');
                $('#price{{$index}}').val(data.price);
              });
            });
          });

          //Price Type
          $('#price_type_id{{$index}}').on('change', function(e){
            var price_type_id = e.target.value;
            var product_id = $('#product_id{{$index}}').val();
            var route = "{{route('get.product')}}/"+product_id;
            ////console.log(product_id);
            $.get(route, function(data) {
              //console.log(data);
              $.each(data, function(index,data){
                ////console.log(data);
                ////console.log(price_type_id);
                if (price_type_id == 1) {
                  $('#price{{$index}}').val(data.price);
                } else if(price_type_id == 2){
                  $('#price{{$index}}').val(data.price_bag);
                } else if(price_type_id == 3){
                  $('#price{{$index}}').val(data.price_10);
                } else {
                  $('#price{{$index}}').val(data.price_12);
                }
              });
            });
          });
          //Price Type

          // Total price calculation
          priceKeyupEdit{{$index}}();
          function priceKeyupEdit{{$index}}() {
            $(".form-edit-add").on("keyup","#price{{$index}}", function(e){ 
              let _price = e.target.value;
              let _quantity = $("#quantity{{$index}}").val();
              let _total = _quantity*_price;
              $("#totalPrice{{$index}}").val(_total);
              //console.log(_total);
            });
          }
          quantityKeyupEdit{{$index}}();
          function quantityKeyupEdit{{$index}}() {
            $(".form-edit-add").on("keyup","#quantity{{$index}}", function(e){ 
              let _quantity = e.target.value;
              let _price = $("#price{{$index}}").val();
              let _total = _quantity*_price;
              $("#totalPrice{{$index}}").val(_total);
              //console.log(_total);
            });
          }
        // Total price calculation


        @endforeach
      @endif

      


      let max_fields      = 70;
      let wrapper         = $(".container1");
      let add_button      = $(".add_form_field"); 

      @if ( $edit && count($sale_details) > 0)
        let x = {{count($sale_details)}}; 
      @else
        let x = 1;
      @endif

      
      $(add_button).click(function (event) {
          event.preventDefault();
          if(x < max_fields){
            
              x++;

              $(wrapper).append(
                '<div class="col-md-12 col-sm-offset-3 academic-qualification-jsc mb-2">'+
                    '<div class="col-md-12" style="height: 100%;">'+
                        '<div class="jsc_collapse hide">'+
                        '<div class="row">'+
                        '<div class="col-md-2">'+
                          '<div class="form-group">'+
                            '<label for="category_id'+ x +'">{{ __('admin.sale.category') }} <span style="color: red"> * </span></label>'+
                            '<select class="form-control select2" name="arraydata[stock'+ x +'][category_id]" id="category_id'+ x +'" required>'+
                              '<option value="">{{ __('admin.common.select') }}</option>'+
                              @foreach ($categories as $key => $item)
                                '<option value="{{ $item->id }}" {{(old('category_id') == $item->id) ? 'selected' : ''}} >{{ $item->{'title_'. app()->getLocale()} }}</option>'+
                              @endforeach
                            '</select>'+
                          '</div>'+
                          '</div>'+
                          '<div class="col-md-2">'+
                          '<div class="form-group">'+
                            '<label for="product_id'+ x +'">{{ __('admin.sale.product') }} <span style="color: red"> * </span></label>'+
                            '<select class="form-control select2" name="arraydata[stock'+ x +'][product_id]" id="product_id'+ x +'" required>'+
                              '<option value="">{{ __('admin.common.select') }}</option>'+
                            '</select>'+
                          '</div>'+
                          '</div>'+

                          // '<div class="form-group">'+
                          //   '<label for="unit_id'+ x +'">{{ __('admin.sale.unit') }}</label>'+
                          //   '<select disabled class="form-control select2" name="arraydata[stock'+ x +'][unit_id]" id="unit_id'+ x +'">'+
                          //     '<option value="">{{ __('admin.common.select') }}</option>'+
                          //   '</select>'+
                          // '</div>'+
                          '<div class="col-md-2">'+
                          '<div class="form-group">'+
                            '<label for="price_type_id'+ x +'">{{ __('admin.sale.price_type') }} <span style="color: red"> * </span></label>'+
                            '<select class="form-control select2 price_type_id" name="arraydata[stock'+ x +'][price_type_id]" id="price_type_id'+ x +'" required>'+
                              '<option value="">{{ __('admin.common.select') }}</option>'+
                              @foreach ($price_types as $key => $item)
                                '<option value="{{ $item->id }}" {{(old('price_type_id') == $item->id) ? 'selected' : ''}} >{{ $item->{'title_'. app()->getLocale()} }}</option>'+
                              @endforeach
                            '</select>'+
                          '</div>'+
                          '</div>'+
                          '<div class="col-md-3">'+
                          '<div class="form-group">'+
                            '<label for="price'+ x +'">{{ __('admin.sale.price') }}</label> <span style="color: red"> * </span> <span class="text-info">({{__('admin.common.en_lang_use')}}) </span>'+
                            '<input required type="number" name="arraydata[stock'+ x +'][price]" id="price'+ x +'" placeholder="{{ __('admin.sale.price') }}" style="background-color: #51915f !important; color: #ffffff !important; font-size: 2rem !important;" value="0" class="form-control price">'+
                          '</div>'+
                          '</div>'+
                          '<div class="col-md-3">'+
                          '<div class="form-group">'+
                            '<label for="quantity'+ x +'">{{ __('admin.sale.quantity') }}</label> <span style="color: red"> * </span> <span class="text-info">({{__('admin.common.en_lang_use')}}) </span>'+
                            '<input type="number" required name="arraydata[stock'+ x +'][quantity]" id="quantity'+ x +'" placeholder="{{ __('admin.sale.quantity') }}" min="1" style="background-color: #51915f !important; color: #ffffff !important; font-size: 2rem !important;" value="0" class="form-control quantity">'+
                            '<input type="hidden" id="totalPrice'+ x +'" value="0" class="form-control totalPrice">'+
                          '</div>'+
                          '</div>'+

                        '</div>'+
                        '<button title="Remove This Section" id="delete'+ x +'"class="delete btn btn-warning btn-sm" style="margin-left: 97%;"> <i class="fa fa-minus-circle" aria-hidden="true"></i></button>'+
                    '</div>'+
                '</div>'
              );

              // ajax code
              $('.select2').select2();
              $('#category_id'+x).on('change', function(e){
                var category_id = e.target.value;
                var route = "{{route('get.products')}}/"+category_id;
                ////console.log('category x value ' + x);
                $.get(route, function(data) {
                  ////console.log(data);
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
                ////console.log('product x value ' + x);
                $.get(route, function(data) {
                  ////console.log(data);
                  $('#price'+x).val(0);
                  $('#unit_id'+x).empty();
                  $.each(data, function(index,data){
                    $('#unit_id'+x).append('<option value="' + data.unit.id + '">' + data.unit.title_{{app()->getLocale()}} +  '</option>');
                    $('#price'+x).val(data.price);
                  });
                });
              });
              // ajax code

              //Price Type
              
              $('#price_type_id'+x).on('change', function(e){
                var price_type_id = e.target.value;
                var product_id = $('#product_id'+x).val()
                var route = "{{route('get.product')}}/"+product_id;
                ////console.log('product x value ' + x);
                $.get(route, function(data) {
                  //console.log(data);
                  $.each(data, function(index,data){
                    if (price_type_id == 1) {
                      $('#price'+x).val(data.price);
                    } else if(price_type_id == 2){
                      $('#price'+x).val(data.price_bag);
                    } else if(price_type_id == 3){
                      $('#price'+x).val(data.price_10);
                    } else {
                      $('#price'+x).val(data.price_12);
                    }
                  });
                });
              });
              
              //Price Type
              getNursery();

              // Total price calculation
              priceKeyup();
              quantityKeyup();

              getTotalPrice();
              getTotalQuentity();

              totalQuentityKeyup();
              totalPriceKeyup();
              totalDueAmount();

              // Total price calculation
          }else{
              alert('You Reached the limits')
          }
      });

      $(wrapper).on("click",".delete", function(e){ 
        e.preventDefault(); 
        $(this).parent('div').parent('div').remove();x--;
        
        getTotalPrice();
        getTotalQuentity();

        totalQuentityKeyup();
        totalPriceKeyup();

        totalDueAmount();
        // //Total Calculation
        //   getTotalQuentity();
        //   getTotalPrice();
        //   totalDueAmount();
        // //Total Calculation
      });

      // Total price calculation
        priceKeyup();
        function priceKeyup() {
          $(".form-edit-add").on("keyup","#price"+x, function(e){ 
            let _price = e.target.value;
            let _quantity = $("#quantity"+x).val();
            let _total = _quantity*_price;
            $("#totalPrice"+x).val(_total);
            //console.log(_total);
          });
        }
        quantityKeyup();
        function quantityKeyup() {
          $(".form-edit-add").on("keyup","#quantity"+x, function(e){ 
            let _quantity = e.target.value;
            let _price = $("#price"+x).val();
            let _total = _quantity*_price;
            $("#totalPrice"+x).val(_total);
            //console.log(_total);
          });
        }
      // Total price calculation

      

      $('#category_id'+x).on('change', function(e){
        var category_id = e.target.value;
        var route = "{{route('get.products')}}/"+category_id;
        ////console.log(category_id);
        $.get(route, function(data) {
          ////console.log(data);
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
        ////console.log(product_id);
        $.get(route, function(data) {
          ////console.log(data);
          $('#price'+x).val(0);
          $('#unit_id'+x).empty();
          $.each(data, function(index,data){
            $('#unit_id'+x).append('<option value="' + data.unit.id + '">' + data.unit.title_{{app()->getLocale()}} +  '</option>');
            $('#price'+x).val(data.price);
          });
        });
      });


      //Price Type
              
      $('#price_type_id'+x).on('change', function(e){
        var price_type_id = e.target.value;
        var product_id = $('#product_id'+x).val()
        var route = "{{route('get.product')}}/"+product_id;
        ////console.log('product x value ' + x);
        $.get(route, function(data) {
          //console.log(data);
          $.each(data, function(index,data){
            if (price_type_id == 1) {
              $('#price'+x).val(data.price);
            } else if(price_type_id == 2){
              $('#price'+x).val(data.price_bag);
            } else if(price_type_id == 3){
              $('#price'+x).val(data.price_10);
            } else {
              $('#price'+x).val(data.price_12);
            }
          });
        });
      });
      
      //Price Type


      $('#forest_beat_id').on('change', function(e){
        var forest_beat_id = e.target.value
        var route = "{{route('get.forest_beat.forest_division')}}/"+forest_beat_id;
        $.get(route, function(data) {
          //console.log(data);
          if (Object.keys(data).length > 0) {
            if (data.price_type == 1) {
              //console.log(data.price_type);
              $(".price_type_id").attr('disabled','disabled');
            } else {
              $(".price_type_id").removeAttr('disabled');
            }
          } else {
            //console.log('null');
            $(".price_type_id").attr('disabled','disabled');
          }
        });
      });

      function getNursery() {
        @if (Auth::guard('admin')->user()->userType->default_role <= Admin::DEFAULT_ROLE_LIST[5])
          var forest_beat_id = $('#forest_beat_id').val();
        @else
          var forest_beat_id = "{{Auth::guard('admin')->user()->forest_beat_id}}";
        @endif
        var route = "{{route('get.forest_beat.forest_division')}}/"+forest_beat_id;
        console.log('forest_beat_id');
        $.get(route, function(data) {
          if (Object.keys(data).length > 0) {
            if (data.price_type == 1) {
              console.log(data.price_type);
              $(".price_type_id").attr('disabled','disabled');
            } else {
              $(".price_type_id").removeAttr('disabled');
            }
          } else {
            //console.log('null');
            $(".price_type_id").attr('disabled','disabled');
          }
        });
      }

      $(document).on('click', '.delete1', function (e) {
          e.preventDefault();
          ////console.log($(this).attr('href'))
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
              ////console.log(route);
            } else if (result.isDenied) {
              //Swal.fire("{{__('admin.common.not_save1')}}", '', 'info')
            }
          })
      });

      // Price & Quentity Calculations
      
      

      //getTotalPrice();
      //getTotalQuentity();
      var total_quantity = 0;
      var total_price = 0;
      var discount = 0

      var due_amount = 0;
      var paid_amount = 0

      firstTimeLoad();
      function firstTimeLoad() {
        getTotalPrice();
        getTotalQuentity();
        totalDueAmount();
      }

      $("#submit").on('mouseenter', function (e) {
        getTotalPrice();
        getTotalQuentity();
        totalDueAmount();
      });

      totalQuentityKeyup();
      function totalQuentityKeyup() {
        $(document).on("keyup",".quantity", function(e){ 
          getTotalQuentity();
          totalDueAmount();
        });
      }
      totalPriceKeyup();
      function totalPriceKeyup() {
        $(document).on("keyup",".price", function(e){ 
          getTotalPrice();
          totalDueAmount();
        });
      }
      getTotalPrice();
      getTotalQuentity();
      function getTotalQuentity() {
        total_quantity = 0;
        $(document).find(".quantity").each(function() {
          total_quantity += parseFloat($(this).val());
        });
        $("#total_quantity").val(total_quantity);
      }

      
      function getTotalPrice() {
        
        total_price = 0;
        $(document).find(".totalPrice").each(function() {
          total_price += parseFloat($(this).val());
        });
        $("#total_price").val(total_price);
        ////console.log(total_price);
      }

      function totalDueAmount() {
        //let discont_val = parseFloat($('#discount').val());
        let discount_amount = get_discount_amount();
        due_amount = total_price - discount_amount;
        $('#due_amount').val(due_amount);
        $('#paid_amount').val(due_amount);
      }


      function get_discount_amount() {
        getTotalPrice();
        getTotalQuentity();
        let ischecked= $(".percent").is(':checked');
        if(ischecked){
          let discont_val = parseFloat($('#discount').val());
          return discount = (discont_val*total_price)/100;
        }else{
          return discount = parseFloat($('#discount').val());
        }
      }
      

      $(document).on("keyup","#discount", function(e){ 
        if(e.target.value == ""){
          $('#discount').val(0);
        }
        getTotalPrice();
        getTotalQuentity();
        totalDueAmount();
        ////console.log('discont clicked');
      });


      getPercentPrice();
      function getPercentPrice() {
        $("#percent").change(function(e) {
          if(this.checked) {
            getTotalPrice();
            getTotalQuentity();
            totalDueAmount();
          }else{
            getTotalPrice();
            getTotalQuentity();
            totalDueAmount();
          }
        });
      }
      // Price & Quentity Calculations


      // Get Users
      getUsers();
      function getUsers() {
        var route = "{{route('get.users')}}/";
        $.get(route, function(data) {
          $('#user_id').empty();
          //$('#user_id').append('<option value="">{{ __('admin.common.select') }}</option>');
          $.each(data, function(index,data){
            $('#user_id').append('<option value="' + data.id + '">' + data.title_{{app()->getLocale()}} + ' - ' + data.contact +'</option>');
          });
        });
      }

      

      $('#user-form-submit').click(function (e) { 
        e.preventDefault();
        let edit = "{{$edit}}";
        let title_en = $('#title_en').val();
        let title_bn = $('#title_en').val();
        let contact = $('#contact').val();
        let where = $('#where').val();
        let data = {
          'title_en' : title_en,
          'title_bn' : title_bn,
          'contact' : contact,
          'where' : where,
          '_token' : "{{ csrf_token() }}",
        }

        let url = $('#customer-form').attr('action');
        let method = $('#customer-form').attr('method');

        $.post(url, data)
          .done(function(res){  
            ////console.log(res.success);
            $('#myModal').modal('hide');
            getUsers();
            if (edit == true) {
              location.reload();
            }
          })
          .fail(function(error) {
            ////console.log(error.responseJSON.message);
            alert(error.responseJSON.message);
          });
      });

      
    }); 
  </script>

  <script>
    $(document).ready(function () {
      //Form Submit Confirmation
      $('#sale_entry_form').submit(function(event) {
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