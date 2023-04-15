 <main>
     <div class="container" id="page-container">
         <div class="form-box" id="sign-in">
             <div class="login-register">
                 <h2>Don't have an account?</h2>
                 <button onclick="toggleForms()" class="btn">Become a Member</button>
             </div>
             <form method="POST" id="sign-in-form" data-action="sign-in">
                 <h2>Sign in to existing account</h2>
                 <div class="input-box">
                     <span class="icon"><i class="fa-solid fa-envelope"></i></span>
                     <input type="text" name="email" required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$">
                     <label for="email">Email</label>
                 </div>
                 <div class="input-box">
                     <span class="icon"><i class="fa-solid fa-lock"></i></span>
                     <input type="password" required name="password">
                     <label for="password">Password</label>
                 </div>
                 <div class="error-message"><i class="fa-solid fa-triangle-exclamation"></i><span>Error Message</span></div>
                 <button type="submit" name="SignIn" class="btn">Sign In</button>
             </form>
             <h3 class="divider">OR</h3>
             <button type="submit" class="btn" id="abort">Abort</button>
         </div>

         <div class="form-box" id="sign-up">
             <form method="POST" id="sign-up-form" data-action="sign-up">
                 <h2>Become our latest member</h2>
                 <div class="input-box">
                     <span class="icon"><i class="fa-solid fa-envelope"></i></span>
                     <input id="email" type="text" name="email" required title="Your email address.">
                     <label for="email">Email</label>
                 </div>
                 <div class="input-box">
                     <span class="icon"><i class="fa-solid fa-user"></i></span>
                     <input id="username" type="text" name="username" required pattern="[a-zA-Z0-9]+" title="Desired username. Only uppercase, lowercase and numbers allowed.">
                     <label for="username">Username</label>
                 </div>
                 <div class="input-box">
                     <span class="icon"><i class="fa-solid fa-lock"></i></span>
                     <input id="password" type="password" name="password" required title="Please enter a unique password.">
                     <label for="password">Password</label>
                 </div>
                 <div class="input-box">
                     <input id="retype-password" type="password" name="_password" required title="Enter your password again. Make sure you remember it.">
                     <label for="retype-password">Retype Password</label>
                 </div>
                 <div class="error-message"><i class="fa-solid fa-triangle-exclamation"></i><span>Error Message</span></div>
                 <button type="submit" name="SignUp" class="btn">Sign Up</button>
             </form>
             <h3 class="divider">OR</h3>
             <button onclick="toggleForms()" class="btn" id="abort">Back To Sign In</button>
         </div>
     </div>
 </main>