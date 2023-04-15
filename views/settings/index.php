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
                <form class="account-details" id="update-email-form" method="POST" data-action="update-email">
                    <label for="new-email">Enter New Email</label>
                    <input id="new-email" name="new-email" placeholder="<?php echo $_SESSION['email'] ?>" title="Email address that will be used to log in" pattern="[a-zA-Z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}" required>
                    <div class="form-button">
                        <button type="submit" class="button" name="change-email">Change Email</button>
                    </div>
                </form>
            </div>
            <div class="section">
                <h2 class="section-header">Change Password</h2>
                <form class="account-details" data-action="update-password">
                    <label for="current-password">Enter Old Password:</label>
                    <input id="current-password" name="current-password" type="password">
                    <label for="new-password">Enter New Password:</label>
                    <input id="new-password" name="new-password" type="password">
                    <div class="input-container">
                        <label for="retype-new-password">Retype New Password:</label>
                        <input id="retype-new-password" name="retype-new-password" type="password">
                    </div>
                    <div class="form-button">
                        <button class="button">Change Password</button>
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
<script src="./public/js/settings.js"></script>