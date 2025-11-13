/// <reference types="cypress" />

describe('Admin Authentication', () => {
  it('should allow an admin to log in and redirect to the dashboard', () => {
    // 1. Kunjungi halaman login admin
    cy.visit('/admin/login');

    // 2. Isi form login dengan kredensial yang benar
    cy.get('input[name="email"]').type('admin@gritfitness.com');
    cy.get('input[name="password"]').type('admin123');

    // 3. Klik tombol login
    cy.get('button[type="submit"]').click();

    // 4. Verifikasi bahwa URL adalah dashboard admin
    cy.url().should('include', '/admin/dashboard');

    // 5. Verifikasi bahwa halaman berisi judul "Laporan Keuangan Harian"
    cy.contains('h5', 'Laporan Keuangan Harian').should('be.visible');
  });
});
