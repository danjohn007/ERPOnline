<?php
/**
 * Controlador de Bancos
 */
class BanksController extends BaseController {
    
    public function index() {
        $this->requireAuth();
        
        $user = $this->getCurrentUser();
        $flash = $this->getFlash();
        
        $this->loadView('banks/index', [
            'user' => $user,
            'flash' => $flash
        ]);
    }
}
?>