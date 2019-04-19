@extends('admin.dash')

@section('content')

    <div class="container" id="admin-category-container">

        <div id="app">
            <app-main></app-main>
        </div>

        {{--<br><br>--}}
        {{--<a href="#menu-toggle" class="btn btn-default" id="menu-toggle"><i class="fa fa-bars fa-5x"></i></a>--}}
        {{--<a href="{{ route('admin.pages.index') }}" class="btn btn-danger">Назад</a>--}}
        {{--<br><br>--}}

        {{--<div class="col-sm-8 col-md-9" id="admin-category-container">--}}
            {{--<ul class="collection with-header">--}}
                {{--<form role="form" method="POST" action="">--}}
                    {{--{{ csrf_field() }}--}}
                    {{--<li class="collection-item blue">--}}
                        {{--<h4 class="white-text text-center">--}}
                            {{--Додати типи фільтрів--}}
                        {{--</h4>--}}
                    {{--</li>--}}
                    {{--<li class="collection-item">--}}
                        {{--<label for="category">Додати тип фільтру</label>--}}
                        {{--<div class="form-group">--}}
                            {{--<input type="text" class="form-control" placeholder="Додати тип фільтру">--}}
                        {{--</div>--}}
                        {{--<div class="form-group">--}}
                            {{--<button type="submit" class="btn btn-primary">Додати фільтр</button>--}}
                        {{--</div>--}}
                    {{--</li>--}}
                    {{--<li class="collection-item blue">--}}
                        {{--<h4 class="white-text text-center">--}}
                            {{--Існуючі фільтри--}}
                        {{--</h4>--}}
                    {{--</li>--}}
                    {{--<li class="collection-item">--}}
                        {{--<label for="category_ru">Фільтри</label>--}}
                        {{--<div class="form-group">--}}
                            {{--<input type="text" class="form-control" value="123" readonly>--}}
                        {{--</div>--}}
                    {{--</li>--}}
                {{--</form>--}}
            {{--</ul>--}}
        {{--</div>--}}

    </div>


@endsection

