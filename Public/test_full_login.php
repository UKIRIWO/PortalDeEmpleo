<?php
require_once __DIR__ . "/../vendor/autoload.php";
include_once __DIR__ . "/../Loaders/miAutoLoader.php";

Session::abrirsesion();

echo "<h1>Test Completo de Login</h1>";

// Test 1: Verificar que RepoUser existe y funciona
echo "<h3>Test 1: Buscar usuario 'adrian'</h3>";
try {
    $user = RepoUser::findByUsername('adrian');
    if ($user) {
        echo "<p style='color: green;'>✓ Usuario encontrado</p>";
        echo "ID: " . $user->getId() . "<br>";
        echo "Username: " . $user->getNombreUsuario() . "<br>";
        echo "Password Hash: " . $user->getPassword() . "<br>";
        echo "Rol ID: " . $user->getIdRolFk() . "<br>";
    } else {
        echo "<p style='color: red;'>✗ Usuario NO encontrado</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>ERROR: " . $e->getMessage() . "</p>";
}

// Test 2: Verificar password_verify
echo "<h3>Test 2: Verificar contraseña</h3>";
$passwordIngresada = "root"; // Cambia esto por tu contraseña real
$hashBD = '$2y$10$2y6zYQw8cRhlLrVjOAhMqOD20ZgJWzNQjWWxvVfwR0XbLoQv5Aj6a';

echo "Contraseña ingresada: $passwordIngresada<br>";
echo "Hash en BD: $hashBD<br>";

if (password_verify($passwordIngresada, $hashBD)) {
    echo "<p style='color: green;'>✓ Contraseña correcta</p>";
} else {
    echo "<p style='color: red;'>✗ Contraseña incorrecta</p>";
    
    // Generar nuevo hash con "root"
    $nuevoHash = password_hash("root", PASSWORD_DEFAULT);
    echo "<p>Nuevo hash para 'root': <code>$nuevoHash</code></p>";
    echo "<p>Ejecuta en MySQL:</p>";
    echo "<code>UPDATE user SET password = '$nuevoHash' WHERE nombre_usuario = 'adrian';</code>";
}

// Test 3: Verificar findByIdWithRole
echo "<h3>Test 3: Buscar usuario con rol</h3>";
try {
    $userWithRole = RepoUser::findByIdWithRole(1);
    if ($userWithRole) {
        echo "<p style='color: green;'>✓ Usuario con rol encontrado</p>";
        echo "<pre>";
        print_r($userWithRole);
        echo "</pre>";
    } else {
        echo "<p style='color: red;'>✗ Usuario con rol NO encontrado</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>ERROR: " . $e->getMessage() . "</p>";
}

// Test 4: Test completo de login
echo "<h3>Test 4: Login completo</h3>";
if (Login::login('adrian', 'root')) {
    echo "<p style='color: green;'>✓ Login exitoso</p>";
    echo "<pre>";
    print_r($_SESSION);
    echo "</pre>";
} else {
    echo "<p style='color: red;'>✗ Login falló</p>";
}
?>