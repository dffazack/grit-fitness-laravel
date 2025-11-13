/// <reference types="cypress" />

describe('Admin CRUD - Facilities', () => {
  
  beforeEach(() => {
    // Log in as an admin before each test
    cy.visit('/admin/login');
    cy.get('input[name="email"]').type('admin@gritfitness.com');
    cy.get('input[name="password"]').type('admin123');
    cy.get('button[type="submit"]').click();
    cy.url().should('include', '/admin/dashboard');
  });

  it('should perform a full CRUD cycle on facilities', () => {
    const facilityName = `Cypress Facility ${Date.now()}`;
    const facilityDesc = 'Treadmill, Dumbbells, Bench Press';
    const updatedDesc = 'Treadmill, Dumbbells, Bench Press, Cable Machine';

    // 1. READ: Navigate to the facilities page
    cy.visit('/admin/facilities');
    cy.contains('h3', 'Kelola Fasilitas Gym').should('be.visible');

    // 2. CREATE: Add a new facility
    cy.contains('button', 'Tambah Fasilitas').click();
    
    cy.get('#addFacilityModal').should('be.visible').within(() => {
      cy.get('input[name="name"]').type(facilityName);
      cy.get('textarea[name="description"]').type(facilityDesc);
      // Note: File upload is not tested here as it requires a different approach (e.g., using cypress-file-upload plugin)
      cy.get('button[type="submit"]').contains('Simpan Fasilitas').click();
    });

    // Assert the new facility card is visible
    cy.contains('.alert', 'Fasilitas berhasil ditambahkan').should('be.visible');
    cy.contains('h4', facilityName).should('be.visible')
      .parents('.card')
      .as('facilityCard');

    cy.get('@facilityCard').contains(facilityDesc.split(',')[0]).should('be.visible');

    // 3. UPDATE: Edit the new facility
    cy.get('@facilityCard').within(() => {
      cy.contains('button', 'Edit').click();
    });

    cy.get('#editFacilityModal').should('be.visible').within(() => {
      cy.get('textarea[name="description"]').clear().type(updatedDesc);
      cy.get('button[type="submit"]').contains('Update Fasilitas').click();
    });

    // Assert the description is updated on the card
    cy.contains('.alert', 'Fasilitas berhasil diperbarui').should('be.visible');
    cy.get('@facilityCard').contains(updatedDesc.split(',')[3]).should('be.visible');

    // 4. DELETE: Remove the facility
    cy.get('@facilityCard').within(() => {
      cy.get('form[action*="/admin/facilities/"]').submit();
    });
    // Cypress handles the confirm() dialog automatically

    // Assert the facility card is gone and success message is shown
    cy.contains('.alert', 'Fasilitas berhasil dihapus').should('be.visible');
    cy.contains('h4', facilityName).should('not.exist');
  });

});
