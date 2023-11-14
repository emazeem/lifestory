<!doctype html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="LifeStory description">
    <meta name="keywords" content="">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('front-end-assets/favicon.ico') }}" />
    <title>Life Story</title>


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>


</head>

<body class="ltr app horizontal landing-page form-membership navigation-toggle-one">



<div id="main">
    <div class="main-content">



        <div class="container-fluid bg-danger" style="height: 100vh">
            <div class="row justify-content-center ">
                <div class="col-md-8 ">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-fle">






                                {{ env('STRIPE_PUBLISH_KEY') }}
                                <div class="dashboard-card-add-card-inner">
                                    <form method="post" id="payment-form" class="require-validation" data-cc-on-file="false" data-stripe-publishable-key="{{ env('STRIPE_PUBLISH_KEY') }}">
                                        <div class="dashboard-card-add-card-main">
                                            <div class="add-card-html">
                                                <div class="add-card-html-inner">
                                                    <div class="add-card-html-main">
                                                        <div class="add-card-html-top-bar">
                                                            <div class="title-text">
                                                                Card Name
                                                            </div>
                                                            <div class="card-type">
                                                                <img src="{{asset('main-assets/images/wallet-icons/Mastercard.png')}}" alt="">
                                                            </div>
                                                        </div>
                                                        <div class="card-field-outer">

                                                            <div class="card-field-inner">
                                                                <div class="card-field-single">
                                                                    @csrf
                                                                    <input type="hidden"
                                                                           name="store"
                                                                           value="1">
                                                                    <div class="card-field-single-row">
                                                                        <div class="card-single-field">
                                                                            <label for="field-01">Card
                                                                                Number</label>
                                                                            <input type="number"
                                                                                   onkeypress="return isNumeric(event)"
                                                                                   oninput="maxLengthCheck(this)"
                                                                                   class="card-field-input card-number form-control"
                                                                                   id="card_number"
                                                                                   name="card_number"
                                                                                   placeholder="0000 0000 0000 0000" max="9999999999999999" >
                                                                            <span class='icon'>
                                                                                                        <span class="ti-credit-card"></span>
                                                                                                    </span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="card-field-single-row rows">
                                                                        <div class="card-single-field mr-2">
                                                                            <label for="field-01">Expiry
                                                                                Date</label>
                                                                            <input type="hidden"
                                                                                   id="expiration_year"
                                                                                   name="expiration_year">
                                                                            <input type="hidden"
                                                                                   id="expiration_month"
                                                                                   name="expiration_month">
                                                                            <input type="hidden"
                                                                                   id="card_type"
                                                                                   name="card_type">

                                                                            <input type="text"
                                                                                   class="card-field-input"
                                                                                   placeholder="MM/YY"
                                                                                   id="month_year"
                                                                                   maxlength="5">
                                                                            <span class='icon'>
                                                                                                        <span class="ti-calendar"></span>
                                                                                                    </span>
                                                                        </div>
                                                                        <div class="card-single-field">
                                                                            <label for="field-01">CVC/CV</label>
                                                                            <input type="number"
                                                                                   onkeypress="return isNumeric(event)"
                                                                                   oninput="maxLengthCheck(this)"
                                                                                   class="card-field-input card-cvc"
                                                                                   placeholder="..."
                                                                                   name="cvc"
                                                                                   id="cvc"

                                                                                   max="9999">
                                                                            <span class='icon'>
                                                                                                        <span class="ti-info-alt"></span>
                                                                                                    </span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="card-field-single-row">
                                                                        <div class="card-single-field">
                                                                            <label for="field-01">Card
                                                                                Holder
                                                                                Name</label>
                                                                            <input type="text"
                                                                                   class="card-field-input"
                                                                                   placeholder="Enter Card Holder's Full Name"
                                                                                   name="full_name"
                                                                                   id="full_name">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="add-card-action">
                                                <div class="add-card-action-inner">
                                                    <div class="add-card-toptext-title">
                                                        <div class="text">
                                                            We Accept
                                                        </div>
                                                        <div class="all-cards">
                                                            <div class="all-cards-grid">
                                                                <div class="single-card-icon">
                                                                    <img src="{{asset('main-assets/images/wallet-icons/Mastercard.png')}}"
                                                                         alt="">
                                                                </div>
                                                                <div class="single-card-icon">
                                                                    <img src="{{asset('main-assets/images/wallet-icons/Visa.png')}}"
                                                                         alt="">
                                                                </div>
                                                                <div class="single-card-icon">
                                                                    <img src="{{asset('main-assets/images/wallet-icons/AmericanExpress.png')}}"
                                                                         alt="">
                                                                </div>
                                                                <div class="single-card-icon">
                                                                    <img src="{{asset('main-assets/images/wallet-icons/UnionPay.png')}}"
                                                                         alt="">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="add-card-bottom-text">
                                                        <div class="text">
                                                            Terms & Conditions
                                                        </div>
                                                        <div class="pera">
                                                        </div>
                                                        <div class="agree">
                                                            <p>Your payment methods are saved and stored securely.</p>
                                                        </div>
                                                        <div class="agree-check">
                                                            <label for="chec-0"
                                                                   class='label-flex'>
                                                                <input type="checkbox"
                                                                       id="chec-0"
                                                                       name="agree">
                                                                <div class="text">
                                                                    I agree! to terms & conditions
                                                                </div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="add-button-div">
                                                        <button class="black-button no-border"
                                                                type="submit" id="save-card-btn">
                                                            Save
                                                        </button>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <script src="{{ asset('js/jquery.min.js') }}"></script>










