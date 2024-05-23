

<?php
require 'Database.php';
require 'Auth.php';
require 'models/UserModel.php';
require 'models/AuthModel.php';
require 'Controller/UserController.php';


use DI\ContainerBuilder;


return function (ContainerBuilder $builder) {
    $builder->addDefinitions([
        Database::class => function () {
            $dsn = 'mysql:host=localhost;dbname=pruebasautenticacion';
            $dbUser = 'root';
            $dbPassword = '';
            return Database::getInstance($dsn, $dbUser, $dbPassword);
        },
        
        PDO::class => \DI\factory([Database::class, 'getConnection']),
        UserModel::class => \DI\create(UserModel::class)
            ->constructor(\DI\get(PDO::class)),
        AuthModel::class => \DI\create(AuthModel::class)
            ->constructor(\DI\get(PDO::class)),
        Auth::class => \DI\create(Auth::class)
            ->constructor(
                \DI\get(UserModel::class),
                \DI\get(AuthModel::class)
            ),
        UserController::class => \DI\create(UserController::class)
            ->constructor(
                \DI\get(UserModel::class),
                \DI\get(Auth::class)
            ),
    ]);
};

?>