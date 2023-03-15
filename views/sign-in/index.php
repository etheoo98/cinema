 <main>
        <div class="container">
            <h1>Login to existing account</h1>
            <p>Enter the credentials used during sign up.</p>
            <form action="#" method="post" id="register-form">
                <div class="form-item">
                    <input type="text" name="email" placeholder="Email address" title="Email address that will be used to log in" pattern="[a-zA-Z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}" required   >
                </div>

                <div class="form-item">
                    <input type="password" name="password" placeholder="Password" title="Enter new password" required>
                </div>

                <div id="error-message"></div>

                <div class="form-button">
                    <button type="submit" form="register-form" value="submit" name="submit">Log in</button>
                </div>
            </form>
            <h1>Not a member yet?</h1>
            <a href="sign-up">
                <button id="abort">Sign Up</button>
            </a>
        </div>
    </main>