<?php
/**
 * Controlador de Nómina/RRHH
 */
class PayrollController extends BaseController {
    
    public function index() {
        $this->requireAuth();
        
        $user = $this->getCurrentUser();
        $flash = $this->getFlash();
        
        $this->loadView('payroll/index', [
            'user' => $user,
            'flash' => $flash
        ]);
    }
}
?>