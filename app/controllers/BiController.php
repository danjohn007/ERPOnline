<?php
/**
 * Controlador de Business Intelligence
 */
class BiController extends BaseController {
    
    public function index() {
        $this->requireAuth();
        
        $user = $this->getCurrentUser();
        $flash = $this->getFlash();
        
        $this->loadView('bi/index', [
            'user' => $user,
            'flash' => $flash
        ]);
    }
}
?>