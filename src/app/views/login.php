<div class="breadcrumb">
    <div class="container">
        <a href="/">Home</a> / <span>Login</span>
    </div>
</div>

<section class="auth-section">
    <div class="container">
        <div class="auth-box">
            <h1>Login</h1>

            <?php if (isset($error) && $error): ?>
                <div class="alert alert-error"><?= htmlspecialchars(
                    $error,
                ) ?></div>
            <?php endif; ?>

            <form method="POST" action="/login">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <button type="submit" class="btn btn-full">Login</button>
            </form>

            <p class="auth-links">
                <a href="/forgot-password">Forgot Password?</a>
                <span>|</span>
                <a href="/register">Create Account</a>
            </p>

            <div class="oauth-section">
                <p class="oauth-divider">Or login with</p>
                <div class="oauth-buttons">
                    <a href="#" class="btn-oauth btn-google" onclick="alert('Google OAuth: Requires API configuration'); return false;">
                        Login with Google
                    </a>
                    <a href="#" class="btn-oauth btn-facebook" onclick="alert('Facebook OAuth: Requires API configuration'); return false;">
                        Login with Facebook
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
