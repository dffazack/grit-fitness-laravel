// @ts-check
const { test, expect } = require('@playwright/test');

test('Login ke Website Live Grit Fitness', async ({ page }) => {
  
  // TRIK: Menyamar jadi Browser Manusia Asli (Chrome Windows)
  await page.setExtraHTTPHeaders({
    'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36'
  });

  // 1. Buka Halaman Login
  // Ganti dengan domain InfinityFree kamu
  await page.goto('http://grit-fitness.infinityfreeapp.com/login');

  // Tunggu sebentar (biar security check InfinityFree lewat dulu)
  await page.waitForTimeout(3000); 

  // Debugging: Kita screenshot dulu buat lihat apakah kena blokir atau tidak
  await page.screenshot({ path: 'debug-step-1.png' });

  // 2. Isi Form
  // Playwright pakai 'fill' untuk mengetik
  await page.fill('input[name="email"]', 'males@gmail.com'); // Ganti email valid
  await page.fill('input[name="password"]', 'password');   // Ganti password valid

  // 3. Klik Login
  await page.click('button[type="submit"]');

  // Tunggu loading (agak lama buat server gratisan)
  await page.waitForTimeout(5000);

  // 4. Verifikasi Berhasil
  // Cek apakah URL bukan lagi halaman login
  await expect(page).not.toHaveURL(/login/);
  
  // Screenshot hasil akhir
  await page.screenshot({ path: 'bukti-sukses-login.png' });
});