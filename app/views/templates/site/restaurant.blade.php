<?
#$data = Dic::valueBySlugs('news_type', 'spa');
#Helper::tad($data);
$news = News::where('type_id', Dic::valueBySlugs('news_type', 'restaurant')->id)
    ->with('meta.photo')
    ->orderBy('published_at', 'DESC')
    ->take(3)->get();
#Helper::tad($news);
?>

@extends(Helper::layout())


@section('style')
@stop


@section('content')

    <main role="main">
        <div class="slideshow" id="slideshow">
            <div class="slide">

                {{ $page->block('slide-cont') }}

            </div>
            <div class="arrow arrow-left"><span class="icon icon-angle-left"></span></div>
            <div class="arrow arrow-right"><span class="icon icon-angle-right"></span></div>
            <div class="arrow-bottom"><span class="icon icon-angle-down"></span></div>
        </div>
        <section class="sect-wrap restaurant">

            {{ $page->block('description') }}

        </section>
        <section class="sect-wrap tabs-sect">

            {{ $page->block('menu') }}

            <ul id="tabContent" class="tab-content">
                <li class="tab-li" data-tab="firm">
                    {{ $page->block('menu_1') }}
                </li>
                <li class="tab-li" data-tab="snack">
                    {{ $page->block('menu_2') }}
                </li>
                <li class="tab-li" data-tab="salad">
                    {{ $page->block('menu_3') }}
                </li>
                <li class="tab-li" data-tab="bar">
                    {{ $page->block('menu_4') }}
                </li>
                <li class="tab-li" data-tab="desert">
                    {{ $page->block('menu_5') }}
                </li>
            </ul>
        </section>

        @if (@count($news))
        <section class="sect-wrap square-list-sect">

            <ul class="square-ul clearfix">

                @foreach ($news as $new)
                <?
                $photo = @is_object($new->meta->photo) ? $new->meta->photo->thumb() : false;
                ?>
                <li class="square-li">
                    <a href="#"></a>
                    <div class="square-li-img" style="background-image: url({{ $photo }});"></div>
                    <span>{{ $new->meta->title }}</span>
                </li>
                @endforeach
            </ul>

        </section>
        @endif

        @if (0)
        <section class="sect-wrap mini-footer">
            <header>
                <div class="small-logo">

                </div> 1000 и 1 ночь
            </header>
            <div class="call">
                Вопросы администратору<br>
                Вы можете задать по телефону
                <a href="tel:+74958944455">+7 495 894 44 55</a>
            </div>
        </section>
        @endif
    </main>

@stop


@section('scripts')
@stop