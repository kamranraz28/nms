@extends('layouts.app')

@section('title')
  {{__('admin.menu.site')}}
@endsection

@section('styles')

@endsection

@section('content')

@if ($content)
<section id="ts-service-area" class="ts-service-area pb-0" style="min-height: auto">
  <div class="container">
    <div class="row text-center">
        <div class="col-12">
          <h3 class="section-sub-title">{{$content->sub_title}}</h3>
        </div>
        
    </div>
    <!--/ Title row end -->

    <div class="row" style="margin-bottom:10%!important">
        <div class="col-md-12">
          {!! $content->body !!}
        </div>

        {{-- <div class="col-md-12">
          <h4>Our Mission</h4>  
          <p>
          Our mission is to integrate services to create a digital platform for the thousands of people on the move. We intend to provide them with a digital identity, review and verify their qualifications, and provide e-learning and training to prepare them for the digital economy.
          </p>

          <h4>Our Vision</h4>  
          <p>
              Our vision is to engage people on the move all over the world in a localized common digital platform. Our plan is to engage them using modern digital tools and help them to attain a sustainable livelihood.
          </p>
      </div><div class="col-md-12">
        <h4>Our Mission</h4>  
        <p>
        Our mission is to integrate services to create a digital platform for the thousands of people on the move. We intend to provide them with a digital identity, review and verify their qualifications, and provide e-learning and training to prepare them for the digital economy.
        </p>

        <h4>Our Vision</h4>  
        <p>
            Our vision is to engage people on the move all over the world in a localized common digital platform. Our plan is to engage them using modern digital tools and help them to attain a sustainable livelihood.
        </p>
    </div><div class="col-md-12">
            <h4>Our Mission</h4>  
            <p>
            Our mission is to integrate services to create a digital platform for the thousands of people on the move. We intend to provide them with a digital identity, review and verify their qualifications, and provide e-learning and training to prepare them for the digital economy.
            </p>

            <h4>Our Vision</h4>  
            <p>
                Our vision is to engage people on the move all over the world in a localized common digital platform. Our plan is to engage them using modern digital tools and help them to attain a sustainable livelihood.
            </p>
        </div><div class="col-md-12">
            <h4>Our Mission</h4>  
            <p>
            Our mission is to integrate services to create a digital platform for the thousands of people on the move. We intend to provide them with a digital identity, review and verify their qualifications, and provide e-learning and training to prepare them for the digital economy.
            </p>

            <h4>Our Vision</h4>  
            <p>
                Our vision is to engage people on the move all over the world in a localized common digital platform. Our plan is to engage them using modern digital tools and help them to attain a sustainable livelihood.
            </p>
        </div><div class="col-md-12">
            <h4>Our Mission</h4>  
            <p>
            Our mission is to integrate services to create a digital platform for the thousands of people on the move. We intend to provide them with a digital identity, review and verify their qualifications, and provide e-learning and training to prepare them for the digital economy.
            </p>

            <h4>Our Vision</h4>  
            <p>
                Our vision is to engage people on the move all over the world in a localized common digital platform. Our plan is to engage them using modern digital tools and help them to attain a sustainable livelihood.
            </p>
        </div><div class="col-md-12">
            <h4>Our Mission</h4>  
            <p>
            Our mission is to integrate services to create a digital platform for the thousands of people on the move. We intend to provide them with a digital identity, review and verify their qualifications, and provide e-learning and training to prepare them for the digital economy.
            </p>

            <h4>Our Vision</h4>  
            <p>
                Our vision is to engage people on the move all over the world in a localized common digital platform. Our plan is to engage them using modern digital tools and help them to attain a sustainable livelihood.
            </p>
        </div><div class="col-md-12">
          <h4>Our Mission</h4>  
          <p>
          Our mission is to integrate services to create a digital platform for the thousands of people on the move. We intend to provide them with a digital identity, review and verify their qualifications, and provide e-learning and training to prepare them for the digital economy.
          </p>

          <h4>Our Vision</h4>  
          <p>
              Our vision is to engage people on the move all over the world in a localized common digital platform. Our plan is to engage them using modern digital tools and help them to attain a sustainable livelihood.
          </p>
      </div> --}}
    </div>

  </div>
  <!--/ Container end -->
</section><!-- Service end -->
@else
<section id="ts-service-area" class="ts-service-area pb-0" style="min-height: auto">
  <div class="container">
    <div class="row text-center">
        <div class="col-12">
          <h3 class="section-sub-title">Who We Are</h3>
        </div>
        
    </div>
    <!--/ Title row end -->

    <div class="row" style="margin-bottom:10%!important">
        <div class="col-md-12">
            <h4>Our Mission</h4>  
            <p>
            Our mission is to integrate services to create a digital platform for the thousands of people on the move. We intend to provide them with a digital identity, review and verify their qualifications, and provide e-learning and training to prepare them for the digital economy.
            </p>

            <h4>Our Vision</h4>  
            <p>
                Our vision is to engage people on the move all over the world in a localized common digital platform. Our plan is to engage them using modern digital tools and help them to attain a sustainable livelihood.
            </p>
        </div>
    </div>

  </div>
  <!--/ Container end -->
</section><!-- Service end -->

@endif

@endsection

@section('scripts')
@endsection


