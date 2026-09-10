package com.abox.voicechanger

import android.app.Notification
import android.app.NotificationChannel
import android.app.NotificationManager
import android.app.PendingIntent
import android.app.Service
import android.content.Context
import android.content.Intent
import android.media.AudioFormat
import android.media.AudioManager
import android.media.AudioRecord
import android.media.AudioTrack
import android.media.MediaRecorder
import android.os.Build
import android.os.IBinder
import androidx.core.app.NotificationCompat

class VoiceChangerService : Service() {

    companion object {
        const val ACTION_START = "ACTION_START"
        const val ACTION_STOP = "ACTION_STOP"
        const val EXTRA_PITCH = "EXTRA_PITCH"
        const val EXTRA_VOICE_NAME = "EXTRA_VOICE_NAME"

        const val CHANNEL_ID = "abox_voice_channel"
        const val NOTIFICATION_ID = 1001

        @Volatile
        var isRunning = false

        @Volatile
        var currentPitch = 0.0

        @Volatile
        var activeVoiceName = "AI Voice"
    }

    private var audioThread: Thread? = null
    private var isRecording = false

    private val SAMPLE_RATE = 44100
    private val CHANNEL_CONFIG_IN = AudioFormat.CHANNEL_IN_MONO
    private val CHANNEL_CONFIG_OUT = AudioFormat.CHANNEL_OUT_MONO
    private val AUDIO_FORMAT = AudioFormat.ENCODING_PCM_16BIT

    private lateinit var dspProcessor: AudioDspProcessor
    private lateinit var audioManager: AudioManager

    override fun onCreate() {
        super.onCreate()
        dspProcessor = AudioDspProcessor(SAMPLE_RATE)
        audioManager = getSystemService(Context.AUDIO_SERVICE) as AudioManager
        createNotificationChannel()
    }

    override fun onStartCommand(intent: Intent?, flags: Int, startId: Int): Int {
        val action = intent?.action

        if (action == ACTION_STOP) {
            stopVoiceService()
            return START_NOT_STICKY
        }

        currentPitch = intent?.getDoubleExtra(EXTRA_PITCH, 0.0) ?: 0.0
        activeVoiceName = intent?.getStringExtra(EXTRA_VOICE_NAME) ?: "AI Voice"

        startForeground(NOTIFICATION_ID, buildNotification())
        startAudioLoop()

        return START_STICKY
    }

    private fun startAudioLoop() {
        if (isRecording) return

        isRecording = true
        isRunning = true

        audioThread = Thread {
            val minBufSizeIn = AudioRecord.getMinBufferSize(SAMPLE_RATE, CHANNEL_CONFIG_IN, AUDIO_FORMAT)
            val bufferSize = minBufSizeIn.coerceAtLeast(2048)

            var recorder: AudioRecord? = null
            var player: AudioTrack? = null

            try {
                recorder = AudioRecord(
                    MediaRecorder.AudioSource.MIC,
                    SAMPLE_RATE,
                    CHANNEL_CONFIG_IN,
                    AUDIO_FORMAT,
                    bufferSize * 2
                )

                player = AudioTrack(
                    AudioManager.STREAM_VOICE_CALL,
                    SAMPLE_RATE,
                    CHANNEL_CONFIG_OUT,
                    AUDIO_FORMAT,
                    bufferSize * 2,
                    AudioTrack.MODE_STREAM
                )

                // Optional Bluetooth SCO routing for headsets
                if (audioManager.isBluetoothScoAvailableOffCall) {
                    audioManager.startBluetoothSco()
                    audioManager.isBluetoothScoOn = true
                }

                recorder.startRecording()
                player.play()

                val inBuffer = ShortArray(bufferSize / 2)
                val outBuffer = ShortArray(bufferSize / 2)

                while (isRecording) {
                    val readSamples = recorder.read(inBuffer, 0, inBuffer.size)
                    if (readSamples > 0) {
                        // Real-time Pitch & Formant processing
                        val processedSamples = dspProcessor.processBuffer(inBuffer, outBuffer, currentPitch)
                        player.write(outBuffer, 0, processedSamples)
                    }
                }
            } catch (e: Exception) {
                e.printStackTrace()
            } finally {
                try {
                    recorder?.stop()
                    recorder?.release()
                    player?.stop()
                    player?.release()
                    if (audioManager.isBluetoothScoOn) {
                        audioManager.stopBluetoothSco()
                        audioManager.isBluetoothScoOn = false
                    }
                } catch (e: Exception) {
                    e.printStackTrace()
                }
            }
        }.apply {
            priority = Thread.MAX_PRIORITY
            start()
        }
    }

    private fun stopVoiceService() {
        isRecording = false
        isRunning = false
        audioThread?.interrupt()
        audioThread = null
        stopForeground(true)
        stopSelf()
    }

    private fun createNotificationChannel() {
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.O) {
            val channel = NotificationChannel(
                CHANNEL_ID,
                "ABox Voice Changer Live Hook",
                NotificationManager.IMPORTANCE_LOW
            ).apply {
                description = "Running real-time voice changer for calls"
            }
            val manager = getSystemService(NotificationManager::class.java)
            manager.createNotificationChannel(channel)
        }
    }

    private fun buildNotification(): Notification {
        val launchIntent = packageManager.getLaunchIntentForPackage(packageName)
        val pendingIntent = PendingIntent.getActivity(
            this,
            0,
            launchIntent,
            PendingIntent.FLAG_UPDATE_CURRENT or (if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.M) PendingIntent.FLAG_IMMUTABLE else 0)
        )

        return NotificationCompat.Builder(this, CHANNEL_ID)
            .setContentTitle("ABox Live Voice Active: $activeVoiceName")
            .setContentText("Transforming microphone audio in real time for imo HD")
            .setSmallIcon(android.R.drawable.ic_btn_speak_now)
            .setContentIntent(pendingIntent)
            .setOngoing(true)
            .build()
    }

    override fun onDestroy() {
        stopVoiceService()
        super.onDestroy()
    }

    override fun onBind(intent: Intent?): IBinder? = null
}
