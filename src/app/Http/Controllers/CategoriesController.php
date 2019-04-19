<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditCategoryRequest;
use App\Http\Traits\SlugTrait;
use App\Product;
use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;

use App\Http\Traits\CartTrait;
use App\Http\Traits\BrandAllTrait;
use App\Http\Traits\CategoryTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class CategoriesController extends Controller {

    use BrandAllTrait, CategoryTrait, CartTrait, SlugTrait;


    /**
     * Return all categories with their sub-categories
     *
     * @return $this
     */
    public function showCategories() {

        // From Traits/CategoryTrait.php
        // ( Show Categories in side-nav )
        $categories = $this->categoryAll();

        // From Traits/CartTrait.php
        // ( Count how many items in Cart for signed in user )
        $cart_count = $this->countProductsInCart();

        return view('admin.category.show', compact('cart_count'))->with('categories', $categories);

    }


    /**
     * Return all Products under sub-categories
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getProductsForSubCategory($id) {

        // Get the Category name under this category
        $categories = Category::where('id',  $id)->get();

        // Get all products under this sub-category
        $products = Product::where('cat_id',  $id)->get();

        // Count to see if there are any products under this category
        $count = Product::where('cat_id', '=', $id)->count();

        // From Traits/CartTrait.php
        // ( Count how many items in Cart for signed in user )
        $cart_count = $this->countProductsInCart();



        return view('admin.category.show_products', compact('categories', 'products', 'count', 'cart_count'));
    }


    /**
     * Return the view for add new category
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addCategories() {

        // From Traits/CartTrait.php
        // ( Count how many items in Cart for signed in user )
        $cart_count = $this->countProductsInCart();

        return view('admin.category.add', compact('cart_count'));
    }


    /**
     * Add a new category to database
     *
     * @param CategoryRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function addPostCategories(CategoryRequest $request) {
        // Assign $category to the Category Model, and request all validation rules

        $category = new Category($request->all());

        //$this->addSlugToCategory($category);

        if (Auth::user()->id == 2) {
            // If user is a test user (id = 2),display message saying you cant delete if your a test user
            flash()->error('Error', 'Cannot create Category because you are signed in as a test user.');
        } else {
            // Then save the newly created category in DB
            $category->save();
            if (!$request->get('slug'))
            {
                $this->addSlugToCategory($category);
            }

            AdminController::generateSiteMap();

            // Flash a success message
            flash()->overlay('Успіх', 'Категорія додана успішно!','success');

        }

        // Redirect back to Show all categories page.
        return redirect()->route('admin.category.show');
    }


    /**
     * Get the view ot edit a category
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editCategories($id) {
        // Select all from categories where the id = the id on the page
        $category = Category::with('children.children')->where('id', $id)->find($id);

        //dd($category->children->count() );

        // If no category exists with that particular ID, then redirect back to Show Category Page.
        if (!$category) {
            return redirect('admin/categories');
        }

        // From Traits/CartTrait.php
        // ( Count how many items in Cart for signed in user )
        $cart_count = $this->countProductsInCart();

        $count_categories = $this->categoryAll()->count();

        $parents_categories_list = [];

        $category_nested_depth = 1;

        if ( $category->children->count() ) {
            $category_nested_depth = 2;
            foreach ($category->children as $child) {
                if ( $child->children->count() ) {
                    $category_nested_depth = 3;
                }
            }
        }
        else {
            $category_nested_depth = 1;
        }

        switch ($category_nested_depth) {
            case 1:
                $parents_categories_list = Category::with('children')->where("parent_id", null)->get()->sortBy('sequence');
                break;
            case 2:
                $parents_categories_list = Category::where("parent_id", null)->get()->sortBy('sequence');
                break;
            case 3:
                $parents_categories_list = [];
                break;
        }

//        dd($parents_categories_list);

        return view('admin.category.edit', compact('category', 'cart_count', 'count_categories','category_nested_depth','parents_categories_list'));
    }


    /**
     * Update a Category.
     *
     * @param $id
     * @param CategoryRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function updateCategories($id, EditCategoryRequest $request) {
        // Find the category id being updated
        $category = Category::findOrFail($id);

//        dd($request->all());
        $model = $request->all();

        if ($model["parent_id"] == "null") {
            $model["parent_id"] = null;
        }

        if($request->get('is_marked') == null){
            $model ['is_marked'] = 0;
        }

        if (Auth::user()->id == 2) {
            // If user is a test user (id = 2),display message saying you cant delete if your a test user
            flash()->error('Error', 'Cannot edit Category because you are signed in as a test user.');
        } else {

           // $swap = Category::select('sequence')->where('id', '=', $request->id)->first();

            Category::whereNull('parent_id')->where('sequence', '=', $request->sequence)->update(['sequence' => $category->sequence]);

            // Update the category with all the validation rules from CategoryRequest.php
            $category->update($model);
            //$this->addSlugToCategory($category);


            AdminController::generateSiteMap();

            // Flash a success message
             flash()->overlay('Успіх', 'Категорія змінена успішно!','success');

        }

        // Redirect back to Show all categories page.
        return redirect()->route('admin.category.show');
    }


    /**
     * Delete a Category
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteCategories($id) {
        // Find the category id and delete it from DB.
        $delete = Category::findOrFail($id);

        //dd($delete->parent_id, $delete->id);

        //DB::table('category_product')->where('category_id', $delete->id)->update(['category_id' => $delete->parent_id]);

        $unique = collect(DB::select(DB::raw('SELECT category_product.product_id FROM category_product GROUP BY category_product.product_id HAVING COUNT(category_product.product_id) = 1')))->pluck('product_id')->toArray();

        DB::table('category_product')->where('category_id', $delete->id)->whereIn('product_id', $unique);
        DB::table('category_product')->where('category_id', $delete->id)->whereIn('product_id', $unique)->update(['category_id' => $delete->parent_id]);
//        dd (
//            DB::select(DB::raw('SELECT category_product.product_id FROM category_product GROUP BY category_product.product_id HAVING COUNT(category_product.product_id) = 1'))
//        );

        // Get all sub categories where the parent_id = to the category id
        $sub_category = Category::where('parent_id', '=', $id)->count();

        // If there are any sub-categories under a parent category, then throw
        // a error overlay message, saying to delete all sub categories under the parent
        // category, else delete the parent category
        if ($sub_category > 0) {
            // Flash a error overlay message
            flash()->customErrorOverlay('Error', 'Є підкатегорій в рамках цієї батьківської категорії. Неможливо видалити цю категорію, поки все підкатегорії в рамках цієї батьківської категорії не будуть видалені');
        } elseif (Auth::user()->id == 2) {
            // If user is a test user (id = 2),display message saying you cant delete if your a test user
            flash()->error('Error', 'Cannot delete Category because you are signed in as a test user.');
        } else {
            $delete->delete();

            AdminController::generateSiteMap();
        }

        // Then redirect back.
        return redirect()->back();
    }


    /************************************ ****Sub-Categories below ****************************************************/


    /**
     * Return the view for add new sub category
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addSubCategories($id) {

        $category = Category::findOrFail($id);

        // From Traits/CartTrait.php
        // ( Count how many items in Cart for signed in user )
        $cart_count = $this->countProductsInCart();

        return view('admin.category.addsub', compact('category', 'cart_count'));
    }


    /**
     * Add a sub category to a parent category
     *
     * @param $id
     * @param CategoryRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addPostSubCategories($id, CategoryRequest $request) {

        // Find the Parent Category ID
        $category = Category::findOrFail($id);

        $obj_request = $request->all();
        $obj_request['sequence']  = $category->children()->count() + 1 ;
      
        // Create the new Subcategory
        $subcategory = new Category($obj_request);

        if (Auth::user()->id == 2) {
            // If user is a test user (id = 2),display message saying you cant delete if your a test user
            flash()->error('Error', 'Cannot create Sub-Category because you are signed in as a test user.');
        } else {
            // Save the new subcategory into the relationship
            $category->children()->save($subcategory);

            if (!$request->get('slug'))
            {
                $this->addSlugToCategory($subcategory);
            }

            //$this->addSlugToCategory($subcategory);


            AdminController::generateSiteMap();

            // Flash a success message
            flash()->overlay('Успіх', 'Під категорія створена успішно!','success');


        }

        // Redirect back to Show all categories page.
        return redirect()->route('admin.category.show');
    }


    /**
     * Get the view ot edit a sub-category
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editSubCategories($id) {
        // Select all from categories where the id = the id on the page
        $category = Category::where('id', '=', $id)->find($id);

        $count_sub_categories = Category::where('parent_id' ,'=' , $category->parent_id)->count();

        // If no sub-category exists with that particular ID, then redirect back to Show Category Page.
        if (!$category) {
            return redirect('admin/categories');
        }

        // From Traits/CartTrait.php
        // ( Count how many items in Cart for signed in user )
        $cart_count = $this->countProductsInCart();

        $parents_categories_list = [];

        $category_nested_depth = 1;

        if ( $category->children->count() ) {
            $category_nested_depth = 2;
            foreach ($category->children as $child) {
                if ( $child->children->count() ) {
                    $category_nested_depth = 3;
                }
            }
        }
        else {
            $category_nested_depth = 1;
        }

        switch ($category_nested_depth) {
            case 1:
                $parents_categories_list = Category::with('children')->where("parent_id", null)->get()->sortBy('sequence');
                break;
            case 2:
                $parents_categories_list = Category::where("parent_id", null)->get()->sortBy('sequence');
                break;
            case 3:
                $parents_categories_list = [];
                break;
        }

        return view('admin.category.editsub', compact('category', 'cart_count','count_sub_categories','category_nested_depth','parents_categories_list'));
    }


    /**
     * Update a Sub-Category.
     *
     * @param $id
     * @param CategoryRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function updateSubCategories($id, CategoryRequest $request) {
        // Find the category id being updated
        $category = Category::findOrFail($id);

        if (Auth::user()->id == 2) {
            // If user is a test user (id = 2),display message saying you cant delete if your a test user
            flash()->error('Error', 'Cannot edit Sub-Category because you are signed in as a test user.');
        } else {

            Category::where('parent_id', '=' , $category->parent_id)->where('sequence', '=', $request->sequence)->update(['sequence' => $category->sequence]);
            // Update the category with all the validation rules from CategoryRequest.php
            $category->update($request->all());
            //$this->addSlugToCategory($category);


            AdminController::generateSiteMap();


            // Flash a success message
            flash()->overlay('Успіх', 'Під категорію  зміненно успішно!','success');
        }

        // Redirect back to Show all categories page.
        return redirect()->route('admin.category.show');
    }

    /**
     * Delete a Sub-Category
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteSubCategories($id) {

        // Find the sub-category id and delete it from DB.
        $delete_sub = Category::findOrFail($id);

        if(!$delete_sub){
            return redirect()->back();
        }
        $delete_sub->delete();

        AdminController::generateSiteMap();



        // Then redirect back.
        return redirect()->back();
    }

}