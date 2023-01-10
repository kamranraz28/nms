@php
  $edit = false;
  if(!empty($content_category)){
     if($content_category->id !=''){
         $edit=true;
     }
  }
@endphp


@extends('admin.layouts.master')
@section('title')
  {{ $sitesetting->{'title_'. app()->getLocale()} }} :: {{__('admin.menu.content_category')}}
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
  .select2-container--default .select2-selection--single .select2-selection__rendered {
    color: #444;
    line-height: 18px;
  }
</style>

@endsection

@section('breadcrumb')
  @include('../admin.layouts.partials.breadcrumb', ['path1' => __('admin.menu.content_category'), 'route1' => route('admin.content_category') ])
@endsection

@section('content')

  <div class="content-wrapper">
    
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          
          <div class="col-sm-2">
            <a href="{{ route('admin.content_category') }}" class="btn btn-info form-control btn-add-new">
                <i class="fas fa-backward"></i> <span>{{ __('admin.common.back') }}</span>
            </a>
          </div>

          <div class="col-sm-10">
            <h1 class="text-info">
              @if ($edit)
                <i class="fas fa-bookmark"></i> {{ __('admin.common.update') }} {{ __('admin.menu.content_category') }} {{ __('admin.common.info') }}
              @else
                <i class="fas fa-bookmark"></i> {{ __('admin.common.add') }} {{ __('admin.menu.content_category') }} {{ __('admin.common.info') }}
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


<form class="form-edit-add" role="form" id="content_category_entry_form"
              action="{{!$edit ? route('admin.content_category.store') : route('admin.content_category.update', $content_category->id)}}"
              method="POST" enctype="multipart/form-data" autocomplete="off">
            <!-- PUT Method if we are editing -->
            @if($edit)
                <input type="hidden" name="id" value="{{$content_category->id}}">
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

                  <div class="form-group">
                    <label for="sort">{{ __('admin.content_category.sort') }}</label>
                    <input type="number" min="0" max="10000000" name="sort"
                     id="sort" placeholder="{{ __('admin.content_category.sort') }}" 
                     value="{{ $edit?$content_category->sort:old('sort') }}"
                     class="form-control" required>
                  </div>

                  <div class="form-group">
                    <label for="name">{{ __('admin.content_category.name') }}</label>
                    <input type="text" name="name"
                     id="name" placeholder="{{ __('admin.content_category.name') }}" 
                     value="{{ $edit?$content_category->name:old('name') }}"
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
                  



                  {{-- <div class="form-group">
                    <label for="thumb">
                        {{ __('admin.content_category.thumb') }} <span
                                class="text-warning">(Maximum file size: 100 KB)</span>
                    </label>
                    <input style="padding: 3px;" type="file" name="thumb" id="thumb"
                           class="form-control">
                    @if($edit && $content_category->thumb)
                        <a target="_blank"
                           href="{{asset('storage/'.$content_category->thumb)}}">Show</a>
                    @endif
                  </div> --}}


                  @if ($edit)
                  <div class="form-group">
                    <label for="position">{{ __('admin.content_category.position') }}</label>
                    <select class="select2" style="width: 100%" name="position">
                      <option value="header" {{($content_category->position == "header") ? 'selected' : ''}} >Header</option>
                      <option value="footer" {{($content_category->position == "footer") ? 'selected' : ''}}>Footer</option>
                      <option value="body" {{($content_category->position == "body") ? 'selected' : ''}}>Body</option>
                    </select>
                  </div>
                  @else
                  <div class="form-group">
                    <label for="position">{{ __('admin.content_category.position') }}</label>
                    <select class="select2" style="width: 100%" name="position">
                      <option value="" >{{__('admin.common.select')}}</option>
                      <option value="header" {{(old('position') == 'header') ? 'selected' : ''}} >Header</option>
                      <option value="footer" {{(old('position') == 'footer') ? 'selected' : ''}}>Footer</option>
                      <option value="body" {{(old('position') == 'body') ? 'selected' : ''}}>Body</option>
                    </select>
                  </div>   
                  @endif

                  @if ($edit)
                  <div class="form-group">
                    <label for="parent_id">{{ __('admin.content_category.parent_id') }}</label>
                    <select class="select2" style="width: 100%" name="parent_id">
                      <option value="" >{{__('admin.common.select')}}</option>
                      @foreach ($contentCategories as $key=>$item)
                        <option value="{{$key}}" {{($key == $content_category->parent_id) ? 'selected' : ''}} >{{$item}}</option>
                      @endforeach
                    </select>
                  </div>  
                  @else
                  <div class="form-group">
                    <label for="parent_id">{{ __('admin.content_category.parent_id') }}</label>
                    <select class="select2" style="width: 100%" name="parent_id">
                      <option value="" >{{__('admin.common.select')}}</option>
                      @foreach ($contentCategories as $key=>$item)
                        <option value="{{$key}}" >{{$item}}</option>
                      @endforeach
                    </select>
                  </div>   
                  @endif

                  <br><br>



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
    $(document).ready(function() {
      $('.select2').select2();
    });
  </script>


@endsection


