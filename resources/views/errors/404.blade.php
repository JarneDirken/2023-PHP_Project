@extends('errors::minimal')

@section('title', __('Not Found'))
@section('code', '404')
@section('message', __('Not Found'))

@push('script')
    <script>
        // remove the last div inside the nav tag
        document.querySelector('header nav  div:last-child').remove();
    </script>
@endpush
