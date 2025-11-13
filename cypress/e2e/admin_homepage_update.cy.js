/// <reference types="cypress" />

describe('Admin - Homepage Content Management', () => {
  
  beforeEach(() => {
    // Log in as an admin before each test
    cy.visit('/admin/login');
    cy.get('input[name="email"]').type('admin@gritfitness.com');
    cy.get('input[name="password"]').type('admin123');
    cy.get('button[type="submit"]').click();
    cy.url().should('include', '/admin/dashboard');
  });

  it('should allow updating the Hero Section content', () => {
    const newHeroTitle = `New Title ${Date.now()}`;

    // 1. Navigate to the homepage edit page
    cy.visit('/admin/homepage/edit');
    cy.contains('h5', 'Edit Hero Section').should('be.visible');

    // 2. The "Hero" tab is active by default. Find the title input, clear it, and type a new title.
    cy.get('input[name="title"]').clear().type(newHeroTitle);

    // 3. Submit the form for the Hero Section
    cy.get('form[action*="/admin/homepage/hero"]').within(() => {
      cy.get('button[type="submit"]').click();
    });

    // 4. Assert that a success message is shown
    cy.contains('.alert', 'Konten homepage berhasil diperbarui').should('be.visible');

    // 5. Reload the page and verify the change persists
    cy.reload();
    cy.get('input[name="title"]').should('have.value', newHeroTitle);
  });

  it('should allow updating the Statistics Section content', () => {
    const newStatValue = Math.floor(Math.random() * 1000).toString();

    // 1. Navigate to the homepage edit page and switch to the stats tab
    cy.visit('/admin/homepage/edit');
    cy.contains('button', 'Statistics').click();
    cy.contains('h5', 'Edit Statistics Section').should('be.visible');

    // 2. Find the first stat's value input, clear it, and type a new value
    cy.get('input[name="stats[0][value]"]').clear().type(newStatValue);

    // 3. Submit the form for the Statistics Section
    cy.get('form[action*="/admin/homepage/stats"]').within(() => {
      cy.get('button[type="submit"]').click();
    });

    // 4. Assert that a success message is shown
    cy.contains('.alert', 'Konten homepage berhasil diperbarui').should('be.visible');

    // 5. Verify the change persists after switching tabs and back
    cy.contains('button', 'Hero Section').click();
    cy.contains('button', 'Statistics').click();
    cy.get('input[name="stats[0][value]"]').should('have.value', newStatValue);
  });

});
