<?php

namespace App\Controllers;

// Classe gérant les erreurs (404, 403)
class ErrorController extends CoreController {
    /**
     * Method handling display of 404 error
     *
     * @return void
     */
    public function err404() {
        // We send 404 header
        header('HTTP/1.0 404 Not Found');
        $this->show('error/err404');
    }
    /**
     * Method handling display of 403 error
     *
     * @return void
     */
    public function err403() {
    
        header('HTTP/1.1 403 Forbidden');
        $this->show('error/err403');
    }
}