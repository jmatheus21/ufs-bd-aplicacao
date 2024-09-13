<?php

namespace App\Http\Controllers;

use App\Models\Mecanico;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MecanicoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $mecanico = Mecanico::all();

        return response()->json([
            'status' => true,
            'mecanico' => $mecanico
        ], 200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $mecanico = Mecanico::create([
                'cpf' => $request->cpf,
                'primeiro_nome' => $request->primeiro_nome,
                'sobrenome' => $request->sobrenome,
                'email' => $request->email,
                'telefone' => $request->telefone,
                'escolaridade' => $request->escolaridade,
                'data_nascimento' => $request->data_nascimento,
                'salario' => $request->salario,
                'cargo' => $request->cargo,
                'admissao' => $request->admissao,
                'validade' => $request->validade,
                'sexo' => $request->sexo,
                'endereco' => $request->endereco,
                'estado_civil' => $request->estado_civil,
                'raca' => $request->raca,
                'licencas' => $request->licencas,
                'habilidades' => $request->habilidades 
            ]);

            DB::commit();

            return response()->json([
                'status'=> true,
                'mecanico' => $mecanico,
                'message' => 'Mecânico cadastrado com sucesso'
            ], 201);

        } catch (Exception $e) {

            DB::rollback();

            return response()->json([
                'status' => false,
                'message' => 'Mecanico não cadastrado!'
            ], 400);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $cpf)
    {

        DB::beginTransaction();

        try{

            $mecanico = Mecanico::where('cpf', $cpf)->firstOrFail();

            $mecanico->update([
                'primeiro_nome' => $request->primeiro_nome,
                'sobrenome' => $request->sobrenome,
                'email' => $request->email,
                'telefone' => $request->telefone,
                'escolaridade' => $request->escolaridade,
                'data_nascimento' => $request->data_nascimento,
                'salario' => $request->salario,
                'cargo' => $request->cargo,
                'admissao' => $request->admissao,
                'validade' => $request->validade,
                'sexo' => $request->sexo,
                'endereco' => $request->endereco,
                'estado_civil' => $request->estado_civil,
                'raca' => $request->raca,
                'licencas' => $request->licencas,
                'habilidades' => $request->habilidades 
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'mecanico' => $mecanico,
                'message' => 'Mecânico atualizado com sucesso!'
            ], 200);
        }catch (ModelNotFoundException $e){
            
                DB::rollBack();

                return response()->json([
                'status' => false,
                'message' => 'Mecanico não foi encontrado!'
                ], 404);
        } catch (Exception $e){

            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Erro ao atualizar os dados do mecânico!',
                'error' => $e->getMessage()
            ], 500); 
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($cpf)
    {

        try{

            $mecanico = Mecanico::where('cpf', $cpf)->firstOrFail();

            $mecanico->delete();

            return response()->json([
                'status' => true,
                'mecanico' => $mecanico,
                'message' => 'Mecanico deletado com sucesso!'
            ], 200);
        
        } catch (ModelNotFoundException $e){
            
            return response()->json([
            'status' => false,
            'message' => 'Mecanico não foi encontrado!'
            ], 404);
        } catch (Exception $e){

            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Erro ao atualizar os dados do mecânico!',
                'error' => $e->getMessage()
            ], 500); 
        }
    }
}
