<?php
session_start();
/*
 *       Application's all routes should be here.
 *
 */

$default_uri = '/';
if (isset($_GET['uri'])) {
    $default_uri = $_GET['uri'];
}

function csrfFailedMessage($message = '')
{
    echo json_encode(['success' => false, 'message' => $message]);
    die('Invalid CSRF token. Unauthorised access!');
    http_response_code(403); // 403: FORBIDDEN http code.
}

function csrfSuccessMessage($message = '')
{
    echo json_encode(['success' => true, 'message' => $message]);
    http_response_code(200);
}


switch ($default_uri) {

    case 'admin-panel':
        require_once('models/Properties.php');
        require_once('view/AdminPanel.php');
        break;

    /* --------------------------- */

    case 'propery-list-from-api':
        require_once('models/Properties.php');
        require_once('view/PropertyListPageFromAPI.php');
        break;

    /* --------------------------- */

    case 'add-property':   //Admin Controller
        require_once('controller/AdminController.php');

        if (CsrfVerify::csrfCheck($_SESSION['csrf_token'], $_POST['data']['token_'], 'AdminPanel.php')) {
            $adminController = new AdminController();
            if ($adminController->storeJsonData($_POST['data'])) {
                csrfSuccessMessage();
            }
        } else {
            csrfFailedMessage();
        }
        break;

    /* --------------------------- */


    case 'get-property':   //Admin Controller
        require_once('controller/AdminController.php');

        $adminController = new AdminController();
        $data            = $adminController->getData($_GET['id']);


        if (!empty($data)) {
            echo json_encode($data);
            http_response_code(200);// 200: OK http code
        } else {
            http_response_code(404); //400: not found http code
        }


        break;

    /* --------------------------- */


    case 'delete-property': //Admin Controller
        require_once('controller/AdminController.php');


        if (CsrfVerify::csrfCheck($_SESSION['csrf_token'], $_POST['token_'], 'AdminPanel.php')) {
            $adminController = new AdminController();

            if ($adminController->delete($_POST['id'])) {
                csrfSuccessMessage();
            }
        } else {
            csrfFailedMessage();
        }
        break;

    /* --------------------------- */


    /* --------------------------- */


    case 'update-property': //Admin Controller
        require_once('controller/AdminController.php');

        if (CsrfVerify::csrfCheck($_SESSION['csrf_token'], $_POST['token_'], 'AdminPanel.php')) {
            $adminController = new AdminController();


            if ($adminController->updateData([$_POST['county'],
                                              $_POST['country'],
                                              $_POST['num_bedrooms'],
                                              $_POST['type'],

            ], $_POST['id'])) {

                csrfSuccessMessage();
            }
        } else {
            csrfFailedMessage();
        }
        break;

    /* --------------------------- */


    default:
        require_once('view/WelcomePage.php');
        break;
}


