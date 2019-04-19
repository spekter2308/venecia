<?php

namespace App\Http\Middleware;

use Closure;

class Redirect302
{
    private $redirects_list = [
//        'ru/category/19/sumky' => 	'ru/category/19/sumky-jinochi',
//        'ru/category/13/tufli' => 	'ru/category/13/tufli-',
//        'category/16/mokasyny' => 	'category/16/mokasyny-',
//        'category/13/tufli' => 	'category/13/tufli-',
//        'category/30/cherevyky' => 	'category/30/cherevyky-cholovichi',
//        'category/10/tufli' => 	'category/10/tufli-cholovichi',
//        'category/18/mokasyny' => 	'category/18/mokasyny-cholovichi',
//        'category/31/sportyvne-vzuttya' => 	'category/31/choloviche-sportyvne-vzuttya',
//        'ru/category/17/bosonijky' => 	'ru/category/17/cholovichi-bosonijky',
//        'category/19/sumky' => 	'category/19/sumky-jinochi',
//        'category/11/espadril' => 	'category/11/espadril-',
//        'category/17/bosonijky' => 	'category/17/cholovichi-bosonijky',
//        'category/22/velyki' => 	'category/22/velyki-sumky',
//        'category/23/malenki' => 	'category/23/malenki-sumky',
//        'category/26/cherevyky' => 	'category/26/cherevyky-',
//        'category/67/shlopanci-' => 	'category/67/shlopanci',
//        'ru/category/11/espadril' => 	'ru/category/11/espadril-',
//        'ru/category/16/mokasyny' => 	'ru/category/16/mokasyny-',
//        'ru/category/18/mokasyny' => 	'ru/category/18/mokasyny-cholovichi',
//        'ru/category/22/velyki' => 	'ru/category/22/velyki-sumky',
//        'ru/category/26/cherevyky' => 	'ru/category/26/cherevyky-',
//        'ru/category/30/cherevyky' => 	'ru/category/30/cherevyky-cholovichi',
//        'ru/category/31/sportyvne-vzuttya' => 	'ru/category/31/choloviche-sportyvne-vzuttya',
//        'ru/category/66/espadril' => 	'ru/category/66/espadril-cholovichi',
//        'ru/category/67/shlopanci-' => 	'ru/category/67/shlopanci',
//        'ru/category/23/malenki' => 	'ru/category/23/malenki-sumky',
//        'category/66/espadril' => 	'category/66/espadril-cholovichi',
//        'category/68/shlopanci' => 	'category/68/shlopanci-cholovichi',
//        'ru/category/68/shlopanci' => 	'ru/category/68/shlopanci-cholovichi',
//        'ru/category/10/tufli' => 	'ru/category/10/tufli-cholovichi',
        '/ru/category/19/sumky' => 	'/ru/category/19/sumky-jinochi',
        '/ru/category/13/tufli' => 	'/ru/category/13/tufli-zhinochi',
        '/ru/category/13/tufli-' => 	'/ru/category/13/tufli-zhinochi',
        '/category/16/mokasyny' => 	'/category/16/mokasyny-zhinochi',
        '/category/16/mokasyny-' => 	'/category/16/mokasyny-zhinochi',
        '/category/13/tufli' => 	'/category/13/tufli-zhinochi',
        '/category/13/tufli-' => 	'/category/13/tufli-zhinochi',
        '/category/30/cherevyky' => 	'/category/30/cherevyky-cholovichi',
        '/category/10/tufli' => 	'/category/10/tufli-cholovichi',
        '/category/18/mokasyny' => 	'/category/18/mokasyny-cholovichi',
        '/category/31/sportyvne-vzuttya' => 	'/category/31/choloviche-sportyvne-vzuttya',
        '/ru/category/17/bosonijky' => 	'/ru/category/17/cholovichi-bosonijky',
        '/category/19/sumky' => 	'/category/19/sumky-jinochi',
        '/category/11/espadril' => 	'/category/11/espadrili-zhinochi',
        '/category/11/espadril-' => 	'/category/11/espadrili-zhinochi',
        '/category/17/bosonijky' => 	'/category/17/cholovichi-bosonijky',
        '/category/22/velyki' => 	'/category/22/velyki-sumky',
        '/category/23/malenki' => 	'/category/23/malenki-sumky',
        '/category/26/cherevyky' => 	'/category/26/cherevyky-zhinochi',
        '/category/26/cherevyky-' => 	'/category/26/cherevyky-zhinochi',
        '/category/67/shlopanci-' => 	'/category/67/shlopanci',
        '/ru/category/11/espadril' => 	'/ru/category/11/espadrili-zhinochi',
        '/ru/category/11/espadril-' => 	'/ru/category/11/espadrili-zhinochi',
        '/ru/category/16/mokasyny' => 	'/ru/category/16/mokasyny-zhinochi',
        '/ru/category/16/mokasyny-' => 	'/ru/category/16/mokasyny-zhinochi',
        '/ru/category/18/mokasyny' => 	'/ru/category/18/mokasyny-cholovichi',
        '/ru/category/22/velyki' => 	'/ru/category/22/velyki-sumky',
        '/ru/category/26/cherevyky' => 	'/ru/category/26/cherevyky-',
        '/ru/category/26/cherevyky-' => 	'/ru/category/26/cherevyky-zhinochi',
        '/ru/category/30/cherevyky' => 	'/ru/category/30/cherevyky-cholovichi',
        '/ru/category/31/sportyvne-vzuttya' => 	'/ru/category/31/choloviche-sportyvne-vzuttya',
        '/ru/category/66/espadril' => 	'/ru/category/66/espadril-cholovichi',
        '/ru/category/67/shlopanci-' => 	'/ru/category/67/shlopanci',
        '/ru/category/23/malenki' => 	'/ru/category/23/malenki-sumky',
        '/category/66/espadril' => 	'/category/66/espadril-cholovichi',
        '/category/68/shlopanci' => 	'/category/68/shlopanci-cholovichi',
        '/ru/category/68/shlopanci' => 	'/ru/category/68/shlopanci-cholovichi',
        '/ru/category/10/tufli' => 	'/ru/category/10/tufli-cholovichi',
        '//' => 	'/',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //dd($request->getRequestUri());

        if ( isset( $this->redirects_list[ $request->getRequestUri() ] ) )
        {
            return redirect( $this->redirects_list[ $request->getRequestUri() ], 301 );
        }

        preg_match("/\/\/+/", $request->getRequestUri(), $output_array);

        if (count($output_array))
        {
            return redirect( preg_replace("/\/\/+/", "/", $request->getRequestUri()), 301 );
        }

        return $next($request);
    }
}
