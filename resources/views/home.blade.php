<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MarketPlace — Главная</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800 min-h-screen flex flex-col">

{{--
    Маршруты, которые нужно добавить в routes/web.php,
    чтобы кнопки/формы заработали (пока — просто 404 при клике):

    Route::view('/', 'home')->name('home');
    Route::view('/profile', 'profile')->name('profile');      // только для auth
    Route::view('/cart', 'cart')->name('cart');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');

    Формы в шаблоне намеренно используют url('/...') вместо route('...'),
    чтобы страница рендерилась даже до определения маршрутов.
    Когда добавишь маршруты — можешь заменить url('/cart/add') на route('cart.add') и т.д.
--}}

@php
    // Fallback-данные, если контроллер не передал $products
    $products = $products ?? [
        ['id' => 1, 'name' => 'Наушники Wireless',   'price' => 2490, 'image' => 'https://picsum.photos/seed/1/400/400'],
        ['id' => 2, 'name' => 'Смарт-часы',          'price' => 5990, 'image' => 'https://picsum.photos/seed/2/400/400'],
        ['id' => 3, 'name' => 'Кроссовки беговые',   'price' => 3490, 'image' => 'https://picsum.photos/seed/3/400/400'],
        ['id' => 4, 'name' => 'Рюкзак городской',    'price' => 1890, 'image' => 'https://picsum.photos/seed/4/400/400'],
        ['id' => 5, 'name' => 'Кофемолка ручная',    'price' => 1290, 'image' => 'https://picsum.photos/seed/5/400/400'],
        ['id' => 6, 'name' => 'Лампа настольная',    'price' => 990,  'image' => 'https://picsum.photos/seed/6/400/400'],
        ['id' => 7, 'name' => 'Термокружка 500 мл',  'price' => 1490, 'image' => 'https://picsum.photos/seed/7/400/400'],
        ['id' => 8, 'name' => 'Книга "Clean Code"',  'price' => 2190, 'image' => 'https://picsum.photos/seed/8/400/400'],
    ];
    $cartCount = $cartCount ?? 0;
@endphp

{{-- ===== HEADER ===== --}}
<header class="bg-white shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 py-3 flex items-center gap-4">
        <a href="{{ url('/') }}" class="text-2xl font-bold text-indigo-600 shrink-0">MarketPlace</a>

        <form action="{{ url('/') }}" method="GET" class="flex-1 flex">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Искать товары..."
                   class="w-full border border-gray-300 rounded-l-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <button type="submit" class="bg-indigo-600 text-white px-6 rounded-r-lg hover:bg-indigo-700">
                Найти
            </button>
        </form>

        <nav class="flex items-center gap-4 text-sm shrink-0">
            @auth
                <a href="{{ url('/profile') }}" class="hover:text-indigo-600">Профиль</a>
                <form action="{{ url('/logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="hover:text-indigo-600">Выйти</button>
                </form>
            @else
                <a href="{{ url('/login') }}" class="hover:text-indigo-600">Войти</a>
            @endauth

            <a href="{{ url('/cart') }}" class="relative hover:text-indigo-600">
                🛒 Корзина
                @if ($cartCount > 0)
                    <span class="absolute -top-2 -right-3 bg-red-500 text-white text-xs rounded-full px-1.5">
                        {{ $cartCount }}
                    </span>
                @endif
            </a>
        </nav>
    </div>
</header>

{{-- ===== HERO ===== --}}
<section class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
    <div class="max-w-7xl mx-auto px-4 py-10 text-center">
        <h1 class="text-3xl md:text-4xl font-bold mb-2">Тысячи товаров в одном месте</h1>
        <p class="opacity-90">Покупай выгодно — доставка по всей России</p>
    </div>
</section>

{{-- ===== ТОВАРЫ ===== --}}
<main class="flex-1 max-w-7xl mx-auto px-4 py-10 w-full">
    <h2 class="text-2xl font-bold mb-6">Товары</h2>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach ($products as $p)
            <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-lg transition group flex flex-col">
                <div class="aspect-square overflow-hidden bg-gray-100">
                    <img src="{{ $p['image'] }}" alt="{{ $p['name'] }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                </div>

                <div class="p-4 flex flex-col flex-1">
                    <div class="font-medium mb-2 line-clamp-2 flex-1">{{ $p['name'] }}</div>
                    <div class="text-lg font-bold text-gray-900 mb-3">
                        {{ number_format($p['price'], 0, '', ' ') }} ₽
                    </div>

                    <form action="{{ url('/cart/add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $p['id'] }}">
                        <button type="submit"
                                class="w-full bg-indigo-600 text-white text-sm py-2 rounded-lg hover:bg-indigo-700 transition">
                            В корзину
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
</main>

{{-- ===== FOOTER ===== --}}
<footer class="bg-gray-900 text-gray-300">
    <div class="max-w-7xl mx-auto px-4 py-6 flex flex-col md:flex-row items-center justify-between gap-4 text-sm">
        <div>© {{ date('Y') }} MarketPlace. Все права защищены.</div>
        <nav class="flex gap-6">
            <a href="{{ url('/') }}" class="hover:text-white">Главная</a>
            <a href="{{ url('/profile') }}" class="hover:text-white">Профиль</a>
            <a href="{{ url('/cart') }}" class="hover:text-white">Корзина</a>
        </nav>
    </div>
</footer>

</body>
</html>