@php
  $edit = false;
  if(!empty($site_setting)){
     if($site_setting->id !=''){
         $edit=true;
     }
  }
@endphp


@extends('admin.layouts.master')
@section('title')
{{ $sitesetting->{'title_'. app()->getLocale()} }} :: {{__('admin.menu.site_setting')}}
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

<style>
  .radio-inline{
    font-weight: 100;
    padding: 0px 10px;
  }
</style>

@endsection

@section('breadcrumb')
  @include('../admin.layouts.partials.breadcrumb', ['path1' => __('admin.menu.site_setting'), 'route1' => route('admin.site_setting') ])
@endsection

@section('content')

  <div class="content-wrapper">
    
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          
          <div class="col-sm-2">
            <a href="{{ route('admin.site_setting') }}" class="btn btn-info form-control btn-add-new">
                <i class="fas fa-backward"></i> <span>{{ __('admin.common.back') }}</span>
            </a>
          </div>

          <div class="col-sm-10">
            <h1 class="text-info">
              @if ($edit)
                <i class="fas fa-bookmark"></i> {{ __('admin.common.update') }} {{ __('admin.menu.site_setting') }} {{ __('admin.common.info') }}
              @else
                <i class="fas fa-bookmark"></i> {{ __('admin.common.add') }} {{ __('admin.menu.site_setting') }} {{ __('admin.common.info') }}
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


