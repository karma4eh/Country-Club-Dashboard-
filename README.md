# 🏆 Country Club - Sistema Integral de Gestión & Control de Socios

![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?style=for-the-badge&logo=php&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/TailwindCSS-3.4-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![BCV](https://img.shields.io/badge/BCV-Realtime_Sync-F59E0B?style=for-the-badge)

Sistema ejecutivo y administrativo moderno para la gestión de socios, membresías, control de cobros y caja bimoneda (USD / Bs.) con sincronización automática en tiempo real de la tasa oficial del Banco Central de Venezuela (BCV).

---

## ✨ Características Principales

* **🏛️ Tasa BCV Oficial en Tiempo Real:** 
  * Servicio de scraping resiliente que consulta directamente el nodo oficial del Banco Central de Venezuela (`bcv.org.ve`).
  * Fallback automático a DolarApi oficial.
  * Ticker dinámico en el navbar y portal de acceso con botón de sincronización instantánea sin recarga de página (`POST /api/bcv/sync`).
* **💎 Estética "Luxury Executive Club":**
  * Interfaz moderna en modo oscuro basada en *Plus Jakarta Sans* y detalles en *Cinzel*.
  * Iluminación ambiental y efectos *glassmorphism* (`backdrop-blur`).
  * CSS estático precompilado y optimizado (tiempos de respuesta sub-100ms).
* **👥 Padrón y Expedientes de Socios:**
  * Directorio con filtros avanzados por cédula, acción y estatus de solvencia.
  * Formulario de afiliación con **Carnet Digital Interactivo** que se previsualiza en tiempo real mientras se llena el formulario.
  * Historial financiero por socio con balance contable y meses adeudados.
* **💳 Caja y Liquidación Bimoneda (USD y Bs.):**
  * TPV con botones rápidos de meses ($10, $20, $30, $50, $100).
  * Conversor automático e instantáneo a Bolívares usando la tasa oficial del BCV.
  * Detección automática del estado de morosidad al tipear la cédula del socio.
* **📄 Recibo Oficial de Caja Imprimible:**
  * Factura y comprobante membretado listo para impresión (`window.print()`).
  * Desglose cambiario conforme al Convenio Cambiario N° 1 y bloques de firmas directivas.
* **🔒 Seguridad y Bitácora de Auditoría:**
  * Trazabilidad completa de inicios de sesión, registros de socios y pagos recibidos.
  * Categorización por niveles de usuario y roles de acceso.

---

## 🚀 Requisitos del Sistema

* PHP 8.2 o superior (Recomendado PHP 8.3 con extensiones: `zip`, `curl`, `pdo_mysql`, `mbstring`, `openssl`, `gd`).
* MySQL 8.0 o MariaDB 10.4+.
* Composer 2.x.
* Laragon, XAMPP o servidor web Apache/Nginx.

---

## 🛠️ Instalación y Puesta en Marcha

1. **Clonar el repositorio:**
   ```bash
   git clone https://github.com/karma4eh/Country-Club-Dashboard-.git
   cd Country-Club-Dashboard-
   ```

2. **Instalar dependencias de PHP:**
   ```bash
   composer install
   ```

3. **Configurar el entorno:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *Configura tus credenciales de base de datos en `.env` (DB_DATABASE=Country_DB, DB_USERNAME, etc.).*

4. **Ejecutar migraciones y datos de prueba:**
   ```bash
   php artisan migrate --seed
   ```

5. **Iniciar el servidor local:**
   * **Con Laragon:** Colocar el proyecto en `C:\laragon\www\country-club` y abrir `http://127.0.0.1/country-club/public/login`.
   * **Con Artisan Serve:**
     ```bash
     php artisan serve
     ```
     Abrir `http://127.0.0.1:8000`.

---

## 🔑 Credenciales Demo por Defecto

* **URL de Acceso:** `/login`
* **Correo Electrónico:** `admin@countryclub.com`
* **Contraseña:** `password`

---

## 🧪 Pruebas Automatizadas

El proyecto incluye tests de integración y características en PHPUnit:
```bash
php artisan test
```

---

## 📄 Licencia

Este proyecto es software privado desarrollado para Country Club C.A.
