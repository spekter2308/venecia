
{{--@if ($carts->count() == 0)--}}
    {{--<p>Не знайдено жодного активного сесії</p>--}}
{{--@else--}}
    {{--<table class="table table-bordered table-responsive table-condensed">--}}
        {{--<thead>--}}
        {{--<tr>--}}
            {{--<th>Cart ID</th>--}}
            {{--<th>Пошта користувача</th>--}}
            {{--<th>Ім'я користувача</th>--}}
            {{--<th>Назва продукту</th>--}}
            {{--<th>Створено в</th>--}}
            {{--<th>Видалити</th>--}}

        {{--</tr>--}}
        {{--</thead>--}}
        {{--<tbody>--}}
            {{--@foreach($carts as $cart)--}}
            {{--<tr>--}}

                {{--<td>#{{ $cart->id }}</td>--}}
                {{--<td>{{ $cart->user->email }}</td>--}}
                {{--<td>{{ $cart->user->username }}</td>--}}
                {{--<td>{{ $cart->products->product_name }}</td>--}}
                {{--<td>{{ prettyDate($cart->created_at) }}</td>--}}
                {{--<td class="text-center">--}}
                    {{--<form method="post" action="{{ route('admin.cart.delete', $cart->id) }}">--}}
                        {{--{{ csrf_field() }}--}}
                        {{--<input type="hidden" name="_method" value="DELETE">--}}
                        {{--<button id="delete-cart-btn">--}}
                            {{--<i class="material-icons red-text">delete_forever</i>--}}
                        {{--</button>--}}
                    {{--</form>--}}
                {{--</td>--}}

            {{--</tr>--}}
            {{--@endforeach--}}
        {{--</tbody>--}}
    {{--</table>--}}
{{--@endif--}}