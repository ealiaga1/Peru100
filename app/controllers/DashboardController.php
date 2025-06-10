<?php
// app/controllers/DashboardController.php


    // Puedes agregar otros métodos para el dashboard aquí, protegiéndolos si es necesario
    public function reportesDiarios() {
        // Este método podría requerir un rol específico, por ejemplo, 'Super Admin' o 'Cajero'
        $this->requireAuth(['Super Admin', 'Cajero']);
        $data['title'] = 'Reportes Diarios';
        $data['message'] = 'Contenido de los reportes diarios.';
        $this->view('dashboard/reportes_diarios', $data);
    }
}
