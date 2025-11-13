import { renderBlade } from '../support/component';

describe('DataMasterTabs Blade Component', () => {

  beforeEach(() => {
    // Mount the component before each test
    const component = `<x-admin.components.datamaster-tabs />`;
    cy.renderBlade(component);
  });

  it('renders all the navigation tabs', () => {
    cy.contains('a', 'Paket Membership').should('be.visible');
    cy.contains('a', 'Fasilitas').should('be.visible');
    cy.contains('a', 'Trainers').should('be.visible');
    cy.contains('a', 'Homepage').should('be.visible');
    cy.contains('a', 'Notifikasi').should('be.visible');
  });

  it('has the correct URL for the "Paket Membership" tab', () => {
    cy.contains('a', 'Paket Membership')
      .should('have.attr', 'href')
      .and('include', '/admin/memberships');
  });

  it('has the correct URL for the "Fasilitas" tab', () => {
    cy.contains('a', 'Fasilitas')
      .should('have.attr', 'href')
      .and('include', '/admin/facilities');
  });

  it('has the correct URL for the "Trainers" tab', () => {
    cy.contains('a', 'Trainers')
      .should('have.attr', 'href')
      .and('include', '/admin/trainers');
  });

  it('has the correct URL for the "Homepage" tab', () => {
    cy.contains('a', 'Homepage')
      .should('have.attr', 'href')
      .and('include', '/admin/homepage');
  });

  it('has the correct URL for the "Notifikasi" tab', () => {
    cy.contains('a', 'Notifikasi')
      .should('have.attr', 'href')
      .and('include', '/admin/notifications');
});

});
