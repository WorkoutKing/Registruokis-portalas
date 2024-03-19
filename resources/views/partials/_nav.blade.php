<header style="min-height: fit-content;padding: 20px;">
   <div class="nav-menu">
      <div class="branding-web">
         {{--  <a class="nav-brand-name" href='/'><img src="#!"></a>  --}}
       </div>
   <div class="nav-links">
      <ul class="top-nav">
        @if(auth()->check() && auth()->user()->isAdmin())
            <li><a href="/admin/dashboard">Admin zona</a></li>
        @endif
          @if(Auth::check())
            <li><a href="/events">Renginiai</a></li>
            <li><a href="/events/create">Kurti Renginį</a></li>
            <li><a href="/">Mano Renginiai</a></li>
            <li><a href="/logout">Atsijungti</a></li>
          @else

            <li><a href="/login">Prisijungti</a></li>
            <li><a href="/register">Registruotis</a></li>
          @endif
        </ul>
      </div>
     </div>
</header>