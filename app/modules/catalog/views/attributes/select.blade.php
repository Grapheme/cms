    {{ Helper::dd_($attribute) }}

    <?
    $settings = $attribute->settings;
    if (!is_array($settings['values'])) {
        $settings['values'] = explode("\n", $settings['values']);
    }
    $attribute->settings = (array)$settings;
    ?>

    {{ Helper::tad_($attribute) }}

    <section>
        <label class="label">{{ $attribute->name }}</label>
        <label class="select">
            {{ Form::select('attributes[' . $locale_sign . '][' . $attribute->slug . ']', $attribute->settings['values']) }}
        </label>
    </section>
