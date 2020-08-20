<?php
/**
 * Plugin Name: ACF Wysiwyg Style Select
 * Description: Custom style select boxes for ACF Wysiwyg editors.
 * Author:      Alex Brombal
 * Author URI:  http://www.brombal.com
 * Version:     1.1
 * License:     MIT
 */
 
/*

This plugin allows you to create custom styles in a dropdown menu for ACF wysiwyg editors.

To use it, there are 3 steps you have to take:

1. Use the 'acf/fields/wysiwyg/toolbars' filter to create or modify the wysiwyg toolbars that ACF allows you to 
   associate with an editor. 

2. Create your custom style dropdowns by specifying a tree-like menu structure that defines the styles.

3. Add your own CSS to the wysiwyg editor.


See this example code, which should be added to your theme's functions.php file:


<?php // (Add to your theme's functions.php)

// ========================================
// Create TinyMCE custom style select boxes
// ========================================

// 1. Create custom style dropdowns:

if (function_exists('create_custom_style_select'))
{
    create_custom_style_select('my_custom_styles', 'My Custom Styles', [

        // Repeat this for a tree menu:
        [
            'title' => 'Headers',
            'items' => [
                // Repeat this for sub items:
                [
                    'title' => 'Header 1',
                    'block' => 'h1',
                ],
                // ...
            ]
        ],

        // Repeat this for a top level style:
        [
            'title' => 'Paragraph',
            'block' => 'p'
        ],
        // ...

        // An object with all available properties:
        [
            'title' => 'Title',
            'block' => 'h1',
            'inline' => 'b',
            'classes' => 'the-class',
            'styles' => [ 'font-weight' => 'bold' ],
            'selector' => ''
        ]
    ]);
}


// 2. Create available wysiwyg toolbars:

add_filter('acf/fields/wysiwyg/toolbars', function($toolbars) {
    // Uncomment to view current format of $toolbars:
    // print_r($toolbars); 

    return [
        'Full' => [
            // Full list, in original order: 
            1 => [
                'bold',
                'italic',
                'strikethrough',
                'bullist',
                'numlist',
                'blockquote',
                'alignleft',
                'aligncenter',
                'alignright',
                'link',
                'unlink',
                'wp_more',
                'spellchecker',
                'wp_fullscreen',
                'wp_adv',
                'separator',
            ],
            2 => [
                'formatselect',
                'underline',
                'forecolor',
                'pastetext',
                'pasteword',
                'removeformat',
                'charmap',
                'outdent',
                'indent',
                'undo',
                'redo',
                'wp_help',
                // 'custom_styles' // Add the name of the custom style select box that you create
            ]
        ],
        'Minimal' => [
            1 => [
                'custom_styles',
                'separator',
                'bold',
                'italic',
                'underline',
                'strikethrough',
                'separator',
                'link',
                'unlink',
                'separator',
                'undo',
                'redo',
            ]
        ]
        // Create others here
    ];
});


// 3. Add CSS files to the wysiwyg editor:

add_editor_style(); // Add the default styles (optional)
add_editor_style('css/wysiwyg.css');

*/

global $custom_style_select_js_output;
$custom_style_select_js_output = false;

$custom_style_select_styles = array();

function create_custom_style_select($id, $title, $styles)
{
    global $custom_style_select_styles;
    $custom_style_select_styles[] = array(
        'id' => $id,
        'title' => $title,
        'styles' => $styles
    );
}

add_action('acf/input/admin_head', function()
{
    global $custom_style_select_styles, $custom_style_select_js_output;

    // Prevent duplicate output
    if ($custom_style_select_js_output)
        return;
    $custom_style_select_js_output = true;

    ?>
    <script type="text/javascript">

        var styles = <?=json_encode($custom_style_select_styles)?>;

        acf.add_filter('wysiwyg_tinymce_settings', function (settings) {
            var newFormats = [];
            var count = 0;

            function createMenu(items) {
                if (!items) {
                    return;
                }

                var menu = [];

                for (var i = 0, len = items.length; i < len; i++) {
                    var item = items[i];

                    var menuItem = {
                        text: item.title,
                        icon: item.icon
                    };

                    if (item.items) {
                        menuItem.menu = createMenu(item.items);
                    }
                    else {
                        var formatName = item.format || 'custom' + count++;

                        if (!item.format) {
                            item.name = formatName;
                            newFormats.push(item);
                        }

                        menuItem.format = formatName;
                        menuItem.cmd = item.cmd;
                    }

                    menu.push(menuItem);
                }

                return menu;
            }

            settings._oldSetup = settings.setup;
            settings.setup = function (editor) {
                if (settings._oldSetup && settings._oldSetup !== settings.setup)
                    settings._oldSetup(editor);

                editor.on('init', function () {
                    for (var i = 0, len = newFormats.length; i < len; i++) {
                        var format = newFormats[i];
                        editor.formatter.register(format.name, format);
                    }
                });

                for (var i = 0, len = styles.length; i < len; i++) {
                    var style = styles[i];

                    editor.addButton(style.id, {
                        type: 'menubutton',
                        text: style.title,
                        menu: {
                            type: 'menu',
                            items: createMenu(style.styles),
                            onPostRender: function (e) {
                                editor.fire('renderFormatsMenu', {control: e.control});
                            },
                            itemDefaults: {
                                preview: true,

                                textStyle: function () {
                                    if (this.settings.format) {
                                        return editor.formatter.getCssText(this.settings.format);
                                    }
                                },

                                onPostRender: function () {
                                    var self = this;

                                    self.parent().on('show', function () {
                                        var formatName, command;

                                        formatName = self.settings.format;
                                        if (formatName) {
                                            self.disabled(!editor.formatter.canApply(formatName));
                                            self.active(editor.formatter.match(formatName));
                                        }

                                        command = self.settings.cmd;
                                        if (command) {
                                            self.active(editor.queryCommandState(command));
                                        }
                                    });
                                },

                                onclick: function () {
                                    if (this.settings.format) {
                                        editor.execCommand('mceToggleFormat', false, this.settings.format);
                                    }

                                    if (this.settings.cmd) {
                                        editor.execCommand(this.settings.cmd);
                                    }
                                }
                            }
                        }
                    });
                }
            };

            return settings;
        });
    </script>
    <?php
});