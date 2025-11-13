/// <reference types="cypress" />

describe('Public Pages Accessibility', () => {

  it('should successfully load the Classes page', () => {
    cy.visit('/classes');
    // Correct heading from resources/views/classes/index.blade.php
    cy.contains('h1, h2', 'Jadwal Kelas & Trainer').should('be.visible');
  });

  it('should successfully load the Membership page', () => {
    cy.visit('/membership');
    // Correct heading from resources/views/membership/index.blade.php
    cy.contains('h1, h2', 'Paket Reguler').should('be.visible');
  });

  it('should successfully load the Trainers page', () => {
    cy.visit('/trainers');
    // Correct heading from resources/views/admin/trainers/index.blade.php (used for public trainers page)
    cy.contains('h3', 'Data Trainer').should('be.visible');
  });

});
