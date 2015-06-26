<?php

return array(

    'fields' => function() {

        return array(
            'gallery' => array(
                'title' => 'Галерея изображений',
                'type' => 'gallery',
                'params' => array(
                    'maxfilesize' => 2, // MB
                    #'acceptedfiles' => 'image/*',
                ),
                'handler' => function($array, $element) {
                    return ExtForm::process('gallery', array(
                        'module'  => 'DicValMeta',
                        'unit_id' => $element->id,
                        'gallery' => $array,
                        'single'  => true,
                    ));
                }
            ),
        );
    },

    #'seo' => ['title', 'description', 'keywords'],
);