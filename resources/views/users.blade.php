@extends('layouts.user')


@section('title', 'Home Page')

@section('header')
    @include('components.header', ['pagename' => 'Home'])
@endsection

{{-- @section('style')
    @include('components.style')
@endsection --}}

@section('content')
    @include('components.userTable')
@endsection

{{-- 
@section('sidebar')
    @include('components.sidebar')
@endsection --}}


@section('footer')
    @include('components.footer')
@endsection
