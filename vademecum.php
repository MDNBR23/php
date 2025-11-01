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
<title>Vademecum — Med Tools Hub</title>
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
        <a href="vademecum.php" class="active"><span class="icon">💊</span><span>Vademecum</span></a>
        <a href="herramientas.php"><span class="icon">🔧</span><span>Herramientas</span></a>
        <a href="sugerencias.php"><span class="icon">💬</span><span>Sugerencias</span><span id="sugerenciasBadge" class="sidebar-notification-badge" style="display:none;">0</span></a>
        <a href="configuracion.php"><span class="icon">⚙️</span><span>Configuración</span></a>
        <a href="admin.php" id="adminNavLink" style="display:none;"><span class="icon">🛠️</span><span>Administración</span><span id="adminBadge" class="sidebar-notification-badge" style="display:none;">0</span></a>
      </nav>
    </aside>
    <div class="content">
      <header class="header">
        <button id="btnToggleSidebar" type="button"></button>
        <h2>Vademecum</h2>
        <div id="colombiaClock" style="font-size:13px;color:var(--muted);margin:0 auto 0 12px;white-space:nowrap;"></div>
        <div class="user-info">
          <img id="avatarTop" alt="">
          <span id="mainUserInfo" class="text-muted"></span>
          <button class="btn danger sm" type="button" onclick="logout()">Salir</button>
        </div>
      </header>
      <main>
        <section class="card glass">
          <div style="display:flex;align-items:center;gap:12px;margin-bottom:10px;">
            <h3 style="margin:0;">Listado</h3>
            <input id="drugSearch" type="search" placeholder="Buscar fármaco…" style="margin-left:auto;width:260px;">
          </div>
          <div class="table-wrap">
            <table id="drugTable">
              <thead><tr><th>Fármaco</th><th>Grupo</th><th>Dilución sugerida</th><th>Comentarios</th></tr></thead>
              <tbody></tbody>
            </table>
          </div>
          <p class="text-muted mt12">Próximamente agregaremos cálculo y fórmulas automáticas.</p>
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
  <script src="script.js"></script>
</body></html>