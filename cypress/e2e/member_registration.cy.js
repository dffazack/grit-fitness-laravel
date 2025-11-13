/// <reference types="cypress" />

describe('Member Registration', () => {
  it('should allow a new user to register and be redirected to the member dashboard', () => {
    // Generate a unique email for each test run to avoid conflicts
    const uniqueEmail = `testuser_${Date.now()}@example.com`;

    // 1. Visit the registration page
    cy.visit('/register');

    // 2. Fill out the registration form
    cy.get('input[name="name"]').type('Test User');
    cy.get('input[name="email"]').type(uniqueEmail);
    cy.get('input[name="phone"]').type('081234567890'); // Ensure phone number is filled
    cy.get('input[name="password"]').type('password123');
    cy.get('input[name="password_confirmation"]').type('password123');

    // 3. Submit the form
    cy.get('button[type="submit"]').click();

    // 4. Verify that the URL is the member dashboard
    cy.url().should('include', '/member/dashboard');

    // 5. Verify that the page contains a message to become a member
    cy.contains('h4', 'Akses Member Diperlukan').should('be.visible');
  });
});