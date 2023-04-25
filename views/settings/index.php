<main>
    <div class="page-info">
        <h1 class="page-title">Settings</h1>
    </div>
    <div class="container">

        <button type="button" class="collapsible">Profile</button>
        <div class="settings">
            <div class="section">
                <h2 class="section-header">Bio</h2>
                <h2 class="section-header">Hide Country</h2>
            </div>
        </div>

        <button type="button" class="collapsible">Site Preferences</button>
        <div class="settings">
            <div class="section">
                <h2 class="section-header">Language</h2>
                <h2 class="section-header">Theme</h2>
                <h2 class="section-header">Age Certification</h2>
                <h2 class="section-header">Blacklist genres</h2>
            </div>
        </div>

        <button type="button" class="collapsible">Email, username and password</button>
        <div class="settings">
            <div class="section">
                <h2 class="section-header">Change Email</h2>
                <form class="account-details" id="update-email-form" method="POST" data-action="change-email">
                    <label for="new-email">Enter New Email</label>
                    <input id="new-email" name="new-email" placeholder="<?php echo $_SESSION['email'] ?>" title="Email address that will be used to log in" pattern="[a-zA-Z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}" required>

                    <div class="error-message">
                        <div class="svg-container">
                            <svg class="error-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M256 32c14.2 0 27.3 7.5 34.5 19.8l216 368c7.3 12.4 7.3 27.7 .2 40.1S486.3 480 472 480H40c-14.3 0-27.6-7.7-34.7-20.1s-7-27.8 .2-40.1l216-368C228.7 39.5 241.8 32 256 32zm0 128c-13.3 0-24 10.7-24 24V296c0 13.3 10.7 24 24 24s24-10.7 24-24V184c0-13.3-10.7-24-24-24zm32 224a32 32 0 1 0 -64 0 32 32 0 1 0 64 0z"/></svg>
                        </div>
                        <span></span>
                    </div>

                    <div class="form-button">
                        <button type="submit" class="button" name="change-email">Change Email</button>
                    </div>
                </form>
            </div>
            <div class="section">
                <h2 class="section-header">Change Password</h2>
                <form class="account-details" id="change-password-form" data-action="change-password">
                    <label for="current-password">Enter Current Password:</label>
                    <input id="current-password" name="current-password" type="password">
                    <label for="new-password">Enter New Password:</label>
                    <input id="new-password" name="new-password" type="password">
                    <div class="input-container">
                        <label for="retype-new-password">Retype New Password:</label>
                        <input id="retype-new-password" name="retype-new-password" type="password">
                    </div>

                    <div class="error-message">
                        <div class="svg-container">
                            <svg class="error-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M256 32c14.2 0 27.3 7.5 34.5 19.8l216 368c7.3 12.4 7.3 27.7 .2 40.1S486.3 480 472 480H40c-14.3 0-27.6-7.7-34.7-20.1s-7-27.8 .2-40.1l216-368C228.7 39.5 241.8 32 256 32zm0 128c-13.3 0-24 10.7-24 24V296c0 13.3 10.7 24 24 24s24-10.7 24-24V184c0-13.3-10.7-24-24-24zm32 224a32 32 0 1 0 -64 0 32 32 0 1 0 64 0z"/></svg>
                        </div>
                        <span></span>
                    </div>

                    <div class="form-button">
                        <button class="button" type="submit" name="change-password">Change Password</button>
                    </div>
                </form>
            </div>
        </div>

        <button type="button" class="collapsible">Sessions</button>
        <div class="settings">
            <div class="section">
                <form class="sessions" id="terminate-session-form" method="POST" data-action="terminate-session">
                    <div class="grid-container">
                        <div class="grid-item"><input type="checkbox" onClick="toggle(this)"></div>
                        <div class="grid-item">Signed In</div>
                        <div class="grid-item">Country</div>
                        <div class="grid-item">User Agent</div>
                        <div class="grid-item"></div>
                        <?php
                        foreach ($this->sessions as $session) {
                        ?>
                            <div class="grid-item"><input type="checkbox" name="checkBoxes[]" value="<?php echo $session['phpsessid'] ?>"></div>
                            <div class="grid-item"><?php echo $session['date'] ?></div>
                            <div class="grid-item"><?php echo $session['country_code'] ?></div>
                            <div class="grid-item"><?php echo $session['user_agent'] ?></div>
                            <div class="grid-item"><?php if ($session['phpsessid'] == session_id()) { echo "&#8592 This is you"; } ?></div>
                        <?php } ?>
                    </div>
                    <p>Beware that terminating the current session will log you out.</p>
                    <div class="form-button">
                        <button class="button" type="submit" name="terminate">Terminate Session(s)</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>