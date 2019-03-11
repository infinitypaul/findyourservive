@extends('layouts.app')

@section('content')
    <div class="container">
            <search endpoint="{{ route('search') }}"></search>

    </div>

@endsection
