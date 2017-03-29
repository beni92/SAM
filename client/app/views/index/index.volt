<div class="sign-container">
    <div class="sign-header">
        <img src="" alt="logo" class="sign-logo">
    </div>
    <div class="sign-form">
        {{ form("index/login", "method": "post") }}
        <div class="sign-form-group">
            <label for="username">Username</label>
            <div>
                <input type="text" name="username" placeholder="Your username here ...">
            </div>
            <label for="username" class="error hidden"></label>
        </div>
        <div class="sign-form-group">
            <label for="password">Password</label>
            <div>
                <input type="password" name="password" placeholder="Your password here ...">
            </div>
            <label for="password" class="error hidden"></label>
        </div>
        <input type="submit" class="btn" value="Sign-in">
        <input type="hidden" class="hidden" name="{{ security.getTokenKey() }}" value="{{ security.getToken() }}">
        {{ end_form() }}
    </div>
</div>