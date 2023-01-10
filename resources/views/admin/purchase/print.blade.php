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
            
            <a href="{{ route('admin.purchase') }}" class="btn btn-info form-control btn-add-new">
              <i class="fas fa-backward"></i> <span>{{ __('admin.common.back') }}</span>
            </a>
          </div>

          <div class="col-sm-10">
            <h1 class="text-info">
              <i class="fas fa-bookmark"></i> {{ __('admin.purchase.view') }}
            </h1>
          </div>

        </div>
      </div>
    </section>


    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">


            <!-- Main content -->
            <div class="invoice p-3 mb-3">
              <!-- title row -->
              <!-- info row -->
              <div class="row invoice-info">
                <div class="col-sm-3 invoice-col">
                  <b>{{__('admin.purchase.code')}} : <br> </b> {{(app()->getLocale() == 'en') ? $purchase->code : NumberToBanglaWord::engToBn($purchase->code)}}<br>
                </div>

                <div class="col-sm-3 invoice-col">
                  <b>{{__('admin.purchase.forest_beat')}} : <br> </b> {{ $purchase->forestBeat->{'title_'. app()->getLocale()} }}<br>
                </div>
                <!-- /.col -->
                <div class="col-sm-3 invoice-col">
                  <b>{{__('admin.purchase.vch_date')}} : <br> </b> {{(app()->getLocale() == 'en') ? date('d-m-Y', strtotime($purchase->vch_date)) : EnglishToBanglaDate::dateFormatEnglishToBangla(date('d-m-Y', strtotime($purchase->vch_date)))}}<br>
                </div>
                <!-- /.col -->
                <div class="col-sm-3 invoice-col">
                  {{-- <b>{{__('admin.purchase.approved')}} :</b> {{(app()->getLocale() == 'en') ? App\Models\Status::EN1[$purchase->approved] : App\Models\Status::BN1[$purchase->approved]}}<br> --}}
                  <b>{{__('admin.purchase.approved_by')}} : <br> </b> {{ @$purchase->approvedBy->{'title_'. app()->getLocale()} }}<br>
                </div>
                <!-- /.col -->
                <br><br>
              </div>

              <div class="row invoice-info">
                <br><br>
              </div>
              <!-- /.row -->

              <!-- Table row -->
              <div class="row">
                <div class="col-12 table-responsive">
                  <table class="table table-striped">
                    <thead>
                    <tr>
                      <th>#</th>
                      <th>{{__('admin.purchase.stock_type')}}</th>
                      <th>{{__('admin.purchase.price_type')}}</th>
                      <th>{{__('admin.purchase.category')}}</th>
                      <th>{{__('admin.purchase.product')}}</th>
                      <th>{{__('admin.purchase.quantity')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                      @php
                          $quantity = 0;
                      @endphp
                    @foreach ($purchase_etails as $key => $item)
                    <tr>
                      <td>{{(app()->getLocale() == 'en') ? $key+1 : NumberToBanglaWord::engToBn($key+1)}}</td>
                      <td>{{ $item->stockType->{'title_'. app()->getLocale()} }}</td>
                      <td>{{ $item->priceType->{'title_'. app()->getLocale()} }}</td>
                      <td>{{ $item->category->{'title_'. app()->getLocale()} }}</td>
                      <td>{{ $item->product->{'title_'. app()->getLocale()} }}</td>
                      <td>{{(app()->getLocale() == 'en') ? $item->quantity : NumberToBanglaWord::engToBn($item->quantity)}}</td>
                      
                      @php
                          $quantity += $item->quantity;
                      @endphp
                    
                    </tr>
                    @endforeach
                    
                    </tbody>
                  </table>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->

              <div class="row">
                <!-- accepted payments column -->
                <div class="col-6">
                </div>
                <!-- /.col -->
                <div class="col-6">
                  {{-- <p class="lead">Amount Due 2/22/2014</p> --}}
                  <div class="table-responsive">
                    <table class="table">
                      <tr>
                        <th style="width:{{(app()->getLocale() == 'en')? '72%' : '59%'}}">{{__('admin.purchase.total')}} :</th>
                        <td>
                          {{ (app()->getLocale() == 'en') ? $quantity : NumberToBanglaWord::engToBn($quantity) }}
                        </td>
                      </tr>
                    </table>
                  </div>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->

              <!-- this row will not appear when printing -->
              <div class="row no-print">
                <div class="col-12">
                </div>
              </div>
            </div>
            <!-- /.invoice -->
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>




@endsection



@section('scripts')

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
  window.addEventListener("load", window.print());
</script>

@endsection


