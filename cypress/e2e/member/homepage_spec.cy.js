/// <reference types="cypress" />

describe("Homepage Test", () => {
    it("Successfully loads the homepage", () => {
        // Visit the homepage
        cy.visit("/");

        // Assert that a key element is visible
        // Based on the project structure, we can assume a hero section exists.
        // Let's look for a heading with text like "Welcome" or the site name.
        // We will use a flexible selector to make the test less brittle.
        cy.get("h1").should("be.visible");

        // You can also add assertions for other elements
        cy.contains("Transform Your Body, Transform Your Life").should(
            "be.visible"
        );
    });
});
