<main class="auth">
    <article>
        <h1>Create account</h1>
        <form action="<?= url('/register') ?>" method="post">
            <?= csrf_field() ?>
            <label class="field"><span>Full name</span><input class="input input-lg" type="text" name="name"
                    maxlength="100" value="<?= old('name') ?>" required></label>
            <label class="field"><span>Email</span><input class="input input-lg" type="email" name="email"
                    maxlength="150" value="<?= old('email') ?>" required></label>
            <label class="field"><span>Password (min. 6 characters)</span><input class="input input-lg" type="password"
                    name="password" minlength="6" required></label>
            <label class="field"><span>Confirm password</span><input class="input input-lg" type="password"
                    name="confirm_password" required></label>
            <button class="btn btn-primary btn-lg btn-block" type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="<?= url('/login') ?>"><strong>Login</strong></a></p>
    </article>
</main>