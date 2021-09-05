<?php

return [
    /*
     * The default route and controller will be registered using this route name.
     * This is a good place to hook in your own route and controller if necessary.
     */
    'form_action_route' => 'supportBubble.submit',

    /*
     * Enable or disable fields in the support bubble.
     * Keep in mind that `name` and `email` will be hidden automatically
     * when a logged in user is detected and `prefill_logged_in_user` is set.
     */
    'fields' => [
        'name' => false,
        'email' => false,
        'subject' => true,
        'message' => true,
    ],

    /*
     * When set to true we'll use currently logged in user to fill in
     * the name and email fields. Both fields will also be hidden.
     */
    'prefill_logged_in_user' => true,

    /*
     * We'll send any chat bubble responses to this e-mail address.
     *
     * Set this to
     */
    'mail_to' => 'contact@envyclient.com',

    /*
     * The TailwindCSS classes used on a couple of key components.
     *
     * To customize the components further, you can publish
     * the views of this package.
     */
    'classes' => [
        'bubble' => 'hidden sm:block | bg-gray-600 hover:bg-gray-700 active:bg-gray-700 rounded-full shadow-lg w-14 h-14 text-white p-4',
        'input' => 'border bg-gray-100 border-gray-200 w-full max-w-full p-2 rounded-md shadow-sm text-gray-800 text-base focus:ring-gray-900 focus:border-gray-900',
        'button' => 'inline-flex place-center px-4 py-3 h-10 border-0 bg-gray-600 hover:bg-gray-700 active:bg-gray-700 overflow-hidden rounded-sm text-white leading-none no-underline rounded-md shadow-sm',
    ],
];
