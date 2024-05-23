

<?php
require_once 'models/AuthModel.php';
require_once 'models/UserModel.php';

class Auth_sin_inyeccion
{
    private $db;
    private $authModel;
    private $userModel;
    private $maxAttemptsPerUser = 5;  // Máximo número de intentos permitidos por usuario
    private $maxTotalAttempts = 100;   // Máximo número total de intentos permitidos
    private $timeFrame = '15 MINUTE';  // Intervalo de tiempo para contar los intentos

    public function __construct(PDO $db)
    {
        $this->db = $db;
        $this->authModel = new AuthModel($db);
        $this->userModel = new UserModel($db);
    }

    public function canAttemptLogin($username, $ipAddress) {
        $userAttempts = $this->authModel->countRecentLoginAttempts($username, $ipAddress, $this->timeFrame);
        $totalAttempts = $this->authModel->countRecentTotalLoginAttempts($this->timeFrame);

        return $userAttempts < $this->maxAttemptsPerUser && $totalAttempts < $this->maxTotalAttempts;
    }


    public function login($username, $password, $ipAddress)
    {
        if (!$this->canAttemptLogin($username, $ipAddress)) {
            return 'Demasiados intentos fallidos. Intenta nuevamente más tarde.';
        }

        // LOGICA DE AUTENTICACION

        if ($this->authenticate($username, $password)) {
            $this->authModel->clearLoginAttempts($username, $ipAddress);  // Restablecer intentos en caso de éxito
            return 'Inicio de sesión exitoso';
        } else {
            $this->authModel->recordLoginAttempt($username, $ipAddress);
            return 'Nombre de usuario o contraseña incorrectos.';
        }
    }

    private function authenticate($username, $password)
    {
        $user = $this->userModel->getUser($username, $password);
        if ($user) {
            return $user;
        } else {
            return false;
        }
    }
    
}


?>