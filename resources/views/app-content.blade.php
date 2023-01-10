@extends('layouts.app')

@section('title')
  {{__('admin.menu.site')}}
@endsection

@section('styles')

@endsection

@section('content')

<section id="ts-service-area" class="ts-service-area pb-0" style="min-height: auto">
  <div class="container">
    <div class="row text-center">
        <div class="col-12">
          {{-- <h2 class="section-title">Why do we use it?</h2> --}}
          {{-- <h3 class="section-sub-title">What We Do</h3> --}}
          <h3 class="section-sub-title">{{ $content->sub_title }}</h3>
        </div>
        
    </div>
    <!--/ Title row end -->

    <div class="row" style="margin-bottom:10%!important">
        <div class="col-md-12">
            {!! $content->body !!}
        </div>
    </div>

  </div>
  <!--/ Container end -->
</section><!-- Service end -->
@endsection

@section('scripts')
@endsection


