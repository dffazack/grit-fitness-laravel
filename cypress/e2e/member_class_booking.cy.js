/// <reference types="cypress" />

describe('Member Class Booking Pre-conditions', () => {
  
  beforeEach(() => {
    // Log in as a member before each test
    cy.visit('/login');
    cy.get('input[name="email"]').type('member@gritfitness.com');
    cy.get('input[name="password"]').type('password');
    cy.get('button[type="submit"]').click();
    cy.url().should('include', '/member/dashboard'); // Ensure login is successful
  });

  it('should navigate to class schedule and find an available class', () => {
    // 1. Navigate to the class schedule page
    cy.visit('/classes');
    cy.url().should('include', '/classes');
    cy.contains('h1', 'Jadwal Kelas & Trainer').should('be.visible');

    // 2. Find an available class (a "Booking Kelas" button that is not disabled)
    // This assumes there is at least one class with available slots.
    cy.contains('button', 'Booking Kelas')
      .not('[disabled]')
      .first()
      .should('be.visible')
      .as('availableBookingButton'); // Alias the button for potential future use

    // Assert that the button is indeed visible and enabled
    cy.get('@availableBookingButton').should('not.be.disabled');
  });

});
