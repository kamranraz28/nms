@php
    use App\Helper\EnglishToBanglaDate;
    use App\Helper\NumberToBanglaWord;
    use Rakibhstu\Banglanumber\NumberToBangla;
    $numto = new NumberToBangla();
    use App\Models\Admin;

@endphp
@php
  $edit = false;
  if(!empty($user_type)){
     if($user_type->id !=''){
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
  @include('../admin.layouts.partials.breadcrumb', ['path1' => __('admin.menu.user_type'), 'route1' => route('admin.user_type') ])
@endsection

@section('content')

  <div class="content-wrapper">
    
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          
          <div class="col-sm-2">
            <a href="{{ route('admin.user_type') }}" class="btn btn-info form-control btn-add-new">
                <i class="fas fa-backward"></i> <span>{{ __('admin.common.back') }}</span>
            </a>
          </div>

          <div class="col-sm-10">
            <h1 class="text-info">
              @if ($edit)
                <i class="fas fa-bookmark"></i> {{ __('admin.common.update') }} {{ __('admin.menu.user_type') }} {{ __('admin.common.info') }}
              @else
                <i class="fas fa-bookmark"></i> {{ __('admin.common.add') }} {{ __('admin.menu.user_type') }} {{ __('admin.common.info') }}
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


<form class="form-edit-add" user_type="form" id="user_type_entry_form"
              action="{{!$edit ? route('admin.user_type.store') : route('admin.user_type.update', $user_type->id)}}"
              method="POST" enctype="multipart/form-data" autocomplete="off">
            <!-- PUT Method if we are editing -->
            @if($edit)
                <input type="hidden" name="id" value="{{$user_type->id}}">
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
                    <label for="name">{{ __('admin.user_type.name') }}</label>
                    <input type="text" name="name"
                     id="name" placeholder="{{ __('admin.user_type.name') }}" 
                     value="{{ $edit?$user_type->name:old('name') }}"
                     class="form-control" >
                  </div> --}}

                  <div class="form-group">
                    <label for="sort">{{ __('admin.user_type.sort') }}</label>
                    <input type="number" min="0" max="10000000" name="sort"
                     id="sort" placeholder="{{ __('admin.user_type.sort') }}" 
                     value="{{ $edit?$user_type->sort:old('sort') }}"
                     class="form-control" required>
                  </div>

                  <div class="form-group">
                    <label for="title_en">{{ __('admin.user_type.title_en') }}</label>
                    <input type="text" name="title_en"
                     id="title_en" placeholder="{{ __('admin.user_type.title_en') }}" 
                     value="{{ $edit?$user_type->title_en:old('title_en') }}"
                     class="form-control" >
                  </div>

                  <div class="form-group">
                    <label for="title_bn">{{ __('admin.user_type.title_bn') }}</label>
                    <input type="text" name="title_bn"
                     id="title_bn" placeholder="{{ __('admin.user_type.title_bn') }}" 
                     value="{{ $edit?$user_type->title_bn:old('title_bn') }}"
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
                    <label for="default_role">{{ __('admin.user_type.default_role') }}</label>
                    <select name="default_role" id="" class="form-control select2">
                      <option value="">{{ __('admin.common.select') }}</option>
                      @foreach (Admin::DEFAULT_ROLE as $key => $item)
                        <option value="{{$key}}" {{($edit && ($key == $user_type->default_role)) ? 'selected' : ''}} > {{(app()->getLocale() == 'en') ? $item : gtrans($item) }} </option>
                      @endforeach
                    </select>
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

  <script>
    $(document).ready(function () {
      $('.select2').select2();
    });
  </script>

@endsection


