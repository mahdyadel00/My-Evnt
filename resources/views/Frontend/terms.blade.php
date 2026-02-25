@extends('Frontend.layouts.master')
@section('title', 'Terms & Conditions')
@section('content')
  <!-- start privacy section -->
  <section class="privacy">
    <div class="container my-5">
      <h3 class="mb-4 ">Terms and Conditions </h3>
      <!-- start post content -->
      <div class="mx-auto text-secondary position-relative">
        <p class="mb-2">{!! $terms_conditions->terms_condition !!} </p>
      </div>
      <!-- end  content -->
    </div>
  </section>
  <!-- end post section -->
  @endsection
