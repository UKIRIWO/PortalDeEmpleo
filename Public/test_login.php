<?php
echo "<h1>Test de Login</h1>";

echo "<h3>Datos POST:</h3>";
echo "<pre>";
print_r($_POST);
echo "</pre>";

echo "<h3>Datos SERVER:</h3>";
echo "REQUEST_METHOD: " . $_SERVER['REQUEST_METHOD'] . "<br>";
echo "SCRIPT_NAME: " . $_SERVER['SCRIPT_NAME'] . "<br>";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<p style='color: green;'>✓ Es POST</p>";
    
    if (isset($_POST['accion'])) {
        echo "<p style='color: green;'>✓ accion existe: " . $_POST['accion'] . "</p>";
    } else {
        echo "<p style='color: red;'>✗ accion NO existe</p>";
    }
    
    if (isset($_POST['username'])) {
        echo "<p style='color: green;'>✓ username existe: " . $_POST['username'] . "</p>";
    } else {
        echo "<p style='color: red;'>✗ username NO existe</p>";
    }
    
    if (isset($_POST['password'])) {
        echo "<p style='color: green;'>✓ password existe: " . $_POST['password'] . "</p>";
    } else {
        echo "<p style='color: red;'>✗ password NO existe</p>";
    }
} else {
    echo "<p style='color: orange;'>⚠ No es POST, es: " . $_SERVER['REQUEST_METHOD'] . "</p>";
}
?>

<hr>
<h3>Formulario de prueba:</h3>
<form action="test_login.php" method="POST">
    <label>Usuario:</label>
    <input type="text" name="username" value="adrian"><br><br>
    
    <label>Contraseña:</label>
    <input type="password" name="password" value="root"><br><br>
    
    <input type="submit" name="accion" value="Login">
</form>