<div class="breadcrumb">
    <div class="container">
        <a href="/">Home</a> / <span>Forgot Password</span>
    </div>
</div>

<section class="auth-section">
    <div class="container">
        <div class="auth-box">
            <h1>Forgot Password</h1>
            <p style="color: var(--text-secondary); margin-bottom: 2rem; font-size: 0.875rem;">
                Enter your email and we'll send you a password reset link.
            </p>

            <?php if (isset($error) && $error): ?>
                <div class="alert alert-error"><?= htmlspecialchars(
                    $error,
                ) ?></div>
            <?php endif; ?>

            <?php if (isset($success) && $success): ?>
                <div class="alert alert-success">
                    Password reset link generated successfully!<br>
                    <a href="<?= htmlspecialchars($resetLink ?? "") ?>"
                       style="color: #6ee7b7; text-decoration: underline; word-break: break-all;">
                        Click here to reset your password
                    </a>
                </div>
            <?php endif; ?>

            <form method="POST" action="/forgot-password">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <button type="submit" class="btn btn-full">Send Reset Link</button>
            </form>

            <p class="auth-links">
                <a href="/login">Back to Login</a>
            </p>
        </div>
    </div>
</section>
