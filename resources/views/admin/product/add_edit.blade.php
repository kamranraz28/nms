@php
  $edit = false;
  if(!empty($product)){
     if($product->id !=''){
         $edit=true;
     }
  }
@endphp


@extends('admin.layouts.master')
@section('title')
  {{__('admin.menu.site')}} :: {{__('admin.menu.dashboard')}}
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
  @include('../admin.layouts.partials.breadcrumb', ['path1' => __('admin.menu.product'), 'route1' => route('admin.product') ])
@endsection

@section('content')

  <div class="content-wrapper">
    
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          
          <div class="col-sm-2">
            <a href="{{ route('admin.product') }}" class="btn btn-info form-control btn-add-new">
                <i class="fas fa-backward"></i> <span>{{ __('admin.common.back') }}</span>
            </a>
          </div>

          <div class="col-sm-10">
            <h1 class="text-info">
              @if ($edit)
                <i class="fas fa-bookmark"></i> {{ __('admin.common.update') }} {{ __('admin.menu.product') }} {{ __('admin.common.info') }}
              @else
                <i class="fas fa-bookmark"></i> {{ __('admin.common.add') }} {{ __('admin.menu.product') }} {{ __('admin.common.info') }}
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


<form class="form-edit-add" product="form" id="product_entry_form"
              action="{{!$edit ? route('admin.product.store') : route('admin.product.update', $product->id)}}"
              method="POST" enctype="multipart/form-data" autocomplete="off">
            <!-- PUT Method if we are editing -->
            @if($edit)
                <input type="hidden" name="id" value="{{$product->id}}">
                {{ method_field("PUT") }}
            @endif
            {{ csrf_field() }}

    <section class="content" style="margin-top: -10px;">
      <div class="container-fluid">
          <div class="row">
            <!-- col start -->
            <div class="col-md-4">
              <!-- card start -->
              <div class="card">
                
                <div class="card-header">
                  <!--<h3 class="card-title">DataTable with minimal features & hover style</h3>-->
                </div>

                
                <div class="card-body">

                @if ($edit)
                    <div class="form-group">
                      <label for="category_id">{{ __('admin.product.category') }} <!--<span style="category: red"> * </span>--></label>
                      <select class="form-control select2" name="category_id" id="category_id">
                        <option value="">{{ __('admin.common.select') }}</option>
                        @foreach ($categories as $key => $item)
                          <option value="{{ $item->id }}" {{($product->category_id == $item->id) ? 'selected' : ''}} >{{ $item->{'title_'. app()->getLocale()} }} - {{ $item->code }}</option>
                        @endforeach
                      </select>
                    </div>    
                  @else
                    <div class="form-group">
                      <label for="category_id">{{ __('admin.product.category') }} <!--<span style="category: red"> * </span>--></label>
                      <select class="form-control select2" name="category_id" id="category_id">
                        <option value="">{{ __('admin.common.select') }}</option>
                        @foreach ($categories as $key => $item)
                          <option value="{{ $item->id }}" {{(old('category_id') == $item->id) ? 'selected' : ''}} >{{ $item->{'title_'. app()->getLocale()} }} - {{ $item->code }}</option>
                        @endforeach
                      </select>
                    </div>
                  @endif
                  
                  @if ($edit)
                    <div class="form-group">
                      <label for="unit_id">{{ __('admin.product.unit') }} <!--<span style="color: red"> * </span>--></label>
                      <select class="form-control select2" name="unit_id" id="unit_id">
                        <option value="">{{ __('admin.common.select') }}</option>
                        @foreach ($units as $key => $item)
                          <option value="{{ $item->id }}" {{($product->unit_id == $item->id) ? 'selected' : ''}} >{{ $item->{'title_'. app()->getLocale()} }} - {{ $item->code }}</option>
                        @endforeach
                      </select>
                    </div>    
                  @else
                    <div class="form-group">
                      <label for="unit_id">{{ __('admin.product.unit') }} <!--<span style="color: red"> * </span>--></label>
                      <select class="form-control select2" name="unit_id" id="unit_id">
                        <option value="">{{ __('admin.common.select') }}</option>
                        @foreach ($units as $key => $item)
                          <option value="{{ $item->id }}" {{(old('unit_id') == $item->id) ? 'selected' : ''}} >{{ $item->{'title_'. app()->getLocale()} }} - {{ $item->code }}</option>
                        @endforeach
                      </select>
                    </div>
                  @endif

                  @if ($edit)
                    <div class="form-group">
                      <label for="size_id">{{ __('admin.product.size') }} <!--<span style="color: red"> * </span>--></label>
                      <select class="form-control select2" name="size_id" id="size_id">
                        <option value="">{{ __('admin.common.select') }}</option>
                        @foreach ($sizes as $key => $item)
                          <option value="{{ $item->id }}" {{($product->size_id == $item->id) ? 'selected' : ''}} >{{ $item->{'title_'. app()->getLocale()} }} - {{ $item->code }}</option>
                        @endforeach
                      </select>
                    </div>    
                  @else
                    <div class="form-group">
                      <label for="size_id">{{ __('admin.product.size') }} <!--<span style="color: red"> * </span>--></label>
                      <select class="form-control select2" name="size_id" id="size_id">
                        <option value="">{{ __('admin.common.select') }}</option>
                        @foreach ($sizes as $key => $item)
                          <option value="{{ $item->id }}" {{(old('size_id') == $item->id) ? 'selected' : ''}} >{{ $item->{'title_'. app()->getLocale()} }} - {{ $item->code }}</option>
                        @endforeach
                      </select>
                    </div>
                  @endif


                  @if ($edit)
                    <div class="form-group">
                      <label for="age_id">{{ __('admin.product.age') }} <!--<span style="age: red"> * </span>--></label>
                      <select class="form-control select2" name="age_id" id="age_id">
                        <option value="">{{ __('admin.common.select') }}</option>
                        @foreach ($ages as $key => $item)
                          <option value="{{ $item->id }}" {{($product->age_id == $item->id) ? 'selected' : ''}} >{{ $item->{'title_'. app()->getLocale()} }} - {{ $item->code }}</option>
                        @endforeach
                      </select>
                    </div>    
                  @else
                    <div class="form-group">
                      <label for="age_id">{{ __('admin.product.age') }} <!--<span style="age: red"> * </span>--></label>
                      <select class="form-control select2" name="age_id" id="age_id">
                        <option value="">{{ __('admin.common.select') }}</option>
                        @foreach ($ages as $key => $item)
                          <option value="{{ $item->id }}" {{(old('age_id') == $item->id) ? 'selected' : ''}} >{{ $item->{'title_'. app()->getLocale()} }} - {{ $item->code }}</option>
                        @endforeach
                      </select>
                    </div>
                  @endif

                  {{-- @if ($edit)
                    <div class="form-group">
                      <label for="color_id">{{ __('admin.product.color') }} <!--<span style="color: red"> * </span>--></label>
                      <select class="form-control select2" name="color_id" id="color_id">
                        <option value="">{{ __('admin.common.select') }}</option>
                        @foreach ($colors as $key => $item)
                          <option value="{{ $item->id }}" {{($product->color_id == $item->id) ? 'selected' : ''}} >{{ $item->{'title_'. app()->getLocale()} }} - {{ $item->code }}</option>
                        @endforeach
                      </select>
                    </div>    
                  @else
                    <div class="form-group">
                      <label for="color_id">{{ __('admin.product.color') }} <!--<span style="color: red"> * </span>--></label>
                      <select class="form-control select2" name="color_id" id="color_id">
                        <option value="">{{ __('admin.common.select') }}</option>
                        @foreach ($colors as $key => $item)
                          <option value="{{ $item->id }}" {{(old('color_id') == $item->id) ? 'selected' : ''}} >{{ $item->{'title_'. app()->getLocale()} }} - {{ $item->code }}</option>
                        @endforeach
                      </select>
                    </div>
                  @endif --}}


                  <div class="form-group">
                    <label for="thumb">
                        {{ __('admin.product.thumb') }} <span
                                class="text-warning">({{__('admin.common.max_size')}})</span>
                    </label>
                    <input style="padding: 3px;" type="file" name="thumb" id="thumb"
                           class="form-control" {{$edit?'':''}}>
                    @if($edit && $product->thumb)
                        <a target="_blank"
                           href="{{asset('storage/'.$product->thumb)}}">Show</a>
                    @endif
                  </div>
                  

                </div>

              </div>
              <!-- card start -->
            </div>
            <!-- col end -->


            <!-- col start -->
            <div class="col-md-4">
              <!-- card start -->
              <div class="card">
                
                <div class="card-header">
                  <!--<h3 class="card-title">DataTable with minimal features & hover style</h3>-->
                </div>

                <div class="card-body">

                  <div class="form-group">
                    <label for="title_en">{{ __('admin.product.title_en') }}</label>
                    <input type="text" name="title_en"
                     id="title_en" placeholder="{{ __('admin.product.title_en') }}" 
                     value="{{ $edit?$product->title_en:old('title_en') }}"
                     class="form-control" >
                  </div>

                  <div class="form-group">
                    <label for="title_bn">{{ __('admin.product.title_bn') }}</label>
                    <input type="text" name="title_bn"
                     id="title_bn" placeholder="{{ __('admin.product.title_bn') }}" 
                     value="{{ $edit?$product->title_bn:old('title_bn') }}"
                     class="form-control" >
                  </div>

                  <div class="form-group">
                    <label for="scientific_en">{{ __('admin.product.scientific_en') }}</label>
                    <input type="text" name="scientific_en"
                     id="scientific_en" placeholder="{{ __('admin.product.scientific_en') }}" 
                     value="{{ $edit?$product->scientific_en:old('scientific_en') }}"
                     class="form-control" >
                  </div>

                  {{-- <div class="form-group">
                    <label for="scientific_bn">{{ __('admin.product.scientific_bn') }}</label>
                    <input type="text" name="scientific_bn"
                     id="scientific_bn" placeholder="{{ __('admin.product.scientific_bn') }}" 
                     value="{{ $edit?$product->scientific_bn:old('scientific_bn') }}"
                     class="form-control" >
                  </div> --}}

                  <div class="form-group">
                    <label for="details_en">{{ __('admin.product.details_en') }}</label>
                    <textarea rows="1" id="details_en" class="form-control" placeholder="{{ __('admin.product.details_en') }}" 
                    name="details_en">{{ $edit?$product->details_en:old('details_en') }}</textarea>
                  </div>

                  <div class="form-group">
                    <label for="details_bn">{{ __('admin.product.details_bn') }}</label>
                    <textarea rows="1" id="details_bn" class="form-control" placeholder="{{ __('admin.product.details_bn') }}" 
                    name="details_bn">{{ $edit?$product->details_bn:old('details_bn') }}</textarea>
                  </div>

                </div>
              </div>
              <!-- card start -->
            </div>
            <!-- col end -->

            <!-- col start -->
            <div class="col-md-4">
              <!-- card start -->
              <div class="card">
                
                <div class="card-header">
                  <!--<h3 class="card-title">DataTable with minimal features & hover style</h3>-->
                </div>

                <div class="card-body">
                  
                  <div class="form-group">
                    <label for="price">{{ __('admin.product.price') }}</label> <span class="text-info">({{__('admin.common.en_lang_use')}}) </span>
                    <input type="number" name="price"
                     id="price" placeholder="{{ __('admin.product.price') }}" 
                     value="{{ $edit?$product->price:old('price') ?? 0 }}"
                     class="form-control" >
                  </div>

                  <div class="form-group">
                    <label for="price_bag">{{ __('admin.product.price_bag') }}</label> <span class="text-info">({{__('admin.common.en_lang_use')}}) </span>
                    <input type="number" name="price_bag"
                     id="price_bag" placeholder="{{ __('admin.product.price_bag') }}" 
                     value="{{ $edit?$product->price_bag:old('price_bag') ?? 0 }}"
                     class="form-control" >
                  </div>

                  <div class="form-group">
                    <label for="price_10">{{ __('admin.product.price_10') }}</label> <span class="text-info">({{__('admin.common.en_lang_use')}}) </span>
                    <input type="number" name="price_10"
                     id="price_10" placeholder="{{ __('admin.product.price_10') }}" 
                     value="{{ $edit?$product->price_10:old('price_10') ?? 0 }}"
                     class="form-control" >
                  </div>

                  <div class="form-group">
                    <label for="price_12">{{ __('admin.product.price_12') }}</label> <span class="text-info">({{__('admin.common.en_lang_use')}}) </span>
                    <input type="number" name="price_12"
                     id="price_12" placeholder="{{ __('admin.product.price_12') }}" 
                     value="{{ $edit?$product->price_12:old('price_12') ?? 0 }}"
                     class="form-control" >
                  </div>

                  {{-- <div class="form-group">
                    <label for="discount">{{ __('admin.product.discount') }}</label> <span class="text-info">({{__('admin.common.en_lang_use')}}) </span>
                    <input type="number" name="discount"
                     id="discount" placeholder="{{ __('admin.product.discount') }}" 
                     value="{{ $edit?$product->discount:(old('discount')) ?? 0 }}"
                     class="form-control" >
                  </div>

                  @if ($edit)
                  <div class="form-group">
                    <label for="percent">{{ __('admin.product.percent') }}  </label><br>
                    <div class="icheck-primary d-inline">
                      <input type="radio" id="percent1" value="1" name="percent" 
                      {{ ($product->percent == '1') ? 'checked' : '' }}>
                      <label for="percent1">
                        {{(app()->getLocale() == 'en') ? App\Models\Status::EN1[1] : App\Models\Status::BN1[1]}}
                      </label>
                    </div>
                    <div class="icheck-danger d-inline">
                      <input type="radio" id="percent2" value="0" name="percent"
                      {{ ($product->percent == '0') ? 'checked' : '' }}>
                      <label for="percent2">
                        {{(app()->getLocale() == 'en') ? App\Models\Status::EN1[0] : App\Models\Status::BN1[0]}}
                      </label>
                    </div>
                  </div>    
                  @else
                  <div class="form-group">
                    <label for="percent">{{ __('admin.product.percent') }}  </label><br>
                    <div class="icheck-primary d-inline">
                      <input type="radio" id="percent1" value="1" name="percent" 
                      {{ (old('percent') == '1') ? 'checked' : '' }}>
                      <label for="percent1">
                        {{(app()->getLocale() == 'en') ? App\Models\Status::EN1[1] : App\Models\Status::BN1[1]}}
                      </label>
                    </div>
                    <div class="icheck-danger d-inline">
                      <input type="radio" id="percent2" value="0" name="percent"
                      {{ (old('percent') == '0') ? 'checked' : 'checked' }}>
                      <label for="percent2">
                        {{(app()->getLocale() == 'en') ? App\Models\Status::EN1[0] : App\Models\Status::BN1[0]}}
                      </label>
                    </div>
                  </div>    
                  @endif --}}



                  @if ($edit)
                  <div class="form-group">
                    <label for="saleable">{{ __('admin.product.saleable') }}  </label><br>
                    <div class="icheck-primary d-inline">
                      <input type="radio" id="saleable1" value="1" name="saleable" 
                      {{ ($product->saleable == '1') ? 'checked' : '' }}>
                      <label for="saleable1">
                        {{(app()->getLocale() == 'en') ? App\Models\Status::EN1[1] : App\Models\Status::BN1[1]}}
                      </label>
                    </div>
                    <div class="icheck-danger d-inline">
                      <input type="radio" id="saleable2" value="0" name="saleable"
                      {{ ($product->saleable == '0') ? 'checked' : '' }}>
                      <label for="saleable2">
                        {{(app()->getLocale() == 'en') ? App\Models\Status::EN1[0] : App\Models\Status::BN1[0]}}
                      </label>
                    </div>
                  </div>    
                  @else
                  <div class="form-group">
                    <label for="saleable">{{ __('admin.product.saleable') }}  </label><br>
                    <div class="icheck-primary d-inline">
                      <input type="radio" id="saleable1" value="1" name="saleable" 
                      {{ (old('saleable') == '1') ? 'checked' : '' }} checked>
                      <label for="saleable1">
                        {{(app()->getLocale() == 'en') ? App\Models\Status::EN1[1] : App\Models\Status::BN1[1]}}
                      </label>
                    </div>
                    <div class="icheck-danger d-inline">
                      <input type="radio" id="saleable2" value="0" name="saleable"
                      {{ (old('saleable') == '0') ? 'checked' : '' }}>
                      <label for="saleable2">
                        {{(app()->getLocale() == 'en') ? App\Models\Status::EN1[0] : App\Models\Status::BN1[0]}}
                      </label>
                    </div>
                  </div>    
                  @endif
                  
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

  </div>




@endsection


@section('scripts')

  <script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/moment/moment.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/inputmask/jquery.inputmask.min.js') }}"></script>

  <script src="{{ asset('assets/plugins/daterangepicker/daterangepicker.js') }}"></script>
  <script src="{{ asset('assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>

  <script src="{{ asset('assets/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/bs-stepper/js/bs-stepper.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/dropzone/min/dropzone.min.js') }}"></script>

  <script>
    $(document).ready(function () {
      $('.select2').select2();
    });
  </script>
@endsection


