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
        {!! __('admin.report_eight.view') !!} 
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
                @if (count($report_eights) > 0)
                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">

                            <!-- Table row -->
                            <div class="row">
                            <div>
                                <table class="page-break">
                                <thead>
                      <tr>
                        <th colspan="7"> 
                          <p style="text-align: center;">
                            {{ @$parameters['forest_division_'. app()->getLocale()] }} 
                            <br> 
                            {!! __('admin.report_eight.title') !!}
                            <br>
                            {{(app()->getLocale() == 'en') ? $parameters['title_date_view'] : EnglishToBanglaDate::dateFormatEnglishToBangla($parameters['title_date_view'])}}
                          </p> 
                        </th>
                      </tr>
                      
                      <tr>
                        <th>{{__('admin.report_eight.district')}}</th>
                        <th>{{__('admin.report_eight.upazila')}}</th>
                        <th>{{__('admin.report_eight.forest_beat')}}</th>
                        <th>
                          {{(app()->getLocale() == 'en') ? $parameters['from_date_pre_view'] : EnglishToBanglaDate::dateFormatEnglishToBangla($parameters['from_date_pre_view'])}} <br>
                          {!! __('admin.report_eight.pre_stock') !!}
                        </th>
                        <th>
                          {{(app()->getLocale() == 'en') ? $parameters['from_date'] : EnglishToBanglaDate::dateFormatEnglishToBangla($parameters['from_date'])}} - <br>
                          {{(app()->getLocale() == 'en') ? $parameters['to_date'] : EnglishToBanglaDate::dateFormatEnglishToBangla($parameters['to_date'])}} <br>
                          {!! __('admin.report_eight.current_total_stock_in1') !!} 
                        </th>
                        <th>
                          {{(app()->getLocale() == 'en') ? $parameters['till_date_view'] : EnglishToBanglaDate::dateFormatEnglishToBangla($parameters['till_date_view'])}} <br>
                          {!! __('admin.report_eight.current_total_stock_out') !!}
                        </th>
                        <th>
                          {{(app()->getLocale() == 'en') ? $parameters['to_date_view'] : EnglishToBanglaDate::dateFormatEnglishToBangla($parameters['to_date_view'])}} <br>
                          {!! __('admin.report_eight.current_total_stock') !!}
                        </th>
                      </tr>
                      </thead>
                      <tbody>
                      @php
                          usort($report_eights, function($a, $b) {
                          return strcmp($a['district_'. app()->getLocale()], $b['district_'. app()->getLocale()]);
                          });
                        @endphp
                        @foreach ($report_eights as $key => $item)
                        <tr>
                        <td>
                            @if ($previousDistrict && $item['district_'. app()->getLocale()] === $previousDistrict)
                              @else
                              {{ $item['district_'. app()->getLocale()] }}
                            @endif
                        </td>  
                        <td>
                            @if ($previousUpazila && $item['upazila_'. app()->getLocale()] === $previousUpazila)
                              @else
                              {{ $item['upazila_'. app()->getLocale()] }}
                            @endif
                        </td>
                          
                        <td>
                          @if ($previousBeat && $item['forest_beat_'. app()->getLocale()] === $previousBeat)
            	            @else
                            {{ $item['forest_beat_'. app()->getLocale()] }}
                          @endif
                        </td>
                          <td>{{(app()->getLocale() == 'en') ? $item['pre_stock'] : NumberToBanglaWord::engToBn($item['pre_stock'])}}</td>
                          <td>{{(app()->getLocale() == 'en') ? $item['current_total_stock_in'] : NumberToBanglaWord::engToBn($item['current_total_stock_in'])}}</td>
                          <td>{{(app()->getLocale() == 'en') ? $item['current_total_stock_out'] : NumberToBanglaWord::engToBn($item['current_total_stock_out'])}}</td>
                          <td>{{(app()->getLocale() == 'en') ? $item['current_total_stock'] : NumberToBanglaWord::engToBn($item['current_total_stock'])}}</td>
                        </tr>

                        @php
                        $previousDistrict = $item['district_' . app()->getLocale()]; //Current State storing for comparison
                        $previousUpazila = $item['upazila_' . app()->getLocale()];
                        $previousBeat = $item['forest_beat_' . app()->getLocale()];
                      @endphp
                        @endforeach
                        
                        @if ($authUser->userType->default_role != Admin::DEFAULT_ROLE_LIST[6])
                          @foreach ($forest_district_data as $key => $item)
                            <tr style="font-weight: 600!important;">
                              <td></td>
                              <td> {!! __('admin.report_eight.total_stock_division_wise') !!} </td>
                              <td>{{ $item['forest_division_'. app()->getLocale()] }}</td>
                              <td>{{(app()->getLocale() == 'en') ? $item['pre_stock_arr_total'] : NumberToBanglaWord::engToBn($item['pre_stock_arr_total'])}}</td>
                              <td>{{(app()->getLocale() == 'en') ? $item['current_total_stock_in_arr'] : NumberToBanglaWord::engToBn($item['current_total_stock_in_arr'])}}</td>
                              <td>{{(app()->getLocale() == 'en') ? $item['current_total_stock_out_arr'] : NumberToBanglaWord::engToBn($item['current_total_stock_out_arr'])}}</td>
                              <td>{{(app()->getLocale() == 'en') ? $item['current_total_stock_arr'] : NumberToBanglaWord::engToBn($item['current_total_stock_arr'])}}</td>
                            </tr>
                          @endforeach
                        @endif
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
        Session::forget(['dreport_eights','dparameters','dfooter_report_eights']);
    @endphp
</body>
</html>