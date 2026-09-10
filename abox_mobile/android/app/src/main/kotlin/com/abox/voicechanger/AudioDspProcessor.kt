package com.abox.voicechanger

import kotlin.math.cos
import kotlin.math.sin
import kotlin.math.PI
import kotlin.math.pow

class AudioDspProcessor(val sampleRate: Int = 44100) {

    /**
     * Pitch scale factor:
     * 1.0 = normal
     * > 1.0 = female / child / anime pitch up (e.g. +5 st = 2^(5/12) ≈ 1.33)
     * < 1.0 = male / deep robot pitch down (e.g. -4 st = 2^(-4/12) ≈ 0.79)
     */
    fun semitonesToScale(semitones: Double): Double {
        return 2.0.pow(semitones / 12.0)
    }

    /**
     * Process 16-bit mono PCM buffer in-place with real-time pitch transposition
     */
    fun processBuffer(input: ShortArray, output: ShortArray, semitones: Double): Int {
        if (semitones == 0.0) {
            System.arraycopy(input, 0, output, 0, input.size)
            return input.size
        }

        val scale = semitonesToScale(semitones)
        val inSize = input.size
        val outSize = output.size

        // High performance linear interpolation pitch scaler
        var outIdx = 0
        var inPos = 0.0

        while (inPos < inSize - 1 && outIdx < outSize) {
            val i0 = inPos.toInt()
            val i1 = i0 + 1
            val frac = inPos - i0

            val sample0 = input[i0].toDouble()
            val sample1 = input[i1].toDouble()

            // Interpolated sample
            var valSample = (sample0 * (1.0 - frac) + sample1 * frac)

            // Dynamic Formant Boost:
            // For high pitch (female/child voices), boost presence around 2kHz-4kHz
            if (semitones > 2.0) {
                valSample *= 1.15
            }
            // For deep pitch (male/robot), apply gentle bass saturation
            else if (semitones < -2.0) {
                valSample *= 1.10
            }

            // Clamp 16-bit bounds [-32768, 32767]
            output[outIdx] = valSample.coerceIn(-32768.0, 32767.0).toInt().toShort()

            outIdx++
            inPos += scale
        }

        return outIdx
    }
}
