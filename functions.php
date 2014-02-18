<?php
//Gets post cat slug and looks for single-[cat slug].php and applies it
add_filter('single_template', create_function(
        '$the_template',
        'foreach( (array) get_the_category() as $cat ) {
            if ( file_exists(STYLESHEETPATH . "/single-{$cat->slug}.php") )
            return STYLESHEETPATH . "/single-{$cat->slug}.php"; }
        return $the_template;' )
);