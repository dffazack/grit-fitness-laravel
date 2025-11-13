/// <reference types="cypress" />

describe('Admin Schedule Management', () => {
  
  beforeEach(() => {
    // Log in as an admin before each test
    cy.visit('/admin/login');
    cy.get('input[name="email"]').type('admin@gritfitness.com');
    cy.get('input[name="password"]').type('admin123');
    cy.get('button[type="submit"]').click();
    cy.url().should('include', '/admin/dashboard');
  });

  it('should allow creating, updating, and deleting a schedule via the modal workflow', () => {
    const newClassName = 'Cypress Test Class';

    // 1. READ: Navigate to schedules list
    cy.visit('/admin/schedules');
    cy.contains('h5', 'Kelola Jadwal Kelas').should('be.visible');

    // 2. CREATE: Add a new schedule using the modal
    cy.contains('button', 'Tambah Jadwal').click();
    
    // --- Fill and submit the creation form within the modal ---
    cy.get('.modal.show').should('be.visible').within(() => {
      cy.get('select[name="class_list_id"]').select('Other (Specify New Class)');
      cy.get('input[name="custom_class_name"]').type(newClassName);
      cy.get('select[name="day"]').select('Senin');
      cy.get('select[name="type"]').select('Online');
      cy.get('input[name="start_time"]').type('15:00');
      cy.get('input[name="end_time"]').type('16:00');
      
      // Assuming a trainer with ID 1 exists from the seeder
      cy.get('select[name="trainer_id"]').select(1); 
      
      cy.get('input[name="max_quota"]').type('25');
      cy.get('textarea[name="description"]').type('This is a test class created by Cypress.');
      
      cy.contains('button', 'Simpan Jadwal').click();
    });

    // Assert new schedule is in the list
    cy.contains('td', newClassName).should('be.visible');

    // 3. UPDATE: Edit the new schedule's details
    cy.contains('td', newClassName).parent('tr').within(() => {
      cy.get('button[data-bs-target*="#editScheduleModal"]').click();
    });

    // --- Fill and submit the edit form within the modal ---
    cy.get('.modal.show').should('be.visible').within(() => {
      cy.get('textarea[name="description"]').clear().type('This is an updated description.');
      cy.contains('button', 'Perbarui Jadwal').click();
    });

    // Assert the success message/redirect
    cy.url().should('include', '/admin/schedules');
    cy.contains('.alert', 'Jadwal kelas berhasil diperbarui').should('be.visible');

    // 4. DELETE: Remove the schedule
    cy.contains('td', newClassName).parent('tr').within(() => {
      // Find the form associated with deletion and submit it
      cy.get('form[action*="/admin/schedules/"]').submit();
    });
    // Cypress handles the confirm() dialog automatically

    // Assert the schedule is no longer in the list
    cy.contains('td', newClassName).should('not.exist');
    cy.contains('.alert', 'Jadwal kelas berhasil dihapus').should('be.visible');
  });

});