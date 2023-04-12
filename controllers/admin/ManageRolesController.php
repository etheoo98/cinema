<?php
require_once(BASE_PATH . '/models/ManageRoles.php');
require_once(BASE_PATH . '/models/Session.php');
require_once (BASE_PATH . '/public/scripts/ManageRolesControllerMiddleware.php');

class ManageRolesController
{
    private mysqli $conn;
    private ManageRoles $manageRolesModel;
    private false|mysqli_result $currentAdmins;

    public function __construct($conn)
    {
        $this->conn = $conn;
        $this->manageRolesModel = new ManageRoles($this->conn);
    }
    
    public function index(): void
    {
        $this->currentAdmins = $this->manageRolesModel->getCurrentAdmins();
        $this->renderIndexView();
    }
    public function renderIndexView(): void
    {

        $title = "Add Title";
        $css = ["admin/main.css", "admin/manage-roles.css"];

        require_once(BASE_PATH . '/views/admin/header.php');
        require_once(BASE_PATH . '/views/admin/manage-roles/index.php');
        echo '<script src="/cinema/public/js/manage-roles.js"></script>';
        echo '<script src="/cinema/public/js/admin.js"></script>';
        require_once(BASE_PATH . '/views/shared/small-footer.php');
    }

    public function ajaxHandler(): void
    {
        $action = isset($_POST['action']) ? mysqli_real_escape_string($this->conn, $_POST['action']) : null;
        switch ($action) {
            case 'promote-user':
                $response = $this->promoteUser();
                break;
            case 'demote-user':
                $response = $this->demoteUser();
                break;
            default:
                $response = [
                    'status' => 'error',
                    'message' => 'Invalid action'
                ];
                break;
        }
        echo json_encode($response);
    }
    public function promoteUser(): array
    {
        {
            try {
                $sanitizedInput = $this->manageRolesModel->sanitizeInput();
                $sanitizedInput = $this->manageRolesModel->usernameLookup($sanitizedInput);
                $this->manageRolesModel->promoteUserToAdmin($sanitizedInput);
                $response = [
                    'status' => 'Success',
                    'message' => 'User successfully promoted to Admin',
                    'user_id' => $sanitizedInput['user_id'],
                    'username' => $sanitizedInput['promote_username']
                ];
            } catch (Exception $e) {
                $response = [
                    'status' => 'Failed',
                    'message' => $e->getMessage()
                ];
            }
            return $response;
        }
    }

    public function demoteUser(): array
    {
        {
            try {
                $sanitizedInput = $this->manageRolesModel->sanitizeInput();
                $sanitizedInput = $this->manageRolesModel->usernameLookup($sanitizedInput);
                $this->manageRolesModel->demoteAdminToUser($sanitizedInput);
                $response = [
                    'status' => 'Success',
                    'message' => 'Admin successfully demoted to user',
                    'username' => $sanitizedInput['demote_username']
                ];
            } catch (Exception $e) {
                $response = [
                    'status' => 'Failed',
                    'message' => $e->getMessage()
                ];
            }
            return $response;
        }
    }
}