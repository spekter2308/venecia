@extends('admin.dash')

@section('content')

    <div class="container-fluid" id="admin-category-container">
            <br><br>
        <a href="#menu-toggle" class="btn btn-default" id="menu-toggle"><i class="fa fa-bars fa-5x"></i></a>
        <a href="{{ url('admin/categories/add') }}" class="btn btn-primary">Додати нову категорію</a>
            <br><br>

        <div class="col-md-12" id="admin-category-container">
        <ul class="collection with-header">
            @foreach ($categories->sortBy('sequence') as $category)
            <li class="collection-item blue">
                <h5 class="white-text">№{{ $category->sequence }} | {{ $category->category }}</h5>
                <li class="collection-item primary-color">
                    <div class="col-xs-3 col-sm-2 col-md-2">
                        <form method="post" action="{{ route('admin.category.delete', $category->id) }}" class="delete_form">
                            {{ csrf_field() }}
                            <input type="hidden" name="_method" value="DELETE">
                            <button class="delete-btn">
                                <i class="material-icons delete-white">delete_forever</i>
                            </button>
                        </form>
                    </div>
                    <a href="{{ route('admin.category.edit', $category->id) }}">
                        <i class="material-icons edit-white">mode_edit</i>
                    </a>
                    <a href="{{ route('admin.category.addsub', $category->id) }}" id="sub-category">+ Підкатегорію</a>
                </li>
            </li>
                @foreach ($category->children->sortBy('sequence') as $children)
                <li class="collection-item">

                    <a href="#!" class="secondary-content">
                        <form method="post" action="{{ route('admin.category.delete', $children->id) }}" class="delete_form_sub">
                            {{ csrf_field() }}
                            <input type="hidden" name="_method" value="DELETE">
                            <button class="delete-btn-sub">
                                <i class="material-icons delete">delete_forever</i>
                            </button>
                        </form>
                    </a>
                    <a href="{{ route('admin.category.editsub', $children->id) }}" class="secondary-content">
                        <i class="material-icons edit">mode_edit</i>
                    </a>
                    <span>№{{ $children->sequence }} | {{ $children->category }}</span>
                    <a href="{{ route('admin.category.addsub', $children->id) }}" class="add-sub-cat"><b class="add">+ Підкатегорію</b></a>
                </li>
                    @foreach ($children->children->sortBy('sequence') as $children)
                        <li class="collection-sub-item">
                            <a href="{{ route('admin.category.editsub', $children->id) }}" class="secondary-content">
                                <i class="material-icons edit">mode_edit</i>
                            </a>
                            <a href="#!" class="secondary-content">
                                <form method="post" action="{{ route('admin.category.deletesub', $children->id) }}" class="delete_form_sub">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button class="delete-btn-sub">
                                        <i class="material-icons delete">delete_forever</i>
                                    </button>
                                </form>
                            </a>
                            <span >№{{ $children->sequence }} | {{ $children->category }}</span>
                        </li>
                    @endforeach
                @endforeach
            @endforeach
        </ul>
        </div>

    </div>  <!-- close container -->

@endsection
