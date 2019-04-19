<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/


/**
 * Group for languages settings ins /config/laravellocalization.php
 **/



Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect']
    ],
    function () {

        Route::group(['middleware' => ['web']], function () {


            /** get Departments **/
            Route::get('/cities', ['uses'=>'NovaPoshta@cities']);

            /** Get the Home Page **/
            Route::get('/', 'PagesController@index');

            /** get Departments **/
            Route::post('/getDepartments', ['as'=>'getDepartments' , 'uses'=>'NovaPoshta@getDepartments']);


            /** Get the Page **/
            Route::get('page/{name}', [
                'uses' => 'PagesController@showPage',
                'as' => 'showPage'
            ]);

            /** Get the Favorite Product **/
            Route::get('/favorites', [
                'uses' => 'PagesController@favorites',
                'as' => 'page.favorites'
            ]);

            Route::get('/sizeInstruction', [
                'uses' => 'PagesController@getTableSizeInstruction',
                'as' => 'SizeInstruction'
            ]);

            /** Display Products by Brand Route **/
            Route::get('brand/{id}', [
                'uses' => 'PagesController@displayProductsByBrand',
                'as' => 'brand.showAll'
            ]);

            /** Route to post search results **/
            Route::get('/queries', [
                'uses' => '\App\Http\Controllers\QueryController@search',
                'as' => 'queries.search',
            ]);

            /** Route to ajax search results **/
            Route::post('/api/queries', [
                'uses' => '\App\Http\Controllers\QueryController@ajaxSearch',
                'as' => 'queries.ajax.search',
            ]);

            /** Route to add message to callback **/
            Route::post('/callback', [
                'uses' => '\App\Http\Controllers\QueryController@callback',
            ]);

            /** Route to add product to favorite **/
            Route::post('/favorite', [
                'uses' => '\App\Http\Controllers\ProductsController@addProductToFavorites',
                'as' => 'addProductToFavorites',
            ]);

            /** Route delete product from favorite **/
            Route::post('delete/favorite', [
                'uses' => '\App\Http\Controllers\ProductsController@deleteProductToFavorites',
                'as' => 'deleteProductToFavorites',
            ]);

            /** Route to Products show page **/
            Route::get('product/{slug}', [
                'uses' => '\App\Http\Controllers\ProductsController@show',
                'as' => 'show.product',
            ]);

            /** Display Products by category Route **/
            Route::get('category/{id}', [
                'uses' => 'PagesController@displayProducts',
                'as' => 'category.showAll'
            ]);
            Route::get('category/{id}/{slug}', [
                'uses' => 'PagesController@displayProducts',
                'as' => 'category.showAll'
            ]);

            /** Route to Products show info from product_info table **/

            Route::post('productInfo', [
                'uses' => '\App\Http\Controllers\ProductsController@getProductInfo',
                'as' => 'show.product.info'
            ]);
            /** Route to subscribe  user email**/

            Route::post('subscribe', [
                'uses' => '\App\Http\Controllers\PagesController@subscribeUser',
                'as' => 'subscribe.user'
            ]);

            /************************************** Order By Routes for Reviews ***********************************/

            /** Route to add review to product **/
            Route::post('/review/add', [
                'uses' => '\App\Http\Controllers\ReviewController@store',
                'as' => 'review.store'
            ]);

            /************************************** Order By Routes for Products By Category ***********************************/

//            /** Route to sort products by price lowest */
//            Route::get('category/{id}/price/lowest', [
//                'uses' => '\App\Http\Controllers\OrderByController@productsPriceLowest',
//                'as' => 'category.lowest',
//            ]);
//
//            /**Route to sort products by price highest */
//            Route::get('category/{id}/price/highest', [
//                'uses' => '\App\Http\Controllers\OrderByController@productsPriceHighest',
//                'as' => 'category.highest',
//            ]);
//
//
//            /** Route to sort products by alphabetical A-Z */
//            Route::get('category/{id}/alpha/highest', [
//                'uses' => '\App\Http\Controllers\OrderByController@productsAlphaHighest',
//                'as' => 'category.alpha.highest',
//            ]);
//
//            /**Route to sort products by alphabetical  Z-A */
//            Route::get('category/{id}/alpha/lowest', [
//                'uses' => '\App\Http\Controllers\OrderByController@productsAlphaLowest',
//                'as' => 'category.alpha.lowest',
//            ]);
//
//            /**Route to sort products by alphabetical  Z-A */
//            Route::get('category/{id}/newest', [
//                'uses' => '\App\Http\Controllers\OrderByController@productsNewest',
//                'as' => 'category.newest',
//            ]);
//
            /** Route to sort products by size */
            Route::post('category/{id}/size', [
                'uses' => '\App\Http\Controllers\OrderByController@productsSize',
                'as' => 'category.size',
            ]);

            //////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            /************************************** Order By Routes for Products By Brand ***********************************/

            /** Route to sort products by price lowest */
            Route::get('brand/{id}/price/lowest', [
                'uses' => '\App\Http\Controllers\OrderByController@productsPriceLowestBrand',
                'as' => 'brand.lowest',
            ]);

            /**Route to sort products by price highest */
            Route::get('brand/{id}/price/highest', [
                'uses' => '\App\Http\Controllers\OrderByController@productsPriceHighestBrand',
                'as' => 'brand.highest',
            ]);


            /** Route to sort products by alphabetical A-Z */
            Route::get('brand/{id}/alpha/highest', [
                'uses' => '\App\Http\Controllers\OrderByController@productsAlphaHighestBrand',
                'as' => 'brand.alpha.highest',
            ]);

            /**Route to sort products by alphabetical  Z-A */
            Route::get('brand/{id}/alpha/lowest', [
                'uses' => '\App\Http\Controllers\OrderByController@productsAlphaLowestBrand',
                'as' => 'brand.alpha.lowest',
            ]);

            /**Route to sort products by alphabetical  Z-A */
            Route::get('brand/{id}/newest', [
                'uses' => '\App\Http\Controllers\OrderByController@productsNewestBrand',
                'as' => 'brand.newest',
            ]);


            /**************************************** Login & Registration Routes *********************************************/

            /** Return view for registration confirm token page ***/
            Route::get('register/confirm/{token}', 'AuthController@confirmEmail');

            Route::get('/register', [
                'uses' => '\App\Http\Controllers\AuthController@getRegister',
                'as' => 'auth.register',
                'middleware' => ['guest']
            ]);

            Route::post('/register', [
                'uses' => '\App\Http\Controllers\AuthController@postRegister',
                'as' => 'auth.register',
            ]);

            Route::get('/login', [
                'uses' => '\App\Http\Controllers\AuthController@getLogin',
                'as' => 'auth.login',
                'middleware' => ['guest']
            ]);

            Route::post('/login', [
                'uses' => '\App\Http\Controllers\AuthController@postLogin',
                'as' => 'auth.login',
                'middleware' => ['guest'],
            ]);

            Route::get('/logout', [
                'uses' => '\App\Http\Controllers\AuthController@logout',
                'as' => 'auth.logout',
                'middleware' => ['auth'],
            ]);




            /**************************************** Cart Routes *********************************************/

            Route::post('/one-click-order/add', array(
                'uses' => 'OrderController@oneClickOrder',
                'as' => 'one_click_order',

            ));

            Route::delete('/one-click-order/{id}', array(
                'uses' => 'OrderController@oneClickOrderDelete',
                'as' => 'one_click_order_delete',

            ));

            /** Get the view for Cart Page **/
            Route::get('/cart', array(
                'before' => 'auth.basic',
                'as' => 'cart',
                'uses' => 'CartController@showCart'
            ));
            //
            Route::get('/cart/add', function () {
                abort(404);
            });

            /** Add items in the cart **/
            Route::post('/cart/add', array(
                'before' => 'auth.basic',
                'uses' => 'CartController@addCart',
                'as' => 'addToCart',

            ));

            /** Update items in the cart **/
            Route::post('/cart/update', [
                'uses' => 'CartController@update'
            ]);

            /** Delete items in the cart **/
            Route::get('/cart/delete/{id}', array(
                'before' => 'auth.basic',
                'as' => 'delete_book_from_cart',
                'uses' => 'CartController@delete'
            ));


            /** Add items in the cart **/
            Route::post('/cart/refresh', array(
                'before' => 'auth.basic',
                'uses' => 'CartController@refresh',
                'as' => 'cart.refresh',

            ));

            /**************************************** Order Routes *********************************************/


            /** Get thew checkout view **/
            Route::get('/checkout', [
                'uses' => '\App\Http\Controllers\OrderController@index',
                'as' => 'checkout',
                'middleware' => ['auth'],
            ]);


            /** Post an Order **/
            Route::post('/order',
                array(
                    'before' => 'auth.basic',
                    'as' => 'order',
                    'uses' => 'OrderController@postOrder'
                ));


            /** Post an Order From /cart UI **/
            Route::post('/order/confirm',
                array(
                    'before' => 'auth.basic',
                    'as' => 'orderConfirm',
                    'uses' => 'OrderController@confirmOrder'
                ));




            /******************************************* User Profile Routes below ************************************************/

            /** route for Profile **/
//            Route::get('/profile', 'ProfileController@index');

            Route::get('/profile', [
                'uses' => 'ProfileController@index',
                'as' => 'profile',
                'middleware' => ['auth'],
            ]);


            /** route for Profile **/
            Route::get('/profile/{id}', 'ProfileController@show');

        });

        //  end section wrapper for languages (ua/ru)
    });
