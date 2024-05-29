<?php

namespace App\Http\Controllers\API\V1\web;

use App\Events\CreateChat;
use App\Events\GroupChatMessage;
use App\Events\MessageSent;
use App\Events\ReadMessage;
use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Encryption;
use App\Http\Resources\V1\ChatUserResource;
use App\Http\Resources\V1\MessageResource;
use App\Http\ValidatorResponse;
use App\Models\Chat;
use App\Models\ChatGroup;
use App\Models\Group;
use App\Models\Message;
use App\Models\MessageGroup;
use App\Models\User;
use App\Repositories\ChatRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChatController extends AppBaseController
{
    private ChatRepository $chatRepository;

    public function __construct(ChatRepository $chatRepo)
    {
        $this->middleware('auth:api', ['except' => []]);
        $this->chatRepository = $chatRepo;
    }


    /**
     * @OA\Post(
     *      path="/send-message",
     *      summary="New Chat",
     *      tags={"Chat"},
     *      description="Chat yozish",
     *      security={{ "api": {} }},
     *      @OA\Parameter(name="text",description="Chat", required=true, in="query",
     *          @OA\Schema(type="string",format="text",example="Xabar yozish")
     *      ),
     *      @OA\Parameter(name="user_id",description="User ID", required=true, in="query",
     *          @OA\Schema(type="integer",format="number",example="1")
     *      ),
     *      @OA\Parameter(name="token",description="User ID", required=true, in="query",
     *          @OA\Schema(type="string",format="text",example=" token")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @OA\Property(
     *                  property="data",
     *                  ref="#/components/schemas/Chat"
     *              ),
     *              @OA\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function sendMessage(Request $request)
    {
        $rules = [
            'text' => 'required',
            'user_id' => 'required',
            'token' => 'required|string'
        ];
        $validator = new ValidatorResponse();
        $validator->check($request, $rules);
        if ($validator->fails) {
            return response()->json($validator->response, 400);
        }

        $auth_user = auth('api')->user();
        $user_id = $request->user_id;
      //  $chat = Chat::where('user_id', $user_id)->where('sender_id', $auth_user->id)->first();

        $chat = Chat::where(function ($query) use ($request, $auth_user) {
            $query->where('sender_id', $auth_user->id)
                ->where('user_id', $request->user_id);
        })->orWhere(function ($query) use ($request, $auth_user) {
            $query->where('user_id', $auth_user->id)
                ->where('sender_id', $request->user_id);
        })->first();

        $isNew = false;
        if (!$chat) {
            $chat = new Chat();
            $chat->user_id = $user_id;
            $chat->sender_id = $auth_user->id;
            $chat->save();
            $isNew = true;
        }

        $message = new Message();
        $message->sender_id = $auth_user->id;
        $message->chat_id = $chat->id;
        $message->text = Encryption::encryptMessage($request->text);
        $message->save();
        $message->token = $request->token;
        if ($isNew) {
            broadcast(new CreateChat($message, $user_id));
        } else {
            broadcast(new MessageSent($message));
        }

        return $this->sendSuccess($request->all());
    }

    public function readMessage(Request $request)
    {
        $rules = [
            'messages' => 'required|array',
        ];
        $validator = new ValidatorResponse();
        $validator->check($request, $rules);
        if ($validator->fails) {
            return response()->json($validator->response, 400);
        }
        $model = Message::whereIn('id', $request->messages);
        $model->update(['is_read' => true]);
        $message = $model->get();
        if ($message) {
            $chat_id = $message[0]->chat_id;
            $read = Message::where('chat_id', $chat_id)->where('sender_type', 'student')->where('is_read', false);
            $read->update(['is_read' => true]);
            broadcast(new ReadMessage($request->messages, $chat_id));

        }
        return response()->json(['message' => 'Read message successfully', 'success' => true,]);
    }


    /**
     * @OA\Get(
     *      path="/get-chat-messages/{user_id}/{paginate}",
     *      summary="GetChatMessage",
     *      tags={"Chat"},
     *      description="Get Course By Group ID",
     *      security={{ "api": {} }},
     *      @OA\Parameter(
     *          name="user_id",
     *          description="id of Course",
     *           @OA\Schema(
     *             type="string"
     *          ),
     *          required=true,
     *          in="path"
     *      ),
     *     @OA\Parameter(
     *          name="paginate",
     *          description="id of Course",
     *           @OA\Schema(
     *             type="string"
     *          ),
     *          required=true,
     *          in="path"
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @OA\Property(
     *                  property="data",
     *                  ref="#/components/schemas/Chat"
     *              ),
     *              @OA\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function getMessages($user_id, $paginate = 100)
    {
        $auth_user = auth('api')->user();
        $user = User::where('id', $user_id)->first();

        if ($user === null) {
            return $this->sendError('User not found');
        }
        $chat = Chat::where(function ($query) use ($user, $auth_user) {
            $query->where('user_id', $user->id)->where('sender_id', $auth_user->id)
                ->orWhere('sender_id', $user->id)->where('user_id', $auth_user->id);
        })->first();

        if (empty($chat)){
            return $this->sendError('Chat Not found');
        }

          $message_chat = Message::where("chat_id", $chat->id)
            ->orderByDesc('created_at')
            ->paginate($paginate);
        return MessageResource::collection($message_chat);

        $data = [
            'success' => true,
            //'data' => MessageResource::collection($message_chat),
            'meta' => [
                'current_page' => $message_chat->currentPage(),
                'from' => $message_chat->firstItem(),
                'to' => $message_chat->lastItem(),
                'last_page' => $message_chat->lastPage(),
                'per_page' => $message_chat->perPage(),
                'total' => $message_chat->total(),
            ],
            'message' => 'successfully',
        ];
        return response()->json($data, 200);
    }


    /**
     * @OA\Get(
     *      path="/get-chat-users",
     *      summary="GetChatUser",
     *      tags={"Chat"},
     *      description="Get Course By Group ID",
     *      security={{ "api": {} }},
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @OA\Property(
     *                  property="data",
     *                  ref="#/components/schemas/Chat"
     *              ),
     *              @OA\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function getUser()
    {
        $auth_user = auth('api')->user();
        $chats = Chat::where(function ($query) use ($auth_user) {
             $query->orWhere('sender_id', $auth_user->id)
                 ->orWhere('user_id', $auth_user->id);
        })->orderBy('created_at')->get();

        if (!$chats) {
            return $this->sendError('Not Found');
        }

        $user_id = [];
        foreach ($chats as $message) {
            $user_id[] += ($message->sender_id == $auth_user->id) ? $message->user_id : $message->sender_id;
        }

        $users = User::whereIn('id', $user_id)->get();

        return response()->json([
            'chat_user' => ChatUserResource::collection($users),
        ]);
    }
}
