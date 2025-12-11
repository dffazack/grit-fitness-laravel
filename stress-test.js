import http from 'k6/http';
import { check, sleep } from 'k6';

export const options = {
  // Skenario Lebih Berat
  stages: [
    { duration: '30s', target: 50 },  // Naik ke 50 user dalam 30 detik
    { duration: '1m', target: 100 },  // Naik lagi ke 100 user!
    { duration: '30s', target: 100 }, // Tahan 100 user selama 30 detik
    { duration: '30s', target: 0 },   // Turun
  ],
  // Kita set batas toleransi: Jika 5% request gagal, tes dianggap gagal
  thresholds: {
    http_req_failed: ['rate<0.05'], 
  },
};

export default function () {
  const BASE_URL = 'http://127.0.0.1:8000'; // Pastikan artisan serve jalan

  // Hit Homepage
  let res = http.get(BASE_URL);
  check(res, { 'Homepage 200': (r) => r.status == 200 });

  // Hit Membership (biasanya ada query database)
  res = http.get(`${BASE_URL}/membership`);
  check(res, { 'Membership 200': (r) => r.status == 200 });

  sleep(1);
}