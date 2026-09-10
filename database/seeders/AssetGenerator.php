<?php

// Function to generate a simple valid WAV file with a synthesized melodic tone pattern
function generateToneWav($filename, $baseFreq = 440, $duration = 2.0, $type = 'sine') {
    $sampleRate = 22050;
    $numSamples = (int)($sampleRate * $duration);
    $data = '';

    for ($i = 0; $i < $numSamples; $i++) {
        $t = $i / $sampleRate;
        // Pitch variation or speech-like modulation
        $pitchMod = 1.0 + 0.05 * sin(2 * M_PI * 5 * $t);
        $freq = $baseFreq * $pitchMod;
        
        // Envelope: attack, sustain, decay
        $env = 1.0;
        if ($t < 0.1) {
            $env = $t / 0.1;
        } elseif ($t > $duration - 0.2) {
            $env = ($duration - $t) / 0.2;
        }

        if ($type === 'sine') {
            $sample = sin(2 * M_PI * $freq * $t) * 0.7;
            // Add harmonic overtone
            $sample += sin(2 * M_PI * $freq * 2 * $t) * 0.3;
        } elseif ($type === 'robot') {
            $sample = (sin(2 * M_PI * $freq * $t) > 0 ? 0.6 : -0.6) * sin(2 * M_PI * 30 * $t);
        } else {
            // Sawtooth-like buzz for male/deep
            $sample = (2.0 * fmod($freq * $t, 1.0) - 1.0) * 0.5 + sin(2 * M_PI * $freq * $t) * 0.4;
        }

        $val = (int)($sample * $env * 28000);
        $val = max(-32768, min(32767, $val));
        $data .= pack('v', $val);
    }

    $fileSize = 36 + strlen($data);
    $header = "RIFF" . pack('V', $fileSize) . "WAVE" .
              "fmt " . pack('V', 16) . pack('v', 1) . pack('v', 1) .
              pack('V', $sampleRate) . pack('V', $sampleRate * 2) .
              pack('v', 2) . pack('v', 16) .
              "data" . pack('V', strlen($data));

    file_put_contents($filename, $header . $data);
}

// Generate sound previews for each voice
$audioDir = __DIR__ . '/../../public/audio/previews';
if (!is_dir($audioDir)) mkdir($audioDir, 0777, true);

$voicesAudio = [
    'ai_girl2.wav' => [520, 'sine'],
    'ai_male4.wav' => [240, 'buzz'],
    'ai_female5.wav' => [480, 'sine'],
    'ai_male2.wav' => [220, 'buzz'],
    'ai_boy.wav' => [360, 'sine'],
    'ai_female2.wav' => [440, 'sine'],
    'ai_female3.wav' => [410, 'sine'],
    'ai_girl.wav' => [560, 'sine'],
    'ai_male.wav' => [200, 'buzz'],
    'ai_boy2.wav' => [340, 'sine'],
    'robot_x.wav' => [300, 'robot'],
    'deep_bass.wav' => [130, 'buzz'],
];

foreach ($voicesAudio as $file => $cfg) {
    generateToneWav($audioDir . '/' . $file, $cfg[0], 2.2, $cfg[1]);
}
echo "Audio previews generated!\n";

