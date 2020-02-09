<?php


namespace App\Util;

use App\User;
use Unirest\Request;

class AAL
{
    const TYPE_ADD = 1;
    const TYPE_REMOVE = 2;
    const TYPE_RESET = 3;

    /**
     * Code    Description
     * 200 OK
     * 400 BAD REQUEST [User limit reached (paid plan only)]
     * 403 FORBIDDEN [Insufficient Permissions]
     * 404 NOT FOUND [User not registered]
     * 409 CONFLICT [User already has app]
     *
     * @param User $user
     * @return int
     */
    public static function addUser(User $user)
    {
        return self::sendRequest($user, self::TYPE_ADD);
    }

    /**
     * Code    Description
     * 200 OK
     * 403 FORBIDDEN [Insufficient Permissions]
     * 404 NOT FOUND [User doesn't have app]
     *
     * @param User $user
     * @return int
     */
    public static function removeUser(User $user)
    {
        return self::sendRequest($user, self::TYPE_REMOVE);
    }

    /**
     * Code    Description
     * 200 OK
     * 403 FORBIDDEN [Insufficient Permissions]
     * 404 NOT FOUND [User doesn't have app]
     *
     * @param User $user
     * @return int
     */
    public static function resetUser(User $user)
    {
        return self::sendRequest($user, self::TYPE_REMOVE);
    }

    /**
     * @param User $user
     * @param int $type what type of request to make
     * @return int the status code
     */
    private static function sendRequest(User $user, int $type): int
    {
        Request::verifyPeer(false);

        $endpoint = config('aal.endpoint');
        $app_id = config('aal.app_id');
        $name = $user->aal_name;
        $api_key = config('aal.api_key');
        $header = [
            'Accept' => 'application/json',
            'Authorization' => config('aal.session'),
            "Content-Type" => "application/x-www-form-urlencoded"
        ];

        $response = null;
        switch ($type) {
            case self::TYPE_ADD:
            {
                $response = Request::post("$endpoint/apps/$app_id/users/$name?_identity=$api_key", $header, '{}');
                break;
            }
            case self::TYPE_REMOVE:
            {
                $response = Request::delete("$endpoint/apps/$app_id/users/$name?_identity=$api_key", $header, '{}');
                break;
            }
            case self::TYPE_RESET:
            {
                $response = Request::put("$endpoint/apps/$app_id/users/$name?_identity=$api_key", $header, '{}');
                break;
            }
        }
        return $response->code;
    }
}
