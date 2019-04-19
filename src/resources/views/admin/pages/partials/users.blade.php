<table class="table table-hover table-bordered">
    <thead>
    <tr>
        <th>Ім'я користувача</th>
        <th>Пошта</th>
        <th>Приєднався</th>
        <th>Видалити</th>
    </tr>
    </thead>
    <tbody>
    @foreach($users as $user)
        <tr>
            <td>
                {{ $user->username }}
            </td>
            <td>
                {{ $user->email }}
            </td>
            <td>
                {{ prettyDate($user->created_at) }}
            </td>

            <td class="text-center">
                <form method="post" action="{{ route('admin.delete', $user->id) }}" class="delete_form_user">
                    {{ csrf_field() }}
                    <input type="hidden" name="_method" value="DELETE">
                    <button id="delete-user-btn">
                        <i class="material-icons red-text">delete_forever</i>
                    </button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>