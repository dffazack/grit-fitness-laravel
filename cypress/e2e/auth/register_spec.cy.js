/// <reference types="cypress" />

describe("Member Registration", () => {
    it("should allow a new user to register successfully", () => {
        // Use a timestamp to generate a unique email for each test run
        const uniqueId = Date.now();
        const username = `testuser${uniqueId}`;
        const email = `testuser${uniqueId}@example.com`;
        const password = "password123";

        // 1. Visit the registration page
        cy.visit("/register");

        // 2. Fill out the registration form
        cy.get("#name").type(username);
        cy.get("#email").type(email);
        cy.get("#phone").type("08123456789");
        cy.get("#password").type(password);
        cy.get("#password_confirmation").type(password);

        // 3. Submit the form
        cy.get('button[type="submit"]').contains("Buat Akun").click();

        cy.contains(
            "p",
            "Anda tidak memiliki izin untuk mengakses halaman ini."
        ).should("be.visible");
    });
});
