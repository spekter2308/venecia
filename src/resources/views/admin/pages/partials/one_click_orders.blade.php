<table class="table table-hover table-bordered">
    <thead>
    <tr>
        <th>Ім'я замовника</th>
        <th>Телефон</th>
        <th>Фото</th>
        <th>Товар</th>
        <th>Колір</th>
        <th>Дата</th>
        <th>Опрацювати</th>
        <th>Видалити</th>
    </tr>
    </thead>
    <tbody>
    @if(count($one_click_orders))
        @foreach($one_click_orders as $one_click_order)
            <tr>
                <td>
                    {{ $one_click_order->user ? $one_click_order->user->username : "-" }}
                </td>
                <td>{{ $one_click_order->phone_number }}</td>
                <td>
                    <img
                            src="/{{ count($one_click_order->product->photos) ? $one_click_order->product->photos[0]->thumbnail_path : "" }}"
                            alt="photo"
                            style="width: 100px; height: 100px"
                    >
                </td>
                <td>{{ $one_click_order->product->product_name }} {{ $one_click_order->product->product_sku }}</td>
                <td>
                    <div
                           @if ($one_click_order->product->product_information->count()) style="background-color:{{ $one_click_order->product->product_information[0]->color }};  @endif                              height: 20px;width:20px;">  </div>
                </td>
                <td>
                    {{ $one_click_order->created_at }}
                </td>
            
                <td class="text-center">
                    <a href="{{ route('admin_pages_add_order', ['id' => $one_click_order->id]) }}">
                        <button type="submit" style="background-color: transparent; border: 0;">
                            <i class="material-icons green-text">edit</i>
                        </button>
                    </a>
                </td>
            
                <td class="text-center">
                    <form method="post" action="{{ route('one_click_order_delete', ['id' => $one_click_order->id]) }}" class="delete_form_user">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" style="background-color: transparent; border: 0;">
                            <i class="material-icons red-text">delete_forever</i>
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    @endif
    </tbody>
</table>