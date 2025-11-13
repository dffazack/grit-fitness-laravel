import { mount } from 'cypress/react'; // Assuming a React-like mounting, will adjust if needed for Blade
import { renderBlade } from '../support/blade'; // Assuming a custom helper for Blade

describe('FormInput Blade Component', () => {

  it('renders a basic text input with a label', () => {
    const component = `<x-form-input label="Full Name" name="full_name" type="text" />`;
    
    renderBlade(component).then(() => {
      cy.get('label').should('contain.text', 'Full Name');
      cy.get('input[name="full_name"]').should('have.attr', 'type', 'text');
    });
  });

  it('renders with a pre-filled value', () => {
    const component = `<x-form-input label="Email" name="email" type="email" value="test@example.com" />`;
    
    renderBlade(component).then(() => {
      cy.get('input[name="email"]').should('have.value', 'test@example.com');
    });
  });

  it('applies the required attribute', () => {
    const component = `<x-form-input label="Password" name="password" type="password" required />`;
    
    renderBlade(component).then(() => {
      cy.get('input[name="password"]').should('have.attr', 'required');
    });
  });

  it('renders a placeholder when provided', () => {
    const component = `<x-form-input label="Search" name="search" placeholder="Enter search term..." />`;
    
    renderBlade(component).then(() => {
      cy.get('input[name="search"]').should('have.attr', 'placeholder', 'Enter search term...');
    });
  });

  it('shows an error message when passed', () => {
    // This test assumes the component has logic to display an error.
    // We simulate this by adding an 'error' prop.
    const component = `<x-form-input label="Age" name="age" error="Age must be a number." />`;
    
    renderBlade(component).then(() => {
      cy.get('.invalid-feedback').should('be.visible').and('contain.text', 'Age must be a number.');
      cy.get('input[name="age"]').should('have.class', 'is-invalid');
    });
  });

});
