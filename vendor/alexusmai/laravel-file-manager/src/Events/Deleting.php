<?php

namespace Alexusmai\LaravelFileManager\Events;

use App\Models\filemanager_permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Deleting
{
    /**
     * @var string
     */
    private $disk;

    /**
     * @var array
     */
    private $items;

    /**
     * Deleting constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->disk = $request->input('disk');
        $this->items = $request->input('items');
        $this->boot();
    }

    public function boot() {

//        dd(Auth::user()->roles()->get());
        $a = $this->items;
        $p = $a[0]["path"];
        $r = strtok($p, "/");//root folder
        if( !((Auth::user()->hasRole('superadmin')) || (Auth::user()->hasRole('systemsuperadmin'))) ) {
            if(!(filemanager_permission::where("user_id",Auth::user()->id)->where("dir_name",$r)->where("permission_type",'>',2)->first()))
            {
                \Event::listen('Alexusmai\LaravelFileManager\Events\Deleting', function ($event) {
                    return abort(403); });
            }
        }
    }

    /**
     * @return string
     */
    public function disk()
    {
        return $this->disk;
    }

    /**
     * @return array
     */
    public function items()
    {
        return $this->items;
    }
}
