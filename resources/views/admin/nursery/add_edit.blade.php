@php
  
  use App\Helper\EnglishToBanglaDate;
  use App\Helper\NumberToBanglaWord;
  use Rakibhstu\Banglanumber\NumberToBangla;
  $numto = new NumberToBangla();
  
  $edit = false;
  if(!empty($nursery)){
     if($nursery->id !=''){
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
  @include('../admin.layouts.partials.breadcrumb', ['path1' => __('admin.menu.nursery'), 'route1' => route('admin.nursery') ])
@endsection

@section('content')

  <div class="content-wrapper">
    
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          
          <div class="col-sm-2">
            <a href="{{ route('admin.nursery') }}" class="btn btn-info form-control btn-add-new">
                <i class="fas fa-backward"></i> <span>{{ __('admin.common.back') }}</span>
            </a>
          </div>

          <div class="col-sm-10">
            <h1 class="text-info">
              @if ($edit)
                <i class="fas fa-bookmark"></i> {{ __('admin.common.update') }} {{ __('admin.menu.nursery') }} {{ __('admin.common.info') }}
              @else
                <i class="fas fa-bookmark"></i> {{ __('admin.common.add') }} {{ __('admin.menu.nursery') }} {{ __('admin.common.info') }}
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


<form class="form-edit-add" role="form" id="nursery_entry_form"
              action="{{!$edit ? route('admin.nursery.store') : route('admin.nursery.update', $nursery->id)}}"
              method="POST" enctype="multipart/form-data" autocomplete="off">
            <!-- PUT Method if we are editing -->
            @if($edit)
                <input type="hidden" name="id" value="{{$nursery->id}}">
                {{ method_field("PUT") }}
            @endif
            {{ csrf_field() }}

    <section class="content" style="margin-top: -10px;">
      <div class="container-fluid">
          <div class="row">
            {{-- <!-- col start -->
            <div class="col-12">
              <!-- card start -->
              <div class="card">
                
                <div class="card-header">
                  <!--<h3 class="card-title">DataTable with minimal features & hover style</h3>-->
                </div>


                

                <div class="card-body">
                  @if ($edit)
                    <div class="form-group">
                      <label for="admin_id">{{ __('admin.nursery.admin') }} <!--<span style="color: red"> * </span>--></label>
                      <select class="form-control select2" name="admin_id" id="admin_id" required>
                        <option value="">{{ __('admin.common.select') }}</option>
                        @foreach ($admins as $key => $item)
                          <option value="{{ $item->id }}" {{($nursery->admin_id == $item->id) ? 'selected' : ''}} >{{ $item->{'title_'. app()->getLocale()} }} - {{ (app()->getLocale() == 'en') ? $item->code : NumberToBanglaWord::engToBn($item->code) }}</option>
                        @endforeach
                      </select>
                    </div>    
                  @else
                    <div class="form-group">
                      <label for="admin_id">{{ __('admin.nursery.admin') }} <!--<span style="color: red"> * </span>--></label>
                      <select class="form-control select2" name="admin_id" id="admin_id" required>
                        <option value="">{{ __('admin.common.select') }}</option>
                        @foreach ($admins as $key => $item)
                          <option value="{{ $item->id }}" {{(old('admin_id') == $item->id) ? 'selected' : ''}} >{{ $item->{'title_'. app()->getLocale()} }} - {{ (app()->getLocale() == 'en') ? $item->code : NumberToBanglaWord::engToBn($item->code) }}</option>
                        @endforeach
                      </select>
                    </div>
                  @endif


                  @if ($edit)
                    <div class="form-group">
                      <label for="division_id">{{ __('admin.nursery.division') }} <!--<span style="color: red"> * </span>--></label>
                      <select class="form-control select2" name="division_id" id="division_id">
                        <option value="">{{ __('admin.common.select') }}</option>
                        @foreach ($divisions as $key => $item)
                          <option value="{{ $item->id }}" {{($nursery->division_id == $item->id) ? 'selected' : ''}} >{{ $item->{'title_'. app()->getLocale()} }} - {{ $item->bbs_code }}</option>
                        @endforeach
                      </select>
                    </div>    
                  @else
                    <div class="form-group">
                      <label for="division_id">{{ __('admin.nursery.division') }} <!--<span style="color: red"> * </span>--></label>
                      <select class="form-control select2" name="division_id" id="division_id">
                        <option value="">{{ __('admin.common.select') }}</option>
                        @foreach ($divisions as $key => $item)
                          <option value="{{ $item->id }}" {{(old('division_id') == $item->id) ? 'selected' : ''}} >{{ $item->{'title_'. app()->getLocale()} }} - {{ $item->bbs_code }}</option>
                        @endforeach
                      </select>
                    </div>
                  @endif


                  @if ($edit)
                    <div class="form-group">
                      <label for="district_id">{{ __('admin.nursery.district') }} <!--<span style="color: red"> * </span>--></label>
                      <select class="form-control select2" name="district_id" id="district_id">
                        <option value="">{{ __('admin.common.select') }}</option>
                        @foreach ($districts as $key => $item)
                          <option value="{{ $item->id }}" {{($nursery->district_id == $item->id) ? 'selected' : ''}} >{{ $item->{'title_'. app()->getLocale()} }} - {{ $item->bbs_code }}</option>
                        @endforeach
                      </select>
                    </div>    
                  @else
                    <div class="form-group">
                      <label for="district_id">{{ __('admin.nursery.district') }} <!--<span style="color: red"> * </span>--></label>
                      <select class="form-control select2" name="district_id" id="district_id">
                        <option value="">{{ __('admin.common.select') }}</option>
                      </select>
                    </div>
                  @endif


                  @if ($edit)
                    <div class="form-group">
                      <label for="upazila_id">{{ __('admin.nursery.upazila') }} <!--<span style="color: red"> * </span>--></label>
                      <select class="form-control select2" name="upazila_id" id="upazila_id">
                        <option value="">{{ __('admin.common.select') }}</option>
                        @foreach ($upazilas as $key => $item)
                          <option value="{{ $item->id }}" {{($nursery->upazila_id == $item->id) ? 'selected' : ''}} >{{ $item->{'title_'. app()->getLocale()} }} - {{ $item->bbs_code }}</option>
                        @endforeach
                      </select>
                    </div>    
                  @else
                    <div class="form-group">
                      <label for="upazila_id">{{ __('admin.nursery.upazila') }} <!--<span style="color: red"> * </span>--></label>
                      <select class="form-control select2" name="upazila_id" id="upazila_id">
                        <option value="">{{ __('admin.common.select') }}</option>
                      </select>
                    </div>
                  @endif



                </div>
              </div>
              <!-- card start -->
            </div>
            <!-- col end --> --}}


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
                      <label for="admin_id">{{ __('admin.nursery.admin') }} <!--<span style="color: red"> * </span>--></label>
                      <select class="form-control select2" name="admin_id" id="admin_id" required>
                        <option value="">{{ __('admin.common.select') }}</option>
                        @foreach ($admins as $key => $item)
                          <option value="{{ $item->id }}" {{($nursery->admin_id == $item->id) ? 'selected' : ''}} >{{ $item->{'office_'. app()->getLocale()} }} - {{ (app()->getLocale() == 'en') ? $item->code : NumberToBanglaWord::engToBn($item->code) }}</option>
                        @endforeach
                      </select>
                    </div>    
                  @else
                    <div class="form-group">
                      <label for="admin_id">{{ __('admin.nursery.admin') }} <!--<span style="color: red"> * </span>--></label>
                      <select class="form-control select2" name="admin_id" id="admin_id" required>
                        <option value="">{{ __('admin.common.select') }}</option>
                        @foreach ($admins as $key => $item)
                          <option value="{{ $item->id }}" {{(old('admin_id') == $item->id) ? 'selected' : ''}} >{{ $item->{'office_'. app()->getLocale()} }} - {{ (app()->getLocale() == 'en') ? $item->code : NumberToBanglaWord::engToBn($item->code) }}</option>
                        @endforeach
                      </select>
                    </div>
                  @endif

                  <div class="form-group">
                    <label for="title_en">{{ __('admin.nursery.title_en') }}</label>
                    <input type="text" name="title_en"
                     id="title_en" placeholder="{{ __('admin.nursery.title_en') }}" 
                     value="{{ $edit?$nursery->title_en:old('title_en') }}"
                     class="form-control" >
                  </div>

                  <div class="form-group">
                    <label for="title_bn">{{ __('admin.nursery.title_bn') }}</label>
                    <input type="text" name="title_bn"
                     id="title_bn" placeholder="{{ __('admin.nursery.title_bn') }}" 
                     value="{{ $edit?$nursery->title_bn:old('title_bn') }}"
                     class="form-control" >
                  </div>


                  <div class="form-group">
                    <label for="office_en">{{ __('admin.nursery.office_en') }}</label>
                    <textarea rows="1" id="office_en" class="form-control" placeholder="{{ __('admin.nursery.office_en') }}" 
                    name="office_en">{{ $edit?$nursery->office_en:old('office_en') }}</textarea>
                  </div>

                  <div class="form-group">
                    <label for="office_bn">{{ __('admin.nursery.office_bn') }}</label>
                    <textarea rows="1" id="office_bn" class="form-control" placeholder="{{ __('admin.nursery.office_bn') }}" 
                    name="office_bn">{{ $edit?$nursery->office_bn:old('office_bn') }}</textarea>
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
                    <label for="latitude">{{ __('admin.nursery.latitude') }}</label>
                    <input type="text" name="latitude"
                     id="latitude" placeholder="{{ __('admin.nursery.latitude') }}" 
                     value="{{ $edit?$nursery->latitude:old('latitude') }}" {{$edit?'':''}}
                     class="form-control" >
                  </div>

                  <div class="form-group">
                    <label for="longitude">{{ __('admin.nursery.longitude') }}</label>
                    <input type="text" name="longitude"
                     id="longitude" placeholder="{{ __('admin.nursery.longitude') }}" 
                     value="{{ $edit?$nursery->longitude:old('longitude') }}" {{$edit?'':''}}
                     class="form-control" >
                  </div>


                  <div class="form-group">
                    <label for="details_en">{{ __('admin.nursery.details_en') }}</label>
                    <textarea rows="1" id="details_en" class="form-control" placeholder="{{ __('admin.nursery.details_en') }}" 
                    name="details_en">{{ $edit?$nursery->details_en:old('details_en') }}</textarea>
                  </div>

                  <div class="form-group">
                    <label for="details_bn">{{ __('admin.nursery.details_bn') }}</label>
                    <textarea rows="1" id="details_bn" class="form-control" placeholder="{{ __('admin.nursery.details_bn') }}" 
                    name="details_bn">{{ $edit?$nursery->details_bn:old('details_bn') }}</textarea>
                  </div>

                  
                  <div class="form-group">
                    <label for="address_en">{{ __('admin.nursery.address_en') }}</label>
                    <input type="text" name="address_en"
                     id="address_en" placeholder="{{ __('admin.nursery.address_en') }}" 
                     value="{{ $edit?$nursery->address_en:old('address_en') }}"
                     class="form-control" >
                  </div>

                  <div class="form-group">
                    <label for="address_bn">{{ __('admin.nursery.address_bn') }}</label>
                    <input type="text" name="address_bn"
                     id="address_bn" placeholder="{{ __('admin.nursery.address_bn') }}" 
                     value="{{ $edit?$nursery->address_bn:old('address_bn') }}"
                     class="form-control" >
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
                  

                  {{-- <div class="form-group">
                    <label for="email">{{ __('admin.nursery.email') }}</label>
                    <input type="email" name="email"
                     id="email" placeholder="{{ __('admin.nursery.email') }}" 
                     value="{{ $edit?$nursery->email:old('email') }}" {{$edit?'':''}}
                     class="form-control" >
                  </div> --}}

                  @if ($edit)
                  <div class="form-group">
                    <label for="status">{{ __('admin.nursery.status') }}  </label><br>
                    <div class="icheck-primary d-inline">
                      <input type="radio" id="radioPrimary1" value="1" name="status" 
                      {{ ($nursery->status == '1') ? 'checked' : '' }}>
                      <label for="radioPrimary1">
                        {{(app()->getLocale() == 'en') ? App\Models\Status::EN[1] : App\Models\Status::BN[1]}}
                      </label>
                    </div>
                    <div class="icheck-danger d-inline">
                      <input type="radio" id="radioPrimary2" value="0" name="status"
                      {{ ($nursery->status == '0') ? 'checked' : '' }}>
                      <label for="radioPrimary2">
                        {{(app()->getLocale() == 'en') ? App\Models\Status::EN[0] : App\Models\Status::BN[0]}}
                      </label>
                    </div>
                  </div>    
                  @else
                  <div class="form-group">
                    <label for="status">{{ __('admin.nursery.status') }}  </label><br>
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
                    <label for="contact">{{ __('admin.nursery.contact') }}</label> <span class="text-info">({{__('admin.common.en_lang_use')}}) </span>
                    <input type="number" name="contact"
                     id="contact" placeholder="{{ __('admin.nursery.contact') }}" 
                     value="{{ $edit?$nursery->contact:old('contact') }}"
                     class="form-control" >
                  </div> 

                  {{-- <div class="form-group">
                    <label for="password">{{ __('admin.nursery.password') }}</label>
                    <input type="password" name="password"
                     id="password" placeholder="{{ __('admin.nursery.password') }}" 
                     value="{{ $edit?'':old('password') }}" {{$edit?'disabled':'required'}}
                     class="form-control" >
                  </div>

                  <div class="form-group">
                    <label for="password_confirmation">{{ __('admin.nursery.password_confirmation') }}</label>
                    <input type="password" name="password_confirmation"
                     id="password_confirmation" placeholder="{{ __('admin.nursery.password_confirmation') }}" 
                     value="{{ $edit?'':old('password_confirmation') }}" {{$edit?'disabled':''}}
                     class="form-control" >
                  </div> --}}
    

                  <div class="form-group">
                    <label for="thumb">
                        {{ __('admin.nursery.thumb') }} <span
                                class="text-warning">({{__('admin.common.max_size')}})</span>
                    </label>
                    <input style="padding: 3px;" type="file" name="thumb" id="thumb"
                           class="form-control" {{$edit?'':''}}>
                    @if($edit && $nursery->thumb)
                        <a target="_blank"
                           href="{{asset('storage/'.$nursery->thumb)}}">Show</a>
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

      $('#upazila_id').on('change', function(e){
        var district_id = e.target.value;
        var route = "{{route('get.upazila.self')}}/"+district_id;
        //console.log(district_id);
        $.get(route, function(data) {
          $.each(data, function(index,data){
            
            let title = data.title_en;
            let title_result = title.replace(" ", "-").toLocaleLowerCase();
            let email = title_result.replace("'","") + '@gamil.com';
            $('#email').val(email);

            $('#contact').val('01*********');
            $('#password').val('password');
            $('#password_confirmation').val('password');
            $('#radioPrimary1').attr('checked', 'checked');
            
            $('#office_en').text(data.title_en);
            $('#office_bn').text(data.title_bn);
            
            $('#address_en').text(data.title_en);
            $('#address_bn').text(data.title_bn);

            $('#title_en').val(data.title_en);
            $('#title_bn').val(data.title_bn);
            
          });
        });
      });


      
    });
  </script>

@endsection


