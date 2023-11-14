<!doctype html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    {{-- <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'> --}}
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="LifeStory description">
    <meta name="keywords" content="">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('front-end-assets/favicon.ico') }}" />
    <title>{{ env('APP_NAME') }}</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet" />

    <link href="{{ asset('admin-assets/vendors/enjoyhint/enjoyhint.css') }}" type="text/css" rel="stylesheet">

    <link href="{{ asset('admin-assets/assets/css/app.min.css') }}" type="text/css" rel="stylesheet">
    <link href="{{ asset('admin-assets/vendors/bundle.css') }}" type="text/css" rel="stylesheet">

    <script src="https://www.google.com/recaptcha/enterprise.js?render={{ env('GOOGLE_RECAPTCHA_API_KEY') }}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    {{-- Custom css --}}
    <link href="{{ asset('css/custom.css') }}" rel='stylesheet'>
    {{-- <link href="https://www.dafontfree.net/embed/YnJpdGFubmljLWJvbGQtcmVndWxhciZkYXRhLzEzL2IvNjQ1MzAvQlJJVEFOSUMuVFRG" rel="stylesheet" type="text/css"/> --}}

</head>

<body class="ltr app horizontal landing-page form-membership navigation-toggle-one">
    <div id="app">
    </div>

    <script src="{{ asset('js/jquery.min.js') }}"></script>
    @vite('resources/js/app.js')

    <script src="{{ asset('front-end-assets/plugins/bootstrap/js/popper.min.js') }}"></script>
    <script src="{{ asset('front-end-assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>

    <script src="{{ asset('helpers.js') }}"></script>
    <script src="{{ asset('admin-assets/vendors/bundle.js') }}"></script>
    <script src="{{ asset('admin-assets/vendors/enjoyhint/enjoyhint.min.js') }}"></script>
    <script src="{{ asset('admin-assets/assets/js/app.min.js') }}"></script>

    <!-- Include Select2 from a CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <script>
        function applyStylingToParentAnchors() {
            const bookedElements = document.querySelectorAll('.booked');
            bookedElements.forEach(booked => {
                const parentAnchor = booked.closest('a');
                if (parentAnchor) {
                    parentAnchor.style.backgroundColor = 'red';
                    parentAnchor.style.border = '1px solid white';
                }
            });

            var backButton = document.querySelector('.wizard-footer-left button');

            if (backButton) {

                // Apply the CSS using the style attribute
                backButton.style.backgroundColor = 'white';
                backButton.style.borderColor = '#6096b4';
                backButton.style.color = '#6096b4';
            }

            // Find the button with text "Back to Home"
            // const button = document.querySelector(".wizard-footer-right button");
            // if (button && button.textContent === "Back to Home") {
            //     button.classList.add("centered-button");
            // }
            
        }

        // Create a MutationObserver instance
        const observer = new MutationObserver(mutationsList => {
            for (const mutation of mutationsList) {
                if (mutation.type === 'childList') {
                    applyStylingToParentAnchors();
                }
            }
        });

        // Observe changes to the document's body
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    </script>
</body>

</html>
