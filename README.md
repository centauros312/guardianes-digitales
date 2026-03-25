# 🛡️ Guardianes Digitales

## 📋 Descripción del Proyecto

**Guardianes Digitales** es una aplicación web integral diseñada para **reportar, gestionar y documentar incidentes en ambientes de trabajo**. La plataforma permite que los empleados registren eventos de seguridad, accidentes y situaciones anómalas en tiempo real, facilitando la creación de un entorno laboral más seguro y la trazabilidad de problemas de seguridad.

### 🎯 Propósito Principal

Centralizar y sistematizar el reporte de incidentes laborales, permitiendo:
- ✅ Documentación rápida y efectiva de eventos
- ✅ Geolocalización de incidentes (coordenadas GPS)
- ✅ Seguimiento del estado en tiempo real
- ✅ Almacenamiento de evidencias digitales
- ✅ Control de acceso mediante roles y permisos
- ✅ Historial auditable de todos los reportes

---

## 🗂️ Estructura de la Base de Datos

La aplicación utiliza 4 tablas principales:

### 1. **Roles** (`roles`)
```sql
- id_rol (PK)
- nombre_rol (VARCHAR 50)
```
Define los roles de usuario del sistema (Admin, Supervisor, Empleado, etc.)

### 2. **Usuarios** (`usuarios`)
```sql
- id_usuario (PK)
- nombre (VARCHAR 100)
- correo (VARCHAR 100)
- password_hash (VARCHAR 255) - Contraseña cifrada
- id_rol (FK) → roles
```
Gestiona las cuentas de usuario con autenticación y roles

### 3. **Incidentes** (`incidentes`)
```sql
- id_incidente (PK)
- descripcion (TEXT)
- fecha (DATETIME)
- latitud (DECIMAL 10,8)
- longitud (DECIMAL 11,8)
- estado (VARCHAR 50) - Abierto, En Progreso, Cerrado
- id_usuario (FK) → usuarios
```
Almacena los reportes de incidentes con ubicación GPS e información del reportante

### 4. **Evidencias** (`evidencias`)
```sql
- id_evidencia (PK)
- url_archivo (VARCHAR 255)
- id_incidente (FK) → incidentes
```
Vincula archivos (fotos, videos, documentos) a cada incidente

---

## ✨ Características Principales

### 🔐 Gestión de Seguridad
- Sistema de usuarios con contraseñas cifradas (Bcrypt)
- Control de acceso basado en roles
- Autenticación segura

### 📍 Geolocalización
- Captura de coordenadas exactas del incidente
- Integración con mapas para visualizar ubicaciones
- Seguimiento geográfico de incidentes

### 📝 Gestión Integral de Incidentes
- Crear reportes detallados
- Asignar estados (Abierto → En Progreso → Cerrado)
- Editar y actualizar reportes
- Visualizar historial completo

### 📁 Gestión de Evidencias
- Cargar múltiples archivos por incidente
- Almacenar URLs de evidencias (fotos, videos, documentos)
- Trazabilidad documental

### 👥 Gestión de Usuarios
- Agregar y eliminar usuarios
- Asignar roles específicos
- Cambiar permisos en tiempo real

---

## 🚀 Instalación y Configuración

### Requisitos Previos
- PHP 7.4+
- MySQL 5.7+
- Servidor web (Apache, en XAMPP)
- Navegador moderno

### Pasos de Instalación

#### 1. **Crear la Base de Datos**
```bash
mysql -u root -p
```

```sql
CREATE DATABASE guardianes;
USE guardianes;

CREATE TABLE roles (
    id_rol INT PRIMARY KEY AUTO_INCREMENT,
    nombre_rol VARCHAR(50)
);

CREATE TABLE usuarios (
    id_usuario INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100),
    correo VARCHAR(100),
    password_hash VARCHAR(255),
    id_rol INT,
    FOREIGN KEY (id_rol) REFERENCES roles(id_rol)
);

CREATE TABLE incidentes (
    id_incidente INT PRIMARY KEY AUTO_INCREMENT,
    descripcion TEXT,
    fecha DATETIME,
    latitud DECIMAL(10,8),
    longitud DECIMAL(11,8),
    estado VARCHAR(50),
    id_usuario INT,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
);

CREATE TABLE evidencias (
    id_evidencia INT PRIMARY KEY AUTO_INCREMENT,
    url_archivo VARCHAR(255),
    id_incidente INT,
    FOREIGN KEY (id_incidente) REFERENCES incidentes(id_incidente)
);
```

