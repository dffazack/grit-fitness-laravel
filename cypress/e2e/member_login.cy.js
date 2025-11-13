/// <reference types="cypress" />

describe('Member Authentication', () => {
  
  beforeEach(() => {
    // Ensure we are logged out before each test
    // We can do this by visiting the logout route if it's a GET,
    // but since it's a POST, we'll just clear cookies.
    cy.clearCookies();
    cy.visit('/login');
  });

  it('should allow a member to log in and see their dashboard', () => {
    // 1. Use credentials from the seeder
    cy.get('input[name="email"]').type('member@gritfitness.com');
    cy.get('input[name="password"]').type('password');

    // 2. Click the login button
    cy.get('button[type="submit"]').click();

    // 3. Verify redirection to the member dashboard
    cy.url().should('include', '/member/dashboard');

    // 4. Verify the dashboard contains the member's name
    cy.contains('p', 'Selamat datang kembali, John Doe').should('be.visible');
  });

  it('should allow a logged-in member to log out', () => {
    // First, log in the user
    cy.get('input[name="email"]').type('member@gritfitness.com');
    cy.get('input[name="password"]').type('password');
    cy.get('button[type="submit"]').click();

    // Ensure we are on the dashboard
    cy.url().should('include', '/member/dashboard');

    // 1. Find the profile dropdown, click it, then find and click the "Keluar" (Logout) button.
    cy.get('#profileDropdown').click();
    cy.contains('button', 'Keluar').click();

    // 2. Verify the user is redirected to the homepage
    cy.url().should('eq', Cypress.config().baseUrl + '/');

    // 3. Verify that a guest-only element (like the Login button) is visible again.
    cy.contains('a', 'Login').should('be.visible');
  });

});
