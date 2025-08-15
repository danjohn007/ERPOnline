<?php
/**
 * Controlador de Contabilidad
 */
class AccountingController extends BaseController {
    
    public function index() {
        $this->requireAuth();
        
        $user = $this->getCurrentUser();
        $flash = $this->getFlash();
        
        $this->loadView('accounting/index', [
            'user' => $user,
            'flash' => $flash
        ]);
    }
}
?>