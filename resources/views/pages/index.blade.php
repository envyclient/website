<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Envy Client</title>

    <link rel="icon" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="icon" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">

    <meta content="Envy Client" property="og:title">
    <meta content="Official website of the Envy Client" property="og:description">
    <meta content="{{ asset('logo.svg') }}" property="og:image">
    <meta content="128" property="og:image:width">
    <meta content="128" property="og:image:height">

    <link rel="stylesheet" href="{{ asset('css/bundle.css') }}">
</head>

<body>
<div class="nav">
    <a href="#" class="logo">
        <img src="{{ asset('logo.svg') }}" alt="logo">
    </a>
    <div class="menu">
        <a href="#features">Features</a>
        <a href="#media">Media</a>
        <a href="#pricing">Pricing</a>
        @guest
            <a href="{{ route('login') }}">Login</a>
        @else
            <a href="{{ route('home') }}">Home</a>
        @endguest
    </div>
</div>

<div class="landing">
    <div class="content">
        <div class="landing-container">
            <h1><span>Envy</span> Client</h1>
            <p>Envy is a premium Minecraft Hacked Client with the best features in the market.</p>
            <div class="btns">
                @guest
                    <a href="{{ route('login') }}" class="btn">Login</a>
                @else
                    <a href="{{ route('home') }}" class="btn">Home</a>
                @endguest
                <a href="#features" class="btn">Read more</a>
            </div>
        </div>
        <div class="landing-container">
            <img src="{{ asset('logo.svg') }}" alt="logo">
        </div>
    </div>
</div>

<div id="features" class="features">
    <h1>
        Features
        <span class="separator"></span>
    </h1>
    <div class="feature">
        <div class="feature-image">
            <img src="{{ asset('assets/features/feature1.png') }}" alt="feature-image">
        </div>
        <div class="feature-content">
            <h1>Powerful</h1>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsa quisquam culpa aliquam in eos quibusdam
                cumque, libero quos placeat debitis ullam cum ipsum, nemo praesentium impedit ad molestias! Quos,
                nostrum. Lorem ipsum dolor, sit amet consectetur adipisicing elit. Voluptates odio sit vel ratione, iure
                laudantium obcaecati! Corporis, ducimus architecto. Exercitationem ab inventore vitae officiis ducimus
                non nisi aliquid ea iure?</p>
        </div>
    </div>
    <div class="feature right">
        <div class="feature-content">
            <h1>Beautiful</h1>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsa quisquam culpa aliquam in eos quibusdam
                cumque, libero quos placeat debitis ullam cum ipsum, nemo praesentium impedit ad molestias! Quos,
                nostrum. Lorem ipsum dolor, sit amet consectetur adipisicing elit. Voluptates odio sit vel ratione, iure
                laudantium obcaecati! Corporis, ducimus architecto. Exercitationem ab inventore vitae officiis ducimus
                non nisi aliquid ea iure?</p>
        </div>
        <div class="feature-image">
            <img src="{{ asset('assets/features/feature2.png') }}" alt="feature-image">
        </div>
    </div>
</div>

<div class="media" id="media">
    <div class="media-content">
        <h1>
            Media
            <span class="separator"></span>
        </h1>
        <div class="videos">
            <div class="video-container">
                <iframe data-src="https://www.youtube.com/embed/4PQTDTmbUaA"
                        allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                        style="border:0;" allowfullscreen></iframe>
            </div>
            <div class="video-container">
                <iframe data-src="https://www.youtube.com/embed/4PQTDTmbUaA"
                        allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                        style="border:0;" allowfullscreen></iframe>
            </div>
        </div>
    </div>
</div>

<div class="pricing" id="pricing">
    <h1>
        Pricing
        <span class="separator"></span>
    </h1>
    <div class="price-tables">
        <div class="price">
            <h1>Standard</h1>
            <ul>
                <li>monthly updates</li>
                <li>killer features</li>
                <li>lorem ipsum</li>
                <li>dolor sit</li>
            </ul>
            <h2 class="subscription-price">$7.00<span>/month</span></h2>
        </div>
        <div class="price">
            <h1>Premium</h1>
            <ul>
                <li>monthly updates</li>
                <li>killer features</li>
                <li>lorem ipsum</li>
                <li>dolor sit</li>
            </ul>
            <h2 class="subscription-price">$10.00<span>/month</span></h2>
        </div>
    </div>
</div>

<div class="footer">
    <h1>
        <span>Envy</span> Client
        <span class="separator"></span>
    </h1>
    <div class="footer-content">
        <div class="content">
            <a href="#" class="menu-link">Home</a><br>
            <a href="#features" class="menu-link">Features</a><br>
            <a href="#media" class="menu-link">Media</a><br>
            <a href="#pricing" class="menu-link">Pricing</a><br>
            <a href="{{ route('pages.terms') }}" class="menu-link">Terms</a>
        </div>
        {{--     <p class="content">
                 &copy; 2020 Haq Development
             </p>--}}
    </div>
</div>

<script src="{{ asset('js/bundle.js') }}" type="text/javascript"></script>
<script type="text/javascript">
    window.onload = function init() {
        const vidDefer = document.getElementsByTagName('iframe');
        for (let i = 0; i < vidDefer.length; i++) {
            if (vidDefer[i].getAttribute('data-src')) {
                vidDefer[i].setAttribute('src', vidDefer[i].getAttribute('data-src'));
            }
        }
    };
</script>
</body>

</html>
