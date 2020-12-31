@extends('layouts.main-app')

@section('content')



@endsection

@push('scripts')
    <script src="{{env('ASSET_URL').'assets/plugins/global/plugins.bundle.js'}}"></script>
    <script src="{{env('ASSET_URL').'assets/plugins/custom/prismjs/prismjs.bundle.js'}}"></script>
    <script src="{{env('ASSET_URL').'assets/js/scripts.bundle.js'}}"></script>
    <script src="{{env('ASSET_URL').'assets/js/pages/widgets.js'}}"></script>


@endpush
