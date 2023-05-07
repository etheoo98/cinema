<div class="page-info">
    <h1 class="page-title">Manage Roles</h1>
    <p class="page-desc">Here you can manage user roles</p>
</div>
<div>
    <div class="section">
        <h2 class="section-header">Promote To Admin</h2>
        <form class="admin-form" id="promote-form" data-action="promote-user">
            <div class="input-label">
                <label for="promote-username">User To Promote</label>
                <span class="tooltip">
                <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><!--! Font Awesome Pro 6.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M64 160c0-35.3 28.7-64 64-64h32c35.3 0 64 28.7 64 64v3.6c0 21.8-11.1 42.1-29.4 53.8l-42.2 27.1c-25.2 16.2-40.4 44.1-40.4 74V320c0 17.7 14.3 32 32 32s32-14.3 32-32v-1.4c0-8.2 4.2-15.8 11-20.2l42.2-27.1c36.6-23.6 58.8-64.1 58.8-107.7V160c0-70.7-57.3-128-128-128H128C57.3 32 0 89.3 0 160c0 17.7 14.3 32 32 32s32-14.3 32-32zm80 320a40 40 0 1 0 0-80 40 40 0 1 0 0 80z"/></svg>
                <span class="tooltip-text">Enter username you want to promote</span>
            </span>
            </div>
            <div class="input-row">
                <input id="promote-username" type="text" name="promote_username">
            </div>
            <div class="form-button">
                <button class="btn">Promote</button>
            </div>
        </form>

        <div class="alert-box">
            <div class="svg-container">
                <svg class="alert-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"/></svg>
            </div>
            <span>Alert Message</span>
        </div>
    </div>

    <div class="section">
        <h2 class="section-header">Demote To User</h2>
        <form class="admin-form" id="demote-form" data-action="demote-user">
            <div class="input-label">
                <label for="demote-username">User To Demote</label>
                <span class="tooltip">
                    <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><!--! Font Awesome Pro 6.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M64 160c0-35.3 28.7-64 64-64h32c35.3 0 64 28.7 64 64v3.6c0 21.8-11.1 42.1-29.4 53.8l-42.2 27.1c-25.2 16.2-40.4 44.1-40.4 74V320c0 17.7 14.3 32 32 32s32-14.3 32-32v-1.4c0-8.2 4.2-15.8 11-20.2l42.2-27.1c36.6-23.6 58.8-64.1 58.8-107.7V160c0-70.7-57.3-128-128-128H128C57.3 32 0 89.3 0 160c0 17.7 14.3 32 32 32s32-14.3 32-32zm80 320a40 40 0 1 0 0-80 40 40 0 1 0 0 80z"/></svg>
                    <span class="tooltip-text">Enter username you want to demote</span>
                </span>
            </div>
            <div class="input-row">
                <input id="demote-username" type="text" name="demote_username">
            </div>
            <div class="form-button">
                <button class="btn">Demote</button>
            </div>
        </form>

        <div class="alert-box">
            <div class="svg-container">
                <svg class="alert-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"/></svg>
            </div>
            <span>Alert Message</span>
        </div>
    </div>
</div>
<div class="section">
    <h2 class="section-header">Current Admins</h2>
    <?php
    $admins = '';
    while ($row = mysqli_fetch_array($this->currentAdmins)) {
        $admins .= '<a style="color: var(--secondary-red)" href="/cinema/users/' . $row['user_id'] . '">' . $row['username'] . '</a>, ';
    }
    $admins = rtrim($admins, ', '); // remove the last comma
    echo $admins;
    ?>
</div>
