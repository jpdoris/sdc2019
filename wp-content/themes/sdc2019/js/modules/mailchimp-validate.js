// ADDITIONAL JQUERY VALIDATE METHODS
(function($) {
    $.validator.addMethod("skip_or_complete_group", function(value, element, grouping_class) {
        var $fields = $('input:not(:hidden)', $(element).closest(grouping_class)),
            $fieldsFirst = $fields.eq(0),
            validator = $fieldsFirst.data("valid_skip") ? $fieldsFirst.data("valid_skip") : $.extend({}, this),
            numberFilled = $fields.filter(function() {
                return validator.elementValue(this);
            }).length,
            isValid = numberFilled === 0 || numberFilled === $fields.length;

        // Store the cloned validator for future validation
        $fieldsFirst.data("valid_skip", validator);

        // If element isn't being validated, run each field's validation rules
        if (!$(element).data("being_validated")) {
            $fields.data("being_validated", true);
            $fields.each(function() {
                validator.element(this);
            });
            $fields.data("being_validated", false);
        }
        return isValid;
    }, $.validator.format("Please supply missing fields."));

    $.validator.addMethod("skip_or_fill_minimum", function(value, element, options) {
        var $fields = $(options[1], element.form),
            $fieldsFirst = $fields.eq(0),
            validator = $fieldsFirst.data("valid_skip") ? $fieldsFirst.data("valid_skip") : $.extend({}, this),
            numberFilled = $fields.filter(function() {
                return validator.elementValue(this);
            }).length,
            isValid = numberFilled === 0 || numberFilled >= options[0];
        console.log($fields.eq(0));
        // Store the cloned validator for future validation
        $fieldsFirst.data("valid_skip", validator);

        // If element isn't being validated, run each skip_or_fill_minimum field's validation rules
        if (!$(element).data("being_validated")) {
            $fields.data("being_validated", true);
            $fields.each(function() {
                validator.element(this);
            });
            $fields.data("being_validated", false);
        }
        return isValid;
    }, $.validator.format("Please either skip these fields or fill at least {0} of them."));
}(jQuery));

