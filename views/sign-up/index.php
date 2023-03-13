   <main>
       <div class="container">
           <h1>Become a member</h1>
           <p>In just a moment, you will become the latest member of Cinema!</p>
           <form action="" method="post" id="register-form">
               <div class="form-item">
                <!-- pattern="[a-zA-Z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}" -->
                   <input id="email" type="text" name="email" placeholder="Email address" title="Email address that will be used to log in">
               </div>

               <div class="form-item">
                   <input type="text" name="username" placeholder="Username" title="Username that will be public to other users">
               </div>

               <div class="form-item">
                   <input type="password" name="password" placeholder="New Password" title="Enter new password">
               </div>

               <div class="form-item">
                   <input type="password" name="_password" placeholder="Confirm Password" title="Confirm password">
               </div>

               <div id="error-message"></div>

               <div class="form-button">
                   <button type="submit" form="register-form" value="signup" name="submit">Create</button>
               </div>
           </form>
           <a href="sign-in">
               <button id="abort">Go Back</button>
           </a>
       </div>
   </main>