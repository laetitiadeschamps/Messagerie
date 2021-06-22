<main>
    
             <div class="form-container form-container--login">
    <form class="form" action="" method="post">
            <header>
                <h2 class="form__title"> Se connecter</h2>
            </header>
            <section class="form__body">
                    <fieldset>
                        <input type="text" name="login" id="login" placeholder="Votre mail">
                    </fieldset>
                    <fieldset>
                        <input type="password" name="password" id="password" placeholder="Votre mot de passe"><i class="fas passwordDisplay fa-eye"></i><i class=" passwordDisplay fas fa-eye-slash hidden"></i>
                    </fieldset>
                    <small class="form__error"></small>
                    <button class="btn submit">Se connecter</button>
                    <small>Pas de compte ?<a href="<?= $router->generate('account-create')?>"> Inscrivez-vous !</a></small>
            </section>
           
           
    </form>
    </div>
</main>