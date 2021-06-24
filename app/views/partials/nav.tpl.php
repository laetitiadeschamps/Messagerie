<nav>
        <div class="hamburger-icon">
            <span></span>
            <span></span>
            <span></span>
            
        </div>
        <ul>
            <li class="logo"><a href="<?= $router->generate('main-home')?>"><img src="<?= $baseUri?>/assets/images/logo.png" alt=""></a></li>
            <div id="hamburger-nav">
                <ul>
                        <?php if(isset($_SESSION['id'])) : ?>
                        <input type="hidden" id="userId" value="<?= $_SESSION['id'] ?>">
                           
                            <li><a href="<?= $router->generate('contact-list')?>"> Contacts</a></li>
                            <li><a href="<?= $router->generate('chat-list')?>"> Mes conversations</a></li>
                            <li class="dropdown"> 
                                <button class="dropbtn">Profil
                                <i class="fa fa-caret-down"></i>
                                </button>
                                <div class="dropdown-content">
                                    <a href="<?= $router->generate('account-detail')?>">Mon compte</a>
                                    <a href="<?= $router->generate('account-logout')?>">Se déconnecter</a>
                                </div>
                            </li>
                        
                        <?php endif; ?>
                </ul>
            </div>
        </ul>
    
    </nav>