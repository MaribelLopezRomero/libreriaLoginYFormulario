

<?php

use PHPUnit\Framework\TestCase;

require 'vendor/autoload.php';

use DI\ContainerBuilder;

class UserControllerTest extends TestCase
{
    protected $userController;
    protected static $initialized = false;

    // Método setUp() para configurar objetos necesarios antes de cada prueba
    protected function setUp(): void
    {
        parent::setUp();

        // Verificar si ya se inicializó el controlador de usuario
        if (!self::$initialized) {
            // Creamos una instancia de UserController con mi gestor de dependencias
            $containerBuilder = new ContainerBuilder();

            // Load container definitions
            $definitions = require_once 'configDependencias.php';
            $definitions($containerBuilder);

            // Build the container
            $container = $containerBuilder->build();

            // Obtener una instancia de UserController a través del contenedor
            $this->userController = $container->get(UserController::class);

            // Marcar como inicializado
            self::$initialized = true;
        }
    }


    // Método de prueba para el método login con credenciales incorrectas
    // public function testLoginFail()
    // {
    //     // PRUEBAS
    //     $username = 'testuser';
    //     $password = 'testpassword';
    //     $ipAddress = '127.0.0.1';

    //     // Ejecutamos el método login y obtenemos el resultado
    //     $result = $this->userController->login($username, $password, $ipAddress);

    //     // Verificamos si el resultado es el esperado
    //     $this->assertEquals("Nombre de usuario o contraseña incorrectos.", $result);
    // }


    // Método de prueba para el método login con credenciales correctas
    // public function testLogin()
    // {
    //     // PRUEBAS
    //     $username = 'maribel';
    //     $password = 'maribel';
    //     $ipAddress = '127.0.0.1';

    //     // Ejecutamos el método login y obtenemos el resultado
    //     $result = $this->userController->login($username, $password, $ipAddress);

    //     // Verificamos si el resultado es el esperado
    //     $this->assertEquals("Inicio de sesión exitoso", $result);
    // }


    //Metodo para testar el inicio de sesion superando el numero de intentos con mismo nombre usuario
    // public function testcheckAttempsbyUsername()
    // {
    //     // Simular 100 intentos de inicio de sesión fallidos
    //     for ($i = 0; $i < 12; $i++) {

    //         $username = $this->getUsernameRandom();
    //         $password = 'password'; // Usar una contraseña incorrecta para simular un intento fallido
    //         $ipAddress = '127.0.0.0';

    //         // Ejecutamos el método login y obtenemos el resultado
    //         $result = $this->userController->login($username, $password, $ipAddress);
    //     }

    //     // Simular el intento 11, debería bloquear el inicio de sesión
    //     // PRUEBAS
    //     $username = 'maribel';
    //     $password = 'password'; // Usar una contraseña incorrecta para simular un intento fallido
    //     $ipAddress = '127.0.0.0';

    //     // Ejecutamos el método login y obtenemos el resultado
    //     $result = $this->userController->login($username, $password, $ipAddress);

    //     // Verificar si el resultado es el esperado (esperamos un mensaje que indique que se alcanzó el límite de intentos)
    //     $this->assertEquals("Demasiados intentos fallidos. Su cuenta ha sido bloqueada.", $result);
    // }



    //Metodo para testar el inicio de sesion superando el numero de intentos con la misma IP 
    public function testcheckAttempsbySameIP()
    {
        // Simular 100 intentos de inicio de sesión fallidos
        for ($i = 0; $i < 102; $i++) {

            $username = $this->getUsernameRandom();
            $password = 'password'; // Usar una contraseña incorrecta para simular un intento fallido
            $ipAddress = '127.0.0.0';

            // Ejecutamos el método login y obtenemos el resultado
            $result = $this->userController->login($username, $password, $ipAddress);
        }

        // Simular el intento 102, debería bloquear el inicio de sesión
        // PRUEBAS
        $username = 'maribellllllllllll';
        $password = 'password'; // Usar una contraseña incorrecta para simular un intento fallido
        $ipAddress = '127.0.0.0';

        // Ejecutamos el método login y obtenemos el resultado
        $result = $this->userController->login($username, $password, $ipAddress);

        // Verificar si el resultado es el esperado (esperamos un mensaje que indique que se alcanzó el límite de intentos)
        $this->assertEquals("Demasiados intentos fallidos. Su cuenta ha sido bloqueada.", $result);
    }




    //Numero de intetno con IPs ditintas que son 100 y con nosmbre usuario distitno
    // public function testTotalLoginAttempts()
    // {
    //     // Simular 100 intentos de inicio de sesión fallidos
    //     for ($i = 0; $i < 100; $i++) {

    //         $username = $this->getUsernameRandom();
    //         $password = 'password';
    //         $ipAddress = $this->getIpRandom();

    //         // Ejecutamos el método login y obtenemos el resultado
    //         $result = $this->userController->login($username, $password, $ipAddress);
    //     }

    //     // Simular el intento 101, debería bloquear el inicio de sesión
    //     // PRUEBAS
    //     $username = 'maribel';
    //     $password = 'password'; // Usar una contraseña incorrecta para simular un intento fallido
    //     $ipAddress = $this->getIpRandom();

    //     // Ejecutamos el método login y obtenemos el resultado
    //     $result = $this->userController->login($username, $password, $ipAddress);

    //     // Verificar si el resultado es el esperado (esperamos un mensaje que indique que se alcanzó el límite de intentos)
    //     $this->assertEquals("Demasiados intentos fallidos. Intenta nuevamente más tarde.", $result);
    // }

    function getIpRandom()
    {

        $part1 = rand(1, 255);
        $part2 = rand(0, 255);
        $part3 = rand(0, 255);
        $part4 = rand(1, 255);

        $ipAddress = $part1 . '.' . $part2 . '.' . $part3 . '.' . $part4;

        return $ipAddress;
    }
    function getUsernameRandom()
    {

      
        $caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()-_=+[{]}\|;:,<.>/?';
        $longitudCaracteres = strlen($caracteres);
        $cadenaGenerada = '';
    
        for ($i = 0; $i < 15; $i++) {
            $indice = rand(0, $longitudCaracteres - 1);
            $cadenaGenerada .= $caracteres[$indice];
        }
    
        return $cadenaGenerada;
    }
}


?>