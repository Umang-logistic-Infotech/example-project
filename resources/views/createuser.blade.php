@extends('layouts.createuser')


@section('title', 'Home Page')

@section('header')
    @include('components.header', ['pagename' => 'Home'])
@endsection

@section('style')
    @include('components.style')
@endsection

@section('content')
    @include('components.createusercontent')
@endsection

@section('footer')
    @include('components.footer')
@endsection
