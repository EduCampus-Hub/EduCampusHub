<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accedi | EduCampus Hub</title>
    <link rel="stylesheet" href="/public/css/style.css">
</head>
<body>
    <main class="auth-page">
        <section class="auth-card">
            <a class="brand" href="/index.php" aria-label="EduCampus Hub">
                <span class="brand-mark">E</span>
                <span>EduCampus <strong>Hub</strong></span>
            </a>

            <div class="auth-heading">
                <p class="eyebrow">Area riservata</p>
                <h1>Bentornato</h1>
                <p>Accedi al tuo account per continuare.</p>
            </div>

            <form class="auth-form" method="post" action="">
                <div class="field">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" placeholder="nome@scuola.it" autocomplete="email" required>
                </div>

                <div class="field">
                    <div class="label-row">
                        <label for="password">Password</label>
                        <a href="#">Password dimenticata?</a>
                    </div>
                    <input id="password" name="password" type="password" placeholder="Inserisci la password" autocomplete="current-password" required>
                </div>

                <label class="check-row">
                    <input type="checkbox" name="remember">
                    <span>Ricordami</span>
                </label>

                <button class="primary-button" type="submit">Accedi</button>
            </form>

            <p class="auth-switch">
                Non hai ancora un account?
                <a href="register.php">Registrati</a>
            </p>
        </section>
    </main>
</body>
</html>
