@php
    use App\Helper\EnglishToBanglaDate;
    use App\Helper\NumberToBanglaWord;
    use Rakibhstu\Banglanumber\NumberToBangla;
    $numto = new NumberToBangla();
@endphp

@extends('admin.layouts.master')
@section('title')
  {{__('admin.menu.site')}} :: {{__('admin.menu.dashboard')}}
@endsection




@section('styles')
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/sweetalert2/sweetalert2.css') }}">

@endsection

@section('breadcrumb')
  @include('../admin.layouts.partials.breadcrumb', ['path1' => __('admin.menu.purchase'), 'route1' => route('admin.purchase') ])
@endsection


@section('content')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    

    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          
          <div class="col-sm-2">
            
            <a href="{{ route('admin.purchase.create') }}" class="btn btn-info form-control btn-add-new">
                <i class="fas fa-plus"></i> <span>{{ __('admin.common.add') }}</span>
            </a>
          </div>

          <div class="col-sm-10">
            <h1 class="text-info">
              <i class="fas fa-bookmark"></i> {{ __('admin.menu.purchase') }}
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


    <!-- Main content -->
    <section class="content" style="margin-top: -10px;">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">{{ __('admin.common.list') }}
                
                
                </h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-hover">
                  <thead>
                  <tr>
                    {{-- <th>#</th> --}}
                    <th>{{__('admin.purchase.action')}}</th>
                    <th>{{__('admin.purchase.code')}}</th>
                    <th>{{__('admin.purchase.approval_fault')}}</th>
                    <th>{{__('admin.purchase.approval_status')}}</th>
                    <th>{{__('admin.purchase.stock_type')}}</th>
                    <th>{{__('admin.purchase.forest_beat')}}</th>
                    
                    <th>{{__('admin.purchase.vch_date')}}</th>
                    <th>{{__('admin.purchase.approved')}}</th>
                    <th>{{__('admin.purchase.approved_by')}}</th>
                  </tr>
                  </thead>
                  <tbody>
                  @foreach ($purchases as $key => $purchase)
                  <tr style="{{ ($purchase->disapprove_status == 1) ? 'background-color: orange;' : '' }}">
                    {{-- <td>{{(app()->getLocale() == 'en') ? $key+1 : NumberToBanglaWord::engToBn($key+1)}}</td> --}}
                    <td>
                      {{-- @can('approval', app('App\Models\Purchase'))
                        <a href="{{ ($purchase->approved) ? route('admin.purchase.approval', $purchase->id) : route('admin.purchase.approval', $purchase->id) }}" class="btn btn-xs  {{($purchase->approved) ? 'btn-info unapprove1' : 'btn-default approve1' }}"><i class="fas fa-check"></i></a>
                      @endcan --}}

                      @can('update', app('App\Models\Purchase'))
                        <a href="{{ route('admin.purchase.edit', $purchase->id) }}" class="btn btn-xs btn-primary"><i class="fas fa-edit" title="Click to edit"></i></a>
                      @endcan
                      
                      @can('delete', app('App\Models\Purchase'))
                      <a href="{{ route('admin.purchase.delete', $purchase->id) }}" href1="{{ route('admin.purchase.delete', [$purchase->id,1]) }}" class="btn btn-xs btn-danger delete" title="Click to delete">
                          <i class="fas fa-trash-alt"></i>
                      </a>                      
                      @endcan
                      
                      @can('view', app('App\Models\Purchase'))
                      <a href="{{ route('admin.purchase.view', $purchase->id) }}" href1="{{ route('admin.purchase.view', [$purchase->id]) }}" class="btn btn-xs btn-primary" title="Click to view"><i class="fas fa-eye"></i></a>
                      @endcan
                      
                      @can('print', app('App\Models\Purchase'))
                      <a target="_blank" href="/purchase/disapproval/{{$purchase->id}}" class="btn btn-xs btn-secondary" title="Click to print"><i class="fas fa-print"></i></a>
                      @endcan
                    </td>
                    
                    
                    <td>{{(app()->getLocale() == 'en') ? $purchase->code : NumberToBanglaWord::engToBn($purchase->code)}}</td>
                    
                    <td style="text-align:center; vertical-align: middle;">
                      @if($purchase->disapprove_status == 0)
                          <a class="disapprove" data-id="{{ $purchase->id }}" href="{{ route('admin.purchase.disapproval', $purchase->id) }}">
                              <i class="fas fa-check text-success fa-2x"></i>
                          </a>
                      @else
                          <a class="disapprove_change" data-id="{{ $purchase->id }}" href="{{ route('admin.purchase.disapproval_change', $purchase->id) }}">
                              <i class="fas fa-times text-danger fa-2x"></i>
                          </a>
                          <br>
                          <span class="reason btn btn-xs btn-primary" data-id="{{ $purchase->id }}" data-range_comment="{{ $purchase->range_comment }}" data-acf_comment="{{ $purchase->acf_comment }}" data-dfo_comment="{{ $purchase->dfo_comment }}">
                              <i class="fas fa-eye fa-x"></i>
                          </span>
                      @endif
                  </td>




                    <td>
                      
                      @if($purchase->app_status == 1)
                        @can('approval', app('App\Models\Purchase'))
                          <a href="{{ ($purchase->app_status == 1) ? route('admin.purchase.approval', $purchase->id) : 
                            route('admin.purchase.approval', $purchase->id) }}" class="btn btn-xs  
                            {{($purchase->app_status == 1) ? 'btn btn-danger approve1' : 'btn-info unapprove1' }}">
                          {{__('admin.status.ro_pending')}}
                          </a>
                        @endcan
                      @elseif($purchase->app_status == 2)
                        @can('approval', app('App\Models\Purchase'))
                          <a href="{{ ($purchase->app_status == 2) ? route('admin.purchase.approval', $purchase->id) : 
                          route('admin.purchase.approval', $purchase->id) }}" class="btn btn-xs  
                          {{($purchase->app_status == 2) ? 'btn-warning approve1' : 'btn-info unapprove1' }}">
                          
                          {{__('admin.status.acf_pending')}}
                          </a>
                        @endcan
                      @elseif($purchase->app_status == 3)
                        @can('approval', app('App\Models\Purchase'))
                          <a href="{{ ($purchase->app_status == 3) ? route('admin.purchase.approval', $purchase->id) : 
                          route('admin.purchase.approval', $purchase->id) }}" class="btn btn-xs  
                          {{($purchase->app_status == 3) ? 'btn-primary approve1' : 'btn-info unapprove1' }}">
                          
                          {{__('admin.status.dfo_pending')}}
                          </a>
                        @endcan
                      @elseif($purchase->app_status == 4)
                        @can('approval', app('App\Models\Purchase'))
                          <a href="{{ ($purchase->app_status == 4) ? route('admin.purchase.approval', $purchase->id) : 
                          route('admin.purchase.approval', $purchase->id) }}" class="btn btn-xs  
                          {{($purchase->app_status == 4) ? 'btn-success approve1' : 'btn-info unapprove1' }}">
                          {{__('admin.status.final_approved')}}
                          </a>
                        @endcan
                      @else
                        @can('approval', app('App\Models\Purchase'))
                          <a href="{{ ($purchase->app_status == 4) ? route('admin.purchase.approval', $purchase->id) : 
                          route('admin.purchase.approval', $purchase->id) }}" class="btn btn-xs  
                          {{($purchase->app_status == 4) ? 'btn-sucess approve1' : 'btn-info unapprove1' }}">
                          {{__('admin.status.final_approved')}}
                          </a>
                        @endcan
                      @endif

                    </td>
                    
                    
                    <td>{{ $purchase->stockType->{'title_'. app()->getLocale()} }}</td>
                    <td>{{ @$purchase->forestBeat->{'title_'. app()->getLocale()} }} {{(app()->getLocale() == 'en') }}</td>
                   
                    <td>{{(app()->getLocale() == 'en') ? date('d-m-Y', strtotime($purchase->vch_date)) : EnglishToBanglaDate::dateFormatEnglishToBangla(date('d-m-Y', strtotime($purchase->vch_date)))}}</td>
                    <td style="color: {{($purchase->approved) ? 'green':'red'}}" >{{(app()->getLocale() == 'en') ? App\Models\Status::EN1[$purchase->approved] : App\Models\Status::BN1[$purchase->approved]}}</td>
                    <td>{{ @$purchase->approvedBy->{'title_'. app()->getLocale()} }}</td>

                      
                  </tr>  
                  @endforeach
                  
                  </tbody>
                </table>
                

              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
            <div>
              {{ $purchases->links() }}
            </div>
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>




