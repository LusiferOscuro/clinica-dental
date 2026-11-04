<div class="container my-5">
    <div class="hero-banner mb-5">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <span class="badge bg-info text-dark fw-bold mb-3 px-3 py-2">ODONTOLOGÍA DE VANGUARDIA</span>
                <h1 class="display-4 fw-bold mb-3">Tu Sonrisa en Manos de Especialistas Realmente Capacitados.</h1>
                <p class="lead text-light mb-4 opacity-90">Atención integral personalizada, tecnología digital avanzada y facilidades de pago mediante código QR inmediato.</p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="index.php?c=cita&a=lista" class="btn btn-primary btn-lg rounded-3 px-4 shadow">
                        <i class="bi bi-calendar-check me-2"></i>Agendar Cita Medica
                    </a>
                    <a href="index.php?c=pago" class="btn btn-qr-custom btn-lg rounded-3 px-4 shadow">
                        <i class="bi bi-qr-code-scan me-2"></i>Pagar Consulta QR
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Especialidades & Tratamientos</h3>
            <p class="text-muted small">Explora nuestros procedimientos médicos en clínica.</p>
        </div>
        <a href="index.php?c=servicio&a=lista" class="btn btn-outline-primary btn-sm fw-semibold">
            Ver Todos los Tratamientos <i class="bi bi-arrow-right ms-1"></i>
        </a>
    </div>

    <div class="row g-4">
        <?php foreach ($servicios as $srv): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card custom-card h-100 p-3">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge bg-light text-primary border border-primary-subtle px-3 py-2 fw-semibold">
                                <?php echo htmlspecialchars($srv['categoria'] ?? 'General'); ?>
                            </span>
                            <span class="fs-5 fw-bold text-dark">$<?php echo htmlspecialchars($srv['precio']); ?></span>
                        </div>
                        <h5 class="fw-bold text-dark mb-2"><?php echo htmlspecialchars($srv['nombre']); ?></h5>
                        <p class="text-muted small mb-4 flex-grow-1"><?php echo htmlspecialchars($srv['descripcion']); ?></p>
                        
                        <a href="index.php?c=pago" class="btn btn-sm btn-outline-success w-100 fw-semibold rounded-2 py-2">
                            <i class="bi bi-qr-code me-1"></i> Pagar Tratamiento por QR
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
