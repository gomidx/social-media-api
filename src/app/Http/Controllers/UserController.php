<?php

namespace App\Http\Controllers;

use App\Helpers\Http;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    use Http;

    private UserService $service;

    public function __construct()
    {
        $this->service = new UserService;
    }

    /**
     * @OA\Post(
     *      path="/user",
     *      operationId="createUser",
     *      tags={"Usuários"},
     *      summary="",
     *      description="Endpoint para criar um usuário.",
	 *		@OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
	 * 				type="object",
	 * 				@OA\Property(property="name", type="string", example="Lucas Gomide"),
	 * 				@OA\Property(property="username", type="string", example="gomidx"),
     * 				@OA\Property(property="email", type="string", example="lucasgomide@gmail.com"),
	 * 				@OA\Property(property="description", type="string", example="Desenvolvedor PHP Pleno"),
	 * 				@OA\Property(property="password", type="string", example="123456"),
	 * 			)
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Sucesso, usuário criado.",
     *          @OA\JsonContent(
	 * 				@OA\Property(property="data", type="object",
     *                  @OA\Property(property="id", type="integer", example=1),
     *                  @OA\Property(property="name", type="string", example="Lucas Gomide"),
	 * 				    @OA\Property(property="username", type="string", example="gomidx"),
	 * 				    @OA\Property(property="email", type="string", example="lucasgomide@gmail.com"),
	 * 				    @OA\Property(property="description", type="string", example="Desenvolvedor PHP Pleno"),
     *                  @OA\Property(property="created_at", type="string", format="date-time", example="2023-10-25T01:57:56.000000Z"),
     *                  @OA\Property(property="updated_at", type="string", format="date-time", example="2023-10-25T01:57:56.000000Z")
     *              )
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
    public function store(StoreUserRequest $request): JsonResponse
    {
        try {
            $data = $this->service->createUser($request->validated());

            return response()->json($data['response'], $data['code']);
        } catch (\Throwable $th) {
            $data = $this->serverError();

            return response()->json($data['response'], $data['code']);
        }
    }

    /**
     * @OA\Get(
     *      path="/user/{id}",
     *      operationId="getUser",
     *      tags={"Usuários"},
     *      summary="",
     *      description="Endpoint para consultar um usuário.",
     *      @OA\Parameter(
     *          name="id",
     *          description="ID do usuário",
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Sucesso, usuário encontrado.",
     *          @OA\JsonContent(
	 * 				@OA\Property(property="data", type="object",
     *                  @OA\Property(property="id", type="integer", example=1),
     *                  @OA\Property(property="name", type="string", example="Lucas Gomide"),
	 * 				    @OA\Property(property="username", type="string", example="gomidx"),
	 * 				    @OA\Property(property="email", type="string", example="lucasgomide@gmail.com"),
	 * 				    @OA\Property(property="description", type="string", example="Desenvolvedor PHP Pleno"),
	 * 				    @OA\Property(property="email_verified_at", type="string", example="null"),
     *                  @OA\Property(property="created_at", type="string", format="date-time", example="2023-10-25T01:57:56.000000Z"),
     *                  @OA\Property(property="updated_at", type="string", format="date-time", example="2023-10-25T01:57:56.000000Z")
     *              )
     *          )
     *       ),
     *       @OA\Response(
     *          response=404,
     *          description="Não encontrado.",
	 * 			@OA\JsonContent(
	 * 				@OA\Property(property="error", type="string", example="User doesn't exists.")
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
    public function show(string $userId): JsonResponse
    {
        try {
            $data = $this->service->getUser($userId);

            return response()->json($data['response'], $data['code']);
        } catch (\Throwable $th) {
            $data = $this->serverError();

            return response()->json($data['response'], $data['code']);
        }
    }

    /**
     * @OA\Put(
     *      path="/user/{id}",
     *      operationId="updateUser",
     *      tags={"Usuários"},
     *      summary="",
     *      description="Endpoint para atualizar um usuário.",
     *      @OA\Parameter(
     *          name="id",
     *          description="ID do usuário",
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
	 *		@OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
	 * 				type="object",
	 * 				@OA\Property(property="username", type="string", example="gomide3"),
	 * 				@OA\Property(property="description", type="string", example="Desenvolvedor PHP Pleno formado em Análise e Desenvolvimento de Sistemas pela FATEC Franca."),
	 * 			)
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Sucesso, usuário atualizado.",
     *          @OA\JsonContent(
	 * 				@OA\Property(property="data", type="object",
     *                  @OA\Property(property="id", type="integer", example=1),
     *                  @OA\Property(property="name", type="string", example="Lucas Gomide"),
	 * 				    @OA\Property(property="username", type="string", example="gomide3"),
	 * 				    @OA\Property(property="email", type="string", example="lucasgomide@gmail.com"),
     * 				    @OA\Property(property="description", type="string", example="Desenvolvedor PHP Pleno formado em Análise e Desenvolvimento de Sistemas pela FATEC Franca."),
	 * 				    @OA\Property(property="email_verified_at", type="string", example="null"),
     *                  @OA\Property(property="created_at", type="string", format="date-time", example="2023-10-25T01:57:56.000000Z"),
     *                  @OA\Property(property="updated_at", type="string", format="date-time", example="2023-10-25T01:57:56.000000Z")
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Não encontrado.",
	 * 		    @OA\JsonContent(
	 * 		        @OA\Property(property="error", type="string", example="User doesn't exists.")
	 * 		    )
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Permissão insuficiente.",
	 * 			@OA\JsonContent(
	 * 				@OA\Property(property="error", type="string", example="You don't have permission to update or delete this user.")
	 * 			)
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Token inválido.",
	 * 			@OA\JsonContent(
	 * 				@OA\Property(property="error", type="string", example="Invalid token.")
	 * 			)
     *      ),
	 * 		@OA\Response(
	 *     	    response=500,
	 *     		description="Erro interno.",
	 *     	 	@OA\JsonContent(
	 *         		@OA\Property(property="error", type="string", example="Internal error, contact an administrator."),
	 *     	 	)
	 * 		)
     * )
     */
    public function update(string $userId, UpdateUserRequest $request): JsonResponse
    {
        try {
            $data = $this->service->updateUser($userId, $request->validated());

            return response()->json($data['response'], $data['code']);
        } catch (\Throwable $th) {
            $data = $this->serverError();

            return response()->json($data['response'], $data['code']);
        }
    }

    /**
     * @OA\Delete(
     *      path="/user/{id}",
     *      operationId="deleteUser",
     *      tags={"Usuários"},
     *      summary="",
     *      description="Endpoint para excluir um usuário.",
     *      @OA\Parameter(
     *          name="id",
     *          description="ID do usuário",
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Sucesso, usuário excluído.",
     *          @OA\JsonContent(
	 * 				@OA\Property(property="data", type="object", example="User successfully deleted.")
     *          )
     *       ),
     *       @OA\Response(
     *          response=404,
     *          description="Não encontrado.",
	 * 			@OA\JsonContent(
	 * 				@OA\Property(property="error", type="string", example="User doesn't exists.")
	 * 			)
     *       ),
     *       @OA\Response(
     *          response=403,
     *          description="Permissão insuficiente.",
	 * 			@OA\JsonContent(
	 * 				@OA\Property(property="error", type="string", example="You don't have permission to update or delete this user.")
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
    public function destroy(string $userId): JsonResponse
    {
        try {
            $data = $this->service->deleteUser($userId);

            return response()->json($data['response'], $data['code']);
        } catch (\Throwable $th) {
            $data = $this->serverError();

            return response()->json($data['response'], $data['code']);
        }
    }
}
