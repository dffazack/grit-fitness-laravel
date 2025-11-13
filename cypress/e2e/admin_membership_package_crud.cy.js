/// <reference types="cypress" />

describe('Admin CRUD - Membership Packages', () => {
  
  beforeEach(() => {
    // Log in as an admin before each test
    cy.visit('/admin/login');
    cy.get('input[name="email"]').type('admin@gritfitness.com');
    cy.get('input[name="password"]').type('admin123');
    cy.get('button[type="submit"]').click();
    cy.url().should('include', '/admin/dashboard');
  });

  it('should perform a full CRUD cycle on membership packages', () => {
    const packageName = `Cypress Test Pkg ${Date.now()}`;
    const packagePrice = '150000';
    const updatedPrice = '175000';
    const packageFeatures = 'Feature A, Feature B';

    // 1. READ: Navigate to the membership packages page
    cy.visit('/admin/memberships');
    cy.contains('h3', 'Kelola Paket Membership').should('be.visible');

    // 2. CREATE: Add a new package
    cy.contains('button', 'Tambah Paket').click();
    
    cy.get('#addPackageModal').should('be.visible').within(() => {
      cy.get('input[name="name"]').type(packageName);
      cy.get('select[name="type"]').select('reguler'); // Assuming 'reguler' is a valid value
      cy.get('input[name="price"]').type(packagePrice);
      cy.get('input[name="duration_months"]').type('1');
      cy.get('textarea[name="features"]').type(packageFeatures);
      cy.get('button[type="submit"]').contains('Simpan Paket').click();
    });

    // Assert the new package card is visible
    cy.contains('h4', packageName).should('be.visible')
      .parents('.card')
      .as('packageCard'); // Alias the card for later use

    cy.get('@packageCard').contains('Rp 150.000').should('be.visible');

    // 3. UPDATE: Edit the new package
    cy.get('@packageCard').within(() => {
      cy.contains('button', 'Edit').click();
    });

    cy.get('.modal.show').should('be.visible').within(() => {
      cy.get('input[name="price"]').clear().type(updatedPrice);
      cy.get('button[type="submit"]').contains('Update Paket').click();
    });

    // Assert the price is updated on the card
    cy.contains('.alert', 'Paket membership berhasil diperbarui').should('be.visible');
    cy.get('@packageCard').contains('Rp 175.000').should('be.visible');

    // 4. DELETE: Remove the package
    cy.get('@packageCard').within(() => {
      cy.get('form[action*="/admin/memberships/"]').submit();
    });
    // Cypress handles the confirm() dialog automatically

    // Assert the package card is gone and success message is shown
    cy.contains('.alert', 'Paket membership berhasil dihapus').should('be.visible');
    cy.contains('h4', packageName).should('not.exist');
  });

});
