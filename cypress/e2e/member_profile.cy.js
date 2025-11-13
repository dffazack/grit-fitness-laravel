/// <reference types="cypress" />

describe('Member Profile Management', () => {
  
  beforeEach(() => {
    // Log in as the test member before each test
    cy.visit('/login');
    cy.get('input[name="email"]').type('member@gritfitness.com');
    cy.get('input[name="password"]').type('password');
    cy.get('button[type="submit"]').click();
    cy.url().should('include', '/member/dashboard');
  });

  it('should allow a member to view and update their profile information', () => {
    // 1. Navigate to the profile page via the dropdown menu
    cy.get('#profileDropdown').click();
    cy.contains('a', 'Profil Saya').click();
    cy.url().should('include', '/member/profile');

    // 2. Verify current data is displayed
    cy.get('input[name="name"]').should('have.value', 'John Doe');
    cy.get('input[name="email"]').should('have.value', 'member@gritfitness.com');

    // 3. Update profile information
    const newName = 'Johnathan Doe';
    const newPhone = '+62 812 1111 3333';
    cy.get('input[name="name"]').clear().type(newName);
    cy.get('input[name="phone"]').clear().type(newPhone);

    // 4. Submit the form
    // Assuming the update button is the main submit button on the page
    cy.get('button[type="submit"]').contains('Update').click();

    // 5. Assert that a success message is shown
    // This depends on the implementation. Let's assume a div with class 'alert-success' appears.
    cy.contains('.alert-success', 'Profile updated successfully').should('be.visible');

    // 6. Assert that the updated information is displayed in the form
    cy.get('input[name="name"]').should('have.value', newName);
    cy.get('input[name="phone"]').should('have.value', newPhone);

    // 7. Reload the page and verify the changes persist
    cy.reload();
    cy.get('input[name="name"]').should('have.value', newName);
    cy.get('input[name="phone"]').should('have.value', newPhone);

    // -- Cleanup --
    // Revert the name back to the original for subsequent test runs
    cy.get('input[name="name"]').clear().type('John Doe');
    cy.get('button[type="submit"]').contains('Update').click();
    cy.contains('.alert-success', 'Profile updated successfully').should('be.visible');
  });

});
