<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\UnauthorizedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Http\Services\ImageService;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class UserController extends Controller
{
    public function __construct(readonly protected ImageService $imageService)
    {
    }

    /**
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'page' => 'integer|min:1',
            'count' => 'integer|min:1|max:100',
        ]);

        $count = $validated['count'] ?? 5;

        $users = User::orderBy('id', 'desc')->paginate($count);

        return response()->json([
            'success' => true,
            'page' => $users->currentPage(),
            'total_pages' => $users->lastPage(),
            'total_users' => $users->total(),
            'count' => $users->perPage(),
            'links' => [
                'next_url' => $users->nextPageUrl(),
                'prev_url' => $users->previousPageUrl(),
            ],
            'users' => UserResource::collection($users->items()),
        ], ResponseAlias::HTTP_OK);
    }

    /**
     *
     * @param User $user
     * @return JsonResponse
     */
    public function show(User $user): JsonResponse
    {
        return response()->json([
            'success' => true,
            'user' => new UserResource($user),
        ], ResponseAlias::HTTP_OK);
    }

    /**
     *
     * @param StoreUserRequest $request
     * @return JsonResponse
     * @throws UnauthorizedException
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        if (!$request->user()->tokenCan('registration')) {
            throw new UnauthorizedException('The token does not have the necessary permissions.');
        }

        DB::beginTransaction();

        try {

            $photoPath = $this->imageService->processImage($request->file('photo'));

            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'position_id' => $request->input('position_id'),
                'photo' => $photoPath,
            ]);

            $request->user()->currentAccessToken()->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'user_id' => $user->id,
                'message' => 'New user successfully registered',
            ], ResponseAlias::HTTP_CREATED);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to register new user',
                'fails' => ['exception' => [$e->getMessage()]],
            ], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}

