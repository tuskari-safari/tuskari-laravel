<?php

namespace App\Http\Controllers\Frontend;

use App\Events\ChatMessageEvent;
use App\Events\MessageSend;
use App\Events\SendMessageEvent;
use App\Events\SendNotificationEvent;
use App\Helpers\ImageHelper;
use App\Http\Controllers\Controller;
use App\Models\ChatDeletion;
use Illuminate\Support\Str;
use App\Models\ChatRoom;
use App\Models\Member;
use App\Models\Message;
use App\Models\User;
use App\Notifications\SendNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class ChatController extends Controller
{
    public function messages(Request $request)
    {
        try {
            $userId = Auth::id();
            $chat_list = ChatRoom::with(['chatMembers' => function ($query) {
                $query->where('user_id', '!=', Auth::id())->limit(1);
            }, 'chatMembers.user'])
                ->whereHas('chatMembers', function ($q) {
                    $q->where('user_id', Auth::id());
                })
                ->orderBy(function ($query) {
                    $query->select('created_at')
                        ->from('messages')
                        ->whereColumn('messages.chat_room_id', 'chat_rooms.id')
                        ->orderByDesc('created_at')
                        ->limit(1);
                }, 'desc')
                ->when($request->searchChatUser, function ($query) use ($request) {
                    $searchTerm = '%' . trim($request->searchChatUser) . '%';
                    $query->where(function ($q) use ($searchTerm) {
                        $q->where('chat_name', 'like', $searchTerm)
                            ->orWhereHas('chatMembers.user', function ($qq) use ($searchTerm) {
                                $qq->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", [$searchTerm])->where('id', '!=', Auth::id());
                            });
                    });
                })
                ->get()->filter(function ($chatRoom) use ($userId) {
                    // Fetch deletion timestamp for this user & chat room
                    $deletedAt = ChatDeletion::where('chat_room_id', $chatRoom->id)
                        ->where('user_id', $userId)
                        ->value('deleted_at');

                    if ($deletedAt && $chatRoom->last_message && $chatRoom->last_message->created_at <= $deletedAt) {
                        // Hide chat if no new messages after deletion
                        return false;
                    }
                    return true;
                })
                ->values();
            return Inertia::render('Frontend/Auth/messages', ['chat_list' => $chat_list]);
        } catch (\Throwable $th) {
            Log::error(" :: EXCEPTION :: " . $th->getMessage() . "\n" . $th->getTraceAsString());
            abort(500);
        }
    }

    public function reverb(Request $request)
    {
        try {
            broadcast(new ChatMessageEvent("connected"))->toOthers();
        } catch (\Throwable $th) {
            Log::error(" :: EXCEPTION :: " . $th->getMessage() . "\n" . $th->getTraceAsString());
            abort(500);
        }
    }

    public function output(Request $request)
    {
        try {
            return Inertia::render('Output');
        } catch (\Throwable $th) {
            Log::error(" :: EXCEPTION :: " . $th->getMessage() . "\n" . $th->getTraceAsString());
            abort(500);
        }
    }

    public function chatBox()
    {
        try {
            return Inertia::render('Admin/chat/List');
        } catch (\Throwable $th) {
            Log::error(" :: EXCEPTION :: " . $th->getMessage() . "\n" . $th->getTraceAsString());
            abort(500);
        }
    }

    public function getUserList(Request $request)
    {
        try {
            $chat_list = ChatRoom::with(['chatMembers' => function ($query) {
                $query->where('user_id', '!=', Auth::id())->limit(1);
            }, 'chatMembers.user'])
                ->whereHas('chatMembers', function ($q) {
                    $q->where('user_id', Auth::id());
                })
                ->orderBy(function ($query) {
                    $query->select('created_at')
                        ->from('messages')
                        ->whereColumn('messages.chat_room_id', 'chat_rooms.id')
                        ->orderByDesc('created_at')
                        ->limit(1);
                }, 'desc')
                ->when($request->search, function ($query) use ($request) {
                    $searchTerm = '%' . trim($request->search) . '%';

                    $query->where(function ($q) use ($searchTerm) {
                        $q->where('chat_name', 'like', $searchTerm)
                            ->orWhereHas('chatMembers.user', function ($qq) use ($searchTerm) {
                                $qq->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", [$searchTerm])->where('id', '!=', Auth::id());
                            });
                    });
                })
                ->get();

            return response()->json(['chat_list' => $chat_list]);
        } catch (\Throwable $th) {
            Log::error(" :: EXCEPTION :: " . $th->getMessage() . "\n" . $th->getTraceAsString());
            abort(500);
        }
    }

    public function deleteChat(Request $request)
    {
        try {
            $chatRoom = ChatRoom::find($request->id);
            $isMember = $chatRoom->chatMembers()->where('user_id', Auth::id())->exists();
            if (!$isMember) {
                return response()->json(['success' => false, 'error' => 'User not found'], 403);
            }
            $otherUserDeleted = ChatDeletion::where('chat_room_id', $chatRoom->id)
                ->where('user_id', '!=', Auth::id())
                ->exists();
            if ($otherUserDeleted) {
                DB::transaction(function () use ($chatRoom) {
                    Message::where('chat_room_id', $chatRoom->id)->delete();
                    ChatDeletion::where('chat_room_id', $chatRoom->id)->delete();
                    $chatRoom->delete();
                });
            } else {
                $chatdelete = ChatDeletion::updateOrInsert(
                    ['chat_room_id' => $chatRoom->id, 'user_id' => Auth::id()],
                    ['deleted_at' => now(), 'updated_at' => now(), 'created_at' => now()]
                );
            }
            return redirect()->route('frontend.messages')
                ->with('success', 'Chat deleted successfully');
        } catch (\Throwable $th) {
            Log::error('Error:: while deleting chat room ' . $th->getMessage());
            return response()->json(['success' => false, 'error' => 'Something went wrong on server side'], 500);
        }
    }

    public function getMessage(Request $request)
    {
        try {
            $userId = Auth::id();
            $deletedAt = null;
            $chatRoom = ChatRoom::with(['chatMembers' => function ($query) {
                $query->where('user_id', '!=', Auth::id());
            }, 'chatMembers.user'])->where('id', $request->room_id)->first();

            if ($chatRoom) {
                $deletedAt = ChatDeletion::where('chat_room_id', $chatRoom->id)
                    ->where('user_id', $userId)
                    ->value('deleted_at');
            }
            $messages = Message::with('user')
                ->where('chat_room_id', $request->room_id)
                ->when($deletedAt !== null, function ($query) use ($deletedAt) {
                    $query->where('created_at', '>', $deletedAt);
                })
                ->orderBy('id', 'desc')
                ->paginate(10);

            if ($messages->isEmpty()) {
                return response()->json(['chatRoom' => $chatRoom, 'code' => 404]);
            }
            return response()->json(['chatRoom' => $chatRoom, 'messages' => $messages]);
        } catch (\Throwable $th) {
            Log::error(" :: EXCEPTION :: " . $th->getMessage() . "\n" . $th->getTraceAsString());
            abort(500);
        }
    }

    public function sendMessage(Request $request)
    {
        $env = config('app.env');
        $request->validate([
            'isNewUser' => ['nullable', Rule::notIn([Auth::id()])],
            'sendChat' => 'nullable',
            'attachment' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,doc,docx,pdf|max:2048'
        ]);

        try {
            $logged_user_id = Auth::id();
            $newuser = $request->isNewUser;

            if ($newuser) {

                $new_chat_room = new ChatRoom;
                $new_chat_room->group_id = $new_chat_room->id;
                $new_chat_room->is_group = 0;
                $new_chat_room->save();

                ChatRoom::where('id', $new_chat_room->id)->update(['group_id' => 'grp-' . $new_chat_room->id]);

                $members = [
                    ['chat_room_id' => $new_chat_room->id, 'user_id' => $logged_user_id],
                    ['chat_room_id' => $new_chat_room->id, 'user_id' => $newuser]
                ];
                Member::insert($members);
            }
            $chat_room_id = ($request->chatRoom != null) ? $request->chatRoom : $new_chat_room->id;
            $message = new Message();
            $message->chat_room_id = $chat_room_id;
            $message->user_id = $logged_user_id;
            if ($request->isMessage == 1) {
                $message->message = $request->sendChat;
            } else {
                if ($request->hasFile('attachment')) {
                    $file = $request->file('attachment');
                    $path = 'attachment';
                    $final_file_url = ImageHelper::customSaveImage($file, $path);
                    $message->attachment = $final_file_url;
                    $message->attachment_type = $file->getClientOriginalExtension();
                }
            }
            $message->save();

            $sendMessage = Message::with('user')->where('id', $message->id)->first();
            $members = Member::where('chat_room_id', $request->chatRoom)->where('user_id', '!=', Auth::id())->pluck('user_id');
            $users = User::whereIn('id', $members)->get();

            foreach ($users as $user) {
                /** send and receive message event */
                if ($env == 'local' || $env == 'production') {
                    broadcast(new SendMessageEvent($message->chat_room_id, $user, $sendMessage))->toOthers();

                    /** Send Notification */
                    broadcast(new MessageSend($user))->toOthers();

                    /** Send Notification */
                    broadcast(new SendNotificationEvent($user->id))->toOthers();
                }
                /** Send Notification */
                $receiver = User::find($user->id);
                $sender = Auth::user();
                $notifyDetails["type"] = 'Message Receive';
                $notifyDetails["title"] = ($sender->role_name === 'SAFARI_OPERATOR' ? '@ ' : '') . $sender->first_name . ' Sent you a message';
                $notifyDetails["body"] = $request->sendChat
                    ? $sender->full_name . ' sent you a message: "' . Str::limit($request->sendChat, 50, '...') . '"'
                    : $sender->full_name . ' sent you a file';
                $notifyDetails["chat_room_id"] = $message->chat_room_id;
                $notifyDetails["sender"] = Auth::id();
                $notify_users = $receiver;
                Notification::send($notify_users, new SendNotification($notifyDetails));
            }

            $newChat = ChatRoom::with(['chatMembers' => function ($query) {
                $query->where('user_id', '!=', Auth::id())->limit(1);
            }, 'chatMembers.user'])
                ->find($chat_room_id);

            $chatRoom = ChatRoom::with(['chatMembers' => function ($query) {
                $query->where('user_id', '!=', Auth::id());
            }, 'chatMembers.user'])->where('id', $chat_room_id)->first();

            return response()->json(['code' => 200, 'message' => $sendMessage, 'newChat' => $newChat, 'chatRoom' => $chatRoom]);
        } catch (\Throwable $th) {
            Log::error(" :: EXCEPTION :: " . $th->getMessage() . "\n" . $th->getTraceAsString());
            abort(500);
        }
    }

    public function allUsers(Request $request)
    {
        try {
            $loggedInUserId = Auth::id();
            $members = Member::where('user_id', $loggedInUserId)->get();
            $users = User::where('id', '!=', $loggedInUserId)
                ->whereHas('roles', function ($q) {
                    $q->whereIn('name', ['SUPER-ADMIN', 'YOGA-CHAPLAINS']);
                })
                ->when($request->search, function ($query) use ($request) {
                    $searchTerm = '%' . trim($request->search) . '%';
                    $query->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", [$searchTerm]);
                })
                ->get();

            foreach ($users as $user) {
                foreach ($members as $member) {
                    $otherMember = Member::with('chatRoom')
                        ->whereHas('chatRoom', function ($q) {
                            $q->where('is_group', 0);
                        })
                        ->where('chat_room_id', $member->chat_room_id)->where('user_id', '!=', $loggedInUserId)->first();

                    if (isset($otherMember->user_id) && $otherMember->user_id == $user->id) {
                        $user->chat_room_id = $member->chat_room_id;
                        $user->isGroup = $otherMember->chatRoom->is_group;
                    }
                }
            }
            return response()->json(['success' => true, 'users' => $users], 200);
        } catch (\Throwable $th) {
            Log::error('Error:: while getting users ' . $th->getMessage());
            return response()->json(['success' => false, 'users' => [], 'error' => 'Server error occurred'], 500);
        }
    }

    public function createGroup(Request $request)
    {
        $request->validate([
            'group_users_id' => 'required|array',
            'group_name' => 'required',
        ]);

        try {
            $group_ids = $request->group_users_id;
            array_push($group_ids, Auth::id());

            $new_chat_room = new ChatRoom;
            $new_chat_room->chat_name = $request->group_name;
            $new_chat_room->is_group = 1;
            $new_chat_room->save();

            foreach ($group_ids as $user_id) {
                $new_member = new Member;
                $new_member->chat_room_id = $new_chat_room->id;
                $new_member->user_id = $user_id;
                $new_member->save();
            }

            // ======= update group id with grp prefix ===========
            ChatRoom::where('id', $new_chat_room->id)->update(['group_id' => 'grp-' . $new_chat_room->id]);

            $newGroup = ChatRoom::with(['chatMembers' => function ($query) {
                $query->where('user_id', '!=', Auth::id())->limit(1);
            }, 'chatMembers.user'])
                ->find($new_chat_room->id);


            return response()->json(['success' => true, 'status_code' => 200, 'message' => 'Chat Group created successfully', 'data' => $newGroup]);
        } catch (\Throwable $th) {
            Log::error('Error:: while creating chat groups ' . $th->getMessage());
            return response()->json(['success' => false, 'error' => 'Something went wrong in server side'], 500);
        }
    }

    public function getGroupMemberList($chat_room_id, Request $request)
    {
        try {
            $members = Member::with('user')->where('chat_room_id', $chat_room_id)
                ->when($request->search, function ($query) use ($request) {
                    $searchTerm = '%' . trim($request->search) . '%';
                    $query->whereHas('user', function ($q) use ($searchTerm) {
                        $q->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", [$searchTerm]);
                    });
                })
                ->get();

            $chatroomMembers = Member::select('chat_room_id', 'user_id')
                ->whereHas('chatRoom', function ($q) {
                    $q->where('is_group', 0)
                        ->whereHas('chatMembers', function ($q) {
                            $q->where('user_id', Auth::id());
                        });
                })
                ->where('user_id', '!=', Auth::id())
                ->get();

            foreach ($members as $member) {
                foreach ($chatroomMembers as $chatroomMember) {
                    if ($member->user_id == $chatroomMember->user_id) {
                        $member->user->chat_room_id = $chatroomMember->chat_room_id;
                    }
                }
            }

            return response()->json(['success' => true, 'status_code' => 200, 'chat_members' => $members]);
        } catch (\Throwable $th) {
            Log::error('Error:: while creating chat groups ' . $th->getMessage());
            return response()->json(['success' => false, 'error' => 'Something went wrong in server side'], 500);
        }
    }

    public function deleteMessage($id)
    {
        try {
            Message::where('id', $id)
                ->where('user_id', Auth::id())
                ->delete();

            return response()->json(['success' => true, 'status_code' => 200, 'message' => 'Message deleted successfully']);
        } catch (\Throwable $th) {
            Log::error('Error:: while deleting message ' . $th->getMessage());
            return response()->json(['success' => false, 'error' => 'Something went wrong in server side'], 500);
        }
    }

    public function getOnlineUsers($id)
    {
        return User::select('id', 'first_name', 'last_seen_at')->findOrFail($id);
    }

    public function submitLastSeen(Request $request)
    {
        $user = User::find($request->user_id);
        if ($user) {
            $user->update(['last_seen_at' => now()->setTimezone('Asia/Kolkata')]);
        }
        return response()->json(['status' => 'updated']);
    }

    public function getChatForDashboard(Request $request)
    {
        $userId = Auth::id();
        $chat_list = ChatRoom::with(['chatMembers' => function ($query) {
            $query->where('user_id', '!=', Auth::id())->limit(1);
        }, 'chatMembers.user'])
            ->whereHas('chatMembers', function ($q) {
                $q->where('user_id', Auth::id());
            })
            ->orderBy(function ($query) {
                $query->select('created_at')
                    ->from('messages')
                    ->whereColumn('messages.chat_room_id', 'chat_rooms.id')
                    ->orderByDesc('created_at')
                    ->limit(1);
            }, 'desc')
            ->when($request->searchChatUser, function ($query) use ($request) {
                $searchTerm = '%' . trim($request->searchChatUser) . '%';
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('chat_name', 'like', $searchTerm)
                        ->orWhereHas('chatMembers.user', function ($qq) use ($searchTerm) {
                            $qq->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", [$searchTerm])->where('id', '!=', Auth::id());
                        });
                });
            })
            ->get()->filter(function ($chatRoom) use ($userId) {
                // Fetch deletion timestamp for this user & chat room
                $deletedAt = ChatDeletion::where('chat_room_id', $chatRoom->id)
                    ->where('user_id', $userId)
                    ->value('deleted_at');

                if ($deletedAt && $chatRoom->last_message && $chatRoom->last_message->created_at <= $deletedAt) {
                    // Hide chat if no new messages after deletion
                    return false;
                }
                return true;
            })
            ->values();


        if ($chat_list->isEmpty()) {
            return response()->json(['chat_list' => [], 'message' => 'No chat found', 'code' => 404]);
        }

        return response()->json(['chat_list' => $chat_list, 'message' => 'Chat list retrieved successfully']);
    }
}
