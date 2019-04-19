@extends('admin.dash')

@section('content')



        <a href="#menu-toggle" class="btn btn-default" id="menu-toggle"><i class="fa fa-bars fa-5x"></i></a>
        <a href="{{ url('admin/dashboard') }}" class="btn btn-danger">Назад</a>
        <br><br>
        <div class="col-md-12">
            <h3 class="text-center">Повідомлення</h3>
           <table class="table table-hover table-bordered dataTable">
                    <thead>
                    <tr>
                        <th>
                            Пошта відправника
                        </th>
                        <th>
                            Повідомлення
                        </th>

                        <th></th>

                    </tr>
                    </thead>
                    <tbody>
                        @foreach($callbacks as $callback)
                            <tr>
                                <td>
                                    <a href="{{url('/admin/callback/send-mail',$callback->id)}}"> {{$callback->email}} </a>
                                </td>
                                <td>
                                    {{$callback->message}}
                                </td>
                                <td class="text-center">
                                    <form method="post" action="{{ route('deleteCallback', $callback->id) }}"
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

        </div>








@endsection

