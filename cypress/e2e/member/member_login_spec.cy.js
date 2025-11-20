/// <reference types="cypress" />

describe("Member Login and Dashboard Test", () => {
    beforeEach(() => {
        // IMPORTANT: Replace with valid credentials for a test 'member' user
        // from your database seeder.
        const memberEmail = "member@gritfitness.com";
        const memberPassword = "password";

        // Perform login using the custom command
        cy.login(memberEmail, memberPassword);

        // Visit the member dashboard
        cy.visit("/member/dashboard");
    });

    it("Should successfully visit the member dashboard after login", () => {
        // Assert that the URL is correct
        cy.url().should("include", "/member/dashboard");

        // Assert that a welcome message or a key element is visible
        // Based on the routes, we expect a dashboard for members.
        // Let's check for a heading specific to the member area.
        cy.contains("h1", "Dashboard Saya").should("be.visible");
        // Note: The selector 'h2' and text 'My Dashboard' are assumptions.
        // Please adjust it based on your actual view (e.g., resources/views/member/dashboard.blade.php).
    });

    it("Should be able to access member profile page", () => {
        // From the dashboard, navigate to the profile page
        cy.visit("/member/profile");

        // Assert that the URL is correct
        cy.url().should("include", "/member/profile");

        // Check for a heading on the profile page
        cy.contains("h1", "Kelola Profil").should("be.visible");
        // Note: Adjust selector and text based on your actual profile view.
    });
});
