<div class="breadcrumb">
    <div class="container">
        <a href="/">Home</a> / <span>Register</span>
    </div>
</div>

<section class="auth-section">
    <div class="container">
        <div class="auth-box">
            <h1>Create Account</h1>

            <?php if (isset($error) && $error): ?>
                <div class="alert alert-error"><?= htmlspecialchars(
                    $error,
                ) ?></div>
            <?php endif; ?>

            <?php if (isset($success) && $success): ?>
                <div class="alert alert-success"><?= htmlspecialchars(
                    $success,
                ) ?></div>
            <?php endif; ?>

            <form method="POST" action="/register">
                <div class="form-group">
                    <label for="full_name">Full Name</label>
                    <input type="text" id="full_name" name="full_name" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="password">Password (8 chars: uppercase, lowercase, number, special character)</label>
                    <input type="password" id="password" name="password" required minlength="6">
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required minlength="6">
                </div>

                <button type="submit" class="btn btn-full">Register</button>
            </form>

            <p class="auth-links">
                Already have an account? <a href="/login">Login here</a>
            </p>
        </div>
    </div>
</section>
