<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminUpdateReviewRequest;
use App\Http\Requests\ReviewRequest;
use App\Product;
use App\Review;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;
use Validator;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ReviewRequest $request)
    {
        $model = new Review();

        $model->fill($request->all());
        $model->save();

        return View::make('pages.partials.review', ['review' => $model])->render();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AdminUpdateReviewRequest $request)
    {
        if ($request->has("id")) {
            $model = Review::find($request->get('id'));
            if ($model) {
                $model->fill($request->all());
                $model->save();
                return response(["success" => true], 200);

            }
            return response(["success" => false], 404);

        }
        return response(["success" => false], 404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if ($request->has("id")) {
            $model = Review::find($request->get('id'));
            if ($model) {
                $model->delete();
                return response(["success" => true], 200);

            }
            return response(["success" => false], 404);

        }
        return response(["success" => false], 404);

    }

    public function parseExcel (Request $request) {
        $file = $request->file("excel");

        if ($file == null) {
            return response(["message" => "Файл не вибрано"], 404);
        }

        DB::beginTransaction();
        try {
            Excel::load($file->getPathName(), function ($reader) {
                $slug = "";
                $temp = "";
                $num = 2;
                $reader->each(function ($sheet) use (&$slug, &$temp, &$num) {
                    $temp = $slug;
                    $review = $sheet->toArray();

                    $slug = preg_replace("/https:\/\/venezia-online.com.ua\/ru\/product\//", "", $review['posilannya']);

                    if ($slug == "") {
                        if ($slug == "" && $temp != "") {
                            $slug = $temp;
                            $temp = "";
                        } elseif ($slug == "") {
                            throw new \Exception("Помилка в посиланні, рядок №{$num}");
                        }
                    } else {
                        if ($slug == $review['posilannya']) {
                            $slug = preg_replace("/https:\/\/venezia-online.com.ua\/product\//", "", $review['posilannya']);

                            if ($slug == $review['posilannya']) {
                                throw new \Exception("Помилка в посиланні, рядок №{$num}");
                            }
                        }
                    }

                    //get product id
                    $product = Product::select(["id"])->where('slug', '=', $slug)->first();

//                    if ($num == 22) {
//                        echo $slug;
//                        die();
//                    }

                    if ($product == null) {
                        throw new \Exception("Помилка в посиланні, рядок №{$num}");
                    }else {
                        $product_id = $product->id;
                    }

                    //get date
                    if ($review['data'] != null) {
                        $date = $review['data']->toDateString();
                    } else {
                        $date = null;
                    }

                    //get name
                    $name = $review['nik'];

                    //get message
                    $message = $review['vidguk'];

                    if (is_null($name) && is_null($message) && is_null($date)) {
                        return;
                    }

                    $attributes = [
                        'product_id' => $product_id,
                        'name' => $name,
                        'message' => $message,
                        'created_at' => $date
                    ];

                    $validator = Validator::make($attributes, [
                        'product_id' => 'exists:products,id',
                        'name' => 'required|max:255|min:2',
                        'message' => 'required|max:1000|min:2',
                        'created_at' => 'required|date|after:01/01/2000',
                    ]);

                    if ($validator->fails()) {
                        throw new \Exception("Помилка в рядоку №{$num}");
                    }

                    $model = new Review();

                    $model->fill($attributes);

                    $model->save();
                    $num++;
                });
            });
            DB::commit();
            return response(["message" => "Відгукі успішно записані до бази данних"], 200);
        }
        catch (\Exception $e) {
            DB::rollback();
            return response(["message" => $e->getMessage()], 404);
        }
    }
}
