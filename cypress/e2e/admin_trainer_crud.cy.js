/// <reference types="cypress" />

describe('Admin CRUD - Trainers', () => {
  
  beforeEach(() => {
    // Log in as an admin before each test
    cy.visit('/admin/login');
    cy.get('input[name="email"]').type('admin@gritfitness.com');
    cy.get('input[name="password"]').type('admin123');
    cy.get('button[type="submit"]').click();
    cy.url().should('include', '/admin/dashboard');
  });

  it('should perform a full CRUD cycle on trainers', () => {
    const trainerName = `Cypress Trainer ${Date.now()}`;
    const trainerEmail = `trainer_${Date.now()}@test.com`;
    const specialization = 'Cypress Testing';
    const updatedSpecialization = 'Advanced Cypress';

    // 1. READ: Navigate to the trainers page
    cy.visit('/admin/trainers');
    cy.contains('h3', 'Data Trainer').should('be.visible');

    // 2. CREATE: Add a new trainer
    cy.contains('button', 'Tambah Trainer').click();
    
    cy.get('#addTrainerModal').should('be.visible').within(() => {
      cy.get('input[name="name"]').type(trainerName);
      cy.get('input[name="specialization"]').type(specialization);
      cy.get('input[name="email"]').type(trainerEmail);
      cy.get('input[name="experience"]').type('5');
      // Note: File upload is not tested
      cy.get('button[type="submit"]').contains('Simpan Trainer').click();
    });

    // Assert the new trainer card is visible
    cy.contains('.alert', 'Trainer berhasil ditambahkan').should('be.visible');
    cy.contains('h5', trainerName).should('be.visible')
      .parents('.card')
      .as('trainerCard');

    cy.get('@trainerCard').contains(specialization).should('be.visible');

    // 3. UPDATE: Edit the new trainer
    cy.get('@trainerCard').within(() => {
      cy.contains('button', 'Edit').click();
    });

    cy.get('.modal.show').should('be.visible').within(() => {
      cy.get('input[name="specialization"]').clear().type(updatedSpecialization);
      cy.get('button[type="submit"]').contains('Update Trainer').click();
    });

    // Assert the specialization is updated on the card
    cy.contains('.alert', 'Trainer berhasil diperbarui').should('be.visible');
    cy.get('@trainerCard').contains(updatedSpecialization).should('be.visible');

    // 4. DELETE: Remove the trainer
    cy.get('@trainerCard').within(() => {
      cy.get('form[action*="/admin/trainers/"]').submit();
    });
    // Cypress handles the confirm() dialog automatically

    // Assert the trainer card is gone and success message is shown
    cy.contains('.alert', 'Trainer berhasil dihapus').should('be.visible');
    cy.contains('h5', trainerName).should('not.exist');
  });

});
