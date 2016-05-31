/*
 Author: BIPIN THAKUR
 Ver: 0.9.4.3
 Last updated: Mar 22, 2015
 
 The MIT License (MIT)
 
 Copyright (c) 2011 BIPIN THAKUR
 
 Permission is hereby granted, free of charge, to any person obtaining a copy
 of this software and associated documentation files (the "Software"), to deal
 in the Software without restriction, including without limitation the rights
 to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 copies of the Software, and to permit persons to whom the Software is
 furnished to do so, subject to the following conditions:
 
 The above copyright notice and this permission notice shall be included in
 all copies or substantial portions of the Software.
 
 THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 THE SOFTWARE.
 */
jQuery(function () {
//expand all
    jQuery("#expand_all").on("click", function (e) {
        e.preventDefault();
     
        jQuery(".clone").show();
        jQuery(".clone input,.clone textarea,.clone select").removeAttr("disabled");
    });
//apps
    jQuery('#btnAdd_1').click(function () {
        var hid = jQuery(".clonedInput_0:hidden");
        var currElem = hid.eq(0);

        var childs = jQuery(currElem).children().find("input,select,textarea");
        childs.each(function () {
            jQuery(this).removeAttr("disabled", "");
        });
        currElem.slideDown({duration: 500, complete: function (a) {
                jQuery(this).children().find("input").eq(0).focus();
                jQuery('html, body').animate({
                    scrollTop: jQuery(this).offset().top - 200
                });
            }});

        jQuery('#btnDel_1').show();
    });
    jQuery('#btnDel_1').click(function () {
        var lgt = jQuery(".clonedInput_0:visible").length;
        if (lgt == '2') {
            jQuery(this).hide();
        }
        if (lgt != '1')
        {



            var hid = jQuery(".clonedInput_0:visible");
            var currElem = hid.last();

            var childs = jQuery(currElem).children().find("input,select,textarea");

            currElem.slideUp({duration: 500, complete: function () {
                    jQuery(this).prev().children().find("input").eq(0).focus();
                    childs.each(function () {
                        jQuery(this).attr("disabled", "true");
                    });
                    jQuery('html, body').animate({
                        scrollTop: jQuery(this).prev().offset().top - 300
                    });


                }});
        }
    });

//Widgets

    jQuery('#btnAdd_2').click(function () {
        var hid = jQuery(".clonedInput_1:hidden");

        var currElem = hid.eq(0);
        var childs = jQuery(currElem).children().find("input,select,textarea");
        childs.each(function () {
            jQuery(this).removeAttr("disabled", "");
        });

        currElem.slideDown({duration: 500, complete: function (a) {
                jQuery(this).children().find("input").eq(0).focus();
                jQuery('html, body').animate({
                    scrollTop: jQuery(this).offset().top - 200
                });
            }});
        jQuery('#btnDel_2').show();
    });
    jQuery('#btnDel_2').click(function () {
        var lgt = jQuery(".clonedInput_1:visible").length;
        if (lgt == '2') {
            jQuery(this).hide();
        }
        if (lgt != '1')
        {



            var hid = jQuery(".clonedInput_1:visible");
            var currElem = hid.last();
            var childs = jQuery(currElem).children().find("input,select,textarea");

            currElem.slideUp({duration: 500, complete: function () {
                    jQuery(this).prev().children().find("input").eq(0).focus();
                    childs.each(function () {
                        jQuery(this).attr("disabled", "true");
                    });
                    jQuery('html, body').animate({
                        scrollTop: jQuery(this).prev().offset().top - 200
                    });
                }});
        }
    });


//Experience

    jQuery('#btnAdd_3').click(function () {
        var hid = jQuery(".clonedInput_2:hidden");
        var currElem = hid.eq(0);
        var childs = jQuery(currElem).children().find("input,select,textarea");
        childs.each(function () {
            jQuery(this).removeAttr("disabled", "");
        });
        currElem.slideDown({duration: 500, complete: function (a) {
                jQuery(this).children().find("input").eq(0).focus();
                jQuery('html, body').animate({
                    scrollTop: jQuery(this).offset().top - 200
                });
            }});
        jQuery('#btnDel_3').show();
    });
    jQuery('#btnDel_3').click(function () {
        var lgt = jQuery(".clonedInput_2:visible").length;
        if (lgt == '2') {
            jQuery(this).hide();
        }
        if (lgt != '1')
        {



            var hid = jQuery(".clonedInput_2:visible");
            var currElem = hid.last();
            var childs = jQuery(currElem).children().find("input,select,textarea");

            currElem.slideUp({duration: 500, complete: function () {
                    jQuery(this).prev().children().find("input").eq(0).focus();
                    childs.each(function () {
                        jQuery(this).attr("disabled", "true");
                    });
                    jQuery('html, body').animate({
                        scrollTop: jQuery(this).prev().offset().top - 200
                    });
                }});
        }
    });

//Qualifications

    jQuery('#btnAdd_4').click(function () {
        var hid = jQuery(".clonedInput_3:hidden");
        var currElem = hid.eq(0);
        var childs = jQuery(currElem).children().find("input,select,textarea");
        childs.each(function () {
            jQuery(this).removeAttr("disabled", "");
        });
        currElem.slideDown({duration: 500, complete: function (a) {
                jQuery(this).children().find("input").eq(0).focus();
                jQuery('html, body').animate({
                    scrollTop: jQuery(this).offset().top - 200
                });
            }});
        jQuery('#btnDel_4').show();
    });
    jQuery('#btnDel_4').click(function () {
        var lgt = jQuery(".clonedInput_3:visible").length;
        if (lgt == '2') {
            jQuery(this).hide();
        }
        if (lgt != '1')
        {



            var hid = jQuery(".clonedInput_3:visible");
            var currElem = hid.last();
            var childs = jQuery(currElem).children().find("input,select,textarea");

            currElem.slideUp({duration: 500, complete: function () {
                    jQuery(this).prev().children().find("input").eq(0).focus();
                    childs.each(function () {
                        jQuery(this).attr("disabled", "true");
                    });
                    jQuery('html, body').animate({
                        scrollTop: jQuery(this).prev().offset().top - 200
                    });
                }});
        }
    });

//Websites

    jQuery('#btnAdd_5').click(function () {
        var hid = jQuery(".clonedInput_4:hidden");
        var currElem = hid.eq(0);
        var childs = jQuery(currElem).children().find("input,select,textarea");
        childs.each(function () {
            jQuery(this).removeAttr("disabled", "");
        });
        currElem.slideDown({duration: 500, complete: function (a) {
                jQuery(this).children().find("input").eq(0).focus();
                jQuery('html, body').animate({
                    scrollTop: jQuery(this).offset().top - 200
                });
            }});
        jQuery('#btnDel_5').show();
    });
    jQuery('#btnDel_5').click(function () {
        var lgt = jQuery(".clonedInput_4:visible").length;
        if (lgt == '2') {
            jQuery(this).hide();
        }
        if (lgt != '1')
        {



            var hid = jQuery(".clonedInput_4:visible");
            var currElem = hid.last();
            var childs = jQuery(currElem).children().find("input,select,textarea");

            currElem.slideUp({duration: 500, complete: function () {
                    jQuery(this).prev().children().find("input").eq(0).focus();
                    childs.each(function () {
                        jQuery(this).attr("disabled", "true");
                    });
                    jQuery('html, body').animate({
                        scrollTop: jQuery(this).prev().offset().top - 200
                    });
                }});
        }
    });

//video tour

    jQuery('#btnAdd_6').click(function () {
        var hid = jQuery(".clonedInput_5:hidden");
        var currElem = hid.eq(0);
        var childs = jQuery(currElem).children().find("input,select,textarea");
        childs.each(function () {
            jQuery(this).removeAttr("disabled", "");
        });
        currElem.slideDown({duration: 500, complete: function (a) {
                jQuery(this).children().find("input").eq(0).focus();
                jQuery('html, body').animate({
                    scrollTop: jQuery(this).offset().top - 200
                });
            }});
        jQuery('#btnDel_6').show();
    });
    jQuery('#btnDel_6').click(function () {
        var lgt = jQuery(".clonedInput_5:visible").length;
        if (lgt == '2') {
            jQuery(this).hide();
        }
        if (lgt != '1')
        {



            var hid = jQuery(".clonedInput_5:visible");
            var currElem = hid.last();
            var childs = jQuery(currElem).children().find("input,select,textarea");

            currElem.slideUp({duration: 500, complete: function () {
                    jQuery(this).prev().children().find("input").eq(0).focus();
                    childs.each(function () {
                        jQuery(this).attr("disabled", "true");
                    });

                    jQuery('html, body').animate({
                        scrollTop: jQuery(this).prev().offset().top - 200
                    });
                }});
        }
    });

//testimonial 

    jQuery('#btnAdd_7').click(function () {
        var hid = jQuery(".clonedInput_6:hidden");
        var currElem = hid.eq(0);
        var childs = jQuery(currElem).children().find("input,select,textarea");
        childs.each(function () {
            jQuery(this).removeAttr("disabled", "");
        });
        currElem.slideDown({duration: 500, complete: function (a) {
                jQuery(this).children().find("input").eq(0).focus();
                jQuery('html, body').animate({
                    scrollTop: jQuery(this).offset().top - 200
                });
            }});
        jQuery('#btnDel_7').show();
    });
    jQuery('#btnDel_7').click(function () {
        var lgt = jQuery(".clonedInput_6:visible").length;
        if (lgt == '2') {
            jQuery(this).hide();
        }
        if (lgt != '1')
        {



            var hid = jQuery(".clonedInput_6:visible");
            var currElem = hid.last();
            var childs = jQuery(currElem).children().find("input,select,textarea");

            currElem.slideUp({duration: 500, complete: function () {
                    jQuery(this).prev().children().find("input").eq(0).focus();
                    childs.each(function () {
                        jQuery(this).attr("disabled", "true");
                    });
                    jQuery('html, body').animate({
                        scrollTop: jQuery(this).prev().offset().top - 200
                    });
                }});
        }
    });


//Social Media

    jQuery('#btnAdd_8').click(function () {
        var hid = jQuery(".clonedInput_7:hidden");
        var currElem = hid.eq(0);
        var childs = jQuery(currElem).children().find("input,select,textarea");
        childs.each(function () {
            jQuery(this).removeAttr("disabled", "");
        });
        currElem.slideDown({duration: 500, complete: function (a) {
                jQuery(this).children().find("input").eq(0).focus();
                jQuery('html, body').animate({
                    scrollTop: jQuery(this).offset().top - 200
                });
            }});
        jQuery('#btnDel_8').show();
    });
    jQuery('#btnDel_8').click(function () {
        var lgt = jQuery(".clonedInput_7:visible").length;
        if (lgt == '2') {
            jQuery(this).hide();
        }
        if (lgt != '1')
        {



            var hid = jQuery(".clonedInput_7:visible");
            var currElem = hid.last();
            var childs = jQuery(currElem).children().find("input,select,textarea");

            currElem.slideUp({duration: 500, complete: function () {
                    jQuery(this).prev().children().find("input").eq(0).focus();
                    childs.each(function () {
                        jQuery(this).attr("disabled", "true");
                    });
                    jQuery('html, body').animate({
                        scrollTop: jQuery(this).prev().offset().top - 200
                    });
                }});
        }
    });
});