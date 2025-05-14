<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 19/10/2023
 * @copyright NIP CIBER-LAB @2023
 */

namespace App\Http\Controllers\Search;

use App\APIs\CortexApi;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VehicleSearchController extends Controller
{
    /**
     * @param Request $request
     * @return Response|JsonResponse|Application|ResponseFactory
     */
    public function search(Request $request): Response|JsonResponse|Application|ResponseFactory
    {
        $response = $this->cortex($request->plate);
        $status = $response['status'];
        $data = $response['data'];

        if ($status == 200) {
            $dataObject = (object)$data;
            $view = view('cortex.vehicle.show', ['data' => $dataObject])->render();
            return response()->json(['view' => $view, 'data' => $dataObject, 'status' => $status]);
        }

        return response($response['data'], $status);
    }

    /**
     * @param string $plate
     * @return array
     */
    public function cortex(string $plate): array
    {
        $cortex = new CortexApi();
        return $cortex->vehiclePlateAndMoviment($plate);
    }
}