// Function to generate stylized SVG avatars
function generateAvatarSvg($path, $bgColor, $hairColor, $accentColor, $type = 'male', $accessories = '') {
    $accSvg = '';
    if ($accessories === 'sunglasses') {
        $accSvg = '<rect x="34" y="44" width="22" height="12" rx="4" fill="#111" stroke="#fbbf24" stroke-width="1.5"/>
                   <rect x="64" y="44" width="22" height="12" rx="4" fill="#111" stroke="#fbbf24" stroke-width="1.5"/>
                   <line x1="56" y1="50" x2="64" y2="50" stroke="#fbbf24" stroke-width="2"/>';
    } elseif ($accessories === 'glasses') {
        $accSvg = '<circle cx="43" cy="49" r="9" fill="none" stroke="#60a5fa" stroke-width="2"/>
                   <circle cx="77" cy="49" r="9" fill="none" stroke="#60a5fa" stroke-width="2"/>
                   <line x1="52" y1="49" x2="68" y2="49" stroke="#60a5fa" stroke-width="2"/>';
    } elseif ($accessories === 'headphones') {
        $accSvg = '<path d="M 28 56 A 32 32 0 0 1 92 56" fill="none" stroke="#ec4899" stroke-width="4" stroke-linecap="round"/>
                   <rect x="22" y="48" width="10" height="20" rx="4" fill="#ec4899"/>
                   <rect x="88" y="48" width="10" height="20" rx="4" fill="#ec4899"/>';
    }

    $faceShape = '<path d="M 38 45 Q 60 78 82 45 Q 82 30 60 30 Q 38 30 38 45 Z" fill="#fcd34d" opacity="0.95"/>';
    $eyes = '<circle cx="48" cy="50" r="3" fill="#1e293b"/>
             <circle cx="72" cy="50" r="3" fill="#1e293b"/>
             <circle cx="49" cy="49" r="1" fill="#fff"/>
             <circle cx="73" cy="49" r="1" fill="#fff"/>';
    $smile = '<path d="M 52 62 Q 60 67 68 62" fill="none" stroke="#e11d48" stroke-width="2" stroke-linecap="round"/>';

    $hair = '';
    if ($type === 'female') {
        $hair = '<path d="M 26 55 Q 30 20 60 18 Q 90 20 94 55 Q 96 75 90 85 Q 86 60 82 50 Q 60 38 38 50 Q 34 60 30 85 Q 24 75 26 55 Z" fill="' . $hairColor . '"/>';
    } else {
        $hair = '<path d="M 32 40 Q 35 18 60 18 Q 85 18 88 40 Q 82 28 60 26 Q 38 28 32 40 Z" fill="' . $hairColor . '"/>';
    }

    $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 120 120" width="100%" height="100%">
  <defs>
    <radialGradient id="bgGrad" cx="50%" cy="40%" r="60%">
      <stop offset="0%" stop-color="{$accentColor}" stop-opacity="0.6"/>
      <stop offset="100%" stop-color="{$bgColor}"/>
    </radialGradient>
    <linearGradient id="bodyGrad" x1="0" y1="0" x2="0" y2="1">
      <stop offset="0%" stop-color="#3b82f6"/>
      <stop offset="100%" stop-color="#1e1b4b"/>
    </linearGradient>
  </defs>
  <!-- Background Circle -->
  <circle cx="60" cy="60" r="58" fill="url(#bgGrad)" stroke="{$accentColor}" stroke-width="2"/>
  <!-- Body/Shoulders -->
  <path d="M 22 110 Q 25 82 60 82 Q 95 82 98 110 Z" fill="url(#bodyGrad)"/>
  <!-- Hair back -->
  {$hair}
  <!-- Face -->
  {$faceShape}
  <!-- Eyes -->
  {$eyes}
  <!-- Smile -->
  {$smile}
  <!-- Accessories -->
  {$accSvg}
</svg>
SVG;

    file_put_contents($path, $svg);
}

// Generate Voice Avatars matching screenshot
$imgDir = __DIR__ . '/../../public/images/voices';
if (!is_dir($imgDir)) mkdir($imgDir, 0777, true);

$avatars = [
    'ai_girl2.svg'   => ['#312e81', '#7c2d12', '#a855f7', 'female', ''],
    'ai_male4.svg'   => ['#1e1b4b', '#451a03', '#6366f1', 'male', ''],
    'ai_female5.svg' => ['#1e293b', '#f59e0b', '#ec4899', 'female', ''],
    'ai_male2.svg'   => ['#1e1b4b', '#18181b', '#3b82f6', 'male', ''],
    'ai_boy.svg'     => ['#172554', '#78350f', '#06b6d4', 'male', ''],
    'ai_female2.svg' => ['#2e1065', '#e2e8f0', '#fbbf24', 'female', ''],
    'ai_female3.svg' => ['#3b0764', '#713f12', '#f59e0b', 'female', 'sunglasses'],
    'ai_girl.svg'    => ['#4c0519', '#e11d48', '#f43f5e', 'female', 'headphones'],
    'ai_male.svg'    => ['#0f172a', '#334155', '#10b981', 'male', 'glasses'],
    'ai_boy2.svg'    => ['#18181b', '#0284c7', '#38bdf8', 'male', 'headphones'],
    'robot_x.svg'    => ['#09090b', '#22d3ee', '#06b6d4', 'male', 'glasses'],
    'deep_bass.svg'  => ['#111827', '#1f2937', '#9333ea', 'male', 'sunglasses'],
];

foreach ($avatars as $file => $cfg) {
    generateAvatarSvg($imgDir . '/' . $file, $cfg[0], $cfg[1], $cfg[2], $cfg[3], $cfg[4]);
}
echo "Voice avatars generated!\n";

// Function to generate App Icons
$appsDir = __DIR__ . '/../../public/images/apps';
if (!is_dir($appsDir)) mkdir($appsDir, 0777, true);

// 1. imo HD (exact match to screenshot)
$imoSvg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" width="100%" height="100%">
  <rect width="100" height="100" rx="22" fill="#ffffff"/>
  <!-- Speech bubble with blue outline -->
  <path d="M 24 50 C 24 35 36 24 52 24 C 68 24 80 35 80 50 C 80 65 68 76 52 76 C 46 76 40 74 35 71 L 24 78 L 27 67 C 25 62 24 56 24 50 Z" 
        fill="none" stroke="#0077d7" stroke-width="5" stroke-linejoin="round"/>
  <!-- Text 'imo' inside bubble -->
  <text x="52" y="56" font-family="-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif" font-size="20" font-weight="800" fill="#0077d7" text-anchor="middle">imo</text>
  <!-- HD badge on top right -->
  <rect x="62" y="10" width="30" height="16" rx="4" fill="#059669"/>
  <text x="77" y="22" font-family="sans-serif" font-size="10" font-weight="900" fill="#ffffff" text-anchor="middle">HD</text>
