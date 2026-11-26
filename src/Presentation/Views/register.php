<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrati | EduCampus Hub</title>
    <link rel="stylesheet" href="../../../public/css/style.css">
</head>
<body>
    <main class="auth-page">
        <section class="auth-card auth-card-wide">
            <a class="brand" href="login.php" aria-label="EduCampus Hub">
                <span class="brand-mark">E</span>
                <span>EduCampus <strong>Hub</strong></span>
            </a>

            <div class="auth-heading">
                <p class="eyebrow">Nuovo account</p>
                <h1>Crea il tuo profilo</h1>
                <p>Compila i dati per accedere alla piattaforma.</p>
            </div>

            <form class="auth-form" method="post" action="">
                <div class="field">
                    <label for="name">Nome completo</label>
                    <input id="name" name="name" type="text" placeholder="Mario Rossi" autocomplete="name" required>
                </div>

                <div class="form-grid">
                    <div class="field">
                        <label for="email">Email</label>
                        <input id="email" name="email" type="email" placeholder="nome@scuola.it" autocomplete="email" required>
                    </div>

                    <div class="field">
                        <label for="role">Ruolo</label>
                        <select id="role" name="role" required>
                            <option value="" selected disabled>Seleziona un ruolo</option>
                            <option value="STUDENTE">Studente</option>
                            <option value="DOCENTE">Docente</option>
                            <option value="ATA">ATA</option>
                            <option value="ADMIN">Amministratore</option>
                        </select>
                    </div>
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <input id="password" name="password" type="password" placeholder="Almeno 8 caratteri" autocomplete="new-password" minlength="8" required>
                </div>

                <label class="check-row">
                    <input type="checkbox" name="terms" required>
                    <span>Accetto i termini di servizio e la privacy policy</span>
                </label>

                <button class="primary-button" type="submit">Crea account</button>
            </form>

            <p class="auth-switch">
                Hai già un account?
                <a href="login.php">Accedi</a>
            </p>
        </section>
    </main>
</body>
</html>
