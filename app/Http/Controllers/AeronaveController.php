<?php

namespace App\Http\Controllers;

use App\Models\Aeronave;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AeronaveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $aeronave = Aeronave::orderBy('matricula', 'DESC')->get();
        
        return response()->json([
            'status' => true,
            'aeronave' => $aeronave
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        DB::beginTransaction();

        try {
                        
            $aeronave = Aeronave::create([
               'matricula' => $request->matricula,
               'condicao' => $request->condicao,
               'nivel_combustivel' => $request->nivel_combustivel,
               'ultima_manutencao' => $request->ultima_manutencao
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'aeronave' => $aeronave,
                'message' => 'Aeronave cadastrada com sucesso!'
            ], 201);

        } catch (Exception $e) {
            
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Aeronave não cadastrada!'
            ], 400);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Aeronave $aeronave)
    {
        DB::beginTransaction();

        try {

            $aeronave->update([
                'condicao' => $request->condicao,
                'nivel_combustivel' => $request->nivel_combustivel,
                'ultima_manutencao' => $request->ultima_manutencao
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'aeronave' => $aeronave,
                'message' => 'Aeronave editada com sucesso!'
            ], 200);
            
        } catch (ModelNotFoundException $e){
            
            DB::rollBack();

            return response()->json([
            'status' => false,
            'message' => 'Aeronave não foi encontrada!'
            ], 404);
        
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Aeronave não editada!',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Aeronave $aeronave)
    {
        try {

            $aeronave->delete();

            return response()->json([
                'status' => true,
                'aeronave' => $aeronave,
                'message' => 'Aeronave apagada com sucesso!'
            ], 200);
            
        } catch (ModelNotFoundException $e){
            
            return response()->json([
            'status' => false,
            'message' => 'Aeronave não foi encontrada!'
            ], 404);
            
        } catch (Exception $e) {

            return response()->json([
                'status' => false,
                'message' => 'Aeronave não apagada!',
                'error' => $e->getMessage()
            ], 400);
        }
    }
}