@php
  $edit = false;
  if(!empty($role)){
     if($role->id !=''){
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
  @include('../admin.layouts.partials.breadcrumb', ['path1' => __('admin.menu.role'), 'route1' => route('admin.role') ])
@endsection

@section('content')

  <div class="content-wrapper">
    
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          
          <div class="col-sm-2">
            <a href="{{ route('admin.role') }}" class="btn btn-info form-control btn-add-new">
                <i class="fas fa-backward"></i> <span>{{ __('admin.common.back') }}</span>
            </a>
          </div>

          <div class="col-sm-10">
            <h1 class="text-info">
              @if ($edit)
                <i class="fas fa-bookmark"></i> {{ __('admin.common.update') }} {{ __('admin.menu.permission') }} {{ __('admin.common.info') }}
              @else
                <i class="fas fa-bookmark"></i> {{ __('admin.common.add') }} {{ __('admin.menu.permission') }} {{ __('admin.common.info') }}
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


<form class="form-edit-add" role="form" id="role_entry_form"
              action="{{!$edit ? route('admin.role.permission.update') : route('admin.role.permission.update', $role->id)}}"
              method="POST" enctype="multipart/form-data" autocomplete="off">
            <!-- PUT Method if we are editing -->
            @if($edit)
                <input type="hidden" name="id" value="{{$role->id}}">
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
                    <label for="name">{{ __('admin.role.name') }}</label>
                    <input type="text" name="name"
                     id="name" placeholder="{{ __('admin.role.name') }}" 
                     value="{{ $edit?$role->name:old('name') }}"
                     class="form-control" disabled>
                  </div>


                  <div class="form-group">

                    <label for="Permissions">Permissions</label>
                    <div class="custom-control custom-checkbox">
                      <input class="custom-control-input" type="checkbox" id="checkPermissionAll" value="1" >
                      <label for="checkPermissionAll" class="custom-control-label">All</label>
                    </div>
                    <hr>
                    @foreach ($permissions as $key => $permission)
                        <div class="row">
                            <div class="col-3">
                                <div class="custom-control custom-checkbox">
                                  <input class="custom-control-input" type="checkbox" id="{{ $key }}Management" 
                                  value="{{ $permission['group_name'] }}" onclick="checkPermissionByGroup('role-{{ $key }}-management-checkbox', this)"
                                  {{(App\Models\Role::roleHasParentPermissions($role->id, $permission['group_name'])) ? 'checked' : '' }} >
                                  <label for="{{ $key }}Management" class="custom-control-label">{{ $permission['group_name'] }}</label>
                                </div>
                            </div>

                            <div class="col-9 role-{{ $key }}-management-checkbox">
                               
                                @foreach ($permission['childs'] as $ckey => $child)
                                  <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" value="{{ $child['id'] }}"
                                    onclick="checkSinglePermission('role-{{ $key }}-management-checkbox', '{{ $key }}Management', {{ count($permission['childs']) }})" 
                                    name="permissions[]" id="checkPermission{{ $child['id'] }}"
                                    {{(App\Models\Role::roleHasChildPermissions($role->id, $permission['group_name'], $child['name'])) ? 'checked' : '' }}>
                                    <label for="checkPermission{{ $child['id'] }}" class="custom-control-label">{{ $child['name'] }}</label>
                                  </div>
                                @endforeach
                                <br>

                            </div>
                        </div>
                    @endforeach
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
    /**
    * Check all the permissions
    */
    $("#checkPermissionAll").click(function(){
        if($(this).is(':checked')){
            // check all the checkbox
            $('input[type=checkbox]').prop('checked', true);
        }else{
            // un check all the checkbox
            $('input[type=checkbox]').prop('checked', false);
        }
    });

    function checkPermissionByGroup(className, checkThis){
      const groupIdName = $("#"+checkThis.id);
      const classCheckBox = $('.'+className+' input');

      if(groupIdName.is(':checked')){
            classCheckBox.prop('checked', true);
        }else{
            classCheckBox.prop('checked', false);
        }
      implementAllChecked();
    }

    function checkSinglePermission(groupClassName, groupID, countTotalPermission) {
      const classCheckbox = $('.'+groupClassName+ ' input');
      const groupIDCheckBox = $("#"+groupID);

      // if there is any occurance where something is not selected then make selected = false
      if($('.'+groupClassName+ ' input:checked').length == countTotalPermission){
          groupIDCheckBox.prop('checked', true);
      }else{
          groupIDCheckBox.prop('checked', false);
      }
      implementAllChecked();
    }

    function implementAllChecked() {
      const countPermissions = {{ count($all_permissions) }};
      const countPermissionGroups = {{ count($permission_groups) }};
      //  console.log((countPermissions + countPermissionGroups));
      //  console.log($('input[type="checkbox"]:checked').length);
        if($('input[type="checkbox"]:checked').length >= (countPermissions + countPermissionGroups)){
          $("#checkPermissionAll").prop('checked', true);
      }else{
          $("#checkPermissionAll").prop('checked', false);
      }
    }


</script>

@endsection