#### 2. **Insertar Roles Iniciales**
```sql
INSERT INTO roles (nombre_rol) VALUES ('Administrador');
INSERT INTO roles (nombre_rol) VALUES ('Supervisor');
INSERT INTO roles (nombre_rol) VALUES ('Empleado');
```

#### 3. **Colocar los archivos en el servidor**
```
c:\xampp\htdocs\
  ├── index.php (panel CRUD)
  └── README.md
```

#### 4. **Acceder a la aplicación**
Abrir navegador y dirigirse a: `http://localhost`

---

## 📊 Uso de la Aplicación

### Panel de Control CRUD

La interfaz principal ofrece 4 secciones:

#### 🔑 **Roles**
- Ver lista de roles disponibles
- Crear nuevos roles
- Eliminar roles

#### 👥 **Usuarios**
- Registrar nuevos usuarios con email y contraseña
- Asignar roles a usuarios
- Listar todos los usuarios activos
- Eliminar usuarios

#### 🚨 **Incidentes**
- Reportar nuevo incidente con descripción detallada
- Capturar ubicación (latitud/longitud)
- Asignar estado del incidente
- Listar todos los incidentes registrados
- Eliminar incidentes

#### 📁 **Evidencias**
- Cargar URL de archivos comprobantes
- Vincular evidencias a incidentes específicos
- Visualizar y descargar evidencias
- Eliminar evidencias

---

## 🛠️ Stack Tecnológico

| Tecnología | Propósito |
|-----------|-----------|
| **PHP 7.4+** | Backend y lógica de aplicación |
| **MySQL** | Base de datos relacional |
| **HTML5** | Estructura del frontend |
| **CSS3** | Estilos modernos y responsive |
| **JavaScript** | Interactividad y validaciones |
| **Apache (XAMPP)** | Servidor web |

---

## 🔒 Seguridad

- ✅ Contraseñas cifradas con **Bcrypt**
- ✅ Prevención de **SQL Injection** con `mysqli::real_escape_string()`
- ✅ Validación de entrada en formularios
- ✅ Control de acceso basado en roles
- ✅ Timestamps de auditoría en todos los registros

---

## 📝 Casos de Uso Comunes

### Escenario 1: Empleado reporta accidente
1. Accede a la aplicación
2. Va a "Incidentes"
3. Completa el formulario con:
   - Descripción del accidente
   - Hora exacta
   - Ubicación GPS
   - Asigna su nombre como reportante
4. Selecciona estado "Abierto"
5. Sube evidencias (fotos/videos del incidente)

### Escenario 2: Supervisor revisa incidentes
1. Accede a "Incidentes"
2. Visualiza lista de reportes
3. Puede ver detalles y ubicación
4. Actualiza estado a "En Progreso"
5. Revisa evidencias adjuntas

### Escenario 3: Administrador gestiona usuarios
1. Accede a "Usuarios"
2. Crea nuevas cuentas con roles específicos
3. Asigna permisos según departamento
4. Elimina usuarios inactivos

---

## 🚦 Workflow de Incidentes

```
Abierto → En Progreso → Cerrado
```

1. **Abierto**: Incidente reportado, pendiente de investigación
2. **En Progreso**: Se están recopilando evidencias y tomando medidas
3. **Cerrado**: Incidente resuelto con acciones correctivas implementadas

---

## 📞 Contacto y Soporte

Para reportes de bugs o sugerencias de mejora, contactar al equipo de desarrollo.

---

## 📄 Licencia

Guardianes Digitales © 2026. Todos los derechos reservados.