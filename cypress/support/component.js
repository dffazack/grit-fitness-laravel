// cypress/support/component.js

import { mount } from 'cypress/react'; // We can remove this if not using React components

/**
 * Custom command to render a Blade component string.
 * @param {string} bladeString The Blade component string to render.
 * @example cy.renderBlade('<x-form-input name="test" />')
 */
Cypress.Commands.add('renderBlade', (bladeString) => {
  // The port is passed via environment variables by the dev-server
  const port = Cypress.env('bladeDevServerPort');
  
  return cy.request({
    method: 'POST',
    url: `http://12g7.0.0.1:${port}/render`,
    body: bladeString,
    headers: {
      'Content-Type': 'text/plain',
    },
  }).then((response) => {
    // Mount the rendered HTML into the test runner's document
    const html = response.body;
    const document = cy.state('document');
    document.open();
    document.write(html);
    document.close();
  });
});
