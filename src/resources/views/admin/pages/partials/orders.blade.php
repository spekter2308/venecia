<div class="menu">
    <div class="accordion">

        @if ($orders->count() == 0)
            Нема замовлень
        @else
            <table class="table table-hover table-bordered dataTable">
                <thead>
                <tr>
                    <th>
                        Пошта замовника
                    </th>
                    <th>
                        ПІБ замовника
                    </th>
                    <th>
                        Телефон замовника
                    </th>
                    <th>
                        Місто замовника
                    </th>
                    <th>
                        Відділення замовника
                    </th>
                    <th>
                        Номер замовлення
                    </th>
                    <th>
                        Спосіб оплати
                    </th>
                    <th>
                        Артикул
                    </th>
                    <th>
                        Кількість продуктів
                    </th>
                    <th>
                        Колір продукту
                    </th>
                    <th>
                        Розмір продукту
                    </th>
                    <th>
                        Ціна продукту
                    </th>
                    <th>
                       Розмір знижки
                    </th>
                    <th>
                        Загальна ціна
                    </th>
                    <th>
                        Активні дії
                    </th>

                </tr>
                </thead>
                <tbody>
                @foreach($orders as $order)
                    @if($order->state!= null)
                        <tr class="success">
                    @else
                        <tr>
                            @endif
                            <td>
                                {{ (isset($order->user) ? $order->user->email : $order->email) }}
                            </td>
                            <td>
                                {{$order->full_name}}
                            </td>
                            <td>
                                {{$order->phone}}
                            </td>
                            <td>
                                {{$order->city->city_ua}}
                            </td>
                            <td>
                                {{$order->postDepartment}}
                            </td>
                            <td>
                                {{$order->order}}
                            </td>
                            <td>
                                {{ ($order->payment_method != "") ? trans('messages.'.$order->payment_method) : "-" }}
                            </td>
                            <td>
                                @php
                                try{
                                echo $order->product->product_sku;
                                }
                                catch(Exception $ex) {
                                echo "Товар відсутній!";
                                }
                                @endphp
                            </td>
                            <td>
                                {{$order->product_qty}}
                            </td>
                            <td >
                                <div  style="background-color:{{$order->color}};height: 20px;width:20px;  overflow: hidden">  </div>

                            </td>
                            <td>
                                {{$order->size}}
                            </td>

                            <td>
                                {{$order->price}}
                            </td>
                            <td>
                                {{$order->discount}} %
                            </td>
                            <td>
                                {{$order->total_price}}
                            </td>
                            <td class="text-center">
                                <form method="post" action="{{ route('setStatus', $order->id) }}"
                                      class="delete_form_user">
                                    {{ csrf_field() }}
                                    <button id="order-status-user-btn">
                                        <i class="material-icons green-text">check_circle</i>
                                    </button>
                                </form>
                                <form method="post" action="{{ route('deleteOrder', $order->id) }}"
                                      class="delete_form_user">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button id="order-status-user-btn">
                                        <i class="material-icons red-text">delete_forever</i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                </tbody>
            </table>

        @endif
    </div>
</div>
