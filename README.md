# Sistema Integral de Gestión para Clínica Odontológica (`clinica-dental-pro`)

Este paquete contiene la estructura ampliada, base de datos relacional completa (`database.sql`), lógica de seguridad con roles (RBAC), módulo clínico (Anamnesis, Odontograma FDI, Presupuestos), gestión de caja con abonos, control de sillones/doctores, inventario (Kardex) y órdenes de laboratorio externo.

---

## 1. Arquitectura y Seguridad (Control de Acceso por Roles - RBAC)
A diferencia de un CRUD básico, una clínica real maneja información médica confidencial y flujo de dinero diario:
- **Administrador (`admin`):** Acceso total, configuración de usuarios, arqueo y cierre de caja, reportes financieros, inventario y auditoría.
- **Odontólogo (`odontologo`):** Gestión de su agenda diaria, registro de anamnesis/historia clínica, edición del odontograma FDI, creación de planes de tratamiento y evolución clínica. No puede anular pagos ni alterar caja.
- **Recepcionista (`recepcionista`):** Registro de pacientes, programación de citas validando disponibilidad de sillón/doctor, envío de recordatorios por WhatsApp y registro de abonos/pagos en caja.
- **Bitácora de Auditoría (`auditoria_logs`):** Registra cada acción crítica (inserción, edición o anulación de citas, pagos o fichas clínicas) con usuario, fecha/hora e IP.

---

## 2. Módulo Clínico Real
### A. Historia Clínica y Anamnesis (`anamnesis`)
Antes de cualquier procedimiento o anestesia se documenta:
- Alergias críticas (penicilina, lidocaína/anestésicos locales, látex, AINEs).
- Enfermedades sistémicas (hipertensión arterial, diabetes, cardiopatías, trastornos de coagulación).
- Medicación actual y estado de embarazo/lactancia.
- Signos vitales previos a cirugía (presión arterial, pulso) y firma de consentimiento informado.

### B. Odontograma Interactivo Estándar FDI (`odontograma_piezas`)
- **Numeración Internacional FDI:**
  - Adultos (32 piezas): Cuadrantes `1` (11-18), `2` (21-28), `3` (31-38), `4` (41-48).
  - Pediátricos (20 piezas): Cuadrantes `5` (51-55), `6` (61-65), `7` (71-75), `8` (81-85).
- **5 Caras por Pieza Dental:** Vestibular (`V`), Lingual/Palatina (`L`), Mesial (`M`), Distal (`D`) y Oclusal/Incisal (`O`).
- **Estados Clínicos:** Sano, Caries activa (rojo), Restauración/Resina en buen estado (azul), Restauración defectuosa, Endodoncia realizada, Corona, Implante, Extracción indicada, Pieza ausente (`X`), Prótesis fija/removible.

### C. Planes de Tratamiento y Presupuestos (`presupuestos` y `presupuesto_detalles`)
- Agrupa múltiples procedimientos por pieza dental y cara (ej. Pieza 16 Oclusal - Resina compuesta + Pieza 36 - Endodoncia unirradicular).
- Calcula costo total, descuentos autorizados y estado de aprobación del paciente (`Borrador`, `Aprobado`, `En progreso`, `Finalizado`).

---

## 3. Agenda Inteligente y Control de Sillones (`citas` y `sillones`)
- **Asignación de Sillones / Boxes:** Evita el cruce de horarios verificando que ni el odontólogo ni el sillón dental estén ocupados en el rango `[hora_inicio, hora_fin]`.
- **Flujo de Estados Reales:** `Pendiente` -> `Confirmada` -> `En sala de espera` -> `En atención` -> `Atendida` / `No asistió` / `Cancelada`.

---

## 4. Caja, Cuenta Corriente y Abonos (`pagos` y `caja_movimientos`)
- **Pagos por Cuotas / Abonos:** Vinculados al presupuesto aprobado del paciente (`Total Presupuesto - Total Abonado = Saldo Pendiente`).
- **Métodos de Pago:** Efectivo, QR Bancario, Transferencia, Tarjeta de Débito/Crédito.
- **Arqueo Diario:** Control de apertura de caja, ingresos por método de pago, egresos menores (gastos operativos) y cierre diario.

---

## 5. Inventario Clínico y Laboratorio Externo
- **Kardex de Insumos (`insumos` y `insumo_movimientos`):** Control de stock actual, stock mínimo de alerta y fecha de vencimiento (anestesia, resinas, guantes, agujas, ácido grabador).
- **Órdenes de Laboratorio (`ordenes_laboratorio`):** Seguimiento de prótesis, coronas o placas enviadas a protesistas externos (fecha envío, fecha prometida de entrega, costo laboratorio y estado).

---

## 6. Despliegue Rápido (Docker / XAMPP)
1. Importa `database.sql` en MySQL / MariaDB (`clinica_dental_db`).
2. Configura tus credenciales en `config/Database.php`.
3. Si usas Docker, ejecuta: `docker build -t clinica-dental-pro . && docker run -p 8080:80 clinica-dental-pro`.
