/// <reference types="cypress" />

describe('Admin Member Management', () => {
  
  beforeEach(() => {
    // Log in as an admin before each test
    cy.visit('/admin/login');
    cy.get('input[name="email"]').type('admin@gritfitness.com');
    cy.get('input[name="password"]').type('admin123');
    cy.get('button[type="submit"]').click();
    cy.url().should('include', '/admin/dashboard');
  });

  /**
   * NOTE: This test assumes a user with the email 'member@example.com' exists in the database.
   * For a more robust test, you should programmatically create a user before this test runs,
   * for example, by using cy.exec() to run an artisan command or by using cy.request() to an API endpoint.
   */
  it('should display the members list and allow deleting a member', () => {
    const testUserEmail = 'member@example.com';

    // 1. READ: Navigate to members list and verify the page title
    cy.visit('/admin/members');
    cy.contains('h5', 'Manajemen Member').should('be.visible');

    // 2. Find the test user in the list
    cy.contains('td', testUserEmail)
      .should('be.visible')
      .parent('tr')
      .as('userRow'); // Save a reference to the row

    // 3. DELETE: Click the delete button within that user's row
    // The view uses a form for deletion, so we find the form within the row and submit it.
    cy.get('@userRow').find('form[action*="/admin/members/"]').within(() => {
      cy.get('button[type="submit"]').click();
    });

    // Cypress automatically accepts the native window.confirm() dialog.

    // 4. VERIFY: Assert the member is no longer in the list
    cy.contains('td', testUserEmail).should('not.exist');
    
    // Optional: Assert a success message if one is provided by the backend.
    // Example: cy.contains('.alert', 'Member berhasil dihapus').should('be.visible');
  });

});