import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'constants/theme.dart';
import 'providers/voice_provider.dart';
import 'screens/home_screen.dart';
import 'screens/studio_screen.dart';

void main() {
  WidgetsFlutterBinding.ensureInitialized();
  runApp(const ABoxApp());
}

class ABoxApp extends StatelessWidget {
  const ABoxApp({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return MultiProvider(
      providers: [
        ChangeNotifierProvider(create: (_) => VoiceProvider()),
      ],
      child: MaterialApp(
        title: 'ABox - Real-time AI Voice Changer',
        debugShowCheckedModeBanner: false,
        theme: AppTheme.darkTheme,
        home: const MainNavigationScreen(),
      ),
    );
  }
}

class MainNavigationScreen extends StatefulWidget {
  const MainNavigationScreen({Key? key}) : super(key: key);

  @override
  State<MainNavigationScreen> createState() => _MainNavigationScreenState();
}

class _MainNavigationScreenState extends State<MainNavigationScreen> {
  int _currentIndex = 0;

  final List<Widget> _screens = const [
    HomeScreen(),
    StudioScreen(),
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: _screens[_currentIndex],
      bottomNavigationBar: Container(
        decoration: BoxDecoration(
          color: AppColors.darkCard,
          border: Border(
            top: BorderSide(color: AppColors.darkBorder.withOpacity(0.6)),
          ),
        ),
        child: BottomNavigationBar(
          currentIndex: _currentIndex,
          backgroundColor: Colors.transparent,
          elevation: 0,
          selectedItemColor: AppColors.purpleLight,
          unselectedItemColor: Colors.white38,
          selectedFontSize: 11,
          unselectedFontSize: 11,
          onTap: (index) => setState(() => _currentIndex = index),
          items: const [
            BottomNavigationBarItem(
              icon: Icon(Icons.graphic_eq),
              label: 'Voices & Calling',
            ),
            BottomNavigationBarItem(
              icon: Icon(Icons.mic),
              label: 'Studio & Share',
            ),
          ],
        ),
      ),
    );
  }
}