@endsection



@section('scripts')

<script>

    $(document).on('click', '.disapprove', function (e) {
        e.preventDefault();

        var clickedElement = $(this);
        var idValue = clickedElement.data('id');

        @php
          $user = Auth::user();
          $roleId = $user ? $user->role_id : null;
        @endphp

        if ({{ in_array($roleId, [9, 7, 8]) ? 'true' : 'false' }}) {
            
            Swal.fire({
              title: '{{ __('admin.purchase.disapprove_reason') }}',

                html:
                    '<form id="myForm" action="/purchaseStore" method="post" >' +
                    '   @csrf' +
                    '   <div class="form-group">' +
                    '       <label for="inputValue">{{ __('admin.purchase.write_reason') }}</label>' +
                    '       <input type="text" class="form-control" id="inputValue" name="inputValue" placeholder="{{ __('admin.purchase.write_reason') }}">' +
                    '       <input type="hidden" id="inputId" name="inputId" value="' + idValue + '">' +
                    '   </div>' +
                    '   <button type="submit" class="btn btn-primary">{{ __('admin.purchase.submit') }}</button>' +
                    '</form>',
                showCancelButton: true,
                cancelButtonText: '{{ __('admin.purchase.cancel') }}',
                showConfirmButton: false,
                focusConfirm: false,
            });
        } else {
           
            var message = '{{ __('admin.common.error_eligible_msg') }}';
            Swal.fire({
                text: message,
                icon: 'info',
                confirmButtonText: '{{ __('admin.purchase.cancel') }}',
            });
        }
    });
