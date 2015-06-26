<?php

return array(

    'fields' => function() {

        return array(
            'answer' => array(
                'title' => 'Текст ответа',
                'type' => 'textarea_redactor',
            ),
        );
    },

    'hooks' => array(
        'before_index_view' => function ($dic, $dicvals) {
            $dicvals->load('textfields');
            $dicvals = DicLib::extracts($dicvals, null, true, true);
        },
    ),

    'second_line_modifier' => function($line, $dic, $dicval) {
        #Helper::ta($dicval);
        return (isset($dicval->answer) && $dicval->answer ? strip_tags($dicval->answer) : '');
    },

    #'seo' => ['title', 'description', 'keywords'],
);