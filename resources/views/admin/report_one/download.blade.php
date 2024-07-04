@php
    use App\Helper\EnglishToBanglaDate;
    use App\Helper\NumberToBanglaWord;
    use Rakibhstu\Banglanumber\NumberToBangla;
    $numto = new NumberToBangla();
@endphp

@php
  $edit = false;
  use App\Models\Admin;
  $authUser = Auth::guard('admin')->user()->load(['userType']);
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @if (count($parameters)>0)
        <title>
            {{ @$parameters['forest_division_'. app()->getLocale()] }} 
        {!! __('admin.report_one.view') !!} 
        {{(app()->getLocale() == 'en') ? @$parameters['title_date_view'] : EnglishToBanglaDate::dateFormatEnglishToBangla(@$parameters['title_date_view'])}}
        - {{ @$parameters['stock_type']->{'title_'. app()->getLocale()} }} - {{ @$parameters['budget']->{'title_'. app()->getLocale()} }}
        </title>
    @else
        <title>download</title>
    @endif

    <!-- Latest compiled and minified CSS -->
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"> --}}

    {{-- 
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script> --}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<style>
    table, td, th {
        border:1px solid black;
        border-collapse: collapse;
    }

    /* table {
        border-collapse: collapse;
    } */

    /* .page-break {
        page-break-after: always;
    } */
    
    /* @page { size: 1500pt 595pt;}

    @font-face {
        font-family: 'Siyam Rupali';
        src: url({{ asset('assets/fonts/SiyamRupali.ttf') }}) format('truetype');
    }

    body, table, td, th {
        font-family: 'Siyam Rupali';
    } */

    @page { size: auto;}
    
    th{
        color: black; font-weight: bolder
    }

    body {
        font-family: 'examplefont', sans-serif;
    }
</style>

</head>
<body>
    <div class="containe">
        <div class="row">
            <div class="col-md-12">
                @if (count($report_ones) > 0)
                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">

                            <!-- Table row -->
                            <div class="row">
                            <div>
                            <table class="table table-striped" id="example1">
                      <thead>
                      <tr>
                          <th style="text-align: center;" colspan="{{(count($categories) + count($categories) + count($categories)) + 3 + 3}}"> 
                            <br>
                            {!! __('admin.report_one.title') !!}
                            <br>
                           <br>
                           <br>
                            
                          </th>
                        </tr>
                      <tr>
                        <th colspan="1" style="min-width: 150px;">{{__('admin.report_one.from_date')}} : </th>
                        <th colspan="3">{{(app()->getLocale() == 'en') ? date('d-m-Y', strtotime($parameters['from_date'])) : EnglishToBanglaDate::dateFormatEnglishToBangla(date('d-m-Y', strtotime($parameters['from_date']))) }}
                        
                        </th>
                        <th colspan="1" style="min-width: 150px;">{{__('admin.report_one.to_date')}} : </th>
                        <th colspan="3">{{(app()->getLocale() == 'en') ? date('d-m-Y', strtotime($parameters['to_date'])) : EnglishToBanglaDate::dateFormatEnglishToBangla(date('d-m-Y', strtotime($parameters['to_date']))) }}
                      </tr>
                      <tr>
                        <th>{!! __('admin.report_one.forest_state') !!}</th>
                        <th>{!! __('admin.report_one.forest_division') !!}</th>
                        <th>{!! __('admin.report_one.forest_range') !!}</th>
                        <th>{!! __('admin.report_one.forest_beat') !!}</th>
                        <th>{!! __('admin.report_one.category') !!}</th>
                        <th>{!! __('admin.report_one.product') !!}</th>
                        <th>{!! __('admin.report_one.stock_in') !!}</th>
                      </tr>
                      </thead>
                      <tbody>
                        @php
                          usort($report_ones, function($a, $b) {
                          return strcmp($a['forest_state_'. app()->getLocale()], $b['forest_state_'. app()->getLocale()]);
                          });
                        @endphp
                        
                      @foreach ($report_ones as $key => $item)
                      <tr>
                        <td>
                          @if ($previousState && $item['forest_state_'. app()->getLocale()] === $previousState)
            	            @else
                            {{ $item['forest_state_'. app()->getLocale()] }}
                          @endif
                        </td>
            
                        <td>
                          @if ($previousDivision && $item['forest_division_'. app()->getLocale()] === $previousDivision)
            	            @else
                            {{ $item['forest_division_'. app()->getLocale()] }}
                          @endif
                        </td>
                  
                        <td>
                          @if ($previousRange && $item['forest_range_'. app()->getLocale()] === $previousRange)
            	            @else
                            {{ $item['forest_range_'. app()->getLocale()] }}
                          @endif
                        </td>

                        <td>
                          @if ($previousBeat && $item['forest_beat_'. app()->getLocale()] === $previousBeat)
            	            @else
                            {{ $item['forest_beat_'. app()->getLocale()] }}
                          @endif
                        </td>
            
                        <td>
                          @if ($previousCategory && $item['category_'. app()->getLocale()] === $previousCategory)
            	            @else
                            {{ $item['category_'. app()->getLocale()] }}
                          @endif
                        </td>
                        
                        <td>
                          @if ($previousProduct && $item['product_'. app()->getLocale()] === $previousProduct)
            	            @else
                            {{ $item['product_'. app()->getLocale()] }}
                          @endif
                        </td>
                        <td>{{(app()->getLocale() == 'en') ? $item['stock_in'] : NumberToBanglaWord::engToBn($item['stock_in'])}}</td>
                      </tr>
                      @php
                        $previousState = $item['forest_state_' . app()->getLocale()]; //Current State storing for comparison
                        $previousDivision = $item['forest_division_' . app()->getLocale()];
                        $previousRange = $item['forest_range_' . app()->getLocale()];
                        $previousBeat = $item['forest_beat_' . app()->getLocale()];
                        $previousCategory = $item['category_' . app()->getLocale()];
                        $previousProduct = $item['product_' . app()->getLocale()];
                      @endphp
                      @endforeach

                     
                      </tbody>
                    </table>
                            </div>
                            <!-- /.col -->
                            </div>
                            <!-- /.row -->
                        </div>
                        <!-- /.invoice -->
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                    </div><!-- /.container-fluid -->
                </section>
                <!-- /.content -->
                @endif

            </div>
        </div>
    </div>


    <script type="text/javascript">
        function ExportToPDF(){
            $("#btnExport").css("display","none");
            var css = '@page { size: landscape; }',
            head = document.head || document.getElementsByTagName('head')[0],
            style = document.createElement('style');

            style.type = 'text/css';
            style.media = 'print';

            if (style.styleSheet){
            style.styleSheet.cssText = css;
            } else {
            style.appendChild(document.createTextNode(css));
            }
            head.appendChild(style);
            window.print();
        }

        ExportToPDF();
  
    </script>

    @php
        Session::forget(['dreport_ones','dparameters','dfooter_report_ones']);
    @endphp
</body>
</html>