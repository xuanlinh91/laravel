<?php

return [
    /*
     * Public client id provided by Imgur
     */
    'client_id' => 'e79ce21dd98303e',

    /**
     * Client secret provided by Imgur
     */
    'client_secret' => 'e79ce21dd98303e',

    /**
     * The storage facility to be used to store a user's token.
     * Should be a name of a class implementing the
     *   Redeman\Imgur\TokenStorage\Storage
     * interface.
     */
    'token_storage' => 'Redeman\Imgur\TokenStorage\SessionStorage',
];
