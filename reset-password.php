<!doctype html><html lang="es" data-theme="light"><head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=5,user-scalable=yes">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="theme-color" content="#008B8B">
<title>Restablecer Contraseña — Med Tools Hub</title>
<link rel="stylesheet" href="style.css">
<script>
  document.documentElement.classList.add('preload');
  window.addEventListener('DOMContentLoaded', function(){
    document.body.classList.add('loaded');
    document.documentElement.classList.remove('preload');
  });
</script>
</head><body>
  <div class="auth-wrapper">
    <div class="auth-box glass" id="requestBox">
      <h2 class="auth-title">Restablecer Contraseña</h2>
      <form id="resetRequestForm" class="stack" autocomplete="off">
        <div class="field"><label for="resetUser">Usuario</label><input id="resetUser" type="text" required></div>
        <div class="field"><label for="resetEmail">Email</label><input id="resetEmail" type="email" required></div>
        <div class="auth-actions">
          <button class="btn grow" type="submit">Solicitar Código</button>
          <a class="btn secondary" href="index.php">Volver a Inicio</a>
        </div>
      </form>
    </div>
    <div class="auth-box glass" id="resetBox" style="display:none;">
      <h2 class="auth-title">Nueva Contraseña</h2>
      <div class="alert-box" id="tokenDisplay" style="display:none;margin-bottom:16px;padding:12px;background:rgba(var(--primary-rgb),.1);border-radius:8px;border:1px solid var(--primary);word-break:break-all;"></div>
      <form id="resetPasswordForm" class="stack" autocomplete="off">
        <div class="field"><label for="resetToken">Código de Recuperación</label><input id="resetToken" type="text" required></div>
        <div class="field"><label for="newPassword">Nueva Contraseña</label><input id="newPassword" type="password" required></div>
        <div class="field"><label for="confirmPassword">Confirmar Contraseña</label><input id="confirmPassword" type="password" required></div>
        <div class="auth-actions">
          <button class="btn grow" type="submit">Restablecer</button>
          <a class="btn secondary" href="index.php">Volver a Inicio</a>
        </div>
      </form>
    </div>
    <footer class="app-footer">
      <h4>Aviso Legal</h4>
      <p>Las herramientas de Med Tools Hub son de apoyo clínico y no sustituyen el criterio profesional. El uso y aplicación de los resultados son responsabilidad exclusiva de cada profesional de la salud.</p>
      <p class="trademark">NBR® 2025 | Med Tools Hub</p>
      <p class="contact">administrador@medtoolshub.com</p>
    </footer>
  </div>
  <script src="script.js"></script>
  <script>
    // Verificar si hay un token en la URL
    (function() {
      const urlParams = new URLSearchParams(window.location.search);
      const token = urlParams.get('token');
      
      if (token) {
        // Ocultar formulario de solicitud y mostrar formulario de reset
        document.getElementById('requestBox').style.display = 'none';
        document.getElementById('resetBox').style.display = 'block';
        
        // Llenar el campo del token
        document.getElementById('resetToken').value = token;
        
        // Verificar que el token es válido
        api('/verify-reset-token', {
          method: 'POST',
          body: JSON.stringify({ token: token })
        }).then(data => {
          if (!data.valid) {
            showToast('El enlace de recuperación es inválido o ha expirado. Por favor, solicita uno nuevo.', 'error');
            setTimeout(() => {
              window.location.href = 'reset-password.php';
            }, 3000);
          }
        }).catch(err => {
          console.error('Error verificando token:', err);
        });
      }
    })();
  </script>
</body></html>
