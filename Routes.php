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
                CsrfVerify::csrfSuccessMessage();
            }
        } else {
            CsrfVerify::csrfFailedMessage();
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
                CsrfVerify::csrfSuccessMessage();
            }
        } else {
            CsrfVerify::csrfFailedMessage();
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

                CsrfVerify::csrfSuccessMessage();
            }
        } else {
            CsrfVerify::csrfFailedMessage();
        }
        break;

    /* --------------------------- */


    default:
        require_once('view/WelcomePage.php');
        break;
}


