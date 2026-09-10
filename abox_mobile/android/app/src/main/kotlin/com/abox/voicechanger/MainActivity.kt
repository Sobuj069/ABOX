package com.abox.voicechanger

import android.content.Intent
import androidx.annotation.NonNull
import io.flutter.embedding.android.FlutterActivity
import io.flutter.embedding.engine.FlutterEngine
import io.flutter.plugin.common.MethodChannel

class MainActivity: FlutterActivity() {
    private val CHANNEL = "com.abox.voicechanger/audio"

    override fun configureFlutterEngine(@NonNull flutterEngine: FlutterEngine) {
        super.configureFlutterEngine(flutterEngine)

        MethodChannel(flutterEngine.dartExecutor.binaryMessenger, CHANNEL).setMethodCallHandler { call, result ->
            when (call.method) {
                "startVoiceService" -> {
                    val pitch = call.argument<Double>("pitch") ?: 0.0
                    val voiceName = call.argument<String>("voiceName") ?: "AI Voice"

                    val intent = Intent(this, VoiceChangerService::class.java).apply {
                        action = VoiceChangerService.ACTION_START
                        putExtra(VoiceChangerService.EXTRA_PITCH, pitch)
                        putExtra(VoiceChangerService.EXTRA_VOICE_NAME, voiceName)
                    }
                    if (android.os.Build.VERSION.SDK_INT >= android.os.Build.VERSION_CODES.O) {
                        startForegroundService(intent)
                    } else {
                        startService(intent)
                    }
                    result.success(true)
                }
                "stopVoiceService" -> {
                    val intent = Intent(this, VoiceChangerService::class.java).apply {
                        action = VoiceChangerService.ACTION_STOP
                    }
                    startService(intent)
                    result.success(true)
                }
                "updatePitch" -> {
                    val pitch = call.argument<Double>("pitch") ?: 0.0
                    VoiceChangerService.currentPitch = pitch
                    result.success(true)
                }
                "isServiceRunning" -> {
                    result.success(VoiceChangerService.isRunning)
                }
                else -> {
                    result.notImplemented()
                }
            }
        }
    }
}