<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script type="text/javascript">
    $(function () {
        var $form = $("#payment-form");
        $('form#payment-form').bind('submit', function (e) {

            var button = $('#save-card-btn');
            button.attr('disabled', 'disabled').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing ...');

            var monthyear = $('#month_year').val();monthyear = monthyear.split('/');
            $('#expiration_month').val(monthyear[0]);
            $('#expiration_year').val(monthyear[1]);

            if ($('#card_number').val()== '' || $('#chec-0').prop("checked") == false || $('#cvc').val()== '' || $('#full_name').val()== '' || monthyear[0]=='__' || monthyear[1]=='__' || $('#month_year').val()=='' ){
                if ($('#card_number').val() == ''){
                    $.toast({heading: 'Warning', text:'Card number is required.*', icon: 'warning', position: 'top-right'});
                }
                if ($('#month_year').val() == ''){
                    $.toast({heading: 'Warning', text:'Expiration month & year is required.*', icon: 'warning', position: 'top-right'});
                }
                if ($('#cvc').val() == ''){
                    $.toast({heading: 'Warning', text:'CVC is required.*', icon: 'warning', position: 'top-right'});
                }
                if ($('#chec-0').prop("checked") == false){
                    $.toast({heading: 'Warning', text:'Please agree terms and conditions.*', icon: 'warning', position: 'top-right'});
                }
                if (monthyear[0]=='__'){
                    $.toast({heading: 'Warning', text:'Expiration month is required.*', icon: 'warning', position: 'top-right'});
                }
                if (monthyear[1]=='__'){
                    $.toast({heading: 'Warning', text:'Expiration year is required.*', icon: 'warning', position: 'top-right'});
                }
                if ($('#full_name').val() == ''){
                    $.toast({heading: 'Warning', text:'Name on card is required.*', icon: 'warning', position: 'top-right'});
                }
                $('#save-card-btn').attr('disabled', null).html('Save');
                return false;
            }

            var $form = $("#payment-form"),
                inputSelector = ['input[type=email]', 'input[type=password]', 'input[type=text]', 'input[type=file]', 'textarea'].join(', '),
                $inputs = $form.find('.required').find(inputSelector),
                $errorMessage = $form.find('div.error'),
                valid = true;
            $errorMessage.addClass('hide');
            $('.has-error').removeClass('has-error');
            $inputs.each(function (i, el) {
                var $input = $(el);
                if ($input.val() === '') {
                    $input.parent().addClass('has-error');
                    $errorMessage.removeClass('hide');
                    e.preventDefault();
                }
            });
            if (!$form.data('cc-on-file')) {
                e.preventDefault();
                Stripe.setPublishableKey($form.data('stripe-publishable-key'));
                Stripe.createToken({
                    number: $('.card-number').val(),
                    cvc: $('.card-cvc').val(),
                    exp_month: $('#expiration_month').val(),
                    exp_year: $('#expiration_year').val()
                }, stripeResponseHandler);
            }
        });
        function stripeResponseHandler(status, response) {
            if (response.error) {
                $.toast({
                    heading: 'Warning',
                    text: response.error.message,
                    icon: 'warning',
                    position: 'top-right',
                });
                $('#save-card-btn').attr('disabled', null).html('Save');

            } else {
                /* token contains id, last4, and card type */
                var token = response['id'];
                $form.find('input[type=text]').empty();
                $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
                //$form.get(0).submit();

                var route = 'payment.method.store';
                var data = new FormData($form[0]);
                $.ajax({
                    url: route,
                    type: "POST",
                    data: data,
                    dataType: "JSON",
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (data) {

                    },
                    error: function (xhr) {
                        $('#save-card-btn').attr('disabled', null).html('Save');
                        erroralert(xhr);
                    }
                });
            }
        }
    });
</script>
</body>

</html>
