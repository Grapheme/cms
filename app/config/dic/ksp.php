<?php

return array(

    #'new_element_redirect' => 'list', ## list | new | ['route_name' => '...', 'route_params' => [], 'add_query_string' => true]
    'new_element_redirect' => ['route_name' => 'entity.create', 'route_params' => ['dic_slug' => 'ksp'], 'add_query_string' => true]

);