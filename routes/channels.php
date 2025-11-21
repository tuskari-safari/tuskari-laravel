<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('test', function (User $user) {
    Log::Debug('test' . print_r($user, true));
    return true;
});

Broadcast::channel('user-chat.{id}', function ($user, $id) {
    return true;
});

Broadcast::channel('chat-channel', function ($user, $id) {
    return true;
});

Broadcast::channel('send-message.{id}', function ($user, $id) {
    return true;
});
Broadcast::channel('message-notification.{id}', function ($user, $id) {
    return true;
});

Broadcast::channel('online.users', function ($user,) {
    return ['id' => $user->id, 'name' => $user->full_name]; // must return an array for presence
});

Broadcast::channel('send-notification.{id}', function ($user, $id) {
    return true;
});
