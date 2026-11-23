<!-- Intestazione condizionale basata sullo stato di sessione -->;
;header class="main-header">;
<;div class="header-container">;
<;h1>;EduCampus Hub<;/h1>;
<;?php if (isset($user) &amp;&amp; $user !== null): ?>;
<;!-- Se l&#39;utente è loggato, mostra il nome, il ruolo e il pulsante Logout -->;
<;div class="user-info">;
<;span>;Benvenuto, <;strong>;<;?= htmlspecialchars($user->;name) ?>;<;/strong>; (<;?=
htmlspecialchars($user->;role->;value) ?>;)<;/span>;
<;a href="index.php?action=logout" class="btn btn-logout">;Logout<;/a>;
<;/div>;
<;?php endif; ?>;
<;/div>;
<;/header>;