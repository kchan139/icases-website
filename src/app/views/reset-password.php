<div class="breadcrumb">
    <div class="container">
        <a href="/">Home</a> / <span>Reset Password</span>
    </div>
</div>

<section class="auth-section">
    <div class="container">
        <div class="auth-box">
            <h1>Reset Password</h1>

            <?php if (isset($error) && $error): ?>
                <div class="alert alert-error"><?= htmlspecialchars(
                    $error,
                ) ?></div>
            <?php endif; ?>

            <?php if (isset($success) && $success): ?>
                <div class="alert alert-success"><?= $success ?></div>
            <?php else: ?>
                <form method="POST" action="/reset-password?token=<?= htmlspecialchars(
                    $token ?? "",
                ) ?>">
                    <div class="form-group">
                        <label for="password">New Password (min 8 chars: uppercase, lowercase, number, special char)</label>
                        <input type="password" id="password" name="password" required minlength="6">
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" required minlength="6">
                    </div>

                    <button type="submit" class="btn btn-full">Reset Password</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</section>
