@extends(Helper::layout())


@section('style')
<style>
.services.what2look {
    background-image: none;
}
</style>
@stop


@section('content')

    {{ $page->block('content') }}

@stop


@section('scripts')
@stop
