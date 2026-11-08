<main class="auth">
    <article>
        <h1>Welcome back.</h1>
        <form action="<?= url('/login') ?>" method="post">
            <?= csrf_field() ?>
            <label class="field"><span>Email</span><input class="input input-lg" type="email" name="email"
                    value="<?= old('email') ?>" required></label>
            <label class="field"><span>Password</span><input class="input input-lg" type="password" name="password"
                    required></label>
            <button class="btn btn-primary btn-lg btn-block" type="submit">Login</button>
        </form>
        <p>No account yet? <a href="<?= url('/register') ?>"><strong>Create account</strong></a></p>
    </article>
</main>