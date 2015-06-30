<?
/**
 * TITLE: Главная страница
 * AVAILABLE_ONLY_IN_ADVANCED_MODE
 */
?>
@extends(Helper::layout())
<?php
#$temp = Dic::valueBySlugAndId('equipments', 1);
#Helper::ta($temp);
?>


@section('style')
@stop


@section('content')

    {{ $page->draw_blocks() }}

@stop


@section('scripts')
@stop