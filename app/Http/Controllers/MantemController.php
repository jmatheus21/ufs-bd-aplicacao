<?php

namespace App\Http\Controllers;

use App\Models\Aeronave;
use App\Models\Mantem;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Type\Integer;

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

    public function show($id)
    {
        $manutencao = Mantem::find($id);

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
            $validatedData = $request->validate([
                'mecanico_cpf' => 'required|exists:mecanico,cpf', // Verifica se existe o mecanico com esse CPF
                'aeronave_matricula' => 'required|exists:aeronave,matricula', // Verifica se existe a aeronave com essa matrícula
                'horario' => 'required|date',
                'detalhes' => 'required|string|max:50',
            ]);

            // Busca a aeronave pela matrícula
            $aeronave = Aeronave::where('matricula', $validatedData['aeronave_matricula'])->first();

            // Verifica a condição da aeronave
            if (in_array($aeronave->condicao, ['disponivel', 'degradado'])) {

                $aeronave->condicao = 'manutencao'; // Atualiza a condição para "manutencao"
                $aeronave->ultima_manutencao = $validatedData['horario'];
                $aeronave->save();

                // Cria uma nova entrada de manutenção
                $manutencao = Mantem::create([
                    'mecanico_cpf' => $validatedData['mecanico_cpf'],
                    'aeronave_matricula' => $validatedData['aeronave_matricula'],
                    'horario' => $validatedData['horario'],
                    'status' => 'em andamento',
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
    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $manutencao = Mantem::find($id);

            if (!$manutencao) {
                return response()->json([
                    'status' => false,
                    'message' => 'Manutenção não foi encontrada!'
                ], 404);
            }

            $validatedData = $request->validate([
                'mecanico_cpf' => 'sometimes|exists:mecanico,cpf',
                'aeronave_matricula' => 'sometimes|exists:aeronave,matricula',
                'horario' => 'sometimes|date',
                'status' => 'sometimes|string|max:15',
                'detalhes' => 'sometimes|string|max:50',
            ]);

            $manutencao->update($validatedData);

            // Atualiza a condição da aeronave se o status for 'finalizado'
            if ($request->has('status') && $request->status == 'finalizado') {
                $aeronave = Aeronave::where('matricula', $manutencao['aeronave_matricula'])->first();
                if ($aeronave) {
                    $aeronave->condicao = 'disponivel';
                    $aeronave->save();
                }
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'manutencao' => $manutencao,
                'message' => 'Manutenção editada com sucesso!'
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Manutenção não editada!',
                'error' => $e->getMessage() // Opicional, para depuração
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $manutencao = Mantem::find($id);

        try {

            $manutencao->delete();

            return response()->json([
                'status' => true,
                'manutencao' => $manutencao,
                'message' => 'Manutenção apagada com sucesso!'
            ], 200);
        } catch (ModelNotFoundException $e) {

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