/// <reference types="cypress" />

describe('Admin Payment Validation', () => {
  
  beforeEach(() => {
    // Log in as an admin before each test
    cy.visit('/admin/login');
    cy.get('input[name="email"]').type('admin@gritfitness.com');
    cy.get('input[name="password"]').type('admin123');
    cy.get('button[type="submit"]').click();
    cy.url().should('include', '/admin/dashboard');
  });

  /**
   * NOTE: This test assumes a transaction from 'member@example.com' with a 'pending' status exists.
   * For a more robust test, programmatically create this transaction before the test runs.
   */
  it('should allow an admin to approve a payment via the modal workflow', () => {
    const testUserEmail = 'member@example.com';

    // 1. Navigate to the payments page
    // Assuming the link is in a layout, let's visit the URL directly for stability.
    cy.visit('/admin/payments');
    cy.url().should('include', '/admin/payments');
    cy.contains('h5', 'Validasi Pembayaran').should('be.visible');

    // 2. Find the pending transaction row for our test user
    cy.contains('td', testUserEmail)
      .parent('tr')
      .as('paymentRow');

    // 3. Within that row, find and click the "Validasi" button to open the modal
    cy.get('@paymentRow').within(() => {
      cy.contains('button', 'Validasi').click();
    });

    // 4. The modal should now be open. Find the "Setujui" (Approve) button within the modal and click it.
    // We need to find the correct modal. The modal ID is dynamic (e.g., #validateModal-123).
    // A safer way is to find the modal that is currently visible.
    cy.get('.modal.show').should('be.visible').within(() => {
      // The form submission handles the click. We find the correct form and submit it.
      cy.get('form[action*="/approve"]').within(() => {
        cy.contains('button', 'Setujui').click();
      });
    });

    // Cypress handles the confirm dialog automatically.

    // 5. Assert that the row is now updated or gone.
    // A simple assertion is to check for the success message from the backend.
    // Let's assume the backend redirects with a message.
    cy.contains('.alert', 'Pembayaran telah disetujui').should('be.visible');

    // Optional: Verify the row is no longer in a pending state.
    // This depends on whether the approved item stays on the page or not.
    // For now, we'll assume the success message is sufficient.
  });

});