<form class="form-edit-add" role="form" id="site_setting_entry_form"
              action="{{!$edit ? route('admin.site_setting.store') : route('admin.site_setting.update', $site_setting->id)}}"
              method="POST" enctype="multipart/form-data" autocomplete="off">
            <!-- PUT Method if we are editing -->
            @if($edit)
                <input type="hidden" name="id" value="{{$site_setting->id}}">
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

                  {{-- <div class="form-group">
                    <label for="sort">{{ __('admin.site_setting.sort') }}</label>
                    <input type="number" min="0" max="10000000" name="sort"
                     id="sort" placeholder="{{ __('admin.site_setting.sort') }}" 
                     value="{{ $edit?$site_setting->sort:old('sort') }}"
                     class="form-control" required>
                  </div> --}}

                  
                  <div class="form-group">
                    <label for="title_en">{{ __('admin.site_setting.title_en') }}</label>
                    <textarea required="required" class="form-control" placeholder="{{ __('admin.site_setting.title_en') }}" 
                    name="title_en" id="title_en"  rows="1">{{ $edit?$site_setting->title_en:old('title_en') }}</textarea>
                  </div>

                  <div class="form-group">
                    <label for="title_bn">{{ __('admin.site_setting.title_bn') }}</label>
                    <textarea required="required" class="form-control" placeholder="{{ __('admin.site_setting.title_bn') }}" 
                    name="title_bn" id="title_bn"  rows="1">{{ $edit?$site_setting->title_bn:old('title_bn') }}</textarea>
                  </div>

                  <div class="form-group">
                    <label for="site_address_en">{{ __('admin.site_setting.site_address_en') }}</label>
                    <textarea required="required" class="form-control" placeholder="{{ __('admin.site_setting.site_address_en') }}" 
                    name="site_address_en" id="site_address_en"  rows="1">{{ $edit?$site_setting->site_address_en:old('site_address_en') }}</textarea>
                  </div>

                  <div class="form-group">
                    <label for="site_address_bn">{{ __('admin.site_setting.site_address_bn') }}</label>
                    <textarea required="required" class="form-control" placeholder="{{ __('admin.site_setting.site_address_bn') }}" 
                    name="site_address_bn" id="site_address_bn"  rows="1">{{ $edit?$site_setting->site_address_bn:old('site_address_bn') }}</textarea>
                  </div>


                  <div class="form-group">
                    <label for="site_mobile">{{ __('admin.site_setting.site_mobile') }}</label>
                    <input type="text" name="site_mobile"
                    id="site_mobile" placeholder="{{ __('admin.site_setting.site_mobile') }}" 
                    value="{{ $edit?$site_setting->site_mobile:old('site_mobile') }}"
                    class="form-control" >
                  </div>

                  <div class="form-group">
                    <label for="site_email">{{ __('admin.site_setting.site_email') }}</label>
                    <input type="text" name="site_email"
                    id="site_email" placeholder="{{ __('admin.site_setting.site_email') }}" 
                    value="{{ $edit?$site_setting->site_email:old('site_email') }}"
                    class="form-control" >
                  </div>


                  <div class="form-group" style="display: none;">
                    <label for="vat">{{ __('admin.site_setting.vat') }} (%)</label>
                    <input type="number" min="0" max="10000000" name="vat" 
                     id="vat" placeholder="{{ __('admin.site_setting.vat') }}" 
                     value="{{ $edit?$site_setting->vat:old('vat') }}"
                     class="form-control" required>
                  </div>
                  
                  <div class="form-group">
                    <label for="site_web">{{ __('admin.site_setting.site_web') }}</label>
                    <input type="text" name="site_web"
                    id="site_web" placeholder="{{ __('admin.site_setting.site_web') }}" 
                    value="{{ $edit?$site_setting->site_web:old('site_web') }}"
                    class="form-control" >
                  </div>


                  {{-- <div class="form-group">
                    <label for="layout">{{ __('admin.site_setting.layout') }}</label><br>
                    <label class="radio-inline" style="font-weight: 100;margin-right:10px">
                      <input style="margin-right:10px" type="radio" name="layout" 
                      {{ ($edit && ($site_setting->layout == true))? 'checked' : '' }} value="1">Home Page One</label>

                    <label class="radio-inline" style="font-weight: 100;margin-right:10px">
                      <input style="margin-right:10px" type="radio" name="layout" 
                      {{ ($edit && ($site_setting->layout == false))? 'checked' : '' }} value="0">Home Page Two</label>
                  </div> --}}
                  

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
                    <label for="footer_en">{{ __('admin.site_setting.footer_en') }}</label>
                    <textarea required="required" class="form-control" placeholder="{{ __('admin.site_setting.footer_en') }}" 
                    name="footer_en" id="footer_en"  rows="1">{{ $edit?$site_setting->footer_en:old('footer_en') }}</textarea>
                  </div>

                  <div class="form-group">
                    <label for="footer_bn">{{ __('admin.site_setting.footer_bn') }}</label>
                    <textarea required="required" class="form-control" placeholder="{{ __('admin.site_setting.footer_bn') }}" 
                    name="footer_bn" id="footer_bn"  rows="1">{{ $edit?$site_setting->footer_bn:old('footer_bn') }}</textarea>
                  </div>


                  <div class="form-group">
                    <label for="favicon">
                        {{ __('admin.site_setting.favicon') }} <span
                                class="text-warning">(Maximum file size: 100 KB)</span>
                    </label>
                    <input style="padding: 3px;" type="file" name="favicon" id="favicon"
                           class="form-control">
                    @if($edit && $site_setting->favicon)
                        <a target="_blank"
                           href="{{asset('storage/'.$site_setting->favicon)}}">Show</a>
                    @endif
                  </div>

                  
                  <div class="form-group">
                    <label for="logo">
                        {{ __('admin.site_setting.logo') }} <span
                                class="text-warning">(Maximum file size: 100 KB)</span>
                    </label>
                    <input style="padding: 3px;" type="file" name="logo" id="logo"
                           class="form-control">
                    @if($edit && $site_setting->logo)
                        <a target="_blank"
                           href="{{asset('storage/'.$site_setting->logo)}}">Show</a>
                    @endif
                  </div>
                  
                  <div class="form-group">
                    <label for="footer_logo">
                        {{ __('admin.site_setting.footer_logo') }} <span
                                class="text-warning">(Maximum file size: 100 KB)</span>
                    </label>
                    <input style="padding: 3px;" type="file" name="footer_logo" id="footer_logo"
                           class="form-control">
                    @if($edit && $site_setting->footer_logo)
                        <a target="_blank"
                           href="{{asset('storage/'.$site_setting->footer_logo)}}">Show</a>
                    @endif
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


@endsection


