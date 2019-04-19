

<div id="wrapper">

    <!-- Sidebar -->
    <div id="sidebar-wrapper">
        <ul class="sidebar-nav">
            <li>
                <a href="{{ url('admin/dashboard') }}">Статистика</a>
            </li>
            <li>
                <a href="{{ url('admin/categories') }}">Категорії</a>
            </li>
            <li>
                <a href="{{ route('admin.pages.filters') }}">Фільтри</a>
            </li>
            {{--<li>--}}
                {{--<a href="{{ url('admin/brands') }}">Бренди</a>--}}
            {{--</li>--}}
            <li>
                <a href="{{ url('admin/products') }}">Продукти</a>
            </li>
            <li>
                <a href="{{ url('admin/email') }}">Розсилка листів</a>
            </li>
            <li>
                <a href="{{ url('admin/mainImages') }}">Картинки в слайдер</a>
            </li>
            <li>
                <a href="{{ url('admin/colorPiker') }}">Редагування палітри кольорів</a>
            </li>
            <li>
                <a href="{{ url('admin/settings') }}">Налаштування</a>
            </li>
            <li>
                <a href="{{ url('admin/pages') }}">Сторінки</a>
            </li>
            <li>
                <a href="{{ url('admin/reviews') }}">Відгуки</a>
            </li>

        </ul>
    </div>
