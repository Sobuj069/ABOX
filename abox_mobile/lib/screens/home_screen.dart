import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../constants/theme.dart';
import '../models/voice.dart';
import '../providers/voice_provider.dart';
import '../services/api_service.dart';

class HomeScreen extends StatelessWidget {
  const HomeScreen({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    final provider = Provider.of<VoiceProvider>(context);

    return Scaffold(
      backgroundColor: AppColors.darkBg,
      body: SafeArea(
        child: Column(
          children: [
            // Top App Bar (ABox Brand, VIP Diamond, Menu)
            _buildTopAppBar(context, provider),

            // Scrollable Content
            Expanded(
              child: provider.isLoading
                  ? const Center(
                      child: CircularProgressIndicator(color: AppColors.purple),
                    )
                  : SingleChildScrollView(
                      padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          // SECTION 1: Voice Preview Header & Grid
                          _buildVoicePreviewSection(context, provider),

                          const SizedBox(height: 16),

                          // Dynamic Apply Button
                          _buildApplyButton(context, provider),

                          const SizedBox(height: 24),

                          // SECTION 2: My Apps (imo HD)
                          _buildMyAppsSection(context, provider),

                          const SizedBox(height: 16),

                          // Live Call Hook Activation Card
                          _buildLiveCallCard(context, provider),

                          const SizedBox(height: 24),
                        ],
                      ),
                    ),
            ),
          ],
        ),
      ),
    );
  }

  // Top App Bar
  Widget _buildTopAppBar(BuildContext context, VoiceProvider provider) {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 10),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.between,
        children: [
          // Logo & Name
          Row(
            children: [
              Container(
                width: 32,
                height: 32,
                decoration: BoxDecoration(
                  gradient: AppColors.primaryGradient,
                  borderRadius: BorderRadius.circular(10),
                  boxShadow: [
                    BoxShadow(
                      color: AppColors.purple.withOpacity(0.4),
                      blurRadius: 8,
                    ),
                  ],
                ),
                child: const Icon(Icons.graphic_eq, color: Colors.white, size: 20),
              ),
              const SizedBox(width: 8),
              const Text(
                'ABox',
                style: TextStyle(
                  color: Colors.white,
                  fontSize: 22,
                  fontWeight: FontWeight.w900,
                  letterSpacing: 0.5,
                ),
              ),
            ],
          ),

          // VIP Diamond & Action Menu
          Row(
            children: [
              // VIP Diamond Button
              Container(
                padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 5),
                decoration: BoxDecoration(
                  color: AppColors.gold.withOpacity(0.12),
                  borderRadius: BorderRadius.circular(20),
                  border: Border.all(color: AppColors.gold.withOpacity(0.3)),
                ),
                child: Row(
                  children: [
                    const Icon(Icons.diamond, color: AppColors.gold, size: 14),
                    const SizedBox(width: 4),
                    Text(
                      '${provider.userCredits}',
                      style: const TextStyle(
                        color: AppColors.gold,
                        fontSize: 12,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                    if (provider.isUserVip) ...[
                      const SizedBox(width: 4),
                      Container(
                        padding: const EdgeInsets.symmetric(horizontal: 4, vertical: 1),
                        decoration: BoxDecoration(
                          color: AppColors.gold,
                          borderRadius: BorderRadius.circular(4),
                        ),
                        child: const Text(
                          'VIP',
                          style: TextStyle(
                            color: Colors.black,
                            fontSize: 9,
                            fontWeight: FontWeight.w900,
                          ),
                        ),
                      ),
                    ],
                  ],
                ),
              ),
              const SizedBox(width: 8),

              // Menu Icon
              IconButton(
                icon: const Icon(Icons.menu, color: Colors.white70),
                onPressed: () => _showSettingsModal(context, provider),
              ),
            ],
          ),
        ],
      ),
    );
  }

  // Voice Preview Section
  Widget _buildVoicePreviewSection(BuildContext context, VoiceProvider provider) {
    final selectedVoice = provider.selectedVoice;

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Row(
          mainAxisAlignment: MainAxisAlignment.between,
          children: [
            Row(
              children: [
                const Text(
                  'Voice preview',
                  style: TextStyle(
                    color: Colors.white,
                    fontSize: 15,
                    fontWeight: FontWeight.bold,
                  ),
                ),
                if (selectedVoice != null) ...[
                  const SizedBox(width: 8),
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
                    decoration: BoxDecoration(
                      color: AppColors.purple.withOpacity(0.2),
                      borderRadius: BorderRadius.circular(12),
                      border: Border.all(color: AppColors.purple.withOpacity(0.4)),
                    ),
                    child: Text(
                      selectedVoice.name,
                      style: const TextStyle(color: AppColors.purpleLight, fontSize: 11),
                    ),
                  ),
                ],
              ],
            ),
            const Icon(Icons.keyboard_arrow_up, color: Colors.white54, size: 20),
          ],
        ),
        const SizedBox(height: 12),

        // Grid (5 Columns)
        GridView.builder(
          shrinkWrap: true,
          physics: const NeverScrollableScrollPhysics(),
          gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
            crossAxisCount: 5,
            crossAxisSpacing: 8,
            mainAxisSpacing: 10,
            childAspectRatio: 0.72,
          ),
          itemCount: provider.voices.length,
          itemBuilder: (context, index) {
            final voice = provider.voices[index];
            final isSelected = selectedVoice?.id == voice.id;
            final isPlaying = provider.playingVoiceId == voice.id;

            return GestureDetector(
              onTap: () {
                provider.selectVoice(voice);
                provider.playVoicePreview(voice);
              },
              child: Column(
                children: [
                  // Avatar Box
                  Stack(
                    clipBehavior: Clip.none,
                    children: [
                      Container(
                        width: 58,
                        height: 58,
                        decoration: BoxDecoration(
                          color: AppColors.darkCardLight,
                          borderRadius: BorderRadius.circular(16),
                          border: Border.all(
                            color: isSelected
                                ? AppColors.purple
                                : AppColors.darkBorderLight,
                            width: isSelected ? 2 : 1,
                          ),
                          boxShadow: isSelected
                              ? [
                                  BoxShadow(
                                    color: AppColors.purple.withOpacity(0.3),
                                    blurRadius: 10,
                                    spreadRadius: 1,
                                  ),
                                ]
                              : null,
                        ),
                        child: ClipRRect(
                          borderRadius: BorderRadius.circular(14),
                          child: Container(
                            color: AppColors.darkBorder,
                            child: Icon(
                              voice.category == 'Female'
                                  ? Icons.face_3
                                  : Icons.face_6,
                              color: isSelected
                                  ? AppColors.pink
                                  : Colors.white60,
                              size: 32,
                            ),
                          ),
                        ),
                      ),

                      // Badge: Free or VIP
                      Positioned(
                        top: -4,
                        right: -4,
                        child: voice.isVip
                            ? Container(
                                width: 18,
                                height: 18,
                                decoration: BoxDecoration(
                                  color: AppColors.gold.withOpacity(0.2),
                                  shape: BoxShape.circle,
                                  border: Border.all(color: AppColors.gold),
                                ),
                                child: const Icon(
                                  Icons.diamond,
                                  color: AppColors.gold,
                                  size: 10,
                                ),
                              )
                            : Container(
                                padding: const EdgeInsets.symmetric(
                                    horizontal: 5, vertical: 1.5),
                                decoration: BoxDecoration(
                                  gradient: const LinearGradient(
                                    colors: [Color(0xFF9333EA), Color(0xFF6366F1)],
                                  ),
                                  borderRadius: BorderRadius.circular(8),
                                ),
                                child: const Text(
                                  'Free',
                                  style: TextStyle(
                                    color: Colors.white,
                                    fontSize: 8,
                                    fontWeight: FontWeight.bold,
                                  ),
                                ),
                              ),
                      ),

                      // Green Checkmark when selected (Matches screenshot AI Male4)
                      if (isSelected)
                        Positioned(
                          top: -3,
                          right: -3,
                          child: Container(
                            width: 18,
                            height: 18,
                            decoration: const BoxDecoration(
                              color: AppColors.emerald,
                              shape: BoxShape.circle,
                            ),
                            child: const Icon(
                              Icons.check,
                              color: Colors.white,
                              size: 12,
                            ),
                          ),
                        ),

                      // Playing Audio Waveform Indicator
                      if (isPlaying)
                        Positioned.fill(
                          child: Container(
                            decoration: BoxDecoration(
                              color: AppColors.purple.withOpacity(0.8),
                              borderRadius: BorderRadius.circular(16),
                            ),
                            child: const Icon(
                              Icons.graphic_eq,
                              color: Colors.white,
                              size: 24,
                            ),
                          ),
                        ),
                    ],
                  ),
                  const SizedBox(height: 4),

                  // Name
                  Text(
                    voice.name,
                    maxLines: 1,
                    overflow: TextOverflow.ellipsis,
                    textAlign: TextAlign.center,
                    style: TextStyle(
                      color: isSelected ? Colors.white : Colors.white70,
                      fontSize: 10,
                      fontWeight:
                          isSelected ? FontWeight.bold : FontWeight.normal,
                    ),
                  ),
                ],
              ),
            );
          },
        ),
      ],
    );
  }

  // Dynamic Apply Button
  Widget _buildApplyButton(BuildContext context, VoiceProvider provider) {
    final voice = provider.selectedVoice;
    final name = voice?.name ?? 'Voice';

    return GestureDetector(
      onTap: () {
        if (voice != null) {
          provider.selectVoice(voice);
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(
              content: Text('$name applied successfully! Ready for calls.'),
              backgroundColor: AppColors.purple,
              duration: const Duration(seconds: 2),
            ),
          );
        }
      },
      child: Container(
        width: double.infinity,
        padding: const EdgeInsets.symmetric(vertical: 12),
        decoration: BoxDecoration(
          gradient: AppColors.primaryGradient,
          borderRadius: BorderRadius.circular(16),
          boxShadow: [
            BoxShadow(
              color: AppColors.purple.withOpacity(0.4),
              blurRadius: 12,
              offset: const Offset(0, 4),
            ),
          ],
        ),
        child: Row(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            const Icon(Icons.bolt, color: Colors.white, size: 18),
            const SizedBox(width: 8),
            Text(
              'Apply $name to Calls',
              style: const TextStyle(
                color: Colors.white,
                fontSize: 14,
                fontWeight: FontWeight.w800,
                letterSpacing: 0.3,
              ),
            ),
          ],
        ),
      ),
    );
  }

  // SECTION 2: My Apps (imo HD)
  Widget _buildMyAppsSection(BuildContext context, VoiceProvider provider) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        const Text(
          'My Apps',
          style: TextStyle(
            color: Colors.white,
            fontSize: 15,
            fontWeight: FontWeight.bold,
          ),
        ),
        const SizedBox(height: 10),

        // ONLY imo HD Card (strictly matching screenshot)
        Container(
          padding: const EdgeInsets.all(12),
          decoration: BoxDecoration(
            color: AppColors.darkCard,
            borderRadius: BorderRadius.circular(16),
            border: Border.all(
              color: provider.isImoIntegrationEnabled
                  ? AppColors.emerald.withOpacity(0.4)
                  : AppColors.darkBorder,
            ),
          ),
          child: Row(
            children: [
              // App Icon
              Container(
                width: 44,
                height: 44,
                decoration: BoxDecoration(
                  color: const Color(0xFF0D5EAF).withOpacity(0.2),
                  borderRadius: BorderRadius.circular(12),
                  border: Border.all(color: const Color(0xFF1E88E5)),
                ),
                child: const Center(
                  child: Text(
                    'imo',
                    style: TextStyle(
                      color: Color(0xFF42A5F5),
                      fontWeight: FontWeight.w900,
                      fontSize: 14,
                    ),
                  ),
                ),
              ),
              const SizedBox(width: 12),

              // Title & Status
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const Text(
                      'imo HD',
                      style: TextStyle(
                        color: Colors.white,
                        fontSize: 14,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                    const SizedBox(height: 2),
                    Text(
                      provider.isImoIntegrationEnabled
                          ? 'Voice Changer Active for imo Calls'
                          : 'Tap toggle to enable for imo',
                      style: TextStyle(
                        color: provider.isImoIntegrationEnabled
                            ? AppColors.emerald
                            : Colors.white38,
                        fontSize: 11,
                      ),
                    ),
                  ],
                ),
              ),

              // Switch Toggle
              Switch(
                value: provider.isImoIntegrationEnabled,
                activeColor: AppColors.emerald,
                onChanged: (val) => provider.toggleImoIntegration(),
              ),
            ],
          ),
        ),
      ],
    );
  }

  // Live Call Hook Activation Card
  Widget _buildLiveCallCard(BuildContext context, VoiceProvider provider) {
    final isRunning = provider.isLiveServiceRunning;

    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: isRunning
            ? AppColors.emerald.withOpacity(0.1)
            : AppColors.purple.withOpacity(0.1),
        borderRadius: BorderRadius.circular(18),
        border: Border.all(
          color: isRunning ? AppColors.emerald : AppColors.purple.withOpacity(0.4),
        ),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.between,
            children: [
              Row(
                children: [
                  Icon(
                    isRunning ? Icons.headset_mic : Icons.phone_in_talk,
                    color: isRunning ? AppColors.emerald : AppColors.purpleLight,
                    size: 20,
                  ),
                  const SizedBox(width: 8),
                  Text(
                    isRunning ? 'Live Call Hook Running' : 'Automatic Call Mode',
                    style: TextStyle(
                      color: isRunning ? AppColors.emerald : Colors.white,
                      fontSize: 14,
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                ],
              ),
              Container(
                padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
                decoration: BoxDecoration(
                  color: isRunning ? AppColors.emerald : AppColors.purple,
                  borderRadius: BorderRadius.circular(8),
                ),
                child: Text(
                  isRunning ? 'ON' : 'OFF',
                  style: const TextStyle(
                    color: Colors.white,
                    fontWeight: FontWeight.w900,
                    fontSize: 10,
                  ),
                ),
              ),
            ],
          ),
          const SizedBox(height: 8),
          Text(
            isRunning
                ? 'Your voice is being routed through ${provider.selectedVoice?.name ?? "AI Voice"}. You can now make or answer calls in imo HD!'
                : 'Turn this ON before starting an imo call to route transformed speech through the background audio hook.',
            style: const TextStyle(color: Colors.white70, fontSize: 11),
          ),
          const SizedBox(height: 12),

          // Action Button
          SizedBox(
            width: double.infinity,
            child: ElevatedButton.icon(
              style: ElevatedButton.styleFrom(
                backgroundColor: isRunning ? Colors.redAccent : AppColors.emerald,
                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                padding: const EdgeInsets.symmetric(vertical: 10),
              ),
              icon: Icon(isRunning ? Icons.stop : Icons.play_arrow, size: 18),
              label: Text(
                isRunning ? 'Stop Background Hook' : 'Start Live Call Mode',
                style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 13),
              ),
              onPressed: () async {
                final success = await provider.toggleLiveService();
                if (success && !isRunning) {
                  ScaffoldMessenger.of(context).showSnackBar(
                    const SnackBar(
                      content: Text('Background service started! Open imo HD and call.'),
                      backgroundColor: AppColors.emerald,
                    ),
                  );
                }
              },
            ),
          ),
        ],
      ),
    );
  }

  void _showSettingsModal(BuildContext context, VoiceProvider provider) {
    showModalBottomSheet(
      context: context,
      backgroundColor: AppColors.darkCard,
      shape: const RoundedRectangleBorder(
        borderRadius: BorderRadius.vertical(top: Radius.circular(24)),
      ),
      builder: (context) => Padding(
        padding: const EdgeInsets.all(20),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Text(
              'ABox Settings',
              style: TextStyle(
                color: Colors.white,
                fontSize: 18,
                fontWeight: FontWeight.bold,
              ),
            ),
            const SizedBox(height: 16),
            ListTile(
              leading: const Icon(Icons.refresh, color: AppColors.cyan),
              title: const Text('Refresh Voices from Cloud'),
              onTap: () {
                Navigator.pop(context);
                provider.loadVoices();
              },
            ),
            ListTile(
              leading: const Icon(Icons.diamond, color: AppColors.gold),
              title: const Text('Get VIP & Credits (+100)'),
              onTap: () {
                provider.addCredits(100);
                provider.setVip(true);
                Navigator.pop(context);
                ScaffoldMessenger.of(context).showSnackBar(
                  const SnackBar(
                    content: Text('VIP Unlocked! +100 credits added.'),
                    backgroundColor: AppColors.gold,
                  ),
                );
              },
            ),
          ],
        ),
      ),
    );
  }
}
