// 'describe' adalah Judul Besar skenario tes kita
describe('Pengecekan Website Live Grit Fitness', () => {

  // 'it' adalah satu kasus tes spesifik
  it('1. Halaman utama bisa dibuka dan memuat judul', () => {
    // Perintahkan robot membuka halaman utama (baseUrl + /)
    cy.visit('/');

    // ASSERTION (Pengecekan):
    // Pastikan URL-nya benar
    cy.url().should('include', 'grit-fitness.infinityfreeapp.com'); // Sesuaikan domainmu
    
    // Pastikan ada teks "Grit Fitness" atau judul hero section kamu
    // Ganti teks ini sesuai apa yang tampil di halaman utamamu
    cy.contains('Grit Fitness').should('be.visible'); 
  });

  it('2. Halaman Login menampilkan form yang benar', () => {
    // Robot pergi ke /login
    cy.visit('/login');

    // Cek apakah kolom input email ada?
    cy.get('input[name="email"]')
      .should('be.visible')
      .and('have.attr', 'type', 'email'); // Pastikan tipenya email

    // Cek apakah kolom password ada?
    cy.get('input[name="password"]')
      .should('be.visible');

    // Cek tombol login
    cy.get('button[type="submit"]')
      .should('contain', 'Masuk');
  });

  // Tes Login (HANYA JALANKAN INI JIKA KAMU SUDAH BUAT AKUN TESTER DI WEBSITE)
  it('3. Simulasi Login User', () => {
    cy.visit('/login');

    cy.wait(2000);

    // Robot mengetik email (Ganti dengan akun tester kamu)
    cy.get('input[name="email"]').type('males@gmail.com');

    // Robot mengetik password
    cy.get('input[name="password"]').type('password');

    cy.wait(1000);

    // Robot klik tombol submit
    cy.get('button[type="submit"]').click();

    cy.wait(5000);

    // Robot menunggu halaman berpindah
    // Pastikan URL berubah (misal masuk ke /membership atau /home)
    cy.url().should('not.include', '/login');
    
    // Pastikan ada elemen navbar user (misal nama user atau tombol logout)
    // Sesuaikan dengan tampilan websitemu
    cy.get('nav').should('contain', 'Logout'); 
  });

});