// MC
(function($) {
    // var err_style = '';
    // try {
    //     err_style = mc_custom_error_style;
    // } catch (e) {
    //     err_style = '#mc_embed_signup input.mce_inline_error { border-color:#6B0505; } #mc_embed_signup div.mce_inline_error { margin: 0 0 1em 0; padding: 5px 10px; background-color:#6B0505; font-weight: bold; z-index: 1; color:#fff; }';
    // }
    // var head = document.getElementsByTagName('head')[0];
    // var style = document.createElement('style');
    // style.type = 'text/css';
    // if (style.styleSheet) {
    //     style.styleSheet.cssText = err_style;
    // } else {
    //     style.appendChild(document.createTextNode(err_style));
    // }
    // head.appendChild(style);

    // Expose extra mc form methods in global var
    window.mc = {
        /**
         *  Grab the list subscribe url from the form action and make it work for an ajax post.
         */
        getAjaxSubmitUrl: function() {
            var url = $("form#mc-embedded-subscribe-form").attr("action");
            url = url.replace("/post?u=", "/post-json?u=");
            url += "&c=?";
            return url;
        },
        /**
         *  Classify text inputs in the same field group as group for validation purposes.
         *  All this does is tell jQueryValidation to create one error div for the group, rather
         *  than one for each input. Primary use case is birthday and date fields, where we want
         *  to display errors about the inputs collectively, not individually.
         *
         *  NOTE: Grouping inputs will give you one error div, but you still need to specify where
         *  that div should be displayed. By default, it's inserted after the first input with a
         *  validation error, which can break up a set of inputs. Use the errorPlacement setting in
         *  the validator to control error div placement.
         */
        getGroups: function() {
            var groups = {};
            $(".mc-field-group").each(function(index) {
                var inputs = $(this).find("input:text:not(:hidden), input:checkbox:not(:hidden)");
                if (inputs.length > 1) {
                    var mergeName = inputs.first().attr("name");
                    var fieldNames = $.map(inputs, function(f) { return f.name; });
                    groups[mergeName.substring(0, mergeName.indexOf("["))] = fieldNames.join(" ");
                }
            });
            return groups;
        },
        /**
         *  Check if a field is part of a multipart field
         *  (e.g., A date merge field is composed of individual inputs for month, day and year)
         *  Used in jQuery validation onkeyup method to ensure that we don't evaluate a field
         *  if a user hasn't reached the last input in a multipart field yet.
         */
        // isMultiPartField: function(element) {
        //     return ($('input:not(:hidden)', $(element).closest(".mc-field-group")).length > 1);
        // },
        /**
         *  Checks if the element is the last input in its fieldgroup.
         *  If the field is not the last in a set of inputs we don't want to validate it on certain events (onfocusout, onblur)
         *  because the user might not be finished yet.
         */
        isTooEarly: function(element) {
            var fields = $('input:not(:hidden)', $(element).closest(".mc-field-group"));
            return ($(fields).eq(-1).attr('id') != $(element).attr('id'));
        },
        /**
         *  Handle the error/success message after successful form submission.
         *  Success messages are appended to #mce-success-response
         *  Error messages are displayed with the invalid input when possible, or appended to #mce-error-response
         */
        mce_success_cb: function(resp) {
            $('#mce-success-response').hide();
            $('#mce-error-response').hide();

            // On successful form submission, display a success message and reset the form
            if (resp.result == "success") {
                $('#mce-' + resp.result + '-response').show();
                $('#mce-' + resp.result + '-response').html(resp.msg);
                $('#mc-embedded-subscribe-form').each(function() {
                    this.reset();
                });

                // If the form has errors, display them, inline if possible, or appended to #mce-error-response
            } else {
                if (resp.msg === "captcha") {
                    var url = $("form#mc-embedded-subscribe-form").attr("action");
                    var parameters = $.param(resp.params);
                    url = url.split("?")[0];
                    url += "?";
                    url += parameters;
                    window.open(url);
                };
                // Example errors - Note: You only get one back at a time even if you submit several that are bad.
                // Error structure - number indicates the index of the merge field that was invalid, then details
                // Object {result: "error", msg: "6 - Please enter the date"}
                // Object {result: "error", msg: "4 - Please enter a value"}
                // Object {result: "error", msg: "9 - Please enter a complete address"}

                // Try to parse the error into a field index and a message.
                // On failure, just put the dump thing into in the msg variable.
                var index = -1;
                var msg;
                try {
                    var parts = resp.msg.split(' - ', 2);
                    if (parts[1] == undefined) {
                        msg = resp.msg;
                    } else {
                        i = parseInt(parts[0]);
                        if (i.toString() == parts[0]) {
                            index = parts[0];
                            msg = parts[1];
                        } else {
                            index = -1;
                            msg = resp.msg;
                        }
                    }
                } catch (e) {
                    index = -1;
                    msg = resp.msg;
                }

                try {
                    // If index is -1 if means we don't have data on specifically which field was invalid.
                    // Just lump the error message into the generic response div.
                    if (index == -1) {
                        $('#mce-' + resp.result + '-response').show();
                        $('#mce-' + resp.result + '-response').html(msg);

                    } else {
                        var fieldName = $("input[name*='" + fnames[index] + "']").attr('name'); // Make sure this exists (they haven't deleted the fnames array lookup)
                        var data = {};
                        data[fieldName] = msg;
                        mc.mce_validator.showErrors(data);
                    }
                } catch (e) {
                    $('#mce-' + resp.result + '-response').show();
                    $('#mce-' + resp.result + '-response').html(msg);
                }
            }
        }
    }

    if (document.getElementById('mc-embedded-subscribe-form')) {
        window.mc.mce_validator = $("#mc-embedded-subscribe-form").validate({
            // Set error HTML: <div class="mce_inline_error"></div>
            errorClass: "mce_inline_error",
            errorElement: "div",

            // Validate fields on keyup, focusout and blur.
            onkeyup: false,
            onfocusout: function(element) {
                if (!mc.isTooEarly(element)) {
                    var v = $(element).valid();
                    if (v) {
                        $('.mc-field-group__email__errors').html('');
                    }
                        $("#mce-error-response").hide();
                        $("#mce-success-response").hide();
                }
            },
            onblur: function(element) {
                if (!mc.isTooEarly(element)) {
                    var v = $(element).valid();
                    if (v) {
                        $('.mc-field-group__email__errors').html('');
                    }
                        $("#mce-error-response").hide();
                        $("#mce-success-response").hide();
                }
            },
            // Grouping fields makes jQuery Validation display one error for all the fields in the group
            // It doesn't have anything to do with how the fields are validated (together or separately),
            // it's strictly for visual display of errors
            groups: mc.getGroups(),
            // Place a field's inline error HTML just before the div.mc-field-group closing tag
            errorPlacement: function(error, element) {
                $('.mc-field-group__email__errors').html(error);
            },
            // Submit the form via ajax (see: jQuery Form plugin)
            submitHandler: function(form) {
                $(form).ajaxSubmit(mc.ajaxOptions);
            },
        });

        window.mc.ajaxOptions = {
            url: mc.getAjaxSubmitUrl(),
            type: 'GET',
            dataType: 'json',
            contentType: "application/json; charset=utf-8",
            success: mc.mce_success_cb
        };
    }
}(jQuery));
