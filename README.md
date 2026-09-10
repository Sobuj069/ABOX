# ABox - AI Real-time Voice Changer (Laravel 11 Backend & Web Audio DSP)

A modern, full-stack AI Voice Changer web platform inspired by the **ABox** mobile application. Built with **Laravel 11**, MySQL, Web Audio API DSP, and a responsive Cyberpunk dark theme.

![ABox Preview](public/images/voices/ai_male4.svg)

---

## 🌟 Key Features

1. **Pixel-Perfect Frontend (Mobile & Desktop View)**:
   - Status bar simulation (`4:45`, signal, battery).
   - ABox neon logo & VIP diamond badge with credit counter.
   - **Voice Preview Grid**: Includes `AI Girl2`, `AI Male4` (Selected state with green checkmark and Apply button), `AI Female5`, `AI Male2`, `AI Female2` (VIP), `AI Girl` (VIP), `Robot X`, `Deep Bass`, etc.
   - Built-in audio player with waveform animations.
   - **My Apps Section**: **imo HD** (with authentic green HD badge), plus **Voice Change Guide** interactive modal and **Select Voice-Changing APP** selector.
   - **View Mode Switcher**: Toggle between smartphone simulator view and wide desktop view.

2. **Voice Studio & Audio Recording**:
   - Live microphone audio recording with HTML5 Canvas waveform visualizer.
   - Drag & Drop audio file upload (.wav, .mp3, .m4a).
   - Real-time Web Audio API Pitch Transposition & DSP modulator matching selected AI voice profiles.
   - Side-by-side comparison: Original microphone vs AI Voice.
   - Instant WAV download & save to database library.

3. **Complete Administrative Control Panel (`/admin`)**:
   - **Dashboard**: Key metrics (users, active voice models, recordings, app connections).
   - **AI Voice Management (CRUD)**: Create, edit, and delete AI voices with avatar and preview audio uploads.
   - **Target Apps**: Add and manage supported calling and gaming apps.
   - **User Management**: Adjust credit balances, assign VIP subscriptions.
   - **Recordings Auditor**: Review and listen to user-generated recordings.
   - **Site Settings**: Customize site name, tagline, and guide steps.

---

## 🛠️ Installation & Setup

### Prerequisites
- PHP 8.2 or 8.3+ (with `pdo_mysql`, `fileinfo`, `gd`, `curl`, `mbstring`)
- Composer 2.x
- MySQL / MariaDB (e.g. Laragon)

### Setup Steps
1. Clone the repository:
   ```bash
   git clone https://github.com/Sobuj069/ABOX.git
   cd ABOX
   ```

2. Install PHP dependencies:
   ```bash
   composer install
   ```

3. Configure environment file:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   Set your MySQL credentials in `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=abox
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. Create storage link:
   ```bash
   php artisan storage:link
   ```

5. Run database migrations & seeders:
   ```bash
   php artisan migrate:fresh --seed
   ```

6. Generate assets (avatars & audio previews):
   ```bash
   php database/seeders/AssetGenerator.php
   ```

7. Start the local server:
   ```bash
   php artisan serve
   ```
   Open [http://127.0.0.1:8000](http://127.0.0.1:8000) in your browser.

---

## 🔑 Default Accounts

- **Administrator**:
  - Email: `admin@abox.com`
  - Password: `password123`
  - Access: [http://127.0.0.1:8000/admin](http://127.0.0.1:8000/admin)

- **Demo User**:
  - Email: `user@abox.com`
  - Password: `password123`
  - Credits: 120 (Standard tier)

---

## 🧪 Automated Tests

Run the test suite:
```bash
php artisan test
```
All 11 feature tests covering authentication, voice switching, VIP guards, app hooks, recording uploads, and admin permissions pass with 100% success.
