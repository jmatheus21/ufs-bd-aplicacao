<?php

namespace App\Http\Controllers;

use App\Models\Aeronave;
use App\Models\Mantem;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MantemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $manutencao = Mantem::orderBy('id', 'DESC')->get();
        
        return response()->json([
            'status' => true,
            'manutencao' => $manutencao
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        DB::beginTransaction();

        try {

            // Validar dados
            $request->validate([
                'mecanico_cpf' => 'required|exists:mecanico,cpf', // Verifica se existe o mecanico com esse CPF
                'aeronave_matricula' => 'required|exists:aeronave,matricula', // Verifica se existe a aeronave com essa matrícula
                'horario' => 'required|date',
                'detalhes' => 'required|string|max:50',
            ]);
                        
            $manutencao = Mantem::create([
               'mecanico_cpf' => $request->mecanico_cpf,
               'aeronave_matricula' => $request->aeronave_matricula,
               'horario' => $request->horario,
               'detalhes' => $request->detalhes
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'manutencao' => $manutencao,
                'message' => 'Manutenção cadastrada com sucesso!'
            ], 201);

        } catch (Exception $e) {
            
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Manutenção não cadastrada!',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function transacao (Request $request, Mantem $manutencao)
    {
        $validatedData = $request->validate([
            'mecanico_cpf' => 'required|exists:mecanico,cpf',
            'aeronave_matricula' => 'required|exists:aeronave,matricula',
            'horario' => 'required|date',
            'detalhes' => 'required|string|max:50',
        ]);

        DB::beginTransaction();

        try {
            // Busca a aeronave pela matrícula
            $aeronave = Aeronave::where('matricula', $validatedData['aeronave_matricula'])->first();

            // Verifica a condição da aeronave
            if (in_array($aeronave->condicao, ['disponivel', 'degradado'])) {
                
                $aeronave->condicao = 'manutencao'; // Atualiza a condição para "manutencao"
                $aeronave->save();

                // Cria uma nova entrada de manutenção
                $manutencao = Mantem::create([
                    'mecanico_cpf' => $validatedData['mecanico_cpf'],
                    'aeronave_matricula' => $validatedData['aeronave_matricula'],
                    'horario' => $validatedData['horario'],
                    'detalhes' => $validatedData['detalhes']
                 ]);

                DB::commit();

                return response()->json([
                    'status' => true,
                    'manutencao' => $manutencao,
                    'message' => 'Manutenção registrada com sucesso e aeronave atualizada para manutenção'
                ], 200);
            } else {
                // Caso a aeronave não esteja disponível ou degradada
                DB::rollBack();  // Faz rollback se a condição não permitir manutenção
                return response()->json([
                    'status' => false,
                    'error' => 'A aeronave não está disponível para manutenção',
                ], 400);
            }
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Erro ao cadastrar manutenção',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Mantem $manutencao)
    {
        DB::beginTransaction();

        try {

            // Validar dados
            $request->validate([
                'mecanico_cpf' => 'required|exists:mecanico,cpf', // Verifica se existe o mecanico com esse CPF
                'aeronave_matricula' => 'required|exists:aeronave,matricula', // Verifica se existe a aeronave com essa matrícula
                'horario' => 'required|date',
                'detalhes' => 'required|string|max:50',
            ]);

            $manutencao->update([
                'mecanico_cpf' => $request->mecanico_cpf,
                'aeronave_matricula' => $request->aeronave_matricula,
                'horario' => $request->horario,
                'detalhes' => $request->detalhes
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'manutencao' => $manutencao,
                'message' => 'Manutenção editada com sucesso!'
            ], 200);
            
        } catch (ModelNotFoundException $e){
            
            return response()->json([
            'status' => false,
            'message' => 'Manutenção não foi encontrada!'
            ], 404);
            
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Manutenção não editada!'
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mantem $manutencao)
    {
        try {

            $manutencao->delete();

            return response()->json([
                'status' => true,
                'aeronave' => $manutencao,
                'message' => 'Manutenção apagada com sucesso!'
            ], 200);
            
        } catch (ModelNotFoundException $e){
            
            return response()->json([
            'status' => false,
            'message' => 'Manutenção não foi encontrada!'
            ], 404);
            
        } catch (Exception $e) {

            return response()->json([
                'status' => false,
                'message' => 'Manutenção não apagada!',
                'error' => $e->getMessage()
            ], 400);
        }
    }
}