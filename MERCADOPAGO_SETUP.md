# Configuración de Mercado Pago - Guía Completa

## 📋 Índice
1. [Obtener Credenciales de Mercado Pago](#obtener-credenciales)
2. [Configurar en el Proyecto](#configurar-proyecto)
3. [¿Dónde va el dinero?](#donde-va-el-dinero)
4. [Cómo Retirar el Dinero](#retirar-dinero)
5. [Solución de Problemas](#solucion-problemas)

---

## 🔑 1. Obtener Credenciales de Mercado Pago

### Paso 1: Crear cuenta en Mercado Pago

1. Ve a: **https://www.mercadopago.com.bo** (versión de Bolivia)
2. Haz clic en **"Crear cuenta"** o **"Iniciar sesión"** si ya tienes una
3. Completa el registro con tus datos personales o de empresa

### Paso 2: Obtener Access Token

1. Una vez dentro de tu cuenta, ve a **"Desarrolladores"** → **"Tus integraciones"**
2. Crea una nueva aplicación o selecciona una existente
3. En la sección **"Credenciales de producción"** encontrarás:
   - **Access Token**: Esta es la clave que necesitas
   - **Public Key**: Para pagos en el frontend (opcional)

### Paso 3: Modo de Prueba (Testing)

Para probar sin usar dinero real:
1. Ve a **"Desarrolladores"** → **"Credenciales de prueba"**
2. Usa el **Access Token de prueba**
3. Puedes usar tarjetas de prueba de Mercado Pago

---

## ⚙️ 2. Configurar en el Proyecto

### Paso 1: Agregar credenciales al archivo `.env`

Abre el archivo `.env` en la raíz del proyecto y agrega:

```env
# Mercado Pago - Credenciales de Producción
MERCADOPAGO_ACCESS_TOKEN=TU_ACCESS_TOKEN_AQUI

# Mercado Pago - Credenciales de Prueba (para desarrollo)
# MERCADOPAGO_ACCESS_TOKEN=TU_ACCESS_TOKEN_PRUEBA_AQUI
```

**⚠️ IMPORTANTE:**
- **NO** subas el archivo `.env` a Git (ya está en `.gitignore`)
- Usa credenciales de **prueba** durante el desarrollo
- Usa credenciales de **producción** solo cuando estés listo para recibir pagos reales

### Paso 2: Verificar configuración

Ejecuta:
```bash
php artisan config:clear
```

---

## 💰 3. ¿Dónde va el dinero?

### Flujo del dinero:

1. **Cliente escanea QR** → Paga con su app de banca móvil o tarjeta
2. **Mercado Pago recibe el pago** → El dinero se acredita en tu cuenta de Mercado Pago
3. **Dinero disponible** → Aparece en tu cuenta de Mercado Pago (no en tu banco aún)
4. **Retiro manual** → Debes retirar el dinero manualmente a tu cuenta bancaria

### Comisiones de Mercado Pago:

- **Pagos con QR/Tarjeta**: Aproximadamente **3.99% + IVA** por transacción
- **Ejemplo**: Si recibes 100 Bs, Mercado Pago cobra ~4 Bs, recibes ~96 Bs

### Tiempo de acreditación:

- **Pagos con QR**: Inmediato (aparece en tu cuenta de Mercado Pago)
- **Retiro a banco**: 1-3 días hábiles (depende del banco)

---

## 💸 4. Cómo Retirar el Dinero

### Opción 1: Retiro a Cuenta Bancaria (Recomendado)

1. **Inicia sesión** en tu cuenta de Mercado Pago
2. Ve a **"Tu dinero"** → **"Retirar dinero"**
3. Selecciona **"A cuenta bancaria"**
4. **Agrega tu cuenta bancaria** (si no la tienes):
   - Ingresa número de cuenta
   - Tipo de cuenta (corriente/ahorro)
   - Banco
   - Nombre del titular
5. **Ingresa el monto** a retirar
6. **Confirma** el retiro
7. El dinero llegará en **1-3 días hábiles**

### Opción 2: Retiro a Cuenta de Mercado Pago

1. Ve a **"Tu dinero"** → **"Retirar dinero"**
2. Selecciona **"A cuenta de Mercado Pago"**
3. El dinero estará disponible inmediatamente para usar en Mercado Pago

### Opción 3: Retiro Automático (Configurar)

1. Ve a **"Configuración"** → **"Retiros automáticos"**
2. Configura retiros automáticos a tu cuenta bancaria
3. Elige la frecuencia (diario, semanal, mensual)
4. El dinero se retirará automáticamente según tu configuración

### Requisitos para retirar:

- ✅ Cuenta verificada en Mercado Pago
- ✅ Documentos de identidad subidos
- ✅ Cuenta bancaria agregada y verificada
- ✅ Saldo disponible en tu cuenta

---

## 🔍 5. Ver Pagos y Transacciones

### En el Dashboard de Mercado Pago:

1. Ve a **"Tu actividad"** o **"Movimientos"**
2. Verás todas las transacciones:
   - Pagos recibidos
   - Comisiones cobradas
   - Retiros realizados
   - Saldo disponible

### En tu aplicación Laravel:

Los pagos se registran en:
- **Base de datos**: Tabla `pedidos` (campo `metodo_pago = 'qr'`)
- **Logs**: `storage/logs/laravel.log` (webhooks recibidos)

---

## 🛠️ 6. Solución de Problemas

### Error: "Invalid access token"

**Solución:**
- Verifica que el `MERCADOPAGO_ACCESS_TOKEN` en `.env` sea correcto
- Asegúrate de no tener espacios extra
- Ejecuta `php artisan config:clear`

### El QR no se genera

**Solución:**
- Verifica que Mercado Pago SDK esté instalado: `composer show mercadopago/dx-php`
- Revisa los logs: `storage/logs/laravel.log`
- Verifica que el Access Token sea válido

### El dinero no aparece en mi cuenta

**Solución:**
- Verifica en el dashboard de Mercado Pago → "Tu actividad"
- Los pagos pueden tardar unos minutos en aparecer
- Revisa que el webhook esté funcionando correctamente

### No puedo retirar dinero

**Solución:**
- Verifica que tu cuenta esté verificada
- Asegúrate de tener documentos subidos
- Verifica que tu cuenta bancaria esté agregada y verificada
- Contacta soporte de Mercado Pago si persiste

---

## 📞 Contacto y Soporte

- **Soporte Mercado Pago**: https://www.mercadopago.com.bo/ayuda
- **Documentación API**: https://www.mercadopago.com.bo/developers/es
- **Centro de Ayuda**: https://www.mercadopago.com.bo/developers/es/support

---

## 🔒 Seguridad

- ✅ **NUNCA** compartas tu Access Token
- ✅ **NO** subas el `.env` a repositorios públicos
- ✅ Usa credenciales de **prueba** durante desarrollo
- ✅ Cambia las credenciales si sospechas que fueron comprometidas
- ✅ Revisa regularmente los movimientos en tu cuenta

---

## 📝 Notas Importantes

1. **Modo Prueba vs Producción**:
   - En modo prueba, los pagos son simulados
   - No se transfiere dinero real
   - Perfecto para desarrollo y testing

2. **Webhooks**:
   - Mercado Pago notifica automáticamente cuando hay un pago
   - La URL del webhook debe ser accesible desde internet
   - En desarrollo local, usa herramientas como ngrok

3. **Comisiones**:
   - Las comisiones se deducen automáticamente
   - Revisa la estructura de comisiones en el sitio de Mercado Pago

---

¡Listo! Ya tienes todo configurado para recibir pagos reales con QR. 🎉

