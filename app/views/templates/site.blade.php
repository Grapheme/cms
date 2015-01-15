<?
/**
 * MENU_PLACEMENTS: main_menu=Основное меню
 */
?>
@if (@is_object($page->meta->seo))
@section('title'){{ $page->meta->seo->title ? $page->meta->seo->title : $page->name }}@stop
@section('description'){{ $page->meta->seo->description }}@stop
@section('keywords'){{ $page->meta->seo->keywords }}@stop
@elseif (@is_object($page->meta))
@section('title')
{{{ $page->name }}}@stop
@elseif (@is_object($seo))
@section('title'){{ $seo->title }}@stop
@section('description'){{ $seo->description }}@stop
@section('keywords'){{ $seo->keywords }}@stop
@endif
@section('page_class')index @stop
<!DOCTYPE html>
<html class="@yield('page_class') no-js" style="@yield('page_style')">
<head>
	@include(Helper::layout('head'))

    @section('style')
    @show
</head>
<body class="@yield('page_class')" style="@yield('page_style')">
    <!--[if lt IE 7]>
        <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->
    @include(Helper::layout('header'))

    @section('content')
        {{ @$content }}
    @show

    @section('footer')
        @include(Helper::layout('footer'))
    @show
    @include(Helper::layout('scripts'))

    @section('scripts')
    @show
</body>
</html>