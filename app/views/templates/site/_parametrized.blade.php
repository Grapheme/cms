<?
/**
 * TITLE: Шаблон для проверки параметризированных страниц
 * AVAILABLE_ONLY_IN_ADVANCED_MODE
 */
?>
<?php
#extends(Helper::layout())
Helper::ta($page);

$route = Route::current();

$lang = $route->getParameter('lang');
$city_slug = $route->getParameter('city_slug');
Helper::ta('{lang} => ' . $lang);
Helper::ta('{city_slug} => ' . $city_slug);

Helper::ta('URL::route("page.' . $page->sysname . '", ["spb"]) => ' . URL::route("page." . $page->sysname, ["spb"]));

var_dump($route);

die;