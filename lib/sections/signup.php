<a href = 'javascript:void(0)' class = 'fat' type = 'toggler'>Sign Up</a>
    <?php
    $extreg = filter_input(INPUT_GET, "register", FILTER_SANITIZE_STRING);

    /* If linked to register externally */
    if ($extreg == "true") {
        echo "<section class = 'form-wrapper' data-toggled = 'true'>";
    } else {
        echo "<section class = 'form-wrapper' data-toggled>";
    }
    ?>
    <form id = 'register-form' method = 'POST' action = '//dashmash.ddns.net/lib/user/register.php' name = 'register-form' autocomplete='off'>
        <header>
            <h3>Register your Account</h3><a href='javascript:void(0)' class='exit'></a>
        </header>
        <fieldset>
            <legend>* - required</legend><span>Max length: 18</span>
            <label for = 'username'>Choose your Username!*</label><input type = 'text' name = 'username' minlength='0' maxlength = '18' required autocomplete='new-password'/>
            <label for = 'password'>Choose your Password!*</label><input type = 'password' name = 'pass' minlength='0' maxlength = '48' required autocomplete='new-password'/>
            <label for = 'password'>Confirm your Password!*</label><input type = 'password' name = 'pass_confirm' minlength='0' maxlength = '48' required autocomplete='off' autofill='off'><span>We won't spam you (:</span>
            <label for='email'>E-Mail*</label><input type = 'email' name = 'email' minlength='0' required autocomplete='email'/>
        </fieldset>
        <fieldset>
            <legend>Prove your Humanity * </legend>
            <div class='g-recaptcha' data-sitekey='6Lc09DoUAAAAAGqInbvmDZUiJKuKGoYbOs0aMEIb'></div>
        </fieldset>
        <fieldset>
            <legend>Agreement *</legend><input type='checkbox' required></input><p>I agree to the <a href='https://dashmash.ddns.net/?action=terms' target='_blank'>Terms & Conditions</a>.</p> 
        </fieldset>
        <fieldset>
            <legend>Finish Signing Up</legend><button type='submit'>Sign Up!</button>
        </fieldset>
    </section>
</form>