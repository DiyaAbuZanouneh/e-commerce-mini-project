<?php

class ProfileController
{
    public function show(): void
    {
        $user = require_login();

        render('profile', [
            'title'   => 'My profile — KABEL',
            'css'     => 'profile',
            'active'  => 'profile',
            'profile' => (new User())->find($user['id']),
            'orders'  => (new Order())->forUser($user['id']),
        ]);
    }
}