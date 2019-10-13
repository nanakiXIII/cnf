<?php

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

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
Broadcast::channel('fichier.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
Broadcast::channel('activity.1', function ($user, $id) {
    return (int) $user->equipe === (int) 1;
});