</script>

<script>

    $(document).on('click', '.disapprove_change', function (e) {
        e.preventDefault();

        var clickedElement = $(this);
        var idValue = clickedElement.data('id');

        @php
          $user = Auth::user();
          $roleId = $user ? $user->role_id : null;
        @endphp

        if ({{ in_array($roleId, [9, 7, 8]) ? 'true' : 'false' }}) {
            
            Swal.fire({
              title: '{{ __('admin.purchase.change_position') }}',
                html:
                    '<form id="myForm" action="/purchaseStore_change" method="post" >' +
                    '   @csrf' +
                    '   <div class="form-group">' +
    
                    '       <input type="hidden" id="inputId" name="inputId" value="' + idValue + '">' +
                    '   </div>' +
                    '   <button type="submit" class="btn btn-primary">{{__('admin.common.approve1')}}</button>' +
                    '</form>',
                showCancelButton: true,
                cancelButtonText: '{{ __('admin.purchase.cancel') }}',
                showConfirmButton: false,
                focusConfirm: false,
            });
        } else {
           
          var message = '{{ __('admin.common.error_eligible_msg') }}';
            Swal.fire({
                text: message,
                icon: 'info',
                confirmButtonText: '{{ __('admin.purchase.cancel') }}',
            });
        }
    });
</script>


<script>
    $(document).on('click', '.reason', function (e) {
    e.preventDefault();

    var clickedElement = $(this);
    var rangeComment = clickedElement.data('range_comment');
    var acfComment = clickedElement.data('acf_comment');
    var dfoComment = clickedElement.data('dfo_comment');
    
    Swal.fire({
        title: '',
        html:
            '<form id="myForm" action="/purchaseStore_change" method="post" >' +
            '   @csrf' +
            (dfoComment ? 
                '   <div class="form-group">' +
                '       <label for="dfoComment">{{__('admin.purchase.dfo')}}</label><br>' +
                '       <span style="height: 100px; width: 300px; display: inline-block; border: none;">' + dfoComment + '</span>' +
                '   </div>' 
                : ''
            ) +
            
            (acfComment ? 
                '   <div class="form-group">' +
                '       <label for="acfComment">{{__('admin.purchase.acf')}}</label><br>' +
                '       <span style="height: 100px; width: 300px; display: inline-block; border: none;">' + acfComment + '</span>' +
                '   </div>' 
                : ''
            ) +

            (rangeComment ? 
                '   <div class="form-group">' +
                '       <label for="rangeComment">{{__('admin.purchase.range')}}</label><br>' +
                '       <span style="height: 100px; width: 300px; display: inline-block; border: none;">' + rangeComment + '</span>' +
                '   </div>' 
                : ''
            ) +
            
            '</form>',
        showCancelButton: true,
        cancelButtonText: '{{ __('admin.purchase.cancel') }}',
        showConfirmButton: false,
        focusConfirm: false,
    });
});



