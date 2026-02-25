@extends('Frontend.layouts.master')
@section('title', 'Privacy Policy')
@section('content')
  <!-- start privacy section -->
  <section class="privacy">
    <div class="container my-5">
      <h3 class="mb-4 ">Privacy Policy </h3>
      <!-- start post content -->
      <div class="mx-auto text-secondary position-relative">
        <p class="mb-2">{!! $terms_conditions->privacy_policy !!} </p>
      </div>
      <!-- end post content -->
    </div>
  </section>
  <!-- end post section -->
  @endsection
