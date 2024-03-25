<header style="min-height: fit-content;padding: 10px 20px;">
    <div class="nav-menu">
        <div class="branding-web">
            <a class="nav-brand-name" href='/'><span class="logo_word_stl">REGISTRUOKIS</span></a>
        </div>
        <div class="nav-links">
            <input type="checkbox" id="burger-toggle" class="burger-toggle">
            <label for="burger-toggle" class="burger-icon"></label>
            <ul class="top-nav">
                @if(auth()->check() && auth()->user()->isAdmin())
                    <li><a href="/admin/dashboard">Admin zona</a></li>
                @endif
                @if(Auth::check())
                    <li><a href="/">Pagrindinis</a></li>
                    <li><a href="/events/create">Kurti Renginį</a></li>
                    <li><a href="/profile">Mano Profilis</a></li>
                    <li><a href="/logout">Atsijungti</a></li>
                @else
                    <li><a href="/login">Prisijungti</a></li>
                    <li><a href="/register">Registruotis</a></li>
                @endif
            </ul>
        </div>
    </div>
</header>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $('.burger-toggle').change(function() {
            if ($(this).is(":checked")) {
                $('.top-nav').slideDown();
            } else {
                $('.top-nav').slideUp();
            }
        });
    });
</script>

<style>
      span.logo_word_stl {
        color: #fff;
        font-weight: 700;
        font-size: 22px;
        font-style: oblique;
        font-family: system-ui;
    }
    .burger-toggle {
        display: none;
    }

    .burger-icon {
        display: none;
        cursor: pointer;
    }

    .burger-icon:before {
        content: "\2630";
        font-size: 24px;
    }

    @media (max-width: 768px) {
        .burger-icon {
            display: block;
        }

        .top-nav li {
            display: block;
            margin-bottom: 10px;
            width: 100%;
            padding: 15px;
            text-align: justify;
            border-bottom: 1px solid #fff;
            margin: 0px!important;
        }

        .top-nav {
            display: none;
            text-align: center;
            background-color: #000;
            padding: 10px;
            position: absolute;
            width: 40%;
            right: 0px;
            z-index: 999;
            top:64px;
        }

        .burger-toggle:checked ~ .nav-links .top-nav {
            display: block;
        }

        .top-nav li {
            display: block;
            margin-bottom: 10px;
        }

        .top-nav li:last-child {
            margin-bottom: 0;
        }

        .top-nav li a {
            color: #333;
            text-decoration: none;
            font-weight: bold;
        }

        .top-nav li a:hover {
            color: #007bff;
        }
    }
</style>
