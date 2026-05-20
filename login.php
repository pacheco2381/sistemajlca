<?php
session_start();
require 'db.php';
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = trim($_POST['username']);
  $password = $_POST['password'];

  $sql = "SELECT * FROM usersjlca WHERE username = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    if (password_verify($password, $user['password'])) {
      // Iniciar sesión
      $_SESSION['username'] = $username;
      // 🌟 ¡NUEVO! Almacenar el nivel de usuario en la sesión
      $_SESSION['user_level'] = $user['user_level'];

      header("Location: dash.php");
      exit;
    } else {
      $message = "❌ Contraseña incorrecta.";
    }
  } else {
    $message = "⚠️ Usuario no encontrado.";
  }

  $stmt->close();
  $conn->close();
}
?>


<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SIA JLCA</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body,
    html {
      height: 100%;
      margin: 0;
      position: relative;
    }

    .container-fluid {
      height: 100vh;
      display: flex;
    }

    .left-panel {
      flex: 0.15;
      background: linear-gradient(135deg, #4a001f, #6a0f49);
    }

    .right-panel {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
      background-color: #f5f5f5;
    }

    .login-card {
      background: rgba(255, 255, 255, 0.95);
      border-radius: 35px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.85);
      padding: 3rem 2rem 2rem;
      width: 100%;
      max-width: 400px;
      position: relative;
      z-index: 1;
      text-align: center;
    }

    .login-logo {
      width: 350px;
      height: auto;
      position: absolute;
      top: -200px;
      left: 50%;
      transform: translateX(-50%);
      z-index: 2;
    }

    .login-card h3 {
      margin-top: 0;
      margin-bottom: 1.5rem;
      color: #4a001f;
    }

    .login-card .btn-primary {
      transition: all 0.3s ease;
    }

    .login-card .btn-primary:hover {
      background-color: #6a0f49;
      border-color: #6a0f49;
      transform: scale(1.05);
    }

    .footer-legend {
      position: fixed;
      bottom: 10px;
      left: 55%;
      transform: translateX(-50%);
      font-size: 0.9rem;
      color: #4a001f;
      opacity: 0.7;
      white-space: nowrap;
      animation: floatText 6s ease-in-out infinite alternate;
      z-index: 10;
    }

    @keyframes floatText {
      0% {
        transform: translateX(-50%) translateY(0);
      }

      50% {
        transform: translateX(-50%) translateY(-5px);
      }

      100% {
        transform: translateX(-50%) translateY(0);
      }
    }

    @media (max-width: 768px) {
      .left-panel {
        display: none;
      }

      .footer-legend {
        left: 50%;
        font-size: 0.8rem;
      }

      .login-logo {
        width: 200px;
        top: -120px;
      }
    }
  </style>
</head>

<body>
  <div class="container-fluid">
    <div class="left-panel"></div>

    <div class="right-panel">
      <div class="login-card">
        <img src="assets/logo.png" alt="Logo" class="login-logo">
        <h3>Iniciar Sesión</h3>

        <form method="POST" action="">
          <div class="mb-3">
            <label for="username" class="form-label">usuario:</label>
            <input type="text" class="form-control" id="username" name="username" placeholder="Usuario" required>
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Contraseña:</label><br>
            <input type="password" class="form-control" id="password" name="password" placeholder="********" required>
          </div>
          <!--<input type="submit" value="Registrar"><br><br>-->
          <button type="submit" value="Entrar" class="btn btn-primary w-100">Ingresar</button><br><br>
          <!-- <a href="register.php">¿No tienes cuenta? Regístrate</a>-->
        </form>

        <p style="color:red;"><?php echo $message; ?></p>
      </div>
    </div>
  </div>

  <div class="footer-legend">
    Sistema Institucional de Archivos de la Junta Local de Conciliación y Arbitraje
  </div>
</body>

</html>