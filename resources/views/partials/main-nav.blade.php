<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('home') }}">1ClickAllEat</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('restaurants.index') }}">
                        <i class="bi bi-shop"></i> Restaurants
                    </a>
                </li>
                @auth
                    @if(auth()->user()->role && auth()->user()->role->name === 'restaurateur')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('restaurateur.dashboard') }}">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                        </li>
                    @endif
                    @if(auth()->user()->role && auth()->user()->role->name === 'admin')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-shield-lock"></i> Administration
                            </a>
                        </li>
                    @endif
                @endauth
            </ul>
            <ul class="navbar-nav">
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            <i class="bi bi-box-arrow-in-right"></i> Connexion
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">
                            <i class="bi bi-person-plus"></i> Inscription
                        </a>
                    </li>
                @else
                    @if(auth()->user()->role && auth()->user()->role->name === 'client')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('cart.index') }}">
                                <span class="cart-icon">
                                    <i class="bi bi-cart3"></i>
                                    @if(session()->has('cart') && !empty(session('cart')['items']))
                                        <span class="badge bg-danger cart-badge">{{ count(session('cart')['items']) }}</span>
                                    @endif
                                </span>
                            </a>
                        </li>
                    @endif
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}">
                                <i class="bi bi-person"></i> Mon profil
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('orders.index') }}">
                                <i class="bi bi-receipt"></i> Mes commandes
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-box-arrow-right"></i> Déconnexion
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
