<main>
    <div class="container">
        <div class="page-header">
            <h1 class="page-title">Settings</h1>
        </div>
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
                <form class="account-details">
                    <label>Enter New Email</label>
                    <input placeholder="<Current Email Address PHP>"></input>
                    <div class="form-button">
                        <button>Change Email</button>
                    </div>
                </form>
            </div>
            <div class="section">
                <h2 class="section-header">Change Password</h2>
                <form class="account-details">
                    <label>Enter Old Password:</label>
                    <input type="password"></input>
                    <label>Enter New Password:</label>
                    <input type="password"></input>
                    <div class="input-container">
                        <label>Retype New Password:</label>
                        <input type="password"></input>
                    </div>
                    <div class="form-button">
                        <button>Change Password</button>
                    </div>
                </form>
            </div>
        </div>
        <button type="button" class="collapsible">Sessions</button>
        <div class="settings">
            <div class="section">
                <form class="sessions" method="POST">
                    <ul>
                        <li class="list-header list-row">
                            <div class="list-item">
                                <input type="checkbox" onClick="toggle(this)"></input>
                            </div>
                            <div class="list-item">Signed In</div>
                            <div class="list-item">Country</div>
                            <div class="list-item">User Agent</div>
                        </li>
                        <?php
                        foreach ($this->sessions as $session) {
                            ?>
                            <li class="list-row">
                            <div class="list-item">
                                <input type="checkbox" name="checkboxes[]" value="<?php echo $session['phpsessid'] ?>"></input>
                            </div>
                            <div class="list-item"><?php echo $session['date'] ?></div>
                            <div class="list-item"><?php echo $session['country_code'] ?></div>
                            <div class="list-item"><?php echo $session['user_agent'] ?></div>
                            <?php if ($session['phpsessid'] == session_id()) { echo "&#8592 This is you"; } ?>
                            </li>
                        <?php } ?>
                    </ul>
                    <p>Beware that terminating the current session will log you out.</p>
                    <div class="form-button">
                        <button type="submit" name="terminate">Terminate Session(s)</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
<script src="/cinema/public/js/collapsible.js"></script>
<script src="/cinema/public/js/checkall.js"></script>
<script src="/cinema/public/js/redirect.js"></script>