# Post Formats - A how to guide.

## Add theme support
Make sure this theme supports them but checking functions > theme-support.php.
Comment in the code that contains:
`add_theme_support( 'post-formats'...`

## Format dropdown in post edit screen
Adding theme support will add a format dropdown to a post's edit page. This
will list the post formats that were chosen to be supported in the
`add_theme_support` function.

To add support for post formats to a custom post type, make sure post-formats
is listed in the 'supports' section in the `register_post_type` function.

## Get post format
In your single template, get the format that was chosen in the dropdown
by adding this function:
`get_template_part( 'post-formats/format', get_post_format() );`

## format.php
This template will be called when a format was not specified by the admin.
