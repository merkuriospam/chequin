@extends('layouts.theme')

@section('content')
    <header class="masthead">
      <div class="container h-100">
        <div class="row h-100">
          <div class="col-lg-7 my-auto">
            <div class="header-content mx-auto">
              <h1 class="mb-5">
                Crea tu Timbre Digital y comparte el vínculo con quien quieras.
                <br>Cuando tu visita llegue se anunciara con el vínculo que recibió y a tí te sonará el timbre.
                <br>Simple.
              </h1>
              <a href="#download" class="btn btn-outline btn-xl js-scroll-trigger">Descarga la App!</a>
            </div>
          </div>
          <div class="col-lg-5 my-auto">
            <div class="device-container">
              <div class="device-mockup iphone6_plus portrait white">
                <div class="device">
                  <div class="screen">
                    <!-- Demo image for screen mockup, you can put an image here, some HTML, an animation, video, or anything else! -->
                    <img src="{{ asset('theme/img/entrada_chequin.png') }}" class="img-fluid" alt="">
                  </div>
                  <div class="button">
                    <!-- You can hook the "home button" to some JavaScript events or just remove it -->
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </header>

    <section class="download bg-primary text-center" id="download">
      <div class="container">
        <div class="row">
          <div class="col-md-8 mx-auto">
            <h2 class="section-heading">Descarga Chequin!</h2>
            <p>Nuestra app está disponible para Android.<br>Proximamente lanzaremos la versión para iOS!</p>
            <div class="badges">
              <a class="badge-link" href="https://play.google.com/store/apps/details?id=ar.com.plopi.ring"><img src="{{ asset('theme/img/google-play-badge.svg') }}" alt=""></a>
              
              <div class="badge-link">
                <img class="opa50" src="{{ asset('theme/img/app-store-badge.svg') }}" alt="">
                <br><span>Proximamente</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
<!--
    <section class="features" id="features">
      <div class="container">
        <div class="section-heading text-center">
          <h2>Unlimited Features, Unlimited Fun</h2>
          <p class="text-muted">Check out what you can do with this app theme!</p>
          <hr>
        </div>
        <div class="row">
          <div class="col-lg-4 my-auto">
            <div class="device-container">
              <div class="device-mockup iphone6_plus portrait white">
                <div class="device">
                  <div class="screen">
                    <img src="{{ asset('theme/img/demo-screen-1.jpg') }}" class="img-fluid" alt="">
                  </div>
                  <div class="button">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-8 my-auto">
            <div class="container-fluid">
              <div class="row">
                <div class="col-lg-6">
                  <div class="feature-item">
                    <i class="icon-screen-smartphone text-primary"></i>
                    <h3>Device Mockups</h3>
                    <p class="text-muted">Ready to use HTML/CSS device mockups, no Photoshop required!</p>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="feature-item">
                    <i class="icon-camera text-primary"></i>
                    <h3>Flexible Use</h3>
                    <p class="text-muted">Put an image, video, animation, or anything else in the screen!</p>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-6">
                  <div class="feature-item">
                    <i class="icon-present text-primary"></i>
                    <h3>Free to Use</h3>
                    <p class="text-muted">As always, this theme is free to download and use for any purpose!</p>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="feature-item">
                    <i class="icon-lock-open text-primary"></i>
                    <h3>Open Source</h3>
                    <p class="text-muted">Since this theme is MIT licensed, you can use it commercially!</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
-->
<!--
    <section class="cta">
      <div class="cta-content">
        <div class="container">
          <h2>Stop waiting.<br>Start building.</h2>
          <a href="#contact" class="btn btn-outline btn-xl js-scroll-trigger">Let's Get Started!</a>
        </div>
      </div>
      <div class="overlay"></div>
    </section>
-->
<!--
    <section class="contact bg-primary" id="contact">
      <div class="container">
        <h2>We
          <i class="fas fa-heart"></i>
          new friends!</h2>
        <ul class="list-inline list-social">
          <li class="list-inline-item social-instagram">
            <a href="#" style="background-color: #666666;">
              <i class="fab fa-instagram"></i>
            </a>
          </li>
          <li class="list-inline-item social-facebook">
            <a href="#">
              <i class="fab fa-facebook-f"></i>
            </a>
          </li>
        </ul>
      </div>
    </section>
-->
@endsection
