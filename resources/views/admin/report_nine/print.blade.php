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
        {!! __('admin.report_nine.view') !!} 
        {{(app()->getLocale() == 'en') ? @$parameters['title_date_view'] : EnglishToBanglaDate::dateFormatEnglishToBangla(@$parameters['title_date_view'])}}
        - {{ @$parameters['stock_type']->{'title_'. app()->getLocale()} }} - {{ @$parameters['budget']->{'title_'. app()->getLocale()} }}
        </title>
    @else
        <title>download</title>
    @endif

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<style>
    table, td, th {
        border:1px solid black;
        border-collapse: collapse;
    }

    @page { size: 1500pt 595pt;}

</style>

</head>
<body>
    <div class="containe">
        <div class="row">
            <div class="col-md-12">
                @if (count($report_nines) > 0)
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
                                    <th style="text-align: center;font-weight: bolder" colspan="{{(count($categories) + count($categories) + count($categories)) + 3 + 3}}"> 
                                        
                                        {{ @$parameters['forest_division_'. app()->getLocale()] }} 
                                        <br> 
                                        {!! __('admin.report_nine.title') !!}
                                        <br>
                                        {{(app()->getLocale() == 'en') ? @$parameters['title_date_view'] : EnglishToBanglaDate::dateFormatEnglishToBangla(@$parameters['title_date_view'])}}
                                        <br>
                                        {{ @$parameters['stock_type']->{'title_'. app()->getLocale()} }} - {{ @$parameters['budget']->{'title_'. app()->getLocale()} }}
                                        
                                    </th>
                                    </tr>
                                
                                    {{-- <tr>
                                    <th colspan="{{(count($categories) + count($categories) + count($categories)) + 3 + 3}}"> </th>
                                    </tr> --}}

                                    <tr>
                                    <th colspan="3"></th>
                                    <th style="text-align: center;font-weight: bolder" colspan="{{count($categories) + 1}}">{{(app()->getLocale() == 'en') ? __('admin.report_nine.opening_stock') . @$parameters['from_date_pre_view'] : EnglishToBanglaDate::dateFormatEnglishToBangla(@$parameters['from_date_pre_view']) . __('admin.report_nine.opening_stock')  }}</th>
                                    <th style="text-align: center;font-weight: bolder" colspan="{{count($categories) + 1}}">{{(app()->getLocale() == 'en') ? __('admin.report_nine.current_sale') . @$parameters['from_date_view'] .'-'. @$parameters['to_date_view']  : EnglishToBanglaDate::dateFormatEnglishToBangla(@$parameters['from_date_view']) .' - ' . EnglishToBanglaDate::dateFormatEnglishToBangla(@$parameters['to_date_view']) . __('admin.report_nine.current_sale')  }}</th>
                                    <th style="text-align: center;font-weight: bolder" colspan="{{count($categories) + 1}}">{{(app()->getLocale() == 'en') ? __('admin.report_nine.closing_stock') . @$parameters['to_date_view'] : EnglishToBanglaDate::dateFormatEnglishToBangla(@$parameters['to_date_view']) . __('admin.report_nine.closing_stock')  }}</th>
                                    </tr>

                                    {{-- <tr>
                                    <th colspan="{{(count($categories) + count($categories) + count($categories)) + 3 + 3}}"> </th>
                                    </tr> --}}

                                    <tr>
                                    {{-- <th colspan="3"></th> --}}
                                    <th style="text-align: center">{{__('admin.report_nine.district')}}</th>
                                    <th style="text-align: center">{{__('admin.report_nine.upazila')}}</th>
                                    <th style="text-align: center">{{__('admin.report_nine.forest_beat')}}</th>
                                    <!-- Opening Stock-->
                                    @foreach ($categories as $key=>$category)
                                    <th style="text-align: center">{!!@$category->{'title_'. app()->getLocale()} !!}</th>
                                    @endforeach
                                    <th style="text-align: center">{{__('admin.common.total')}}</th>
                                    <!-- Current Stock-->
                                    @foreach ($categories as $key=>$category)
                                    <th style="text-align: center">{!!@$category->{'title_'. app()->getLocale()} !!}</th>
                                    @endforeach
                                    <th style="text-align: center">{{__('admin.common.total')}}</th>
                                    <!-- Closing Stock-->
                                    @foreach ($categories as $key=>$category)
                                    <th style="text-align: center">{!!@$category->{'title_'. app()->getLocale()} !!}</th>
                                    @endforeach
                                    <th style="text-align: center">{{__('admin.common.total')}}</th>
                                    </tr>

                                </thead>
                                <tbody>

                                    @foreach ($report_nines as $key=>$item)
                                    <tr>
                                    <td style="text-align: left">{{ $item['district_'. app()->getLocale()] }}</td>
                                    <td style="text-align: left">{{ $item['upazila_'. app()->getLocale()] }}</td>
                                    <td style="text-align: left">{{ $item['forest_beat_'. app()->getLocale()] }}</td>
                                    
                                    @php
                                        $opening_stock_total = [];
                                    @endphp
                                    @foreach ($item['opening_stock'] as $item1)
                                        @php 
                                        $opening_stock_total[] = $item1;
                                        @endphp
                                        <td style="text-align: right;">
                                        {{(app()->getLocale() == 'en') ? $item1 : NumberToBanglaWord::engToBn($item1)}}
                                        </td>
                                    @endforeach
                                    <td style="text-align: right;">
                                        {{(app()->getLocale() == 'en') ? array_sum($opening_stock_total) : NumberToBanglaWord::engToBn(array_sum($opening_stock_total))}}
                                    </td>
                                    @php
                                        $current_sale_total = [];
                                    @endphp
                                    @foreach ($item['current_sale'] as $item1)
                                        @php 
                                        $current_sale_total[] = $item1;
                                        @endphp
                                        <td style="text-align: right;">
                                        {{(app()->getLocale() == 'en') ? $item1 : NumberToBanglaWord::engToBn($item1)}}
                                        </td>
                                    @endforeach
                                    <td style="text-align: right;">
                                        {{(app()->getLocale() == 'en') ? array_sum($current_sale_total) : NumberToBanglaWord::engToBn(array_sum($current_sale_total))}}
                                    </td>
                                    @php
                                        $closing_stock_total = [];
                                    @endphp
                                    @foreach ($item['closing_stock'] as $item1)
                                        @php 
                                        $closing_stock_total[] = $item1;
                                        @endphp
                                        <td style="text-align: right;">
                                        {{(app()->getLocale() == 'en') ? $item1 : NumberToBanglaWord::engToBn($item1)}}
                                        </td>
                                    @endforeach
                                        <td style="text-align: right;">
                                            {{(app()->getLocale() == 'en') ? array_sum($closing_stock_total) : NumberToBanglaWord::engToBn(array_sum($closing_stock_total))}}
                                        </td>
                                    </tr>
                                    @endforeach

                                    <tr>
                                    @foreach ($footer_report_nines as $key=>$item)
                                        <th colspan="3" style="text-align: center;font-weight: bolder">{{__('admin.common.total')}}</th>
                                        @php
                                        $opening_stock_total = [];
                                        @endphp
                                        @foreach ($item['opening_stock'] as $item1)
                                        @php 
                                            $opening_stock_total[] = $item1;
                                        @endphp
                                        <th style="text-align: right;font-weight: bolder">
                                            {{(app()->getLocale() == 'en') ? $item1 : NumberToBanglaWord::engToBn($item1)}}
                                        </th>
                                        @endforeach
                                        <th style="text-align: right;font-weight: bolder">
                                        {{(app()->getLocale() == 'en') ? array_sum($opening_stock_total) : NumberToBanglaWord::engToBn(array_sum($opening_stock_total))}}
                                        </th>
                                        @php
                                        $current_sale_total = [];
                                        @endphp
                                        @foreach ($item['current_sale'] as $item1)
                                        @php 
                                            $current_sale_total[] = $item1;
                                        @endphp
                                        <th style="text-align: right;font-weight: bolder">
                                            {{(app()->getLocale() == 'en') ? $item1 : NumberToBanglaWord::engToBn($item1)}}
                                        </th>
                                        @endforeach
                                        <th style="text-align: right;font-weight: bolder">
                                        {{(app()->getLocale() == 'en') ? array_sum($current_sale_total) : NumberToBanglaWord::engToBn(array_sum($current_sale_total))}}
                                        </th>
                                        @php
                                        $closing_stock_total = [];
                                        @endphp
                                        @foreach ($item['closing_stock'] as $item1)
                                        @php 
                                            $closing_stock_total[] = $item1;
                                        @endphp
                                        <th style="text-align: right;font-weight: bolder">
                                            {{(app()->getLocale() == 'en') ? $item1 : NumberToBanglaWord::engToBn($item1)}}
                                        </th>
                                        @endforeach
                                        <th style="text-align: right;font-weight: bolder">
                                        {{(app()->getLocale() == 'en') ? array_sum($closing_stock_total) : NumberToBanglaWord::engToBn(array_sum($closing_stock_total))}}
                                        </th>
                                    @endforeach
                                    </tr>
                                    
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
        Session::forget(['report_nines','parameters','footer_report_nines']);
    @endphp
</body>
</html>