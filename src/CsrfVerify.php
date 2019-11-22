<?php


class CsrfVerify
{
    /**
     *
     * This method checks the submit's form's token for Cross-site Request Forgery. If it is safe token it
     * means authorised access. Return value will be true otherwise false.
     *
     * @param string $session_token
     * @param string $form_token
     * @param string $form_name
     * @return bool
     */
    public static function csrfCheck(string $session_token, string $form_token, string $form_name): bool
    {
        //This statement is CSRF security control spot.
        if (isset($session_token, $form_token)) {
            $calc = hash_hmac('sha256', $form_name, $session_token);


            if (hash_equals($calc, $form_token)) {

                return true;

            } else {

                return false;
            }
        } else {
            return false;
        }
    }

    public static function csrfFailedMessage($message = '')
    {
        echo json_encode(['success' => false, 'message' => $message]);
        die('Invalid CSRF token. Unauthorised access!');
        http_response_code(403); // 403: FORBIDDEN http code.
    }

    public static function csrfSuccessMessage($message = '')
    {
        echo json_encode(['success' => true, 'message' => $message]);
        http_response_code(200);
    }

}