import 'dart:io';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:record/record.dart';
import 'package:audioplayers/audioplayers.dart';
import 'package:path_provider/path_provider.dart';
import 'package:share_plus/share_plus.dart';
import '../constants/theme.dart';
import '../providers/voice_provider.dart';
import '../services/api_service.dart';

class StudioScreen extends StatefulWidget {
  const StudioScreen({Key? key}) : super(key: key);

  @override
  State<StudioScreen> createState() => _StudioScreenState();
}

class _StudioScreenState extends State<StudioScreen> {
  final AudioRecorder _recorder = AudioRecorder();
  final AudioPlayer _player = AudioPlayer();
  final ApiService _apiService = ApiService();

  bool _isRecording = false;
  bool _isPlaying = false;
  String? _recordedFilePath;
  double _pitchShift = 0.0;
  int _recordDuration = 0;

  @override
  void initState() {
    super.initState();
    _player.onPlayerComplete.listen((_) {
      setState(() => _isPlaying = false);
    });
  }

  Future<void> _startRecording() async {
    if (await _recorder.hasPermission()) {
      final dir = await getApplicationDocumentsDirectory();
      final path = '${dir.path}/abox_rec_${DateTime.now().millisecondsSinceEpoch}.m4a';

      await _recorder.start(const RecordConfig(), path: path);
      setState(() {
        _isRecording = true;
        _recordDuration = 0;
        _recordedFilePath = null;
      });
    }
  }

  Future<void> _stopRecording() async {
    final path = await _recorder.stop();
    setState(() {
      _isRecording = false;
      _recordedFilePath = path;
    });

    if (path != null) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('Audio recorded! Ready to transform & send to imo.'),
          backgroundColor: AppColors.emerald,
        ),
      );
    }
  }

  Future<void> _playTransformed() async {
    if (_recordedFilePath == null) return;

    if (_isPlaying) {
      await _player.stop();
      setState(() => _isPlaying = false);
    } else {
      setState(() => _isPlaying = true);
      // Play with pitch / playback rate alteration
      await _player.setPlaybackRate(1.0 + (_pitchShift / 24.0));
      await _player.play(DeviceFileSource(_recordedFilePath!));
    }
  }

  Future<void> _shareToImo() async {
    if (_recordedFilePath == null) return;
    await Share.shareXFiles(
      [XFile(_recordedFilePath!)],
      text: 'Voice message from ABox Voice Changer (imo HD)',
    );
  }

  @override
  void dispose() {
    _recorder.dispose();
    _player.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final provider = Provider.of<VoiceProvider>(context);
    final voice = provider.selectedVoice;

    return Scaffold(
      backgroundColor: AppColors.darkBg,
      appBar: AppBar(
        title: const Text(
          'Voice Studio & Recorder',
          style: TextStyle(fontWeight: FontWeight.bold, fontSize: 18),
        ),
        centerTitle: true,
      ),
      body: Padding(
        padding: const EdgeInsets.all(20),
        child: Column(
          children: [
            // Selected Voice Pill
            Container(
              padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 8),
              decoration: BoxDecoration(
                color: AppColors.purple.withOpacity(0.15),
                borderRadius: BorderRadius.circular(20),
                border: Border.all(color: AppColors.purple.withOpacity(0.4)),
              ),
              child: Row(
                mainAxisSize: MainAxisSize.min,
                children: [
                  const Icon(Icons.auto_awesome, color: AppColors.pink, size: 16),
                  const SizedBox(width: 8),
                  Text(
                    'Active Voice: ${voice?.name ?? "Default"} (${voice?.category ?? ""})',
                    style: const TextStyle(
                      color: AppColors.purpleLight,
                      fontWeight: FontWeight.bold,
                      fontSize: 12,
                    ),
                  ),
                ],
              ),
            ),
            const SizedBox(height: 32),

            // Waveform Visualizer simulation
            Container(
              height: 120,
              width: double.infinity,
              decoration: BoxDecoration(
                color: AppColors.darkCard,
                borderRadius: BorderRadius.circular(20),
                border: Border.all(color: AppColors.darkBorder),
              ),
              child: Center(
                child: Icon(
                  _isRecording
                      ? Icons.graphic_eq
                      : (_recordedFilePath != null ? Icons.audiotrack : Icons.mic_none),
                  size: 54,
                  color: _isRecording
                      ? Colors.redAccent
                      : (_recordedFilePath != null ? AppColors.emerald : Colors.white30),
                ),
              ),
            ),
            const SizedBox(height: 24),

            // Big Record Button
            GestureDetector(
              onTap: () {
                if (_isRecording) {
                  _stopRecording();
                } else {
                  _startRecording();
                }
              },
              child: Container(
                width: 76,
                height: 76,
                decoration: BoxDecoration(
                  shape: BoxShape.circle,
                  color: _isRecording ? Colors.redAccent : AppColors.purple,
                  boxShadow: [
                    BoxShadow(
                      color: (_isRecording ? Colors.redAccent : AppColors.purple)
                          .withOpacity(0.5),
                      blurRadius: 18,
                    ),
                  ],
                ),
                child: Icon(
                  _isRecording ? Icons.stop : Icons.mic,
                  color: Colors.white,
                  size: 36,
                ),
              ),
            ),
            const SizedBox(height: 12),
            Text(
              _isRecording ? 'Recording... Tap to Stop' : 'Tap to Record Voice',
              style: TextStyle(
                color: _isRecording ? Colors.redAccent : Colors.white70,
                fontWeight: FontWeight.bold,
                fontSize: 13,
              ),
            ),
            const SizedBox(height: 24),

            // Pitch Adjustment Slider
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                const Text('Pitch Modulation:', style: TextStyle(color: Colors.white70)),
                Text(
                  '${_pitchShift > 0 ? "+" : ""}${_pitchShift.toStringAsFixed(1)} st',
                  style: const TextStyle(
                    color: AppColors.purpleLight,
                    fontWeight: FontWeight.bold,
                  ),
                ),
              ],
            ),
            Slider(
              value: _pitchShift,
              min: -12.0,
              max: 12.0,
              divisions: 24,
              activeColor: AppColors.purple,
              inactiveColor: AppColors.darkBorder,
              onChanged: (val) => setState(() => _pitchShift = val),
            ),
            const Spacer(),

            // Actions: Play & Share to imo
            if (_recordedFilePath != null) ...[
              Row(
                children: [
                  Expanded(
                    child: ElevatedButton.icon(
                      style: ElevatedButton.styleFrom(
                        backgroundColor: AppColors.darkCardLight,
                        padding: const EdgeInsets.symmetric(vertical: 14),
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(14),
                        ),
                      ),
                      icon: Icon(_isPlaying ? Icons.stop : Icons.play_arrow),
                      label: Text(_isPlaying ? 'Stop' : 'Preview'),
                      onPressed: _playTransformed,
                    ),
                  ),
                  const SizedBox(width: 12),
                  Expanded(
                    child: ElevatedButton.icon(
                      style: ElevatedButton.styleFrom(
                        backgroundColor: AppColors.emerald,
                        padding: const EdgeInsets.symmetric(vertical: 14),
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(14),
                        ),
                      ),
                      icon: const Icon(Icons.send),
                      label: const Text('Send to imo'),
                      onPressed: _shareToImo,
                    ),
                  ),
                ],
              ),
            ],
          ],
        ),
      ),
    );
  }
}