</svg>
SVG;
file_put_contents($appsDir . '/imo_hd.svg', $imoSvg);

// 2. WhatsApp
$waSvg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" width="100%" height="100%">
  <rect width="100" height="100" rx="22" fill="#25D366"/>
  <path d="M 28 72 L 32 60 C 29 55 27 49 27 43 C 27 29 38 18 52 18 C 66 18 77 29 77 43 C 77 57 66 68 52 68 C 46 68 41 66 36 63 Z" fill="#ffffff"/>
  <path d="M 43 34 C 42 32 41 32 40 32 C 39 32 38 32 37 34 C 35 36 32 39 32 44 C 32 49 36 54 37 55 C 38 56 46 68 59 71 C 70 74 72 71 74 69 C 76 67 78 61 78 59 C 78 58 77 57 76 56 L 68 52 C 67 51 66 51 65 52 L 61 57 C 60 58 59 58 58 57 C 55 55 49 51 45 45 C 44 44 44 43 45 42 L 48 38 C 49 37 49 36 49 35 L 45 32 C 44 32 44 32 43 34 Z" fill="#25D366"/>
</svg>
SVG;
file_put_contents($appsDir . '/whatsapp.svg', $waSvg);

// 3. Telegram
$tgSvg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" width="100%" height="100%">
  <rect width="100" height="100" rx="22" fill="#229ED9"/>
  <path d="M 22 48 L 74 27 C 77 26 79 28 78 31 L 70 72 C 69 75 66 76 63 74 L 50 63 L 44 69 C 43 70 42 71 41 71 L 42 58 L 65 37 C 66 36 65 35 64 36 L 36 54 L 23 50 C 20 49 20 47 22 48 Z" fill="#ffffff"/>
</svg>
SVG;
file_put_contents($appsDir . '/telegram.svg', $tgSvg);

// 4. Messenger
$fbSvg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" width="100%" height="100%">
  <defs>
    <linearGradient id="msnGrad" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#00C6FF"/>
      <stop offset="50%" stop-color="#0078FF"/>
      <stop offset="100%" stop-color="#A033FF"/>
    </linearGradient>
  </defs>
  <rect width="100" height="100" rx="22" fill="url(#msnGrad)"/>
  <path d="M 20 48 C 20 32 33 20 50 20 C 67 20 80 32 80 48 C 80 63 67 76 50 76 C 45 76 40 75 36 72 L 22 76 L 26 64 C 22 59 20 54 20 48 Z" fill="#ffffff"/>
  <path d="M 33 55 L 45 42 L 53 50 L 67 42 L 55 55 L 47 47 Z" fill="url(#msnGrad)"/>
</svg>
SVG;
file_put_contents($appsDir . '/messenger.svg', $fbSvg);

// 5. Discord
$dcSvg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" width="100%" height="100%">
  <rect width="100" height="100" rx="22" fill="#5865F2"/>
  <path d="M 72 32 C 67 30 62 28 57 28 L 56 30 C 62 32 65 34 68 37 C 59 32 49 30 39 32 C 34 33 30 35 27 37 C 30 34 34 32 39 30 L 38 28 C 33 28 28 30 23 32 C 14 46 16 60 17 73 C 23 77 30 79 37 79 L 40 74 C 35 72 31 70 27 66 C 30 68 33 70 37 71 C 45 74 54 74 62 71 C 66 70 69 68 72 66 C 68 70 64 72 59 74 L 62 79 C 69 79 76 77 82 73 C 84 57 80 44 72 32 Z M 37 60 C 33 60 30 56 30 52 C 30 48 33 44 37 44 C 41 44 44 48 44 52 C 44 56 41 60 37 60 Z M 62 60 C 58 60 55 56 55 52 C 55 48 58 44 62 44 C 66 44 69 48 69 52 C 69 56 66 60 62 60 Z" fill="#ffffff"/>
</svg>
SVG;
file_put_contents($appsDir . '/discord.svg', $dcSvg);

// 6. Free Fire
$ffSvg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" width="100%" height="100%">
  <defs>
    <linearGradient id="ffGrad" x1="0%" y1="0%" x2="0%" y2="100%">
      <stop offset="0%" stop-color="#ff7b00"/>
      <stop offset="100%" stop-color="#b91c1c"/>
    </linearGradient>
  </defs>
  <rect width="100" height="100" rx="22" fill="#18181b"/>
  <circle cx="50" cy="50" r="38" fill="url(#ffGrad)"/>
  <path d="M 50 18 C 50 18 64 36 56 52 C 56 52 64 45 66 41 C 66 41 72 54 62 68 C 53 79 38 74 38 60 C 38 48 45 42 45 42 C 45 42 41 46 41 53 C 35 46 44 32 50 18 Z" fill="#fef08a"/>
  <text x="50" y="88" font-family="sans-serif" font-size="11" font-weight="900" fill="#f97316" text-anchor="middle">FREE FIRE</text>
</svg>
SVG;
file_put_contents($appsDir . '/freefire.svg', $ffSvg);

echo "App icons generated!\n";
