// cypress/e2e/homepage.cy.js

describe('Homepage', () => {
  it('successfully loads and displays the main heading', () => {
    // Visit the homepage
    cy.visit('/');

    // Assert that the main heading is visible and contains the correct text
    cy.get('h1')
      .should('be.visible')
      .and('contain.text', 'Transform Your Body, Transform Your Life');
  });
});
