# Mini-proyecto Comic-Con 🎟️

Aplicación web desarrollada en **PHP** y **HTML5** para la gestión e inscripción de asistentes al evento ficticio **Comic-Con**. El sistema permite seleccionar el tipo de pase al evento, añadir talleres opcionales, validar los datos introducidos según reglas de negocio y generar un resguardo detallado con el desglose del total a pagar.

---

## 📖 Descripción General

El proyecto consiste en un formulario interactivo de reserva que calcula dinámicamente el precio final de inscripción combinando:

- Datos del participante (nombre y edad).
- Pase de acceso según la categoría del asistente.
- Actividades y talleres temáticos complementarios.
- Generación de resguardo final en pantalla con auditoría en los registros del servidor.

---

## 🏷️ Precios y Tarifas

### Tipos de Entrada

| Entrada            | Descripción / Restricción         | Precio (€) |
| :----------------- | :---------------------------------- | :---------: |
| **Infantil** | Acceso general infantil             |    10 €    |
| **General**  | Acceso estándar al evento          |    30 €    |
| **VIP**      | Acceso exclusivo (solo ≥ 18 años) |    60 €    |

### Talleres Opcionales

| Taller                          | Precio (€) |
| :------------------------------ | :---------: |
| **Zona de Rol en Vivo**   |    5 €    |
| **Torneo de Videojuegos** |    10 €    |
| **Taller de Cosplay**     |    15 €    |
