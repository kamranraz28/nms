@php
  $edit = false;
  if(!empty($forest_division)){
     if($forest_division->id !=''){
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
  @include('../admin.layouts.partials.breadcrumb', ['path1' => __('admin.menu.forest_division'), 'route1' => route('admin.forest_division') ])
@endsection

@section('content')

  <div class="content-wrapper">
    
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          
          <div class="col-sm-2">
            <a href="{{ route('admin.forest_division') }}" class="btn btn-info form-control btn-add-new">
                <i class="fas fa-backward"></i> <span>{{ __('admin.common.back') }}</span>
            </a>
          </div>

          <div class="col-sm-10">
            <h1 class="text-info">
              @if ($edit)
                <i class="fas fa-bookmark"></i> {{ __('admin.common.update') }} {{ __('admin.menu.forest_division') }} {{ __('admin.common.info') }}
              @else
                <i class="fas fa-bookmark"></i> {{ __('admin.common.add') }} {{ __('admin.menu.forest_division') }} {{ __('admin.common.info') }}
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


<form class="form-edit-add" forest_division="form" id="forest_division_entry_form"
              action="{{!$edit ? route('admin.forest_division.store') : route('admin.forest_division.update', $forest_division->id)}}"
              method="POST" enctype="multipart/form-data" autocomplete="off">
            <!-- PUT Method if we are editing -->
            @if($edit)
                <input type="hidden" name="id" value="{{$forest_division->id}}">
                {{ method_field("PUT") }}
            @endif
            {{ csrf_field() }}

    <section class="content" style="margin-top: -10px;">
      <div class="container-fluid">
          <div class="row">
            
            <!-- col start -->
            <div class="col-6">
              <!-- card start -->
              <div class="card">
                
                <div class="card-header">
                  <!--<h3 class="card-title">DataTable with minimal features & hover style</h3>-->
                </div>

                <div class="card-body">
                  @if ($edit)
                    <div class="form-group">
                      <label for="forest_state_id">{{ __('admin.forest_division.forest_state') }} <!--<span style="color: red"> * </span>--></label>
                      <select class="form-control select2" name="forest_state_id" id="forest_state_id" required>
                        <option value="">{{ __('admin.common.select') }}</option>
                        @foreach ($forest_states as $key => $item)
                          <option value="{{ $item->id }}" {{($forest_division->forest_state_id == $item->id) ? 'selected' : ''}} >{{ $item->{'title_'. app()->getLocale()} }} - {{ $item->bbs_code }}</option>
                        @endforeach
                      </select>
                    </div>    
                  @else
                    <div class="form-group">
                      <label for="forest_state_id">{{ __('admin.forest_division.forest_state') }} <!--<span style="color: red"> * </span>--></label>
                      <select class="form-control select2" name="forest_state_id" id="forest_state_id" required>
                        <option value="">{{ __('admin.common.select') }}</option>
                        @foreach ($forest_states as $key => $item)
                          <option value="{{ $item->id }}" {{(old('forest_state_id') == $item->id) ? 'selected' : ''}} >{{ $item->{'title_'. app()->getLocale()} }} - {{ $item->bbs_code }}</option>
                        @endforeach
                      </select>
                    </div>
                  @endif


                  @if ($edit)
                  <div class="form-group">
                    <label for="price_type">{{ __('admin.forest_division.price_type') }}  </label><br>
                    <div class="icheck-primary d-inline">
                      <input type="radio" id="price_type1" value="1" name="price_type" 
                      {{ ($forest_division->price_type == '1') ? 'checked' : '' }}>
                      <label for="price_type1">
                        {{(app()->getLocale() == 'en') ? App\Models\Status::EN2[1] : App\Models\Status::BN2[1]}}
                      </label>
                    </div>
                    <div class="icheck-danger d-inline">
                      <input type="radio" id="price_type2" value="0" name="price_type"
                      {{ ($forest_division->price_type == '0') ? 'checked' : '' }}>
                      <label for="price_type2">
                        {{(app()->getLocale() == 'en') ? App\Models\Status::EN2[0] : App\Models\Status::BN2[0]}}
                      </label>
                    </div>
                  </div>    
                  @else
                  <div class="form-group">
                    <label for="price_type">{{ __('admin.forest_division.price_type') }}  </label><br>
                    <div class="icheck-primary d-inline">
                      <input type="radio" id="price_type1" value="1" name="price_type" 
                      {{ (old('price_type') == '1') ? 'checked' : 'checked' }}>
                      <label for="price_type1">
                        {{(app()->getLocale() == 'en') ? App\Models\Status::EN2[1] : App\Models\Status::BN2[1]}}
                      </label>
                    </div>
                    <div class="icheck-danger d-inline">
                      <input type="radio" id="price_type2" value="0" name="price_type"
                      {{ (old('price_type') == '0') ? 'checked' : '' }}>
                      <label for="price_type2">
                        {{(app()->getLocale() == 'en') ? App\Models\Status::EN2[0] : App\Models\Status::BN2[0]}}
                      </label>
                    </div>
                  </div>    
                  @endif

                  <div class="form-group">
                    <label for="bbs_code">{{ __('admin.forest_division.code') }}</label> ({{__('admin.common.en_lang_use')}})
                    <input type="number" name="bbs_code"
                     id="bbs_code" placeholder="{{ __('admin.forest_division.bbs_code') }}" 
                     value="{{ $edit?$forest_division->bbs_code:old('bbs_code') }}"
                     class="form-control" >
                  </div>

                </div>
              </div>
              <!-- card start -->
            </div>
            <!-- col end -->
            
            
            <!-- col start -->
            <div class="col-6">
              <!-- card start -->
              <div class="card">
                
                <div class="card-header">
                  <!--<h3 class="card-title">DataTable with minimal features & hover style</h3>-->
                </div>

                <div class="card-body">
                  


                  <div class="form-group">
                    <label for="title_en">{{ __('admin.forest_division.title_en') }}</label>
                    <input type="text" name="title_en"
                     id="title_en" placeholder="{{ __('admin.forest_division.title_en') }}" 
                     value="{{ $edit?$forest_division->title_en:old('title_en') }}"
                     class="form-control" >
                  </div>

                  <div class="form-group">
                    <label for="title_bn">{{ __('admin.forest_division.title_bn') }}</label>
                    <input type="text" name="title_bn"
                     id="title_bn" placeholder="{{ __('admin.forest_division.title_bn') }}" 
                     value="{{ $edit?$forest_division->title_bn:old('title_bn') }}"
                     class="form-control" >
                  </div>

                  <hr style="visibility: hidden">
                  
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


