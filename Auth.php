

<?php
require_once 'Interfaces/UserModelInterface.php';
require_once 'Interfaces/AuthModelInterface.php';

class Auth
{

    private $authModel;
    private $userModel;
    private $maxAttemptsPerUser = 10;  // Máximo número de intentos permitidos por usuario
    private $maxTotalAttempts = 100;   // Máximo número total de intentos permitidos
    private $timeFrame = '5 MINUTE';  // Intervalo de tiempo para contar los intentos (es decir hace un count de cuantos intentos en los ultimos 15 min)
    private $timeFrameTotalSameIP = '5 MINUTE';  // Intervalo de tiempo para contar los intentos con la mism ip
    private $timeFrameTtotalIP = '5 MINUTE';  // Intervalo de tiempo para contar los intentos de disititnas ip

    public function __construct(UserModelInterface $userModel, AuthModelInterface $authModel)
    {
        $this->userModel = $userModel;
        $this->authModel = $authModel;
    }

    public function checkAttempsbyUsername($username, $ipAddress)
    {

        //1. Ver si la cuenta esta bloqueada o no.

        $userAccountsBlock = $this->userModel->getblockAccount($username);

        if($userAccountsBlock){
            return false;
        }

        //  2. Comprobar los intentos y bloquearla si los ha sobrepasado

        $block = false;
        $userAttempts = $this->authModel->countRecentLoginAttempts($username, $ipAddress, $this->timeFrame);

        //si los intentos son mayores a los permitidos bloqueamos la cuenta
        if ($userAttempts >= $this->maxAttemptsPerUser) {

            $block = $this->userModel->blockAccount($username);
            return false;
        }

        return true;
    }


    public function checkAttempsbySameIP($ipAddress)
    {

        // 1. Verificamos que la IP no este bloqueada
        $IPBlocked = $this->authModel->getblockIP($ipAddress);

        if($IPBlocked!=null){
            return false;
        }


        //2. Comprobamos intentos y bloqueamos IP si los ha superado
        $totalAttempts = $this->authModel->countRecentTotalLoginAttemptsBySameIP($this->timeFrameTotalSameIP, $ipAddress);

        if($totalAttempts >= $this->maxTotalAttempts){

            $blockIP = $this->authModel->blockIP($ipAddress);
            return false;

        }

        //3.Avisamos administrados.TODO: 


        //retornamos true si puede seguir adelante

        return true;
   


    }


    public function login($username, $password, $ipAddress)
    {
        if (!$this->checkAttempsbyUsername($username, $ipAddress)) {
            return 'Demasiados intentos fallidos. Su cuenta ha sido bloqueada.';
        }

        if (!$this->checkAttempsbySameIP($ipAddress)) {
            return 'Demasiados intentos fallidos. Su cuenta ha sido bloqueada.';
        }

        // if (!$this->checkAttempsbyDistinctIP($username, $ipAddress)) {
        //     return 'Demasiados intentos fallidos. Su cuenta ha sido bloqueada.';
        // }


        // LOGICA DE AUTENTICACION

        if ($this->authenticate($username, $password)) {
            $this->authModel->clearLoginAttempts($username, $ipAddress);  // Restablecer intentos en caso de éxito
            return 'Inicio de sesión exitoso';
            // return true;
        } else {
            $this->authModel->recordLoginAttempt($username, $ipAddress, $password);
            return 'Nombre de usuario o contraseña incorrectos.';
            //return false;
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