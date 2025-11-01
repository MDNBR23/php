<?php
require_once 'php/config.php';
if (!isAuthenticated()) {
    header('Location: index.php');
    exit;
}
?>
<!doctype html><html lang="es" data-theme="light"><head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=5,user-scalable=yes">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="theme-color" content="#008B8B">
<title>Configuración — Med Tools Hub</title>
<link rel="stylesheet" href="style.css">
<script>
  document.documentElement.classList.add('preload');
  window.addEventListener('DOMContentLoaded', function(){
    document.body.classList.add('loaded');
    document.documentElement.classList.remove('preload');
  });
</script>
</head><body>
  <div class="layout">
    <aside class="sidebar">
      <div class="logo"><span class="logo-full">Med Tools Hub</span><span class="logo-short">MTH</span></div>
      <nav>
        <a href="main.php"><span class="icon">🏠</span><span>Principal</span></a>
        <a href="vademecum.php"><span class="icon">💊</span><span>Vademecum</span></a>
        <a href="herramientas.php"><span class="icon">🔧</span><span>Herramientas</span></a>
        <a href="sugerencias.php"><span class="icon">💬</span><span>Sugerencias</span><span id="sugerenciasBadge" class="sidebar-notification-badge" style="display:none;">0</span></a>
        <a href="configuracion.php" class="active"><span class="icon">⚙️</span><span>Configuración</span></a>
        <a href="admin.php" id="adminNavLink" style="display:none;"><span class="icon">🛠️</span><span>Administración</span><span id="adminBadge" class="sidebar-notification-badge" style="display:none;">0</span></a>
      </nav>
    </aside>
    <div class="content">
      <header class="header">
        <button id="btnToggleSidebar" type="button"></button>
        <h2>Configuración</h2>
        <div id="colombiaClock" style="font-size:13px;color:var(--muted);margin:0 auto 0 12px;white-space:nowrap;"></div>
        <div class="user-info">
          <img id="avatarTop" alt="">
          <span id="mainUserInfo" class="text-muted"></span>
          <button class="btn danger sm" type="button" onclick="logout()">Salir</button>
        </div>
      </header>
      <main>
        <section class="card glass">
          <h3>Perfil</h3>
          <form id="cfgForm">
            <div class="grid-2col" style="grid-template-columns:280px 1fr;">
              <div style="display:flex;flex-direction:column;align-items:center;gap:12px;">
                <div class="field" style="width:100%;"><label>Imagen de Perfil</label><img id="avatarTopPreview" src="" alt="" class="profile-preview" style="margin:0 auto;display:block;"></div>
                <div style="display:flex;gap:6px;width:100%;">
                  <button class="btn grow" type="button" style="font-size:12px;padding:8px 12px;" onclick="document.getElementById('cfgAvatarFile').click()">Añadir imagen</button>
                  <button class="btn secondary grow" type="button" style="font-size:12px;padding:8px 12px;" id="cfgAvatarClear">Quitar imagen</button>
                  <input id="cfgAvatarFile" type="file" accept="image/*" style="display:none;">
                </div>
              </div>
              <div>
                <div class="field"><label>Usuario</label><input id="cfgUser" type="text" disabled></div>
                <div class="field"><label>Nombre mostrado</label><input id="cfgName" type="text"></div>
                <div class="field"><label>Categoría</label>
                  <select id="cfgCat"><option value="">(sin especificar)</option><option>Estudiante</option><option>Interno</option><option>Médico General</option><option>Residente</option><option>Pediatra</option></select>
                </div>
                <div class="field"><label>Correo</label><input id="cfgMail" type="email"></div>
                <div class="field"><label>Teléfono</label><input id="cfgPhone" type="text"></div>
                <div class="field"><label>Institución</label><input id="cfgInst" type="text"></div>
              </div>
            </div>
            <div class="auth-actions" style="margin-top:14px">
              <button class="btn grow" type="submit">Guardar cambios</button>
              <label class="switch" title="Modo oscuro" style="margin-left:auto">
                <input class="theme-toggle" type="checkbox"><span class="slider"></span><span class="label">Oscuro</span>
              </label>
            </div>
          </form>
        </section>

        <section class="card glass">
          <h3>Cambiar contraseña</h3>
          <form id="cfgPasswordForm">
            <div class="grid" style="grid-template-columns:repeat(auto-fit,minmax(280px,1fr));">
              <div class="field"><label>Contraseña actual</label><input id="cfgCurrentPass" type="password" required></div>
              <div class="field"><label>Nueva contraseña</label><input id="cfgNewPass" type="password" required></div>
              <div class="field"><label>Confirmar contraseña</label><input id="cfgConfirmPass" type="password" required></div>
            </div>
            <div class="auth-actions" style="margin-top:14px">
              <button class="btn grow" type="submit">Actualizar contraseña</button>
            </div>
          </form>
        </section>
        <footer class="app-footer">
          <h4>Aviso Legal</h4>
          <p>Las herramientas de Med Tools Hub son de apoyo clínico y no sustituyen el criterio profesional.</p>
          <p>El uso y aplicación de los resultados son responsabilidad exclusiva de cada profesional de la salud.</p>
          <p class="trademark">NBR® 2025 | Med Tools Hub</p>
          <p class="contact">administrador@medtoolshub.com</p>
        </footer>
      </main>
    </div>
  </div>

  <div class="modal-backdrop" id="modalCrop">
    <div class="modal glass" style="max-width:700px;">
      <header><h3 style="margin:0;">Recortar imagen</h3><button class="btn secondary sm" type="button" id="closeCrop">✕</button></header>
      <div style="position:relative;width:600px;height:400px;overflow:hidden;margin:0 auto;background:#000;border-radius:12px;">
        <canvas id="cropCanvas" style="position:absolute;top:0;left:0;cursor:move;"></canvas>
      </div>
      <div style="margin-top:14px;">
        <label style="display:block;margin-bottom:8px;font-size:14px;">Zoom: <input type="range" id="zoomSlider" min="50" max="300" value="50" style="width:200px;"></label>
      </div>
      <div class="auth-actions" style="margin-top:14px;">
        <button class="btn grow" type="button" id="applyCrop">Aplicar recorte</button>
        <button class="btn secondary" type="button" id="cancelCrop">Cancelar</button>
      </div>
    </div>
  </div>

  <script src="script.js"></script>
</body></html>
