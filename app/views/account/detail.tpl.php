<main>
    <div class="form-container form-container--profile <?= $_SESSION['isEdit'] ?? ''?>">
        <form class="form" action="" method="POST" enctype="multipart/form-data">   
            <input type="hidden" name="token" value="<?= $token ?>">
            <header>
                <h5 class="form__title">Mon profil</h5>
                <div id="prevAvatar" class="avatarSelect hidden">
                    <i class="fas fa-chevron-left"></i>
                </div> 
                <div class="form__image">
                    <img src="<?= $baseUri . '/assets/images/' . $_SESSION['userObject']->getPicture()?>" class="form__image--picture" alt=""> 
                    <span class="close-container tooltip"><i class="fas fa-close form__image--reset"><span class="tooltip-inner">Supprimer la photo</span></span></i>
                    <label class="photoUpload tooltip" for="photoUpload"><i class="fas fa-folder-open"></i><span class="tooltip-inner">Charger une image</span></label>
                    <input type="file" name="photoUpload" id="photoUpload" class="hidden" />
                </div>
                <div id="nextAvatar"  class="avatarSelect hidden">
                    <i class="fas fa-chevron-right"></i>
                </div>
                <button class="editBtn tooltip">
                    <i class="fas fa-user-edit" id="edit-profile"></i><span class="tooltip-inner">Modifier mon profil</span>
                </button>
               
            </header>
            <section class="form__body">
                <input type="hidden" name="avatar" value="<?=$_SESSION['userObject']->getPicture() ?>" id="avatar">
                <fieldset>
                    <span class="label">Login </span>
                    <span data-name="login" class="login"> <?= isset($_SESSION['object'])? $_SESSION['object']->getLogin() : $user->getLogin()?></span>
                    <small></small>
                </fieldset>
                <fieldset>
                    <span class="label">Mail  </span>
                    <span data-name="mail" class="mail"> <?= isset($_SESSION['object'])? $_SESSION['object']->getMail() : $user->getMail()?></span>
                    <small></small>
                </fieldset>
       
                <fieldset>
                    <span class="label"> Prénom  </span>
                    <span data-name="fname" class="firstname"> <?= isset($_SESSION['object'])? $_SESSION['object']->getFirstname() : $user->getFirstname()?></span>
                    <input type="text" class="hidden" name="firstname" id="firstname" placeholder="Prénom" value="<?= isset($_SESSION['object'])? $_SESSION['object']->getFirstname() : $user->getFirstname()?>">
                    <small></small>
            
                </fieldset>
                <fieldset>
                    <span class="label">Nom  </span>
                    <span data-name="name" class="lastname">  <?= isset($_SESSION['object'])? $_SESSION['object']->getLastname() : $user->getLastname()?> </span>
                    <input type="text" class="hidden" name="lastname" id="lastname" placeholder="Nom" value="<?= isset($_SESSION['object'])? $_SESSION['object']->getLastname() : $user->getLastname()?>">
                    <small></small>
                </fieldset>

                <fieldset>
                    <span class="label">Date de naissance  </span>
                    <span data-name="birthdate" class="birthdate"> <?= isset($_SESSION['object'])? $_SESSION['object']->getBirthdate() : $user->getBirthdate()?></span>
                    <input type="date" class="hidden" name="birthdate" id="birthdate" placeholder="Date de naissance" value="<?= isset($_SESSION['object'])? $_SESSION['object']->getBirthdate() : $user->getBirthdate()?>">
                    <small></small>
                </fieldset>
                <fieldset>
                    <span class="label">Description  </span>
                    <span data-name="description" class="description"> <?= isset($_SESSION['object'])? $_SESSION['object']->getDescription() : $user->getDescription()?></span>
                    <textarea class="hidden" name="description" id="description" placeholder="Description"> <?= isset($_SESSION['object'])? $_SESSION['object']->getDescription() : $user->getDescription()?></textarea>
                    <small></small>
                </fieldset>
                <button class="btn submit">
                    <i class="fas fa-check"></i> Valider les modifications
                </button>
        </form>
    </section>
</div>
<?php unset($_SESSION['isEdit']) ?>

</main>