@section('footer')

    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script src="/src/public/vuelidate/dist/vuelidate.min.js"></script>
    <!-- The builtin validators is added by adding the following line. -->
    <script src="/src/public//vuelidate/dist/validators.min.js"></script>

    <script>

        Vue.use(window.vuelidate.default)

        const { required } = window.validators;

        Vue.component('app-main', {
            data: function () {
                return {
                    filter_type: '',
                    filter_type_ru: '',
                    filter_type_list: [],
                    filter_name: '',
                    filter_name_ru: '',
                    filter_name_list: [],
                    current_filter_type_id: null,
                    current_filter_model: null
                }
            },
            validations: {
                filter_type: {
                    required
                },
                filter_type_ru: {
                    required
                },
                filter_name: {
                    required
                },
                filter_name_ru: {
                    required
                }
            },
            watch: {
                current_filter_type_id: function () {
                    this.current_filter_model = this.search_id(this.current_filter_type_id, this.filter_type_list);

                    if ( this.current_filter_type_id !== null) {
                        this.load_filter_names(this.current_filter_type_id);
                    }
                }
            },
            created: function() {
                this.load_filter_type();
            },
            methods: {
                search_id: function (nameKey, myArray) {
                    for (var i=0; i < myArray.length; i++) {
                        if (myArray[i].id === nameKey) {
                            return myArray[i];
                        }
                    }
                },
                load_filter_type : function () {

                    let self = this;

                    fetch('{{ route('admin.pages.filters.get') }}', {
//                        credentials: 'include',
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
//                            'X-CSRF-TOKEN': token
                        }
                    })
                        .then(function(response) {
                            if (response.status != 200 && response.status !== 201) {
//                                EventBus.showErrorInfo();
                                return null;
                            }
                            return response.json();
                        })
                        .then(function(data) {

                            if ( !data ) {
                                return;
                            }

//                            console.log(data);
                            self.filter_type_list = data;
                        })
                        .catch(function(error) {
                            console.log(error);
//                            EventBus.showErrorInfo();
                        });
                },
                add_filter_type: function () {
                    this.$v.$touch();
                    if (this.$v.filter_type.$invalid) {
                        return;
                    }
//                    console.log(this.filter_type);
//                    this.filter_type = "";
//                    this.$v.$reset();

                    let self = this;

                    fetch('{{ route('admin.pages.filters.post') }}', {
//                        credentials: 'include',
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
//                            'X-CSRF-TOKEN': token
                        },
                        body: JSON.stringify({
                            type: self.filter_type,
                            type_ru: self.filter_type_ru,
                        })
                    })
                        .then(function(response) {
                            if (response.status != 200 && response.status !== 201) {
//                                EventBus.showErrorInfo();
                                return null;
                            }
                            return response.json();
                        })
                        .then(function(data) {

                            if ( !data ) {
                                return;
                            }

//                            console.log(data);

                            self.filter_type = "";
                            self.filter_type_ru = "";
                            self.$v.$reset();
                            self.load_filter_type();

                        })
                        .catch(function(error) {
                            console.log(error);
//                            EventBus.showErrorInfo();
                        });
                },
                delete_filter_type: function (id) {

                    let self = this;

                    fetch('{{ route('admin.pages.filters.delete') }}', {
//                        credentials: 'include',
                        method: 'DELETE',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
//                            'X-CSRF-TOKEN': token
                        },
                        body: JSON.stringify({
                            id: id,
                        })
                    })
                        .then(function(response) {
                            if (response.status != 200 && response.status !== 201) {
//                                EventBus.showErrorInfo();
                                return null;
                            }
                            return response.json();
                        })
                        .then(function(data) {

                            if ( !data ) {
                                return;
                            }

//                          console.log(data);

                            if (self.current_filter_type_id == id) {
                                self.current_filter_type_id = null;
                            }

                            self.load_filter_type();
                        })
                        .catch(function(error) {
                            console.log(error);
//                            EventBus.showErrorInfo();
                        });

                },


                load_filter_names : function (id) {

                    let self = this;

                    fetch('/api/admin/filters-name/' + id, {
//                        credentials: 'include',
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
//                            'X-CSRF-TOKEN': token
                        }
                    })
                        .then(function(response) {
                            if (response.status != 200 && response.status !== 201) {
//                                EventBus.showErrorInfo();
                                return null;
                            }
                            return response.json();
                        })
                        .then(function(data) {

                            if ( !data ) {
                                return;
                            }

//                            console.log(data);
                            self.filter_name_list = data;
                        })
                        .catch(function(error) {
                            console.log(error);
//                            EventBus.showErrorInfo();
                        });
                },
                add_filter_name: function () {
                    this.$v.$touch();
                    if (this.$v.filter_name.$invalid) {
                        return;
                    }
//                    console.log(this.filter_type);
//                    this.filter_type = "";
//                    this.$v.$reset();

                    let self = this;

                    fetch('{{ route('admin.pages.filters.name.post') }}', {
//                        credentials: 'include',
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
//                            'X-CSRF-TOKEN': token
                        },
                        body: JSON.stringify({
                            type_id: self.current_filter_type_id,
                            name: self.filter_name,
                            name_ru: self.filter_name_ru,
                        })
                    })
                        .then(function(response) {
                            if (response.status != 200 && response.status !== 201) {
//                                EventBus.showErrorInfo();
                                return null;
                            }
                            return response.json();
                        })
                        .then(function(data) {

                            if ( !data ) {
                                return;
                            }

//                            console.log(data);

                            self.filter_name = "";
                            self.filter_name_ru = "";
                            self.$v.$reset();
                            self.load_filter_names(self.current_filter_type_id);

                        })
                        .catch(function(error) {
                            console.log(error);
//                            EventBus.showErrorInfo();
                        });
                },
                delete_filter_name: function (id) {

                    let self = this;

                    fetch('{{ route('admin.pages.filters.name.delete') }}', {
//                        credentials: 'include',
                        method: 'DELETE',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
//                            'X-CSRF-TOKEN': token
                        },
                        body: JSON.stringify({
                            id: id,
                        })
                    })
                        .then(function(response) {
                            if (response.status != 200 && response.status !== 201) {
//                                EventBus.showErrorInfo();
                                return null;
                            }
                            return response.json();
                        })
                        .then(function(data) {

                            if ( !data ) {
                                return;
                            }

//                          console.log(data);

//                            if (self.current_filter_type_id == id) {
//                                self.current_filter_type_id = null;
//                            }

                            self.load_filter_names(self.current_filter_type_id);
                        })
                        .catch(function(error) {
                            console.log(error);
//                            EventBus.showErrorInfo();
                        });

                },

            },
            template:
        `<div>
            <br><br>
            <a href="#menu-toggle" class="btn btn-default" id="menu-toggle"><i class="fa fa-bars fa-5x"></i></a>
            <a href="{{ route('admin.pages.index') }}" class="btn btn-danger">Назад</a>
            <br><br>

            <div class="col-sm-8 col-md-9" id="admin-category-container">
                <ul class="collection with-header">
                    <form @submit.prevent="add_filter_type">
                        <li class="collection-item blue">
                            <h4 class="white-text text-center">
                                Додати типи фільтрів
                            </h4>
                        </li>
                        <li class="collection-item">
                            <label for="category">Додати тип фільтру укр</label>
                            <div class="form-group">
                                <input ref="filter_type" type="text" class="form-control" v-model.trim="$v.filter_type.$model" placeholder="Додати тип фільтру укр">
                                <div class="error" v-if="!$v.filter_type.required && $v.filter_type.$dirty">Це поле обов'язкове</div>
                            </div>
                        </li>
                        <li class="collection-item">
                            <label for="category">Додати тип фільтру рос</label>
                            <div class="form-group">
                                <input ref="filter_type_ru" type="text" class="form-control" v-model.trim="$v.filter_type_ru.$model" placeholder="Додати тип фільтру рос">
                                <div class="error" v-if="!$v.filter_type_ru.required && $v.filter_type_ru.$dirty">Це поле обов'язкове</div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Додати фільтр</button>
                            </div>
                        </li>
                        <li class="collection-item blue">
                            <h4 class="white-text text-center">
                                Поточні фільтри
                            </h4>
                        </li>
                        <li class="collection-item">
                            <div class="form-group" style="display: flex;" v-for="(item, index) in filter_type_list">
                                <div  class="well well-sm" style="cursor: pointer; flex-grow: 1;" @click="current_filter_type_id = item.id">@{{ item.type }} | @{{ item.type_ru }}</div>
                                <i class="material-icons red-text" style="cursor: pointer;" @click="delete_filter_type(item.id)">delete_forever</i>
                            </div>
                        </li>
                    </form>


                    <form v-if="current_filter_type_id" @submit.prevent="add_filter_name">
                        <li class="collection-item blue">
                            <h4 class="white-text text-center">
                                Параметри фільтра <b>@{{ current_filter_model.type }}</b>
                            </h4>
                        </li>
                        <li class="collection-item">
                            <label for="category">Додати параметр фільтру укр</label>
                            <div class="form-group">
                                <input ref="filter_name" type="text" class="form-control" v-model.trim="$v.filter_name.$model" placeholder="Додати параметр фільтру укр">
                                <div class="error" v-if="!$v.filter_name.required && $v.filter_name.$dirty">Це поле обов'язкове</div>
                            </div>
                        </li>
                        <li class="collection-item">
                            <label for="category">Додати параметр фільтру рос</label>
                            <div class="form-group">
                                <input ref="filter_name" type="text" class="form-control" v-model.trim="$v.filter_name_ru.$model" placeholder="Додати параметр фільтру рос">
                                <div class="error" v-if="!$v.filter_name_ru.required && $v.filter_name_ru.$dirty">Це поле обов'язкове</div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Додати параметр</button>
                            </div>
                        </li>
                        <li class="collection-item">
                            <h5 class="text-center">
                                Поточні параметри
                            </h5>
                        </li>
                        <li class="collection-item">
                            <div class="form-group" style="display: flex;" v-for="(item, index) in filter_name_list">
                                <div  class="well well-sm" style="cursor: pointer; flex-grow: 1;">@{{ item.name }} | @{{ item.name_ru }}</div>
                                <i class="material-icons red-text" style="cursor: pointer;" @click="delete_filter_name(item.id)">delete_forever</i>
                            </div>
                        </li>
                    </form>
                    <form v-else>
                        <li class="collection-item">
                            <h6 class="text-center">
                                Виберіть фільтр
                            </h6>
                        </li>

                    </form>
            </h4>
        </li>
</ul>
</div>
</div>`

        });

        var app = new Vue({
            el: '#app',
        })
    </script>
@endsection