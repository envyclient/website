@extends('layouts.base')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('body')
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
                <a href="{{ route('home') }}">Dashboard</a>
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
                        <a href="{{ route('home') }}" class="btn">Dashboard</a>
                    @endguest
                    <a href="#features" class="btn">Read more</a>
                    <a href="https://discord.gg/5UrBctTnWA" class="btn">
                        <svg width="24" height="24" fill="currentColor"
                             viewBox="0 0 16 16">
                            <path
                                d="M6.552 6.712c-.456 0-.816.4-.816.888s.368.888.816.888c.456 0 .816-.4.816-.888.008-.488-.36-.888-.816-.888zm2.92 0c-.456 0-.816.4-.816.888s.368.888.816.888c.456 0 .816-.4.816-.888s-.36-.888-.816-.888z"/>
                            <path
                                d="M13.36 0H2.64C1.736 0 1 .736 1 1.648v10.816c0 .912.736 1.648 1.64 1.648h9.072l-.424-1.48 1.024.952.968.896L15 16V1.648C15 .736 14.264 0 13.36 0zm-3.088 10.448s-.288-.344-.528-.648c1.048-.296 1.448-.952 1.448-.952-.328.216-.64.368-.92.472-.4.168-.784.28-1.16.344a5.604 5.604 0 0 1-2.072-.008 6.716 6.716 0 0 1-1.176-.344 4.688 4.688 0 0 1-.584-.272c-.024-.016-.048-.024-.072-.04-.016-.008-.024-.016-.032-.024-.144-.08-.224-.136-.224-.136s.384.64 1.4.944c-.24.304-.536.664-.536.664-1.768-.056-2.44-1.216-2.44-1.216 0-2.576 1.152-4.664 1.152-4.664 1.152-.864 2.248-.84 2.248-.84l.08.096c-1.44.416-2.104 1.048-2.104 1.048s.176-.096.472-.232c.856-.376 1.536-.48 1.816-.504.048-.008.088-.016.136-.016a6.521 6.521 0 0 1 4.024.752s-.632-.6-1.992-1.016l.112-.128s1.096-.024 2.248.84c0 0 1.152 2.088 1.152 4.664 0 0-.68 1.16-2.448 1.216z"/>
                        </svg>
                        Discord
                    </a>
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
                <img src="{{ asset('assets/features/client.png') }}" alt="feature-image">
            </div>
            <div class="feature-content" style="margin-left: 30px;">
                <h1>Client</h1>
                <ul>
                    <li>Designed to bypass Watchdog.</li>
                    <li>
                        Comes with a built-in account manager baked into the multiplayer menu.
                    </li>
                    <li>
                        Supports the use of
                        <a target="_blank" href="https://thealtening.com/" style="color: white">The Altening</a>.
                    </li>
                    <li>Features latest OptiFine to provide maximum performance.</li>
                </ul>
            </div>
        </div>
        <div class="feature right">
            <div class="feature-content">
                <h1>Custom Launcher</h1>
                <p>
                    Envy comes with a custom launcher written using C++ to provide the smoothest possible cheating
                    experience.
                </p>
            </div>
            <div class="feature-image">
                <img src="{{ asset('assets/features/launcher.png') }}" alt="feature-image">
            </div>
        </div>
        <div class="feature">
            <div class="feature-image">
                <img src="{{ asset('assets/features/configs.png') }}" alt="feature-image">
            </div>
            <div class="feature-content">
                <h1>Web Configs</h1>
                <p>
                    The new config system allows the ability to store and load configs from the web, providing complete
                    unity across different versions.
                </p>
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
                    <div class="video-image">
                        <img id="video-1-image"
                             src="https://i1.ytimg.com/vi/SL5BOCkN_4E/maxresdefault.jpg"
                             alt="thumbnail">
                        <span onClick="playVideo(this, 'video-1', 'video-1-image');"></span>
                        <iframe id="video-1"
                                class="video-image"
                                data-src="https://youtube.com/embed/SL5BOCkN_4E"
                                allow="autoplay; encrypted-media;"
                                allowfullscreen>
                        </iframe>
                    </div>
                </div>

                <div class="video-container">
                    <div class="video-image">
                        <img id="video-2-image"
                             src="https://i1.ytimg.com/vi/PrlANlbK95Q/maxresdefault.jpg"
                             alt="thumbnail">
                        <span onClick="playVideo(this, 'video-2', 'video-2-image');"></span>
                        <iframe id="video-2"
                                class="video-image"
                                data-src="https://youtube.com/embed/PrlANlbK95Q"
                                allow="autoplay; encrypted-media;"
                                allowfullscreen>
                        </iframe>
                    </div>
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
                    <li>5 configs</li>
                </ul>
                <h2 class="subscription-price">$7.00<span>/month</span></h2>
                <a href="{{ route('home.subscription') }}" class="btn" style="margin-top: 15px;">
                    buy now
                </a>
            </div>
            <div class="price">
                <h1>Premium</h1>
                <ul>
                    <li>monthly updates</li>
                    <li>killer features</li>
                    <li>capes access</li>
                    <li>beta access</li>
                    <li>15 configs</li>
                </ul>
                <h2 class="subscription-price">$10.00<span>/month</span></h2>
                <a href="{{ route('home.subscription') }}" class="btn" style="margin-top: 15px;">
                    buy now
                </a>
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
                <a href="{{ route('terms') }}" class="menu-link">Terms</a>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        // play the clicked video
        function playVideo(span, frameID, imageID) {
            const frame = document.getElementById(frameID);
            const image = document.getElementById(imageID);

            // hiding the play button
            span.style.display = "none";

            // hiding the video thumbnail
            image.style.display = "none";

            // showing the iframe and playing the video
            frame.style.display = "block";
            frame.src = frame.getAttribute('data-src') + "?autoplay=1";
        }
    </script>
@endsection
