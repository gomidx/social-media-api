<?php

namespace App\Http\Controllers;

use App\Helpers\Http;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    use Http;

    public AuthService $service;

    public function __construct()
    {
        $this->service = new AuthService;
    }

    /**
     * @OA\Post(
     *      path="/token",
     *      operationId="generateToken",
     *      tags={"Autenticação"},
     *      summary="",
     *      description="Endpoint para gerar o token utilizado para a autenticação das requisições. É necessário primeiramente criar um usuário e posteriormente gerar o token com seus respectivos dados.",
	 *		@OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
	 * 				type="object",
	 * 				@OA\Property(property="email", type="string", example="lucasgomidecv@gmail.com"),
	 * 				@OA\Property(property="password", type="string", example="abc142536%")
	 * 			)
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Sucesso, token gerado.",
     *          @OA\JsonContent(
	 * 				@OA\Property(property="data", type="string", example="{{token}}")
	 * 			)
     *       ),
     *       @OA\Response(
     *          response=404,
     *          description="Não encontrado.",
	 * 			@OA\JsonContent(
	 * 				@OA\Property(property="error", type="string", example="User doesn't exists.")
	 * 			)
     *       ),
     *       @OA\Response(
     *          response=422,
     *          description="Senha inválida.",
	 * 			@OA\JsonContent(
	 * 				@OA\Property(property="error", type="string", example="Invalid password.")
	 * 			)
     *       ),
	 * 		 @OA\Response(
	 *     	    response=500,
	 *     		description="Erro interno.",
	 *     	 	@OA\JsonContent(
	 *         		@OA\Property(property="error", type="string", example="Internal error, contact an administrator."),
	 *     	 	)
	 * 		 )
     * )
     */
    public function generateToken(LoginRequest $request): JsonResponse
    {
        try {
            $data = $this->service->generateToken($request->validated());

            return response()->json($data['response'], $data['code']);
        } catch (\Throwable $th) {
            $data = $this->serverError();

            return response()->json($data['response'], $data['code']);
        }
    }

    /**
     * @OA\Post(
     *      path="/logout",
     *      operationId="disconnectUser",
     *      tags={"Autenticação"},
     *      summary="",
     *      description="Endpoint para desconectar o usuário.",
     *      security={{"sanctum": {}}},
     *      @OA\Response(
     *          response=200,
     *          description="Sucesso, usuário desconectado.",
     *          @OA\JsonContent(
	 * 				@OA\Property(property="data", type="string", example="Successfully disconnected.")
	 * 			)
     *       ),
     *       @OA\Response(
     *          response=401,
     *          description="Token inválido.",
	 * 			@OA\JsonContent(
	 * 				@OA\Property(property="error", type="string", example="Invalid token.")
	 * 			)
     *       ),
	 * 		 @OA\Response(
	 *     	    response=500,
	 *     		description="Erro interno.",
	 *     	 	@OA\JsonContent(
	 *         		@OA\Property(property="error", type="string", example="Internal error, contact an administrator."),
	 *     	 	)
	 * 		 )
     * )
     */
    public function logout(): JsonResponse
    {
        try {
            $data = $this->service->logout();

            return response()->json($data['response'], $data['code']);
        } catch (\Throwable $th) {
            $data = $this->serverError();

            return response()->json($data['response'], $data['code']);
        }
    }
}
