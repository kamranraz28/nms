@extends('layouts.site')
@section('title')
  {{__('admin.menu.site')}}
@endsection


@section('content-top')
  @include('layouts.partials.header')
  {{-- @include('layouts.partials.banner') --}}
@endsection

@section('content-bottom')
  {{-- @include('layouts.partials.product') --}}
  {{-- @include('layouts.partials.service') --}}
  {{-- @include('layouts.partials.course') --}}
  {{-- @include('layouts.partials.partner') --}}

  @php
      $data = ['Ringku', 'Saim'];
  @endphp

  @include('layouts.partials.body',['data'=>$data])
@endsection