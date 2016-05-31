/*
 * selectList jQuery plugin
 * version 0.4.2
 *
 * Copyright (c) 2009-2011 Michal Wojciechowski (odyniec.net)
 *
 * Dual licensed under the MIT (MIT-LICENSE.txt)
 * and GPL (GPL-LICENSE.txt) licenses.
 *
 * http://odyniec.net/projects/selectlist/
 *
 */
$ = jQuery;

(function ($) {

$.selectList = function (select, options) {
    var

        $selectSingle,

        $list,

        $item, $newItem,

        $option,

        keyEvent,

        ready,

        first = 0,

        change, click, keypress, enter;

    function show($item, callback) {
        if (options.addAnimate && ready)
            if (typeof options.addAnimate == 'function')
                options.addAnimate($item.hide()[0], callback);
            else
                $item.hide().fadeIn(300, callback);
        else {
            $item.show();
            if (callback)
                callback.call($item[0]);
        }
    }

    function hide($item, callback) {
        if (options.removeAnimate && ready)
            if (typeof options.removeAnimate == 'function')
                options.removeAnimate($item[0], callback);
            else
                $item.fadeOut(300, callback);
        else {
            $item.hide();
            if (callback)
                callback.call($item[0]);
        }
    }

    function cmp(item1, item2) {
        return typeof options.sort == 'function' ?
            options.sort(item1, item2)
            : (jQuery(item1).data('text') > jQuery(item2).data('text'))
                == (options.sort != 'desc');
    }

    function add(value, text, callHandler) {
		
		
        if (jQuery(value).is('option')) {
            $option = jQuery(value);

            if ($option[0].index < first)
                return;

            value = $option.val();
            text = $option.text();
        }
        else {
            $option = $selectSingle.find("option[value=\"" +

                    value.replace("'", "\\\"") + "\"]");

            if ($option.length)
                $option = $option.filter(function () {
                    return !text || jQuery(this).text() == text;
                })
                .add($option).eq(0);
            else
                $option = null;
        }

        if(!value)
        	return;
        
        if (text === undefined)
            text = $option ? $option.text() : value;

        if ($option && !options.duplicates)
            $option.attr('disabled', 'disabled')
                .data('disabled', 1);

        $newItem = jQuery(options.template.replace(/%text%/g,
            jQuery('<b/>').text(text).html()).replace(/%value%/g, value)).hide();

        $newItem.data('value', value).data('text', text).data('option', $option)
            .addClass(options.defaultClassPrefix + '-item');

        $newItem.click(function () {
            if (options.clickRemove)
                remove(jQuery(this));
        });

        if (first && !keypress)
            $selectSingle[0].selectedIndex = 0;

        var callback = function () {
            
        };
		
		if (callHandler !== false){
               var result = options.onAdd(select, value, text);
				if(result){
					return;		
				}
		}
		
        if (options.sort && ($item = $list.children().eq(0)).length) {
            while ($item.length && cmp($newItem[0], $item[0]))
                $item = $item.next();

            show($item.length ? $newItem.insertBefore($item)
                : $newItem.appendTo($list), callback);
        }
        else
            show($newItem.appendTo($list), callback);

        jQuery(select).empty();

        $list.children().each(function () {
            jQuery(select).append(jQuery("<option/>").attr({ value: jQuery(this).data('value'),
                    selected: "selected" }));
        });

        checkValidation();
    }

    function remove($item, callHandler) {
        hide($item, function () {
            var value = jQuery(this).data('value'),
                text = jQuery(this).data('text');

            if (jQuery(this).data('option'))
                jQuery(this).data('option').removeAttr('disabled')
                    .removeData('disabled');

            jQuery(this).remove();

            jQuery(select).find("option[value=\"" + value + "\"]").remove();

            checkValidation();

            if (callHandler !== false)
                options.onRemove(select, value, text);
        });
    }

    function checkValidation() {
          if (select.form && typeof (jQuery(select.form).validate) == "function" &&
                  jQuery(select).add($selectSingle).hasClass(jQuery(select.form)
                          .validate().settings.errorClass))
              jQuery(select.form).validate().element(select);
    }

    this.val = function () {
        return jQuery(select).val();
    };

    this.add = function (value, text) {
        add(value, text);
    };

    this.remove = function (value) {
        $list.children().each(function () {
            if (jQuery(this).data('value') == value || typeof value == 'undefined')
                remove(jQuery(this));
        });
    };

    this.setOptions = function (newOptions) {
        var sort = newOptions.sort && newOptions.sort != options.sort;

        options = $.extend(options, newOptions);

        if (sort) {
            var items = [];
            $list.children().each(function () {
                items[items.length] = jQuery(this).data('value')
                items[items.length] = jQuery(this).data('text');
            });
            $list.empty();
            for (var i = 0; i < items.length; i += 2)
                add(items[i], items[i+1], false);
        }
    };

    this.setOptions(options = $.extend({
        addAnimate: true,
        classPrefix: 'selectlist',
        defaultClassPrefix: 'selectlist',
        id: 'facilitiesMultipleSelect',
        clickRemove: true,
        removeAnimate: true,
        template: '<li>%text%</li>',
        onAdd: function () {},
        onRemove: function () {}
    }, options));

    $selectSingle = jQuery(select).clone();
    $selectSingle.removeAttr('id').removeAttr('name')
        .addClass(options.classPrefix + '-select').insertAfter(jQuery(select));
    jQuery(select).empty().hide();

    ($list = jQuery(options.list || jQuery("<ul/>").insertAfter($selectSingle)))
        .addClass(options.classPrefix + '-list');

    $selectSingle.find(':selected').each(function () {
        add(jQuery(this), null, false);
    });

    $selectSingle.removeAttr('multiple');
    $selectSingle.get(0).removeAttribute('size');

    if ($selectSingle.attr("title")) {
        $selectSingle.prepend(jQuery("<option/>")
                .text($selectSingle.attr("title")));
        first = 1;
        $selectSingle[0].selectedIndex = 0;
    }

    keyEvent = $.browser.msie || $.browser.safari ? 'keydown' : 'keypress';

    $selectSingle.bind(keyEvent, function (event) {
        keypress = true;

        if ((event.keyCode || event.which) == 13) {
            enter = true;
            $selectSingle.change();
            keypress = true;
            return false;
        }
    })
    .change(function() {
        if (!keypress && !click) return;
        change = true;
        $option = $selectSingle.find("option:selected");
        if (!$option.data("disabled") && (!keypress || enter))
            add($option);

        if (keypress)
            keypress = change = click = false;

        $selectSingle.find('option:first').attr('selected','selected');

        enter = false;
    })
    .mousedown(function () {
        click = true;
    });

    $selectSingle.find('option').click(function (event) {
        if ($.browser.mozilla && event.pageX >= $selectSingle.offset().left &&
                event.pageX <= $selectSingle.offset().left +

                    $selectSingle.outerWidth() &&
                event.pageY >= $selectSingle.offset().top &&
                event.pageY <= $selectSingle.offset().top +

                    $selectSingle.outerHeight())
            return false;

        click = true;

        if (!(jQuery(this).attr('disabled') || jQuery(this).data('disabled') || keypress
                || change))

            add(jQuery(this));

        if (!keypress)
            change = click = false;

        return false;
    });

    $selectSingle.find('option:first').attr('selected','selected');
    
    ready = true;
};

$.fn.selectList = function (options) {
    options = options || {};

    this.filter('select').each(function () {
        if (jQuery(this).data('selectList'))
            jQuery(this).data('selectList').setOptions(options);
        else
            jQuery(this).data('selectList', new $.selectList(this, options));
    });

    if (options.instance)
        return this.filter('select').data('selectList');

    return this;
};

})(jQuery);