/**************************
 * Password Reset Routes
 *************************/
Route::get('/password/email', '\App\Http\Controllers\PasswordController@getEmail');
Route::post('/password/email', '\App\Http\Controllers\PasswordController@postEmail');
Route::get('/password/reset/{token}', '\App\Http\Controllers\PasswordController@getReset');
Route::post('/password/reset', '\App\Http\Controllers\PasswordController@postReset');
/** Post an check discount code **/
Route::post('/checkDiscount',
    array(
        'before' => 'auth.basic',
        'as' => 'checkDiscount',
        'uses' => 'OrderController@checkDiscount',
    ));


Route::group(["middleware" => 'admin'], function () {

    Route::get('admin/add-order/{id}', [
        'uses' => 'OrderController@addOrder',
        'as' => 'admin_pages_add_order',
        'middleware' => ['auth'],
    ]);

    Route::post('admin/add-order/{id}', [
        'uses' => 'OrderController@storeOrder',
        'as' => 'admin_pages_store_order',
        'middleware' => ['auth'],
    ]);


    Route::get('/1cimport', [
        'as'=>'import',
        'uses'=>'OneSController@postImport',
        'middleware' => ['auth'],
    ]);

    /** Show the Admin Dashboard **/
    Route::get('/admin/slugs', ['as'=>'slug', 'uses'=>'AdminController@generateSlugs']);


    Route::get('admin/dashboard', [
        'uses' => '\App\Http\Controllers\AdminController@index',
        'as' => 'admin.pages.index',
        'middleware' => ['auth'],
    ]);

    //dynamic filters

    Route::get('admin/filters', [
        'uses' => '\App\Http\Controllers\AdminController@filters',
        'as' => 'admin.pages.filters',
        'middleware' => ['auth'],
    ]);

    Route::get('api/admin/filters', [
        'uses' => 'AdminController@get_filters',
        'as' => 'admin.pages.filters.get',
        'middleware' => ['auth'],
    ]);

    Route::post('api/admin/filters', [
        'uses' => 'AdminController@store_filters',
        'as' => 'admin.pages.filters.post',
        'middleware' => ['auth'],
    ]);

    Route::delete('api/admin/filters', [
        'uses' => 'AdminController@delete_filters',
        'as' => 'admin.pages.filters.delete',
        'middleware' => ['auth'],
    ]);

    ///filters name

    Route::get('api/admin/filters-name/{id}', [
        'uses' => 'AdminController@get_filters_name',
        'as' => 'admin.pages.filters.name.get',
        'middleware' => ['auth'],
    ]);

    Route::post('api/admin/filters-name', [
        'uses' => 'AdminController@store_filters_name',
        'as' => 'admin.pages.filters.name.post',
        'middleware' => ['auth'],
    ]);

    Route::delete('api/admin/filters-name', [
        'uses' => 'AdminController@delete_filters_name',
        'as' => 'admin.pages.filters.name.delete',
        'middleware' => ['auth'],
    ]);

    //reviews

    Route::get('admin/reviews', [
        'uses' => '\App\Http\Controllers\AdminController@reviews',
        'as' => 'admin.pages.reviews',
        'middleware' => ['auth'],
    ]);

    Route::get('admin/reviews/get', [
        'uses' => '\App\Http\Controllers\AdminController@getReviews',
        'as' => 'admin.pages.reviews.get',
        'middleware' => ['auth'],
    ]);

    Route::delete('admin/reviews/delete', [
        'uses' => '\App\Http\Controllers\ReviewController@destroy',
        'as' => 'admin.pages.reviews.delete',
        'middleware' => ['auth'],
    ]);

    Route::put('admin/reviews/update', [
        'uses' => '\App\Http\Controllers\ReviewController@update',
        'as' => 'admin.pages.reviews.update',
        'middleware' => ['auth'],
    ]);

    Route::post('admin/reviews/excel', [
        'uses' => '\App\Http\Controllers\ReviewController@parseExcel',
        'as' => 'admin.pages.reviews.excel',
        'middleware' => ['auth'],
    ]);

    // end reviews

    Route::get('admin/sitemap.xml', [
        'uses' => '\App\Http\Controllers\AdminController@generateSiteMap',
        'as' => 'admin.generate.sitemap',
        'middleware' => ['auth'],
    ]);

//    Route::get('admin/main', [
//        'uses' => '\App\Http\Controllers\AdminController@generateSiteMap',
//        'as' => 'admin.generate.sitemap',
//        'middleware' => ['auth'],
//    ]);

    Route::post('admin/dashboard/shopSettings', [
        'uses' => '\App\Http\Controllers\AdminController@shopSettings',
        'as' => 'admin.pages.shopSettings',
        'middleware' => ['auth'],
    ]);

    /** Show the Admin Main Page images **/
    Route::get('admin/settings', [
        'uses' => '\App\Http\Controllers\AdminController@settings',
        'as' => 'admin.pages.settings',
        'middleware' => ['auth'],
    ]);

    /** Show the Admin callback messages **/
    Route::get('admin/callback', [
        'uses' => '\App\Http\Controllers\AdminController@callback',
        'as' => 'callback',
        'middleware' => ['auth'],
    ]);

    /** delete the Admin callback messages **/
    Route::delete('admin/callback/{id}', [
        'uses' => '\App\Http\Controllers\AdminController@deleteCallback',
        'as' => 'deleteCallback',
        'middleware' => ['auth'],
    ]);

    /** Show the Admin callback messages **/
    Route::get('admin/callback/send-mail/{id}', [
        'uses' => '\App\Http\Controllers\AdminController@showCallback',
        'as' => 'showCallback',
        'middleware' => ['auth'],
    ]);

    /** send the Admin callback messages **/
    Route::post('admin/callback/send-mail/{id}', [
        'uses' => '\App\Http\Controllers\AdminController@sendMessage',
        'as' => 'sendCallback',
        'middleware' => ['auth'],
    ]);

    /** update page **/
    Route::post('admin/page/update/{id}', [
        'uses' => '\App\Http\Controllers\AdminController@updatePage',
        'as' => 'updatePage',
        'middleware' => ['auth'],
    ]);

    /** Show the Admin custom pages  **/
    Route::get('admin/main', [
        'uses' => '\App\Http\Controllers\AdminController@mainPage',
        'as' => 'showMain',
        'middleware' => ['auth'],
    ]);

    /** Show the Admin custom pages  **/
    Route::post('admin/main', [
        'uses' => '\App\Http\Controllers\AdminController@updateMainPage',
        'as' => 'updateMainPage',
        'middleware' => ['auth'],
    ]);

    /** Show the Admin main page  **/
    Route::get('admin/pages', [
        'uses' => '\App\Http\Controllers\AdminController@showPages',
        'as' => 'showPages',
        'middleware' => ['auth'],
    ]);

    /** edit custom page  **/
    Route::get('admin/page/edit/{id}', [
        'uses' => '\App\Http\Controllers\AdminController@editPage',
        'as' => 'editPage',
        'middleware' => ['auth'],
    ]);



    /** Add Main Page images **/
    Route::post('admin/settings', [
        'uses' => '\App\Http\Controllers\AdminController@updateSetting',
        'as' => 'admin.pages.settings',
        'middleware' => ['auth'],
    ]);

    /** Show the Admin Main Page images **/
    Route::get('admin/mainImages', [
        'uses' => '\App\Http\Controllers\AdminController@showImages',
        'as' => 'admin.pages.showImages',
        'middleware' => ['auth'],
    ]);

    /** Add Main Page images **/
    Route::post('admin/addMainImages', [
        'uses' => '\App\Http\Controllers\AdminController@addMainImages',
        'as' => 'admin.pages.addMainImages',
        'middleware' => ['auth'],
    ]);
    /** Show the Admin Main Page colors piker **/

    Route::get('admin/colorPiker', [
        'uses' => '\App\Http\Controllers\AdminController@colorPiker',
        'as' => 'admin.pages.colorPiker',
        'middleware' => ['auth'],
    ]);

    Route::post('admin/colorPiker/update/{id}', [
        'uses' => '\App\Http\Controllers\AdminController@updateColorPiker',
        'as' => 'admin.pages.colorPiker',
        'middleware' => ['auth'],
    ]);

    Route::delete('admin/colorPiker/delete/{id}/', [
        'uses' => '\App\Http\Controllers\AdminController@deleteColorPiker',
        'as' => 'admin.pages.colorPiker',
        'middleware' => ['auth'],
    ]);


    /** Insert colors in color_table **/
    Route::post('admin/colorPiker', [
        'uses' => '\App\Http\Controllers\AdminController@addColorPiker',
        'as' => 'admin.pages.addColorPiker',
        'middleware' => ['auth'],
    ]);
    /** Delete  colors in color_table **/
    Route::post('admin/colorPiker/delete', [
        'uses' => '\App\Http\Controllers\AdminController@deleteColorPiker',
        'as' => 'admin.pages.addColorPiker',
        'middleware' => ['auth'],
    ]);


    /** Show the Admin Main Page static images **/
    Route::get('admin/mainImagesStatic', [
        'uses' => '\App\Http\Controllers\AdminController@showImagesStatic',
        'as' => 'admin.pages.showImagesStatic',
        'middleware' => ['auth'],
    ]);

    /** Add Main Page static images **/
    Route::post('admin/mainImagesStatic', [
        'uses' => '\App\Http\Controllers\AdminController@addMainImagesStatic',
        'as' => 'admin.pages.addMainImagesStatic',
        'middleware' => ['auth'],
    ]);


    /** Delete Main Page images **/
    Route::delete('admin/image/photos/{id}', 'AdminController@deleteMainImage');
    /** Delete Main Page static images **/
    Route::delete('admin/image/photosStatic/{id}', 'AdminController@deleteMainImageStatic');

    /** Set category in MainPhoto img**/
    Route::post('admin/image/photos/category', 'AdminController@setCategoryToImg');

    /** Set page in MainPhoto img**/
    Route::post('admin/image/photos/page', 'AdminController@setPageToImg');

    /** Show the Admin SendEmails **/
    Route::get('admin/email', [
        'uses' => '\App\Http\Controllers\AdminController@getEmail',
        'as' => 'admin.pages.getEmail',
        'middleware' => ['auth'],
    ]);
    /** Send the Admin SendEmails to Users **/
    Route::post('admin/email', [
        'uses' => '\App\Http\Controllers\AdminController@sendEmails',
        'as' => 'admin.pages.sendEmail',
        'middleware' => ['auth'],
    ]);

    /** Show the Admin Categories **/
    Route::get('admin/categories', [
        'uses' => '\App\Http\Controllers\CategoriesController@showCategories',
        'as' => 'admin.category.show',
        'middleware' => ['auth'],
    ]);

    /** Show the Admin Add Categories Page **/
    Route::get('admin/categories/add', [
        'uses' => '\App\Http\Controllers\CategoriesController@addCategories',
        'as' => 'admin.category.add',
        'middleware' => ['auth'],
    ]);

    /** Post the Category Route **/
    Route::post('admin/categories/add', [
        'uses' => '\App\Http\Controllers\CategoriesController@addPostCategories',
        'as' => 'admin.category.post',
        'middleware' => ['auth'],
    ]);

    /** Show the Admin Edit Categories Page **/
    Route::get('admin/categories/edit/{id}', [
        'uses' => '\App\Http\Controllers\CategoriesController@editCategories',
        'as' => 'admin.category.edit',
        'middleware' => ['auth'],
    ]);

    /** Show the Admin Update Categories Page **/
    Route::post('admin/categories/update/{id}', [
        'uses' => '\App\Http\Controllers\CategoriesController@updateCategories',
        'as' => 'admin.category.update',
        'middleware' => ['auth'],
    ]);

    /** Delete a category **/
    Route::delete('admin/categories/delete/{id}', [
        'uses' => '\App\Http\Controllers\CategoriesController@deleteCategories',
        'as' => 'admin.category.delete',
        'middleware' => ['auth'],
    ]);


    /****************************************Sub-Category Routes below ***********************************************/


    /** Show the Admin Add Sub-Categories Page **/
    Route::get('admin/categories/addsub/{id}', [
        'uses' => '\App\Http\Controllers\CategoriesController@addSubCategories',
        'as' => 'admin.category.addsub',
        'middleware' => ['auth'],
    ]);

    /** Post the Sub-Category Route **/
    Route::post('admin/categories/postsub/{id}', [
        'uses' => '\App\Http\Controllers\CategoriesController@addPostSubCategories',
        'as' => 'admin.category.postsub',
        'middleware' => ['auth'],
    ]);

    /** Show the Admin Edit Categories Page **/
    Route::get('admin/categories/editsub/{id}', [
        'uses' => '\App\Http\Controllers\CategoriesController@editSubCategories',
        'as' => 'admin.category.editsub',
        'middleware' => ['auth'],
    ]);

    /** Post the Sub-Category update Route**/
    Route::post('admin/categories/updatesub/{id}', [
        'uses' => '\App\Http\Controllers\CategoriesController@updateSubCategories',
        'as' => 'admin.category.updatesub',
        'middleware' => ['auth'],
    ]);


    /** Delete a sub-category **/
    Route::delete('admin/categories/deletesub/{id}', [
        'uses' => '\App\Http\Controllers\CategoriesController@deleteSubCategories',
        'as' => 'admin.category.deletesub',
        'middleware' => ['auth'],
    ]);


    /** Get all the products under a sub-category route **/
    Route::get('admin/categories/products/cat/{id}', [
        'uses' => '\App\Http\Controllers\CategoriesController@getProductsForSubCategory',
        'as' => 'admin.category.products',
        'middleware' => ['auth'],
    ]);

    /** Route for the sub-category drop-down */
    Route::get('api/dropdown', 'ProductsController@categoryAPI');


    /******************************************* Products Routes below ************************************************/


    /** Set status on Order **/
    Route::post('/order/setStatus/{id}',
        array(
            'before' => 'auth.basic',
            'as' => 'setStatus',
            'uses' => 'OrderController@setStatus'
        ));
    /** Delete  Order **/
    Route::delete('/order/deleteOrder/{id}',
        array(
            'before' => 'auth.basic',
            'as' => 'deleteOrder',
            'uses' => 'OrderController@deleteOrder'
        ));

    /** Show the Admin Products Page **/
    Route::get('admin/products', [
        'uses' => '\App\Http\Controllers\ProductsController@showProducts',
        'as' => 'admin.product.show',
        'middleware' => ['auth'],
    ]);

    /** Show the Admin Add product Page **/
//    Route::get('admin/product/add', [
//        'uses' => '\App\Http\Controllers\ProductsController@addProduct',
//        'as' => 'admin.product.add',
//        'middleware' => ['auth'],
//    ]);


    /** Post the Add Product Route **/
    Route::post('admin/product/add', [
        'uses' => '\App\Http\Controllers\ProductsController@addPostProduct',
        'as' => 'admin.product.post',
        'middleware' => ['auth'],
    ]);

    /** Get the Edit product Page **/
    Route::get('admin/product/edit/{id}', [
        'uses' => '\App\Http\Controllers\ProductsController@editProduct',
        'as' => 'admin.product.edit',
        'middleware' => ['auth'],
    ]);

    /** Post the update images options Route **/
    Route::post('admin/update/option', [
        'uses' => '\App\Http\Controllers\AdminController@updateImgAttributes',
        'as' => 'imgAttributes',
        'middleware' => ['auth'],
    ]);

    /** Patch the Admin Update Product Information Route **/
    Route::patch('admin/product/update/{id}/info', [
        'uses' => '\App\Http\Controllers\ProductsController@updateProductInfoById',
        'as' => 'admin.product.update',
        'middleware' => ['auth'],
    ]);
    /** Delete the Admin delete Product Information Route **/
    Route::delete('admin/product/delete/{id}/info', [
        'uses' => '\App\Http\Controllers\ProductsController@deleteProductInfoById',
        'as' => 'admin.product.update',
        'middleware' => ['auth'],
    ]);


    /** Post the Admin Update Product Route **/
    Route::post('admin/product/update/{id}', [
        'uses' => '\App\Http\Controllers\ProductsController@updateProduct',
        'as' => 'admin.product.update',
        'middleware' => ['auth'],
    ]);


    /** Delete a product **/
    Route::delete('admin/product/delete/{id}', [
        'uses' => '\App\Http\Controllers\ProductsController@deleteProduct',
        'as' => 'admin.product.delete',
        'middleware' => ['auth'],
    ]);

    /** Get the Admin Upload Images Page **/
    Route::get('admin/products/{id}', [
        'uses' => '\App\Http\Controllers\ProductsController@displayImageUploadPage',
        'as' => 'admin.product.upload',
        'middleware' => ['auth'],
    ]);

    /** Post a photo to a Product **/
    Route::post('admin/products/{id}/photo', 'ProductPhotosController@store');

    /** Delete Product photos **/
    Route::delete('admin/products/photos/{id}', 'ProductPhotosController@destroy');

    /** Post the Product Add Featured Image Route **/
    Route::post('admin/products/add/featured/{id}', 'ProductPhotosController@storeFeaturedPhoto');


    /******************************************* Brands Routes below ************************************************/


    /** Resource route for Admin Brand Actions **/
    Route::resource('admin/brands', 'BrandsController');

    /** Delete a Brand **/
    Route::delete('admin/brands/delete/{id}', [
        'uses' => '\App\Http\Controllers\BrandsController@delete',
        'as' => 'admin.brand.delete',
        'middleware' => ['auth'],
    ]);

    /** Get all the products under a brand route **/
    Route::get('admin/brands/products/brand/{id}', [
        'uses' => '\App\Http\Controllers\BrandsController@getProductsForBrand',
        'as' => 'admin.brand.products',
        'middleware' => ['auth'],
    ]);


    /** Delete a user **/
    Route::delete('admin/dashboard/delete/{id}', [
        'uses' => '\App\Http\Controllers\AdminController@delete',
        'as' => 'admin.delete',
        'middleware' => ['auth'],
    ]);

    /** Delete a cart session **/
    Route::delete('admin/dashboard/cart/delete/{id}', [
        'uses' => '\App\Http\Controllers\AdminController@deleteCart',
        'as' => 'admin.cart.delete',
        'middleware' => ['auth'],
    ]);


    /** Update quantity from prducts in Admin dashboard **/
    Route::post('/admin/update', [
        'uses' => 'AdminController@update'
    ]);
    
});

