

 <?php if(isset($_SESSION['errors'])): ?>
  
        <?php foreach($_SESSION['errors'] as $error): ?>
            <div class="alert alert-danger">
                <?= $error ?>
            </div>
        <?php endforeach; ?>
        <!-- On nettoie la session après affichage -->
        <?php unset($_SESSION['errors']) ?>
    
<?php endif; ?>
<?php if(isset($_SESSION['infos'])): ?>
  
        <?php foreach($_SESSION['infos'] as $error): ?>
            <div class="alert alert-success">
                <?= $error ?>
            </div>
        <?php endforeach; ?>
        <!-- On nettoie la session après affichage -->
        <?php unset($_SESSION['infos']) ?>
  
<?php endif; ?>
