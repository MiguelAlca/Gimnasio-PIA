<?php
session_start();
echo json_encode([
  'loggedIn' => isset($_SESSION['IdRol']),
  'IdRol' => $_SESSION['IdRol'] ?? null,
  'Nombre' => $_SESSION['Nombre'] ?? null
]);
?>