</script>

<script src="{{asset('assets/plugins/sweetalert2/sweetalert2.js')}}"></script>
<script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/plugins/jszip/jszip.min.js') }}"></script>

<script src="{{ asset('assets/plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('assets/plugins/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>


<script>



  $(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false, "ordering": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });
</script>

<script>
  $(document).ready(function () {
    $(document, 'td').on('click', '.delete', function (e) {
      e.preventDefault();
      //console.log($(this).attr('href'))
      var route = $(this).attr('href');
      var route1 = $(this).attr('href1');
      Swal.fire({
        title: "{{__('admin.common.confirm_msg')}}",
        showDenyButton: true,
        showCancelButton: true,
        cancelButtonText: "{{__('admin.common.cancel')}}",
        confirmButtonText: "{{__('admin.common.sdel')}}",
        denyButtonText: "{{__('admin.common.fdel')}}",
      }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
          //Soft Delete
          window.location.href = route;
        } else if (result.isDenied) {
          //Force Delete
          window.location.href = route1;
        }
      })
    });
  }); 
</script>

<script>
  $(document).ready(function () {
    $(document).on('click', '.approve1', function (e) {
        e.preventDefault();
        //console.log($(this).attr('href'))
        var route = $(this).attr('href');
        Swal.fire({
          title: "{{__('admin.common.approve_msg1')}}",
          showDenyButton: false,
          showCancelButton: true,
          cancelButtonText: "{{__('admin.common.cancel')}}",
          confirmButtonText: "{{__('admin.common.approve1')}}",
          denyButtonText: "{{__('admin.common.not_save')}}",
          confirmButtonColor: '#0c890c',
        }).then((result) => {
          /* Read more about isConfirmed, isDenied below */
          if (result.isConfirmed) {
            window.location.href = route;
            //console.log(route);
          } else if (result.isDenied) {
            Swal.fire("{{__('admin.common.not_save1')}}", '', 'info')
          }
        })
    });



    $(document).on('click', '.disapprove1', function (e) {
        e.preventDefault();
        //console.log($(this).attr('href'))
        var route = $(this).attr('href');
        Swal.fire({
          title: "{{__('admin.common.approve_msg1')}}",
          showDenyButton: false,
          showCancelButton: true,
          cancelButtonText: "{{__('admin.common.cancel')}}",
          confirmButtonText: "{{__('admin.common.approve1')}}",
          denyButtonText: "{{__('admin.common.not_save')}}",
          confirmButtonColor: '#0c890c',
        }).then((result) => {
          /* Read more about isConfirmed, isDenied below */
          if (result.isConfirmed) {
            window.location.href = route;
            //console.log(route);
          } else if (result.isDenied) {
            Swal.fire("{{__('admin.common.not_save1')}}", '', 'info')
          }
        })
    });

    $(document).on('click', '.unapprove1', function (e) {
        e.preventDefault();
        //console.log($(this).attr('href'))
        var route = $(this).attr('href');
        Swal.fire({
          title: "{{__('admin.common.unapprove_msg1')}}",
          showDenyButton: false,
          showCancelButton: true,
          cancelButtonText: "{{__('admin.common.cancel')}}",
          confirmButtonText: "{{__('admin.common.unapprove1')}}",
          denyButtonText: "{{__('admin.common.not_save')}}",
          confirmButtonColor: 'tomato',
        }).then((result) => {
          /* Read more about isConfirmed, isDenied below */
          if (result.isConfirmed) {
            window.location.href = route;
            //console.log(route);
          } else if (result.isDenied) {
            Swal.fire("{{__('admin.common.not_save1')}}", '', 'info')
          }
        })
    });
  }); 
</script>

@endsection


