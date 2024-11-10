<?php

namespace App\Http\Controllers\Server;

use App\Http\Controllers\GlobalController;
use App\Models\Server\Server;
use Elyerr\ApiResponse\Exceptions\ReportError;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServerController extends GlobalController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Server $server)
    {
        //filter params
        $params = $this->filter_transform($server->transformer);
        $data = $this->search($server->table, $params);
        return $this->showAll($data, $server->transformer);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Server $server)
    {
        $request->validate([
            'country' => ['string', 'max:190'],
            'ipv4' => ['required', 'unique:servers,ipv4', 'ipv4'],
            'city' => ['nullable', 'max:190'],
            'ipv6' => ['nullable', 'ipv6'],
            'uri' => ['nullable', 'max:190'],
        ]);

        DB::transaction(function () use ($request, $server) {
            $server = $server->fill($request->all());
            $server->save();
        });

        return $this->showOne($server, $server->transformer, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Server $server)
    {
        return $this->showOne($server);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Server $server)
    {
        $request->validate([
            'country' => ['string', 'max:190'],
            'city' => ['string', 'max:190'],
            'ipv4' => ['required', 'unique:servers,ipv4,' . $server->id, 'ipv4'],
            'ipv6' => ['nullable', 'ipv6'],
            'uri' => ['nullable', 'max:190'],
        ]);

        DB::transaction(function () use ($request, $server) {

            $updated = false;

            if ($this->is_diferent($server->country, $request->country)) {
                $updated = true;
                $server->country = $request->country;
            }

            if ($this->is_diferent($server->city, $request->city)) {
                $updated = true;
                $server->city = $request->city;
            }

            if ($this->is_diferent($server->ipv4, $request->ipv4)) {
                $updated = true;
                $server->ipv4 = $request->ipv4;
            }

            if ($this->is_diferent($server->ipv6, $request->ipv6)) {
                $updated = true;
                $server->ipv6 = $request->ipv6;
            }

            if ($updated) {
                $server->save();
            }
        });

        return $this->showOne($server, $server->transformer, 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Server $server)
    {
        throw_if($server->wgs()->count() > 0, new ReportError(__('Unable to delete this resource because it has assigned dependencies. Please remove any associated resources first.'), 403));

        $server->delete();

        return $this->show($server);
    }

    /**
     * Enable the specified resource
     */
    public function toggle(Server $server)
    {
        $server->active = !$server->active ? now() : null;
        $server->push();

        return $this->show($server, 201);
    }
}
