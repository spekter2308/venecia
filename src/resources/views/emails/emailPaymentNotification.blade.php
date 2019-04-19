<h1>VENEZIA - Інформація про замовлення </h1>


@foreach($data as $item)

  Назва продукту :  {!! $item['productName']!!} <br>
  ПІБ :  {!! $item['fullName']!!} <br>
  Телефон :  {!! $item['phone']!!} <br>
  Пошта покупця :  {!! $item['userEmail']!!} <br>
  Місто  :  {!! $item['city']!!} <br>
  Відділення нової пошти  :  {!! $item['postDepartment']!!} <br>
  Ціна  :  {!! $item['price']!!} <br>
  Кількість продуктів :  {!! $item['productQty']!!} <br>
  Розмір продукту  :  {!! $item['size']!!} <br>
  Колір продукту : <div  style="background-color:{{$item['color']}};height: 20px;width:20px;  overflow: hidden">  </div> <br>
 @if($item['discount']!= 0)
   Знижка  :  {!! $item['discount']!!}  %<br>
   Загальна сума зі знижкою :  {!! $item['totalPrice']!!} <br><br><hr><br>
 @else
  Загальна сума :  {!! $item['totalPrice']!!} <br><br><hr><br>
@endif
@endforeach
