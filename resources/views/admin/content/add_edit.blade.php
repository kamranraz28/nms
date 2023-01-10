@php
  $edit = false;
  if(!empty($content)){
     if($content->id !=''){
         $edit=true;
     }
  }
@endphp


@extends('admin.layouts.master')
@section('title')
{{ $sitesetting->{'title_'. app()->getLocale()} }} :: {{__('admin.menu.content')}}
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
  @include('../admin.layouts.partials.breadcrumb', ['path1' => __('admin.menu.content'), 'route1' => route('admin.content') ])
@endsection

@section('content')

  <div class="content-wrapper">
    
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          
          <div class="col-sm-2">
            <a href="{{ route('admin.content') }}" class="btn btn-info form-control btn-add-new">
                <i class="fas fa-backward"></i> <span>{{ __('admin.common.back') }}</span>
            </a>
          </div>

          <div class="col-sm-10">
            <h1 class="text-info">
              @if ($edit)
                <i class="fas fa-bookmark"></i> {{ __('admin.common.update') }} {{ __('admin.menu.content') }} {{ __('admin.common.info') }}
              @else
                <i class="fas fa-bookmark"></i> {{ __('admin.common.add') }} {{ __('admin.menu.content') }} {{ __('admin.common.info') }}
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


<form class="form-edit-add" role="form" id="content_entry_form"
              action="{{!$edit ? route('admin.content.store') : route('admin.content.update', $content->id)}}"
              method="POST" enctype="multipart/form-data" autocomplete="off">
            <!-- PUT Method if we are editing -->
            @if($edit)
                <input type="hidden" name="id" value="{{$content->id}}">
                {{ method_field("PUT") }}
            @endif
            {{-- csrf_field() --}}

            @csrf

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
                    <label for="sort">{{ __('admin.content.sort') }}</label>
                    <input type="number" min="0" max="10000000" name="sort"
                     id="sort" placeholder="{{ __('admin.content.sort') }}" 
                     value="{{ $edit?$content->sort:old('sort') }}"
                     class="form-control" required>
                  </div>

                  <div class="form-group">
                    <label for="name">{{ __('admin.content.name') }}</label>
                    <input type="text" name="name"
                     id="name" placeholder="{{ __('admin.content.name') }}" 
                     value="{{ $edit?$content->name:old('name') }}"
                     class="form-control" >
                  </div>

                  <div class="form-group">
                    <label for="sub_title">{{ __('admin.content.sub_title') }}</label>
                    <input type="text" name="sub_title"
                     id="sub_title" placeholder="{{ __('admin.content.sub_title') }}" 
                     value="{{ $edit?$content->sub_title:old('sub_title') }}"
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
                    <label for="slug">{{ __('admin.content.slug') }}</label>
                    <input type="text" name="slug"
                     id="slug" placeholder="{{ __('admin.content.slug') }}" 
                     value="{{ $edit?$content->slug:old('slug') }}"
                     class="form-control" >
                  </div>



                  @if ($edit)
                  <div class="form-group">
                    <label for="version_id">{{ __('admin.content.version_id') }}</label>
                    <select class="select2" style="width: 100%" name="version_id">
                      <option value="" >{{__('admin.common.select')}}</option>
                      @foreach ($versions as $id => $version)
                        <option value="{{$id}}" {{(@$content->version->id == $id) ? 'selected' : ''}} >{{$version}}</option>
                      @endforeach
                    </select>
                  </div>    
                  @else
                  <div class="form-group">
                    <label for="version_id">{{ __('admin.content.version_id') }}</label>
                    <select class="select2" style="width: 100%" name="version_id">
                      <option value="" >{{__('admin.common.select')}}</option>
                      @foreach ($versions as $id => $version)
                        <option value="{{$id}}" {{(old('version_id') == $id) ? 'selected' : ''}} >{{$version}}</option>
                      @endforeach
                    </select>
                  </div>    
                  @endif

                  
                  @if ($edit)
                  <div class="form-group">
                    <label for="content_category_id">{{ __('admin.content.content_category_id') }}</label>
                    <select class="select2" style="width: 100%" name="content_category_id">
                      <option value="" >{{__('admin.common.select')}}</option>
                      @foreach ($contentCategories as $id => $contentCategory)
                        <option value="{{$id}}" {{($content->contentCategory->id == $id) ? 'selected' : ''}} >{{$contentCategory}}</option>
                      @endforeach
                    </select>
                  </div>    
                  @else
                  <div class="form-group">
                    <label for="content_category_id">{{ __('admin.content.content_category_id') }}</label>
                    <select class="select2" style="width: 100%" name="content_category_id">
                      <option value="" >{{__('admin.common.select')}}</option>
                      @foreach ($contentCategories as $id => $contentCategory)
                        <option value="{{$id}}" {{(old('content_category_id') == $id) ? 'selected' : ''}} >{{$contentCategory}}</option>
                      @endforeach
                    </select>
                  </div>
                  @endif

                  <div class="form-group">
                    <label for="thumb">
                        {{ __('admin.content.thumb') }} <span
                                class="text-warning">(Maximum file size: 100 KB)</span>
                    </label>
                    <input style="padding: 3px;" type="file" name="thumb" id="thumb"
                           class="form-control">
                    @if($edit && $content->thumb)
                        <a target="_blank"
                           href="{{asset('storage/'.$content->thumb)}}">Show</a>
                    @endif
                  </div>



                  {{-- <div class="form-group">
                    <button type="submit" class="btn btn-info btn-sm form-control save"> 
                      <i class="fas fa-save"></i> {{ __('admin.common.save') }}
                    </button>
                  </div> --}}




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
                    <label for="body">{{ __('admin.content.body') }}</label>
                    <textarea id="page_contents" class="form-control" placeholder="{{ __('admin.content.body') }}" 
                    name="body">{{ $edit?$content->body:old('body') }}</textarea>
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
  <script src="{{ asset('assets/plugins/tinymce/tinymce.min.js') }}"></script>

  {{-- <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script> --}}

  

  <script>
    $(document).ready(function() {
      $('.select2').select2();
    });

    tinymce.init({
      selector: 'textarea#page_contents',
      init_instance_callback : function(editor) {
          var freeTiny = document.querySelector('.tox .tox-notification--in');
          var freeTinyCompany = document.querySelector('.tox .tox-statusbar a');
          freeTiny.style.display = 'none';
          freeTinyCompany.style.display = 'none';
      },
      plugins: 'print preview powerpaste casechange importcss tinydrive searchreplace autolink autosave save directionality advcode visualblocks visualchars fullscreen image link media mediaembed template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists checklist wordcount tinymcespellchecker a11ychecker imagetools textpattern noneditable help formatpainter permanentpen pageembed charmap tinycomments mentions quickbars linkchecker emoticons advtable export',
      menubar: 'file edit view insert format tools table tc help',
      toolbar: ' undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist checklist | forecolor backcolor casechange permanentpen formatpainter removeformat | pagebreak | charmap emoticons | insertfile image media pageembed template link anchor codesample | fullscreen  preview save print | a11ycheck ltr rtl | showcomments addcomment',
  
      codesample_languages: [
        {text: 'HTML/XML', value: 'markup'},
        {text: 'JavaScript', value: 'javascript'},
        {text: 'CSS', value: 'css'},
        {text: 'PHP', value: 'php'},
        {text: 'Ruby', value: 'ruby'},
        {text: 'Python', value: 'python'},
        {text: 'Java', value: 'java'},
        {text: 'C', value: 'c'},
        {text: 'C#', value: 'csharp'},
        {text: 'C++', value: 'cpp'}
      ],
      toolbar_sticky: true,
      image_advtab: true,
      importcss_append: true,
      height: 300,
      image_caption: true,
      quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
      noneditable_noneditable_class: 'mceNonEditable',
      toolbar_mode: 'sliding',
      contextmenu: 'link image imagetools table',
      paste_data_images: true,
      images_upload_handler: imageUploadHandler,
      skin: 'oxide',
      content_css: 'default',
      content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }',
      relative_urls: true,
      remove_script_host: true,
      convert_urls: true,
      image_title: true,
      automatic_uploads: true,
      file_picker_types: 'image',
    });



    function imageUploadHandler(blobInfo, success, failure, progress) {
        let formData;
        formData = new FormData();
        formData.append('file', blobInfo.blob(), blobInfo.filename());
        $.ajax({
            type: 'POST',
            url: "{{ route('admin.contentPage.imageUpload') }}",

            //url: EDIT?'image-upload':'0/image-upload',
            contentType: false,
            data: formData,
            processData: false,

            success: function (response) {
              console.log(response);
                success('{{asset('storage')}}/' + response.location);
            },
            error: function (error) {
              console.log(error);
                if (error.status === 403) {
                    failure('HTTP Error: ' + error.status, {remove: true});
                    return;
                }
                if (error.status < 200 || error.status >= 300) {
                    failure('HTTP Error: ' + error.status);
                    return;
                }
            },
            complete: function () {
                return;
            }
        });
    }


    
  </script>

@endsection


