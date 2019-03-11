@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <edit-service endpoint="{{ route('services.index') }}" id="{{ $service->id }}" url="{{ route('admin_dashboard') }}"></edit-service>
            </div>
        </div>
    </div>
    </div>
@endsection
