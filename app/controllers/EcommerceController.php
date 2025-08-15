<?php
/**
 * Controlador de E-Commerce
 */
class EcommerceController extends BaseController {
    
    public function index() {
        $this->requireAuth();
        
        $user = $this->getCurrentUser();
        $flash = $this->getFlash();
        
        $this->loadView('ecommerce/index', [
            'user' => $user,
            'flash' => $flash
        ]);
    }
}
?>