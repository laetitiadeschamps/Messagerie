<main>
            <div class="form-container form-container--create ">
    <form class="form" action="" method="post">
            <header>
                <h2 class="form__title"> Créer un compte</h2>
            </header>
            <section class="form__body">
            
                    <fieldset>
                        <input type="mail" name="mail" id="mail" placeholder="Votre mail" value ="<?= isset($_SESSION['object']) ? $_SESSION['object']->getMail() : ''?>">
                        <small></small>
                    </fieldset>
                    <fieldset>
                        <input type="text" name="login" id="login" placeholder="Votre nom d'utilisateur" value ="<?= isset($_SESSION['object']) ? $_SESSION['object']->getLogin() : ''?>">
                        <small></small>
                    </fieldset>
                    <fieldset>
                        <input type="text" name="firstname" id="firstname" placeholder="Votre prénom" value ="<?= isset($_SESSION['object']) ? $_SESSION['object']->getFirstname() : ''?>">
                        <small></small>
                    </fieldset>
                    <fieldset>
                        <input type="text" name="lastname" id="lastname" placeholder="Votre nom" value ="<?= isset($_SESSION['object']) ? $_SESSION['object']->getLastname() : ''?>">
                        <small></small>
                    </fieldset>
                    <fieldset>
                        <input type="date" name="birthdate" id="birthdate" placeholder="Votre date de naissance" value ="<?= isset($_SESSION['object']) ? $_SESSION['object']->getBirthdate() : ''?>">
                        <small></small>
                    </fieldset>
                    <fieldset>
                        <input type="password" name="password" id="password" placeholder="Votre mot de passe"><i class="fas passwordDisplay fa-eye"></i><i class=" passwordDisplay fas fa-eye-slash hidden"></i>
                        <small></small>
                    </fieldset>
                    <fieldset>
                        <input type="password" name="passwordRepeat" id="passwordRepeat" placeholder="Retappez votre mot de passe"><i class="fas passwordDisplay fa-eye"></i><i class=" passwordDisplay fas fa-eye-slash hidden"></i>
                        <small></small>
                    </fieldset>
                    <small class="form__error"></small>
                    <button class="btn submit">S'inscrire</button>
                    <small>Vous avez déjà un compte ?<a href="<?= $router->generate('account-login')?>"> Connectez-vous !</a></small>
            
            </section>
           
           
    </form>
    </div>
</main>