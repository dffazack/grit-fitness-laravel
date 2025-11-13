/// <reference types="cypress" />

describe('Admin CRUD - Notifications', () => {
  
  beforeEach(() => {
    // Log in as an admin before each test
    cy.visit('/admin/login');
    cy.get('input[name="email"]').type('admin@gritfitness.com');
    cy.get('input[name="password"]').type('admin123');
    cy.get('button[type="submit"]').click();
    cy.url().should('include', '/admin/dashboard');
  });

  it('should perform a full CRUD cycle on notifications', () => {
    const notificationTitle = `Cypress Promo ${Date.now()}`;
    const notificationContent = 'This is a test notification from Cypress.';
    const updatedContent = 'This is an updated test notification.';
    const today = new Date().toISOString().split('T')[0];
    const tomorrow = new Date(new Date().setDate(new Date().getDate() + 1)).toISOString().split('T')[0];


    // 1. READ: Navigate to the notifications page
    cy.visit('/admin/notifications');
    cy.contains('h5', 'Banner Notifikasi Homepage').should('be.visible');

    // 2. CREATE: Add a new notification
    cy.contains('button', 'Tambah Notifikasi').click();
    
    cy.get('#addNotificationModal').should('be.visible').within(() => {
      cy.get('input[name="title"]').type(notificationTitle);
      cy.get('textarea[name="message"]').type(notificationContent);
      cy.get('select[name="type"]').select('info'); // Assuming 'info' is a valid type
      cy.get('input[name="start_date"]').type(today);
      cy.get('input[name="end_date"]').type(tomorrow);
      cy.get('button[type="submit"]').contains('Tambah Notifikasi').click();
    });

    // Assert the new notification item is visible
    cy.contains('.alert', 'Notifikasi berhasil ditambahkan').should('be.visible');
    cy.contains('p', notificationTitle).should('be.visible')
      .parents('.list-group-item')
      .as('notificationItem');

    cy.get('@notificationItem').contains(notificationContent).should('be.visible');

    // 3. UPDATE: Edit the new notification
    cy.get('@notificationItem').within(() => {
      cy.contains('button', 'Edit').click();
    });

    cy.get('.modal.show').should('be.visible').within(() => {
      cy.get('textarea[name="message"]').clear().type(updatedContent);
      cy.get('button[type="submit"]').contains('Simpan Perubahan').click();
    });

    // Assert the content is updated
    cy.contains('.alert', 'Notifikasi berhasil diperbarui').should('be.visible');
    cy.get('@notificationItem').contains(updatedContent).should('be.visible');

    // 4. DELETE: Remove the notification
    cy.get('@notificationItem').within(() => {
      cy.get('form[action*="/admin/notifications/"]').submit();
    });
    // Cypress handles the confirm() dialog automatically

    // Assert the item is gone and success message is shown
    cy.contains('.alert', 'Notifikasi berhasil dihapus').should('be.visible');
    cy.contains('p', notificationTitle).should('not.exist');
  });

});
