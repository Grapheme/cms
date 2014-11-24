
    <section>
        <label class="checkbox">
            {{ Form::checkbox('attributes[' . $locale_sign . '][' . $attribute->slug . ']') }}
            <i></i>
            {{ $attribute->name }}
        </label>
    </section>
