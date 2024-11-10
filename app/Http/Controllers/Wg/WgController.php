<?php

namespace App\Http\Controllers\Wg;

use App\Models\Server\Wg;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Elyerr\ApiResponse\Exceptions\ReportError;
use App\Http\Controllers\GlobalController as Controller;

class WgController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Wg $wg)
    {
        $params = $this->filter_transform($wg->transformaer);
        $wgs = $this->search($wg->table, $params);

        return $this->showAll($wgs, $wg->transformer);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Wg $wg)
    {
        $request->validate([
            'name' => ['required', 'max:20'],
            'listen_port' => ['required', 'max:10'],
            'dns_1' => ['nullable', 'max:190'],
            'dns_2' => ['nullable', 'max:190'],
            'server_id' => ['required', 'exists:servers,id'],
        ]);

        DB::transaction(function () use ($request, $wg) {
            //generate a private key

            $wg = $wg->fill($request->except('private_key'));
            $wg->private_key = $wg->genratePrivKey();

            $wg->save();

        });

        return $this->showOne($wg, $wg->transformer, 201);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Wg $server)
    {
        return $this->showOne($server, $server->transformer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Wg $wg)
    {
        $request->validate([
            'name' => ['nullable', 'max:20'],
            'dns_1' => ['nullable', 'max:190'],
            'dns_2' => ['nullable', 'max:190'],
        ]);

        DB::transaction(function () use ($request, $wg) {

            $updated = false;

            if ($this->is_diferent($wg->name, $request->name)) {
                $updated = true;
                $wg->name = $request->name;
            }

            if ($this->is_diferent($wg->dns_1, $request->dns_1)) {
                $updated = true;
                $wg->dns_1 = $request->dns_1;
            }

            if ($this->is_diferent($wg->dns_2, $request->dns_2)) {
                $updated = true;
                $wg->dns_2 = $request->dns_2;
            }

            if ($updated) {
                $wg->push();
            }
        });

        return $this->showOne($wg, $wg->transformer, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Wg $wg)
    {
        throw_if($wg->peers()->count() > 0, new ReportError(__('Unable to delete this resource because it has assigned dependencies. Please remove any associated resources first.'), 403));

        $wg->delete();

        return $this->showOne($wg, $wg->transformer);
    }

    /**
     * On and off network interface
     *
     * @param Wg $wg
     */
    public function toggle(Wg $wg)
    {
        /**
         * More action
         */
        $wg->active = !$wg->active ? now() : null;
        $wg->push();

        return $this->showOne($wg, $wg->transformer, 201);
    }
}
