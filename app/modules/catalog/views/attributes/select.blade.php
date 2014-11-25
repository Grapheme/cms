    {{ Helper::dd_($attribute) }}

    <?
    $values = array();
    $settings = $attribute->settings;
    if (isset($settings['values']) && is_string($settings['values'])) {
        $temp = (array)explode("\n", $settings['values']);
        if (count($temp))
            foreach ($temp as $tmp)
                $values[$tmp] = $tmp;
    }
    ?>

    {{ Helper::tad_($attribute) }}
    {{ Helper::d_($value) }}

    <section>
        <label class="label">{{ $attribute->name }}</label>
        <label class="select">
            {{ Form::select('attributes[' . $locale_sign . '][' . $attribute->slug . ']', $values, $value) }}
        </label>
    </section>
