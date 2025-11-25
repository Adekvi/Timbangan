/**
 * MQTT Weight Listener untuk Laravel + Redis
 * Topic: timbang/{id}/berat  →  contoh: timbang/12345/berat
 * Simpan ke Redis: weight_preview_{id}
 */

const mqtt = require('mqtt')
const redis = require('redis')

// ===================== CONFIGURATION =====================
// Ubah sesuai server kamu
const MQTT_BROKER = process.env.MQTT_BROKER || 'mqtt://broker.hivemq.com:1883'
const MQTT_USERNAME = process.env.MQTT_USER || null
const MQTT_PASSWORD = process.env.MQTT_PASS || null

const REDIS_URL = process.env.REDIS_URL || 'redis://127.0.0.1:6379'
// =========================================================

console.log('Starting MQTT Weight Listener...')
console.log('MQTT Broker :', MQTT_BROKER)
console.log('Redis URL   :', REDIS_URL)

// --- Koneksi Redis ---
const redisClient = redis.createClient({
    url: REDIS_URL
})

redisClient.on('error', (err) => {
    console.error('Redis Error:', err)
})

redisClient.on('connect', () => {
    console.log('Connected to Redis')
})

redisClient.on('ready', () => {
    console.log('Redis ready!')
})

// --- Koneksi MQTT ---
const mqttOptions = {
    clientId:
        'laravel_weight_listener_' + Math.random().toString(16).substr(2, 8),
    keepalive: 60,
    reconnectPeriod: 5000,
    clean: true
}

if (MQTT_USERNAME) mqttOptions.username = MQTT_USERNAME
if (MQTT_PASSWORD) mqttOptions.password = MQTT_PASSWORD

const client = mqtt.connect(MQTT_BROKER, mqttOptions)

// --- Event MQTT ---
client.on('connect', () => {
    console.log('Connected to MQTT Broker')
    client.subscribe('timbang/+/berat', { qos: 0 }, (err) => {
        if (err) {
            console.error('Subscribe error:', err)
        } else {
            console.log('Subscribed to topic: timbang/+/berat')
        }
    })
})

client.on('message', async (topic, message) => {
    try {
        const parts = topic.split('/')
        if (parts.length !== 3 || parts[2] !== 'berat') return

        const id = parts[1]
        let berat = message.toString().trim()

        // Bersihkan data (kadang ESP32 kirim spasi atau karakter aneh)
        berat = berat.replace(/[^\d.-]/g, '')
        if (berat === '' || isNaN(berat)) {
            console.warn(
                `Berat tidak valid dari ID ${id}: "${message.toString()}"`
            )
            return
        }

        const beratFloat = parseFloat(berat).toFixed(3)

        console.log(`Berat diterima → ID: ${id} | Berat: ${beratFloat} kg`)

        // Simpan ke Redis (expire 6 detik – cukup untuk polling 1 detik)
        await redisClient.set(`weight_preview_${id}`, beratFloat, {
            EX: 6 // 6 detik cukup aman
        })
    } catch (err) {
        console.error('Error processing message:', err)
    }
})

client.on('error', (err) => {
    console.error('MQTT Client Error:', err)
})

client.on('offline', () => {
    console.warn('MQTT Client Offline')
})

client.on('reconnect', () => {
    console.log('MQTT Reconnecting...')
})

// --- Graceful Shutdown ---
process.on('SIGINT', shutdown)
process.on('SIGTERM', shutdown)

async function shutdown() {
    console.log('\nShutting down gracefully...')
    client.end()
    await redisClient.quit()
    console.log('Disconnected. Bye!')
    process.exit(0)
}
