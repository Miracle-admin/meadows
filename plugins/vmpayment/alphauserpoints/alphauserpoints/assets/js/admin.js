/**
 *
 * Alphauserpoints payment plugin
 *
 * @author Jeremy Magne
 * @author Valérie Isaksen
 * @version $Id: alphauserpoints.php 7217 2013-09-18 13:42:54Z alatak $
 * @package VirtueMart
 * @subpackage payment
 * Copyright (C) 2004-2015 Virtuemart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See /administrator/components/com_virtuemart/COPYRIGHT.php for copyright notices and details.
 *
 * http://virtuemart.net
 */

jQuery().ready(function ($) {

    /************/
    /* Handlers */
    /************/
    handleCredentials = function () {
        var alphauserpointsproduct = $('#params_alphauserpointsproduct').val();
        var sandbox = $("input[name='params[sandbox]']:checked").val();
        if (sandbox == 1) {
            var sandboxmode = 'sandbox';
        } else {
            var sandboxmode = 'production';
        }


        $('.std,.api,.live,.sandbox,.sandbox_warning, .accelerated_onboarding').parents('.control-group').hide();
        $('.get_sandbox_credentials').hide();
        $('.get_alphauserpoints_credentials').hide();
        // $('.authentication').hide();
        $('.authentication').parents('.control-group').hide();


        if (alphauserpointsproduct == 'std' && sandboxmode == 'production') {
            $('.std.live').parents('.control-group').show();
            $('.get_alphauserpoints_credentials').show();
            $('#params_alphauserpoints_merchant_email').addClass("required");

        } else if (alphauserpointsproduct == 'std' && sandboxmode == 'sandbox') {
            $('.std.sandbox').parents('.control-group').show();
            $('.get_sandbox_credentials').show();
            $('#params_sandbox_merchant_email').addClass("required");

        } else if (alphauserpointsproduct == 'api' && sandboxmode == 'production') {
            $('.api.live').parents('.control-group').show();
            $('.get_alphauserpoints_credentials').show();
            $('#params_alphauserpoints_merchant_email').removeClass("required");

        } else if (alphauserpointsproduct == 'api' && sandboxmode == 'sandbox') {
            $('.api.sandbox').parents('.control-group').show();
            $('.get_sandbox_credentials').show();
            $('#params_sandbox_merchant_email').removeClass("required");

        } else if (alphauserpointsproduct == 'exp' && sandboxmode == 'production') {
            $('.api.live').parents('.control-group').show();
            $('.exp.live').parents('.control-group').show();
            $('.accelerated_onboarding').parents('.control-group').show();
            $('.get_alphauserpoints_credentials').show();
            $('#params_alphauserpoints_merchant_email').removeClass("required");

            //$('.authentication.live.certificate').parents('.control-group').show();

        } else if (alphauserpointsproduct == 'exp' && sandboxmode == 'sandbox') {
            $('.api.sandbox').parents('.control-group').show();
            $('.exp.sandbox').parents('.control-group').show();
            $('.accelerated_onboarding').parents('.control-group').show();
            $('.get_sandbox_credentials').show();
            $('#params_sandbox_merchant_email').removeClass("required");
            // $('.sandbox.authentication').show();

        } else if (alphauserpointsproduct == 'hosted' && sandboxmode == 'production') {
            $('.api.live').parents('.control-group').show();
            $('.hosted.live').parents('.control-group').show();
            $('.get_alphauserpoints_credentials').show();
            $('#params_alphauserpoints_merchant_email').removeClass("required");

        } else if (alphauserpointsproduct == 'hosted' && sandboxmode == 'sandbox') {
            $('.api.sandbox').parents('.control-group').show();
            $('.hosted.sandbox').parents('.control-group').show();
            $('.get_sandbox_credentials').show();
            $('#params_sandbox_merchant_email').removeClass("required");
        }

        if (sandboxmode == 'sandbox') {
            $('.sandbox_warning').parents('.control-group').show();
        }
    }

    handlePaymentType = function () {
        var alphauserpointsproduct = $('#params_alphauserpointsproduct').val();
        var currentval = $('#params_payment_type').val();
        $('.payment_type').parents('.control-group').hide();
        if (alphauserpointsproduct == 'std') {
            $('.payment_type').parents('.control-group').show();
        }

        if (alphauserpointsproduct == 'exp' || alphauserpointsproduct == 'api' || alphauserpointsproduct == 'hosted') {
            $('#params_payment_type option[value=_cart]').attr('disabled', '');
            $('#params_payment_type option[value=_oe-gift-certificate]').attr('disabled', '');
            $('#params_payment_type option[value=_donations]').attr('disabled', '');
            $('#params_payment_type option[value=_xclick-auto-billing]').attr('disabled', '');
            if (currentval == '_cart' || currentval == '_oe-gift-certificate' || currentval == '_donations' || currentval == '_xclick-auto-billing') {
                $('#params_payment_type').val('_xclick');
            }

        } else {
            $('#params_payment_type option[value=_cart]').removeAttr('disabled');
            $('#params_payment_type option[value=_oe-gift-certificate]').removeAttr('disabled');
            $('#params_payment_type option[value=_donations]').removeAttr('disabled');
            $('#params_payment_type option[value=_xclick-auto-billing]').removeAttr('disabled');
        }
        $('#params_payment_type').trigger("liszt:updated");


    }

    handleCreditCard = function () {
        var alphauserpointsproduct = $('#params_alphauserpointsproduct').val();
        $('.creditcard').parents('.control-group').hide();
        $('.cvv_required').parents('.control-group').hide();
        if (alphauserpointsproduct == 'api') {
            $('.creditcard').parents('.control-group').show();
            $('.cvv_required').parents('.control-group').show();

        }
    }
    handleRefundOnCancel = function () {
        var alphauserpointsproduct = $('#params_alphauserpointsproduct').val();
        $('.alphauserpoints_vm').parents('.control-group').show();
        if (alphauserpointsproduct == 'std') {
            $('.alphauserpoints_vm').parents('.control-group').hide();
        }
    }

    handleCapturePayment = function () {
        var alphauserpointsproduct = $('#params_alphauserpointsproduct').val();
        var payment_action = $('#params_payment_action').val();
        $('.capture').parents('.control-group').hide();
        if (alphauserpointsproduct == 'hosted' && payment_action == 'Authorization') {
            $('.capture').parents('.control-group').show();
        }
    }
    handleTemplate = function () {
        var alphauserpointsproduct = $('#params_alphauserpointsproduct').val();
        $('.alphauserpointstemplate').parents('.control-group').hide();

        if (alphauserpointsproduct == 'hosted') {
            $('.alphauserpointstemplate').parents('.control-group').show();
        }
    }

    handleTemplateParams = function () {
        var alphauserpointstemplate = $('#params_template').val();
        var alphauserpointsproduct = $('#params_alphauserpointsproduct').val();
        $('.hosted.templateA,.hosted.templateB,.hosted.templateC,.hosted.template_warning').parents('.control-group').hide();

        if (alphauserpointsproduct == 'hosted' && alphauserpointstemplate == 'templateA') {
            $('.hosted.templateA,.hosted.template_warning').parents('.control-group').show();
        }
        if (alphauserpointsproduct == 'hosted' && alphauserpointstemplate == 'templateB') {
            $('.hosted.templateB,.hosted.template_warning').parents('.control-group').show();
        }
        if (alphauserpointsproduct == 'hosted' && alphauserpointstemplate == 'templateC') {
            $('.hosted.templateC,.hosted.template_warning').parents('.control-group').show();
        }
    }

    handlePaymentAction = function () {
        var paymenttype = $('#params_payment_type').val();
        //var currentval = $('#params_payment_action').val();
        if (paymenttype == '_xclick-subscriptions' || paymenttype == '_xclick-payment-plan' || paymenttype == '_xclick-auto-billing') {
            $('#params_payment_action').val('Sale');
            $('#params_payment_action').parents('.control-group').hide();
            $('#params_payment_action').trigger("liszt:updated");
        } else {
            $('#params_payment_action').parents('.control-group').show();
        }
    }

    handleLayout = function () {
        var alphauserpointsproduct = $('#params_alphauserpointsproduct').val();
        $('.alphauserpointslayout').parents('.control-group').hide();
        $('.stdlayout').parents('.control-group').hide();
        $('.explayout').parents('.control-group').hide();
        // $('.hosted.alphauserpointslayout').parents('.control-group').hide();
        if (alphauserpointsproduct == 'std' || alphauserpointsproduct == 'exp' || alphauserpointsproduct == 'hosted') {
            $('.alphauserpointslayout').parents('.control-group').show();
        }
        if (alphauserpointsproduct == 'std') {
            $('.stdlayout').parents('.control-group').show();
        }
        if (alphauserpointsproduct == 'exp') {
            $('.explayout').parents('.control-group').show();
        }
    }
    handleAuthentication = function () {
        var alphauserpointsAuthentication = $('#params_authentication').val();
        var sandbox = $("input[name='params[sandbox]']:checked").val();
        if (sandbox == 1) {
            var sandboxmode = 'sandbox';
        } else {
            var sandboxmode = 'production';
        }

        var alphauserpointsproduct = $('#params_alphauserpointsproduct').val();
        $('.authentication').parents('.control-group').hide();
        if (alphauserpointsproduct != 'std') {
            if (sandboxmode == 'sandbox') {
                $('.authentication.sandbox.select').parents('.control-group').show();
                if (alphauserpointsAuthentication == 'certificate') {
                    $('.authentication.sandbox.certificate').parents('.control-group').show();
                } else {
                    $('.authentication.sandbox.signature').parents('.control-group').show();

                }
            }
            else if (sandboxmode == 'production') {
                // $('.authentication.live.certificate').parents('.control-group').show();
                $('.authentication.live.select').parents('.control-group').show();
                if (alphauserpointsAuthentication == 'certificate') {
                    $('.authentication.live.certificate').parents('.control-group').show();
                } else {
                    $('.authentication.live.signature').parents('.control-group').show();

                }
            }
        }

    }
    handleExpectedMaxAmount = function () {
        var alphauserpointsproduct = $('#params_alphauserpointsproduct').val();
        $('.expected_maxamount').parents('.control-group').hide();

        if (alphauserpointsproduct == 'exp') {
            $('.expected_maxamount').parents('.control-group').show();
        }
    }
    handleWarningAuthorizeStd = function () {
        var alphauserpointsproduct = $('#params_alphauserpointsproduct').val();
        var payment_action = $('#params_payment_action').val();
        $('.warning_std_authorize').parents('.control-group').hide();
        if (alphauserpointsproduct == 'std' && payment_action == 'Authorization') {
            $('.warning_std_authorize').parents('.control-group').show();
        }
    }

    handleWarningHeaderImage = function () {
        var headerimage = $('#paramheaderimg').val();
        $('.warning_headerimg').parents('.control-group').hide();
        if (headerimage != '-1') {
            $('.warning_headerimg').parents('.control-group').show();
        }
    }

    handlePaymentTypeDetails = function () {
        var selectedMode = $('#params_payment_type').val();
        $('.xclick').parents('.control-group').hide();
        $('.cart').parents('.control-group').hide();
        $('.subscribe').parents('.control-group').hide();
        $('.plan').parents('.control-group').hide();
        $('.billing').parents('.control-group').hide();
        var alphauserpointsproduct = $('#params_alphauserpointsproduct').val();
        if (alphauserpointsproduct == 'std') {
            switch (selectedMode) {
                case '_xclick':
                    $('.xclick').parents('.control-group').show();
                    $('.cart').parents('.control-group').hide();
                    $('.subscribe').parents('.control-group').hide();
                    $('.plan').parents('.control-group').hide();
                    $('.billing').parents('.control-group').hide();
                    break;
                case '_cart':
                    $('.xclick').parents('.control-group').hide();
                    $('.cart').parents('.control-group').show();
                    $('.subscribe').parents('.control-group').hide();
                    $('.plan').parents('.control-group').hide();
                    $('.billing').parents('.control-group').hide();
                    break;
                case '_oe-gift-certificate':
                    $('.cart').parents('.control-group').hide();
                    $('.subscribe').parents('.control-group').hide();
                    $('.plan').parents('.control-group').hide();
                    $('.billing').parents('.control-group').hide();
                    break;
                case '_xclick-subscriptions':
                    $('.cart').parents('.control-group').hide();
                    $('.subscribe').parents('.control-group').show();
                    $('.plan').parents('.control-group').hide();
                    $('#params_subcription_trials').trigger('change');
                    $('.billing').parents('.control-group').hide();
                    handleSubscriptionTrials();
                    break;
                case '_xclick-auto-billing':
                    $('.cart').parents('.control-group').hide();
                    $('.subscribe').parents('.control-group').hide();
                    $('.plan').parents('.control-group').hide();
                    $('.billing').parents('.control-group').show();
                    handleMaxAmountType();
                    break;
                case '_xclick-payment-plan':
                    $('.cart').parents('.control-group').hide();
                    $('.subscribe').parents('.control-group').hide();
                    $('.plan').parents('.control-group').show();
                    $('.billing').parents('.control-group').hide();
                    handlePaymentPlanDefer();
                    break;
                case '_donations':
                    $('.cart').parents('.control-group').hide();
                    $('.subscribe').parents('.control-group').hide();
                    $('.plan').parents('.control-group').hide();
                    $('.billing').parents('.control-group').hide();
                    break;
            }
        }
    }

    handleSubscriptionTrials = function () {
        var nbTrials = $('#params_subcription_trials').val();
        switch (nbTrials) {
            case '0':
                $('.trial1').parents('.control-group').hide();
                //$('.trial2').parents('.control-group').hide();
                break;
            case '1':
                $('.trial1').parents('.control-group').show();
                //$('.trial2').parents('.control-group').hide();
                break;
            //case '2':
            //	$('.trial1').parents('.control-group').show();
            //	$('.trial2').parents('.control-group').show();
            //	break;
        }
    }

    handlePaymentPlanDefer = function () {
        var doDefer = $('#params_payment_plan_defer').val();
        var alphauserpointsproduct = $('#params_alphauserpointsproduct').val();
        $('.defer').parents('.control-group').hide();
        if (doDefer == 1) {
            if (alphauserpointsproduct == 'std') {
                $('.defer_std').parents('.control-group').show();
            } else {
                $('.defer_api').parents('.control-group').show();
            }
        }
    }

    handleMaxAmountType = function () {
        var max_amount_type = $('#params_billing_max_amount_type').val();
        switch (max_amount_type) {
            case 'cart':
            case 'cust':
                $('.billing_max_amount').parents('.control-group').hide();
                break;
            case 'value':
            case 'perc':
                $('.billing_max_amount').parents('.control-group').show();
                break;
        }
    }

    handlePaymentFeesWarning = function () {
        var alphauserpointsproduct = $('#params_alphauserpointsproduct').val();
        var selectedMode = $('#params_payment_type').val();
        if ((alphauserpointsproduct == 'api' || alphauserpointsproduct == 'exp') && (selectedMode == '_xclick-subscriptions' || selectedMode == '_xclick-payment-plan')) {
            $('.warning_transaction_cost').parents('.control-group').show();
        } else {
            $('.warning_transaction_cost').parents('.control-group').hide();
        }
    }

    handleProductPricesApi = function () {
        var alphauserpointsproduct = $('#params_alphauserpointsproduct').val();
        var add_prices_api = $('#params_add_prices_api').val();
        if (alphauserpointsproduct == 'api' || alphauserpointsproduct == 'exp') {
            $('.add_prices_api').parents('.control-group').show();
        } else {
            $('.add_prices_api').parents('.control-group').hide();
        }
    }
    /**********/
    /* Events */
    /**********/
    $("input[name='params[sandbox]']").change(function () {
        handleCredentials();
        handleAuthentication();
    });

    $('#params_alphauserpointsproduct').change(function () {
        handleCredentials();
        handleAuthentication();
        handleExpectedMaxAmount();
        handleTemplateParams();
        handleCreditCard();
        handleRefundOnCancel();
        handleLayout();
        handleTemplate();
        handleWarningAuthorizeStd();
        handlePaymentType();
        handlePaymentPlanDefer();
        handleProductPricesApi();

    });
    $('#params_authentication').change(function () {
        handleAuthentication();
    });
    $('#params_template').change(function () {
        handleTemplateParams();
    });
    $('#params_payment_action').change(function () {
        handleWarningAuthorizeStd();
        handleCapturePayment();
    });

    $('#params_payment_type').change(function () {
        handlePaymentAction();
        handlePaymentTypeDetails();
        handlePaymentFeesWarning();
    });

    $('#paramheaderimg').change(function () {
        handleWarningHeaderImage();
    });

    $('#params_subcription_trials').change(function () {
        handleSubscriptionTrials();
    });

    $('#params_payment_plan_defer').change(function () {
        handlePaymentPlanDefer();
    });

    $('#params_billing_max_amount_type').change(function () {
        handleMaxAmountType();
    });


    /*****************/
    /* Initial calls */
    /*****************/
    handleCredentials();
    handleAuthentication();
    handleCreditCard();
    handleExpectedMaxAmount();
    handleCapturePayment();
    handleRefundOnCancel();
    handleLayout();
    handleTemplate();
    handleTemplateParams();
    handleWarningAuthorizeStd();
    handlePaymentType();
    handlePaymentAction();
    handlePaymentTypeDetails();
    handleWarningHeaderImage();
    handlePaymentFeesWarning();
    handlePaymentPlanDefer();
    handleProductPricesApi();

});
