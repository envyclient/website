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
                <a href="{{ route('dashboard') }}">Dashboard</a>
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
                        <a href="{{ route('dashboard') }}" class="btn">Dashboard</a>
                    @endguest
                    <a href="#features" class="btn">Read more</a>
                    <a href="https://discord.gg/5UrBctTnWA" class="btn">
                        <svg style="width:24px;height:24px; float: left; margin-left: 15px;" viewBox="0 0 24 24">
                            <path fill="currentColor"
                                  d="M22,24L16.75,19L17.38,21H4.5A2.5,2.5 0 0,1 2,18.5V3.5A2.5,2.5 0 0,1 4.5,1H19.5A2.5,2.5 0 0,1 22,3.5V24M12,6.8C9.32,6.8 7.44,7.95 7.44,7.95C8.47,7.03 10.27,6.5 10.27,6.5L10.1,6.33C8.41,6.36 6.88,7.53 6.88,7.53C5.16,11.12 5.27,14.22 5.27,14.22C6.67,16.03 8.75,15.9 8.75,15.9L9.46,15C8.21,14.73 7.42,13.62 7.42,13.62C7.42,13.62 9.3,14.9 12,14.9C14.7,14.9 16.58,13.62 16.58,13.62C16.58,13.62 15.79,14.73 14.54,15L15.25,15.9C15.25,15.9 17.33,16.03 18.73,14.22C18.73,14.22 18.84,11.12 17.12,7.53C17.12,7.53 15.59,6.36 13.9,6.33L13.73,6.5C13.73,6.5 15.53,7.03 16.56,7.95C16.56,7.95 14.68,6.8 12,6.8M9.93,10.59C10.58,10.59 11.11,11.16 11.1,11.86C11.1,12.55 10.58,13.13 9.93,13.13C9.29,13.13 8.77,12.55 8.77,11.86C8.77,11.16 9.28,10.59 9.93,10.59M14.1,10.59C14.75,10.59 15.27,11.16 15.27,11.86C15.27,12.55 14.75,13.13 14.1,13.13C13.46,13.13 12.94,12.55 12.94,11.86C12.94,11.16 13.45,10.59 14.1,10.59Z"/>
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
                <a href="{{ route('pages.subscription') }}" class="btn" style="margin-top: 10px;">buy now</a>
            </div>
            <div class="price">
                <h1>Premium</h1>
                <ul>
                    <li>monthly updates</li>
                    <li>killer features</li>
                    <li>15 configs</li>
                    <li>capes access</li>
                    <li>beta access</li>
                </ul>
                <h2 class="subscription-price">$10.00<span>/month</span></h2>
                <a href="{{ route('pages.subscription') }}" class="btn" style="margin-top: 10px;">buy now</a>
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
