<h1>Profile</h1>
<form action="/user" method="post">
    <div class="form-group">
    <label for="email">Email address</label>
    <input type="email" class="form-control"
        aria-describedby="email_help" placeholder="Enter email"
        id="email" name="email" required
        value="<?= htmlspecialchars($user->getData('email')) ?>">
    <small id="email_help" class="form-text text-muted">We'll never share your email with anyone else.</small>
    </div>
    <button type="submit" class="btn btn-primary">Save</button>
</form>
