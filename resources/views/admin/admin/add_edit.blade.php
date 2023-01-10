@php
  $edit = false;
  if(!empty($admin)){
     if($admin->id !=''){
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
  @include('../admin.layouts.partials.breadcrumb', ['path1' => __('admin.menu.admin'), 'route1' => route('admin.admin') ])
@endsection

@section('content')

  <div class="content-wrapper">
    
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          
          <div class="col-sm-2">
            <a href="{{ route('admin.admin') }}" class="btn btn-info form-control btn-add-new">
                <i class="fas fa-backward"></i> <span>{{ __('admin.common.back') }}</span>
            </a>
          </div>

          <div class="col-sm-10">
            <h1 class="text-info">
              @if ($edit)
                <i class="fas fa-bookmark"></i> {{ __('admin.common.update') }} {{ __('admin.menu.admin') }} {{ __('admin.common.info') }}
              @else
                <i class="fas fa-bookmark"></i> {{ __('admin.common.add') }} {{ __('admin.menu.admin') }} {{ __('admin.common.info') }}
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


<form class="form-edit-add" role="form" id="admin_entry_form"
              action="{{!$edit ? route('admin.admin.store') : route('admin.admin.update', $admin->id)}}"
              method="POST" enctype="multipart/form-data" autocomplete="off">
            <!-- PUT Method if we are editing -->
            @if($edit)
                <input type="hidden" name="id" value="{{$admin->id}}">
                {{ method_field("PUT") }}
            @endif
            {{ csrf_field() }}

    <section class="content" style="margin-top: -10px;">
      <div class="container-fluid">
          <div class="row">
            <!-- col start -->
            <div class="col-4">
              <!-- card start -->
              <div class="card">
                
                <div class="card-header">
                  <!--<h3 class="card-title">DataTable with minimal features & hover style</h3>-->
                </div>


                

                <div class="card-body">
                  
                  @if ($edit)
                    <div class="form-group">
                      <label for="user_type_id">{{ __('admin.admin.user_type') }} <!--<span style="color: red"> * </span>--></label>
                      <select class="form-control select2" name="user_type_id" id="user_type_id" required>
                        <option value="">{{ __('admin.common.select') }}</option>
                        @foreach ($user_types as $key => $item)
                          <option value="{{ $item->id }}" {{($admin->user_type_id == $item->id) ? 'selected' : ''}} >{{ $item->{'title_'. app()->getLocale()} }} - {{ $item->code }}</option>
                        @endforeach
                      </select>
                    </div>    
                  @else
                    <div class="form-group">
                      <label for="user_type_id">{{ __('admin.admin.user_type') }} <!--<span style="color: red"> * </span>--></label>
                      <select class="form-control select2" name="user_type_id" id="user_type_id" required>
                        <option value="">{{ __('admin.common.select') }}</option>
                        @foreach ($user_types as $key => $item)
                          <option value="{{ $item->id }}" {{(old('user_type_id') == $item->id) ? 'selected' : ''}} >{{ $item->{'title_'. app()->getLocale()} }} - {{ $item->code }}</option>
                        @endforeach
                      </select>
                    </div>
                  @endif


                  @if ($edit)
                    <div class="form-group">
                      <label for="role_id">{{ __('admin.admin.role') }} <!--<span style="color: red"> * </span>--></label>
                      <select class="form-control select2" name="role_id" id="role_id" required>
                        <option value="">{{ __('admin.common.select') }}</option>
                        @foreach ($roles as $key => $item)
                          <option value="{{ $item->id }}" {{($admin->role_id == $item->id) ? 'selected' : ''}} >{{ $item->{'title_'. app()->getLocale()} }} - {{ $item->code }}</option>
                        @endforeach
                      </select>
                    </div>    
                  @else
                    <div class="form-group">
                      <label for="role_id">{{ __('admin.admin.role') }} <!--<span style="color: red"> * </span>--></label>
                      <select class="form-control select2" name="role_id" id="role_id" required>
                        <option value="">{{ __('admin.common.select') }}</option>
                        @foreach ($roles as $key => $item)
                          <option value="{{ $item->id }}" {{(old('role_id') == $item->id) ? 'selected' : ''}} >{{ $item->{'title_'. app()->getLocale()} }} - {{ $item->code }}</option>
                        @endforeach
                      </select>
                    </div>
                  @endif

                  {{-- @if ($edit)
                    <div class="form-group">
                      <label for="state_id">{{ __('admin.admin.state') }} <!--<span style="color: red"> * </span>--></label>
                      <select class="form-control select2" name="state_id" id="state_id" required>
                        <option value="">{{ __('admin.common.select') }}</option>
                        @foreach ($states as $key => $item)
                          <option value="{{ $item->id }}" {{($admin->state_id == $item->id) ? 'selected' : ''}} >{{ $item->{'title_'. app()->getLocale()} }} - {{ $item->bbs_code }}</option>
                        @endforeach
                      </select>
                    </div>    
                  @else
                    <div class="form-group">
                      <label for="state_id">{{ __('admin.admin.state') }} <!--<span style="color: red"> * </span>--></label>
                      <select class="form-control select2" name="state_id" id="state_id" required>
                        <option value="">{{ __('admin.common.select') }}</option>
                        @foreach ($states as $key => $item)
                          <option value="{{ $item->id }}" {{(old('state_id') == $item->id) ? 'selected' : ''}} >{{ $item->{'title_'. app()->getLocale()} }} - {{ $item->bbs_code }}</option>
                        @endforeach
                      </select>
                    </div>
                  @endif

                  @if ($edit)
                    <div class="form-group">
                      <label for="division_id">{{ __('admin.admin.division') }} <!--<span style="color: red"> * </span>--></label>
                      <select class="form-control select2" name="division_id" id="division_id" required>
                        <option value="">{{ __('admin.common.select') }}</option>
                        @foreach ($divisions as $key => $item)
                          <option value="{{ $item->id }}" {{($admin->division_id == $item->id) ? 'selected' : ''}} >{{ $item->{'title_'. app()->getLocale()} }} - {{ $item->bbs_code }}</option>
                        @endforeach
                      </select>
                    </div>    
                  @else
                    <div class="form-group">
                      <label for="division_id">{{ __('admin.admin.division') }} <!--<span style="color: red"> * </span>--></label>
                      <select class="form-control select2" name="division_id" id="division_id" required>
                        <option value="">{{ __('admin.common.select') }}</option>
                      </select>
                    </div>
                  @endif


                  @if ($edit)
                    <div class="form-group">
                      <label for="district_id">{{ __('admin.admin.district') }} <!--<span style="color: red"> * </span>--></label>
                      <select class="form-control select2" name="district_id" id="district_id">
                        <option value="">{{ __('admin.common.select') }}</option>
                        @foreach ($districts as $key => $item)
                          <option value="{{ $item->id }}" {{($admin->district_id == $item->id) ? 'selected' : ''}} >{{ $item->{'title_'. app()->getLocale()} }} - {{ $item->bbs_code }}</option>
                        @endforeach
                      </select>
                    </div>    
                  @else
                    <div class="form-group">
                      <label for="district_id">{{ __('admin.admin.district') }} <!--<span style="color: red"> * </span>--></label>
                      <select class="form-control select2" name="district_id" id="district_id">
                        <option value="">{{ __('admin.common.select') }}</option>
                      </select>
                    </div>
                  @endif


                  @if ($edit)
                    <div class="form-group">
                      <label for="upazila_id">{{ __('admin.admin.upazila') }} <!--<span style="color: red"> * </span>--></label>
                      <select class="form-control select2" name="upazila_id" id="upazila_id">
                        <option value="">{{ __('admin.common.select') }}</option>
                        @foreach ($upazilas as $key => $item)
                          <option value="{{ $item->id }}" {{($admin->upazila_id == $item->id) ? 'selected' : ''}} >{{ $item->{'title_'. app()->getLocale()} }} - {{ $item->bbs_code }}</option>
                        @endforeach
                      </select>
                    </div>    
                  @else
                    <div class="form-group">
                      <label for="upazila_id">{{ __('admin.admin.upazila') }} <!--<span style="color: red"> * </span>--></label>
                      <select class="form-control select2" name="upazila_id" id="upazila_id">
                        <option value="">{{ __('admin.common.select') }}</option>
                      </select>
                    </div>
                  @endif --}}

                  @if ($edit)
                    <div class="form-group">
                      <label for="forest_state_id">{{ __('admin.admin.forest_state') }} <!--<span style="color: red"> * </span>--></label>
                      <select class="form-control select2" name="forest_state_id" id="forest_state_id" required>
                        <option value="">{{ __('admin.common.select') }}</option>
                        @foreach ($forest_states as $key => $item)
                          <option value="{{ $item->id }}" {{($admin->forest_state_id == $item->id) ? 'selected' : ''}} >{{ $item->{'title_'. app()->getLocale()} }} - {{ $item->bbs_code }}</option>
                        @endforeach
                      </select>
                    </div>    
                  @else
                    <div class="form-group">
                      <label for="forest_state_id">{{ __('admin.admin.forest_state') }} <!--<span style="color: red"> * </span>--></label>
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
                      <label for="forest_division_id">{{ __('admin.admin.forest_division') }} <!--<span style="color: red"> * </span>--></label>
                      <select class="form-control select2" name="forest_division_id" id="forest_division_id">
                        <option value="">{{ __('admin.common.select') }}</option>
                        @foreach ($forest_divisions as $key => $item)
                          <option value="{{ $item->id }}" {{($admin->forest_division_id == $item->id) ? 'selected' : ''}} >{{ $item->{'title_'. app()->getLocale()} }} - {{ $item->bbs_code }}</option>
                        @endforeach
                      </select>
                    </div>    
                  @else
                    <div class="form-group">
                      <label for="forest_division_id">{{ __('admin.admin.forest_division') }} <!--<span style="color: red"> * </span>--></label>
                      <select class="form-control select2" name="forest_division_id" id="forest_division_id">
                        <option value="">{{ __('admin.common.select') }}</option>
                      </select>
                    </div>
                  @endif


                  @if ($edit)
                    <div class="form-group">
                      <label for="forest_range_id">{{ __('admin.admin.forest_range') }} <!--<span style="color: red"> * </span>--></label>
                      <select class="form-control select2" name="forest_range_id" id="forest_range_id">
                        <option value="">{{ __('admin.common.select') }}</option>
                        @foreach ($forest_ranges as $key => $item)
                          <option value="{{ $item->id }}" {{($admin->forest_range_id == $item->id) ? 'selected' : ''}} >{{ $item->{'title_'. app()->getLocale()} }} - {{ $item->bbs_code }}</option>
                        @endforeach
                      </select>
                    </div>    
                  @else
                    <div class="form-group">
                      <label for="forest_range_id">{{ __('admin.admin.forest_range') }} <!--<span style="color: red"> * </span>--></label>
                      <select class="form-control select2" name="forest_range_id" id="forest_range_id">
                        <option value="">{{ __('admin.common.select') }}</option>
                      </select>
                    </div>
                  @endif


                  @if ($edit)
                    <div class="form-group">
                      <label for="forest_beat_id">{{ __('admin.admin.forest_beat') }} <!--<span style="color: red"> * </span>--></label>
                      <select class="form-control select2" name="forest_beat_id" id="forest_beat_id">
                        <option value="">{{ __('admin.common.select') }}</option>
                        @foreach ($forest_beats as $key => $item)
                          <option value="{{ $item->id }}" {{($admin->forest_beat_id == $item->id) ? 'selected' : ''}} >{{ $item->{'title_'. app()->getLocale()} }} - {{ $item->bbs_code }}</option>
                        @endforeach
                      </select>
                    </div>    
                  @else
                    <div class="form-group">
                      <label for="forest_beat_id">{{ __('admin.admin.forest_beat') }} <!--<span style="color: red"> * </span>--></label>
                      <select class="form-control select2" name="forest_beat_id" id="forest_beat_id">
                        <option value="">{{ __('admin.common.select') }}</option>
                      </select>
                    </div>
                  @endif



                </div>
              </div>
              <!-- card start -->
            </div>
            <!-- col end -->


            <!-- col start -->
            <div class="col-4">
              <!-- card start -->
              <div class="card">
                
                <div class="card-header">
                  <!--<h3 class="card-title">DataTable with minimal features & hover style</h3>-->
                </div>

                <div class="card-body">

                  {{--  <div class="form-group">
                    <label for="sort">{{ __('admin.admin.sort') }}</label>
                    <input type="number" min="0" max="10000000" name="sort"
                     id="sort" placeholder="{{ __('admin.admin.sort') }}" 
                     value="{{ $edit?$admin->sort:old('sort') }}"
                     class="form-control" required>
                  </div>  --}}

                  {{-- <div class="form-group">
                    <label for="name">{{ __('admin.admin.name') }}</label>
                    <input type="text" name="name"
                     id="name" placeholder="{{ __('admin.admin.name') }}" 
                     value="{{ $edit?$admin->name:old('name') }}"
                     class="form-control" required>
                  </div> --}}

                  <div class="form-group">
                    <label for="title_en">{{ __('admin.admin.title_en') }}</label>
                    <input type="text" name="title_en"
                     id="title_en" placeholder="{{ __('admin.admin.title_en') }}" 
                     value="{{ $edit?$admin->title_en:old('title_en') }}"
                     class="form-control" required>
                  </div>

                  <div class="form-group">
                    <label for="title_bn">{{ __('admin.admin.title_bn') }}</label>
                    <input type="text" name="title_bn"
                     id="title_bn" placeholder="{{ __('admin.admin.title_bn') }}" 
                     value="{{ $edit?$admin->title_bn:old('title_bn') }}"
                     class="form-control" required>
                  </div>

                  {{-- <div class="form-group">
                    <label for="username">{{ __('admin.admin.username') }}</label>
                    <input type="text" name="username"
                     id="username" placeholder="{{ __('admin.admin.username') }}" 
                     value="{{ $edit?$admin->username:old('username') }}" {{$edit?'disabled':'required'}}
                     class="form-control" >
                  </div> --}}

                  @if (true)
                  <div class="form-group">
                    <label for="office_en">{{ __('admin.admin.office_en') }}</label>
                    <textarea rows="1" id="office_en" class="form-control" placeholder="{{ __('admin.admin.office_en') }}" 
                    name="office_en">{{ $edit?$admin->office_en:old('office_en') }}</textarea>
                  </div>

                  <div class="form-group">
                    <label for="office_bn">{{ __('admin.admin.office_bn') }}</label>
                    <textarea rows="1" id="office_bn" class="form-control" placeholder="{{ __('admin.admin.office_bn') }}" 
                    name="office_bn">{{ $edit?$admin->office_bn:old('office_bn') }}</textarea>
                  </div> 
                  @endif

                  

                  <div class="form-group">
                    <label for="address_en">{{ __('admin.admin.address_en') }}</label>
                    <textarea rows="1" id="address_en" class="form-control" placeholder="{{ __('admin.admin.address_en') }}" 
                    name="address_en" required>{{ $edit?$admin->address_en:old('address_en') }}</textarea>
                  </div>

                  <div class="form-group">
                    <label for="address_bn">{{ __('admin.admin.address_bn') }}</label>
                    <textarea rows="1" id="address_bn" class="form-control" placeholder="{{ __('admin.admin.address_bn') }}" 
                    name="address_bn" required>{{ $edit?$admin->address_bn:old('address_bn') }}</textarea>
                  </div>

                  

                </div>
              </div>
              <!-- card start -->
            </div>
            <!-- col end -->


            <!-- col start -->
            <div class="col-4">
              <!-- card start -->
              <div class="card">
                
                <div class="card-header">
                  <!--<h3 class="card-title">DataTable with minimal features & hover style</h3>-->
                </div>

                <div class="card-body">
                  
                  <div class="form-group">
                    <label for="email">{{ __('admin.admin.email') }}</label>
                    <input type="email" name="email"
                     id="email" placeholder="{{ __('admin.admin.email') }}" 
                     value="{{ $edit?$admin->email:old('email') }}" {{$edit?'required':'required'}}
                     class="form-control" >
                  </div>
                  
                  @if ($edit)
                  <div class="form-group">
                    <label for="status">{{ __('admin.admin.status') }}  </label><br>
                    <div class="icheck-primary d-inline">
                      <input type="radio" id="radioPrimary1" value="1" name="status" 
                      {{ ($admin->status == '1') ? 'checked' : '' }}>
                      <label for="radioPrimary1">
                        {{(app()->getLocale() == 'en') ? App\Models\Status::EN[1] : App\Models\Status::BN[1]}}
                      </label>
                    </div>
                    <div class="icheck-danger d-inline">
                      <input type="radio" id="radioPrimary2" value="0" name="status"
                      {{ ($admin->status == '0') ? 'checked' : '' }}>
                      <label for="radioPrimary2">
                        {{(app()->getLocale() == 'en') ? App\Models\Status::EN[0] : App\Models\Status::BN[0]}}
                      </label>
                    </div>
                  </div>    
                  @else
                  <div class="form-group">
                    <label for="status">{{ __('admin.admin.status') }}  </label><br>
                    <div class="icheck-primary d-inline">
                      <input type="radio" id="radioPrimary1" value="1" name="status" 
                      {{ (old('status') == '1') ? 'checked' : '' }}>
                      <label for="radioPrimary1">
                        {{(app()->getLocale() == 'en') ? App\Models\Status::EN[1] : App\Models\Status::BN[1]}}
                      </label>
                    </div>
                    <div class="icheck-danger d-inline">
                      <input type="radio" id="radioPrimary2" value="0" name="status"
                      {{ (old('status') == '0') ? 'checked' : '' }}>
                      <label for="radioPrimary2">
                        {{(app()->getLocale() == 'en') ? App\Models\Status::EN[0] : App\Models\Status::BN[0]}}
                      </label>
                    </div>
                  </div>    
                  @endif

                  <div class="form-group">
                    <label for="contact">{{ __('admin.admin.contact') }}</label> <span class="text-info">({{__('admin.common.en_lang_use')}}) </span>
                    <input type="text" name="contact"
                     id="contact" placeholder="{{ __('admin.admin.contact') }}" 
                     value="{{ $edit?$admin->contact:old('contact') }}"
                     class="form-control" >
                  </div> 

                  <div class="form-group">
                    <label for="password">{{ __('admin.admin.password') }}</label>
                    <input type="password" name="password"
                     id="password" placeholder="{{ __('admin.admin.password') }}" 
                     value="{{ $edit?'':old('password') }}" {{$edit?'disabled':'required'}}
                     class="form-control" >
                  </div>

                  <div class="form-group">
                    <label for="password_confirmation">{{ __('admin.admin.password_confirmation') }}</label>
                    <input type="password" name="password_confirmation"
                     id="password_confirmation" placeholder="{{ __('admin.admin.password_confirmation') }}" 
                     value="{{ $edit?'':old('password_confirmation') }}" {{$edit?'disabled':''}}
                     class="form-control" >
                  </div>
    

                  <div class="form-group">
                    <label for="thumb">
                        {{ __('admin.admin.thumb') }} <span
                                class="text-warning">({{__('admin.common.max_size')}})</span>
                    </label>
                    <input style="padding: 3px;" type="file" name="thumb" id="thumb"
                           class="form-control" {{$edit?'':''}}>
                    @if($edit && $admin->thumb)
                        <a target="_blank"
                           href="{{asset('storage/'.$admin->thumb)}}">Show</a>
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

  <script>
    $(document).ready(function () {
      $('.select2').select2();

      // $('#state_id').on('change', function(e){
      //   var state_id = e.target.value;
      //   var route = "{{route('get.division')}}/"+state_id;
      //   //console.log(state_id);
      //   $.get(route, function(data) {
      //     //console.log(data);
      //     $('#division_id').empty();
      //     $('#division_id').append('<option value="">{{ __('admin.common.select') }}</option>');
      //     $.each(data, function(index,data){
      //       $('#division_id').append('<option value="' + data.id + '">' + data.title_{{app()->getLocale()}} + ' - ' + data.bbs_code +  '</option>');
      //     });
      //   });
      // });

      // $('#division_id').on('change', function(e){
      //   var division_id = e.target.value;
      //   var route = "{{route('get.district')}}/"+division_id;
      //   //console.log(division_id);
      //   $.get(route, function(data) {
      //     //console.log(data);
      //     $('#district_id').empty();
      //     $('#district_id').append('<option value="">{{ __('admin.common.select') }}</option>');
      //     $.each(data, function(index,data){
      //       $('#district_id').append('<option value="' + data.id + '">' + data.title_{{app()->getLocale()}} + ' - ' + data.bbs_code +  '</option>');
      //     });
      //   });
      // });


      // $('#district_id').on('change', function(e){
      //   var district_id = e.target.value;
      //   var route = "{{route('get.upazila')}}/"+district_id;
      //   //console.log(district_id);
      //   $.get(route, function(data) {
      //     //console.log(data);
      //     $('#upazila_id').empty();
      //     $('#upazila_id').append('<option value="">{{ __('admin.common.select') }}</option>');
      //     $.each(data, function(index,data){
      //       $('#upazila_id').append('<option value="' + data.id + '">' + data.title_{{app()->getLocale()}} + ' - ' + data.bbs_code +  '</option>');
      //     });
      //   });
      // });


      // $('#upazila_id').on('change', function(e){
      //   var district_id = e.target.value;
      //   var route = "{{route('get.upazila.self')}}/"+district_id;
      //   //console.log(district_id);
      //   $.get(route, function(data) {
      //     $.each(data, function(index,data){
      //       @if (true)
      //         let title = data.title_en;
      //         let title_result = title.replace(" ", "-").toLocaleLowerCase();
      //         let email = title_result.replace("'","") + '@bforest.gov.bd';
      //         $('#email').val(email);

      //         $('#contact').val('01*********');
      //         $('#password').val('password');
      //         $('#password_confirmation').val('password');
      //         $('#radioPrimary1').attr('checked', 'checked');
      //       @endif
      //       $('#office_en').text(data.title_en);
      //       $('#office_bn').text(data.title_bn);
            
      //       $('#address_en').text(data.title_en);
      //       $('#address_bn').text(data.title_bn);

      //       $('#title_en').val(data.title_en);
      //       $('#title_bn').val(data.title_bn);
            
      //     });
      //   });
      // });


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
          $('#forest_range_id').append('<option value="">{{ __('admin.common.select') }}</option>');
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
          $('#forest_beat_id').append('<option value="">{{ __('admin.common.select') }}</option>');
          $.each(data, function(index,data){
            $('#forest_beat_id').append('<option value="' + data.id + '">' + data.title_{{app()->getLocale()}} + ' - ' + data.bbs_code +  '</option>');
          });
        });
      });


      // $('#forest_beat_id').on('change', function(e){
      //   var forest_range_id = e.target.value;
      //   var route = "{{route('get.forest_beat.self')}}/"+forest_range_id;
      //   //console.log(forest_range_id);
      //   $.get(route, function(data) {
      //     $.each(data, function(index,data){
      //       @if (true)
      //         let title = data.title_en;
      //         let title_result = title.replace(" ", "-").toLocaleLowerCase();
      //         let email = title_result.replace("'","") + '@bforest.gov.bd';
      //         $('#email').val(email);

      //         $('#contact').val('01*********');
      //         $('#password').val('password');
      //         $('#password_confirmation').val('password');
      //         $('#radioPrimary1').attr('checked', 'checked');
      //       @endif
      //       $('#office_en').text(data.title_en);
      //       $('#office_bn').text(data.title_bn);
            
      //       $('#address_en').text(data.title_en);
      //       $('#address_bn').text(data.title_bn);

      //       $('#title_en').val(data.title_en);
      //       $('#title_bn').val(data.title_bn);
            
      //     });
      //   });
      // });


      
    });
  </script>

@endsection


