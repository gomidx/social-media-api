<?php

namespace App\Http\Controllers;

use App\Helpers\Http;
use App\Http\Requests\Follow\StoreFollowRequest;
use App\Services\FollowService;
use Illuminate\Http\JsonResponse;

class FollowController extends Controller
{
    use Http;

    private FollowService $service;

    public function __construct()
    {
        $this->service = new FollowService;
    }

    /**
     * @OA\Post(
     *      path="/follow",
     *      operationId="createFollow",
     *      tags={"Interações"},
     *      summary="",
     *      description="Endpoint para seguir um usuário.",
	 *		@OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
	 * 				type="object",
	 * 				@OA\Property(property="user_id", type="integer", example="6"),
	 * 			)
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Sucesso, usuário seguido.",
     *          @OA\JsonContent(
	 * 				@OA\Property(property="data", type="string", example="User successfuly followed.")
	 * 			)
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Você já segue esse usuário.",
     *          @OA\JsonContent(
	 * 				@OA\Property(property="data", type="string", example="You already follow this user.")
	 * 			)
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Usuário não encontrado.",
     *          @OA\JsonContent(
	 * 				@OA\Property(property="data", type="string", example="User doesn't exists.")
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
    public function store(StoreFollowRequest $request): JsonResponse
    {
        try {
            $data = $this->service->createFollow($request->validated());

            return response()->json($data['response'], $data['code']);
        } catch (\Throwable $th) {
            $data = $this->serverError();

            return response()->json($data['response'], $data['code']);
        }
    }

    /**
     * @OA\Get(
     *      path="/user/{id}/followers",
     *      operationId="getFollowers",
     *      tags={"Interações"},
     *      summary="",
     *      description="Endpoint para consultar seguidores de um usuário.",
     *      security={{"sanctum": {}}},
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
     *          description="Sucesso, vendedor encontrado.",
     *          @OA\JsonContent(
	 * 				@OA\Property(property="data", type="array",
     *                  @OA\Items(type="object",
     *                      @OA\Property(property="id", type="integer", example=1),
     *                      @OA\Property(property="name", type="string", example="Lucas Gomide"),
	 * 				        @OA\Property(property="username", type="string", example="gomidx"),
	 * 				        @OA\Property(property="email", type="string", example="lucasgomide@gmail.com"),
	 * 				        @OA\Property(property="description", type="string", example="Desenvolvedor PHP Pleno"),
	 * 				        @OA\Property(property="email_verified_at", type="string", example="null"),
     *                      @OA\Property(property="created_at", type="string", format="date-time", example="2023-10-25T01:57:56.000000Z"),
     *                      @OA\Property(property="updated_at", type="string", format="date-time", example="2023-10-25T01:57:56.000000Z")
     *                  )
     *              )
     *          )
     *       ),
     *       @OA\Response(
     *          response=404,
     *          description="Usuário não encontrado.",
     *          @OA\JsonContent(
	 * 				@OA\Property(property="data", type="string", example="User doesn't exists.")
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
    public function getFollowers(int $userId): JsonResponse
    {
        try {
            $data = $this->service->getFollowers($userId);

            return response()->json($data['response'], $data['code']);
        } catch (\Throwable $th) {
            $data = $this->serverError();

            return response()->json($data['response'], $data['code']);
        }
    }

    /**
     * @OA\Get(
     *      path="/user/{id}/followed",
     *      operationId="getFollowed",
     *      tags={"Interações"},
     *      summary="",
     *      description="Endpoint para consultar quem um usuário segue.",
     *      security={{"sanctum": {}}},
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
     *          description="Sucesso, usuários encontrados.",
     *          @OA\JsonContent(
	 * 				@OA\Property(property="data", type="array",
     *                  @OA\Items(type="object",
     *                      @OA\Property(property="id", type="integer", example=1),
     *                      @OA\Property(property="name", type="string", example="Lucas Gomide"),
	 * 				        @OA\Property(property="username", type="string", example="gomidx"),
	 * 				        @OA\Property(property="email", type="string", example="lucasgomide@gmail.com"),
	 * 				        @OA\Property(property="description", type="string", example="Desenvolvedor PHP Pleno"),
	 * 				        @OA\Property(property="email_verified_at", type="string", example="null"),
     *                      @OA\Property(property="created_at", type="string", format="date-time", example="2023-10-25T01:57:56.000000Z"),
     *                      @OA\Property(property="updated_at", type="string", format="date-time", example="2023-10-25T01:57:56.000000Z")
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Usuário não encontrado.",
     *          @OA\JsonContent(
	 * 				@OA\Property(property="data", type="string", example="User doesn't exists.")
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
    public function getFollowed(int $userId): JsonResponse
    {
        try {
            $data = $this->service->getFollowed($userId);

            return response()->json($data['response'], $data['code']);
        } catch (\Throwable $th) {
            $data = $this->serverError();

            return response()->json($data['response'], $data['code']);
        }
    }

    /**
     * @OA\Get(
     *      path="/follower/{id}/remove",
     *      operationId="removeFollower",
     *      tags={"Interações"},
     *      summary="",
     *      description="Endpoint para remover um seguidor.",
     *      security={{"sanctum": {}}},
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
     *          description="Sucesso, usuários encontrados.",
     *          @OA\JsonContent(
	 * 				@OA\Property(property="data", type="string", example="")
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="O usuário informado não lhe segue.",
     *          @OA\JsonContent(
	 * 				@OA\Property(property="data", type="string", example="This user doesn't follow you.")
	 * 			)
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Usuário não encontrado.",
     *          @OA\JsonContent(
	 * 				@OA\Property(property="data", type="string", example="User doesn't exists.")
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
    public function removeFollower(int $userId): JsonResponse
    {
        try {
            $data = $this->service->removeFollower($userId);

            return response()->json($data['response'], $data['code']);
        } catch (\Throwable $th) {
            $data = $this->serverError();

            return response()->json($data['response'], $data['code']);
        }
    }

    /**
     * @OA\Delete(
     *      path="/followed/{id}/remove",
     *      operationId="stopFollowing",
     *      tags={"Interações"},
     *      summary="",
     *      description="Endpoint para parar de seguir um usuário.",
     *      security={{"sanctum": {}}},
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
     *          description="Sucesso, usuários encontrados.",
     *          @OA\JsonContent(
	 * 				@OA\Property(property="data", type="string", example="")
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Você não segue o usuário informado.",
     *          @OA\JsonContent(
	 * 				@OA\Property(property="data", type="string", example="You don't follow this user.")
	 * 			)
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Usuário não encontrado.",
     *          @OA\JsonContent(
	 * 				@OA\Property(property="data", type="string", example="User doesn't exists.")
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
    public function stopFollowing(int $userId): JsonResponse
    {
        try {
            $data = $this->service->stopFollowing($userId);

            return response()->json($data['response'], $data['code']);
        } catch (\Throwable $th) {
            $data = $this->serverError();

            return response()->json($data['response'], $data['code']);
        }
    }
}
