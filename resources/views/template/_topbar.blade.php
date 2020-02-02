@if (!Auth::check())
    <div class="topbar">
        <div class="container">
            <a href="{{ route('home.book-now') }}">Login</a>
            <a href="{{ route('home.book-now') }}">Haven't Registered Yet?</a>
        </div>
    </div>
@endif