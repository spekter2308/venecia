<?php

namespace App\Http\Controllers;

use App\OrderProduct;
use App\User;
use App\Http\Controllers\Controller;

use App\Http\Traits\BrandAllTrait;
use App\Http\Traits\CategoryTrait;
use App\Http\Traits\SearchTrait;
use App\Http\Traits\CartTrait;
use Auth;


class ProfileController extends Controller {


    use BrandAllTrait, CategoryTrait, SearchTrait, CartTrait;


    /* This page uses the Auth Middleware */
    public function __construct() {
        $this->middleware('auth');
        // Reference the main constructor.
        parent::__construct();
    }


    /**
     * Display Profile contents
     *
     * @return mixed
     */
    public function index() {

        // From Traits/CategoryTrait.php
        // ( Show Categories in side-nav )
        $categories = $this->categoryAll();
        //Need to fast fox bug (for all pages render bar with categories);
        $categoryAll =  $this->categoryAll();

        $categoryId = null;


        // From Traits/BrandAll.php
        // Get all the Brands
        $brands = $this->brandsAll();

        // From Traits/SearchTrait.php
        // ( Enables capabilities search to be preformed on this view )
        $search = $this->search();

        // From Traits/CartTrait.php
        // ( Count how many items in Cart for signed in user )
        $cart_count = $this->countProductsInCart();

        // Set user_id to the currently authenticated user ID
        $user_id = Auth::user()->id;

        // Select all from "Orders" where the user_id = the ID og the signed in user to get all their Orders
        $orders = OrderProduct::where('user_id',  $user_id)->get();

        $breadcrumbs = [
            (object) ['page' => trans('messages.mainPage'), "link" => url('/')],
            (object) ['page' => trans('messages.profile'), "link" => url('/profile')]
        ];


        return view('profile.index', compact('categories', 'brands', 'search', 'cart_count', 'username', 'orders','categoryAll','categoryId','breadcrumbs'));
    }

    public function show($id) {


        // Set user_id to the currently authenticated user ID
        $user_id = Auth::user()->id;

        // Select all from "Orders" where the user_id = the ID og the signed in user to get all their Orders
        $order = OrderProduct::where('user_id',  $user_id)->where('id',$id)->first();
        if(!$order){
            return redirect()->back();
        }

        // From Traits/CategoryTrait.php
        // ( Show Categories in side-nav )
        $categories = $this->categoryAll();
        //Need to fast fox bug (for all pages render bar with categories);
        $categoryAll =  $this->categoryAll();

        $categoryId = null;


        // From Traits/BrandAll.php
        // Get all the Brands
        $brands = $this->brandsAll();

        // From Traits/SearchTrait.php
        // ( Enables capabilities search to be preformed on this view )
        $search = $this->search();

        // From Traits/CartTrait.php
        // ( Count how many items in Cart for signed in user )
        $cart_count = $this->countProductsInCart();

        $breadcrumbs = [
            (object) ['page' => trans('messages.mainPage'), "link" => url('/')],
            (object) ['page' => trans('messages.profile'), "link" => url('/profile')],
            (object) ['page' => $order->product->product_sku, "link" => url('/profile',$id)]
        ];


        return view('profile.show', compact('categories', 'brands', 'search', 'cart_count', 'username', 'order','categoryAll','categoryId','breadcrumbs'));
    }
    

}