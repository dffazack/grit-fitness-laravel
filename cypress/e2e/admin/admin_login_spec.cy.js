/// <reference types="cypress" />

describe("Admin Login and Dashboard Test", () => {
    beforeEach(() => {
        // This block now simply calls the custom login command.
        // The command itself handles visiting the page, logging in, and verifying the redirect.
        const adminEmail = "admin@gritfitness.com";
        const adminPassword = "admin123";
        cy.adminLogin(adminEmail, adminPassword);
    });

    it("Should be on the admin dashboard after login", () => {
        // After login, we just verify that we are on the right page
        // and key elements are visible.
        cy.contains("Revenue Bulan Ini").should("be.visible");
        cy.contains("p", "Total Member").should("be.visible");
    });

    it("Should show the list of members", () => {
        // Navigate to the members management page
        cy.visit("/admin/members");

        // Assert URL
        cy.url().should("include", "/admin/members");

        // Check for the page title
        cy.contains("h5", "Manajemen Member").should("be.visible");
        // Note: Adjust selector and text based on your `admin/members/index.blade.php`.
    });
});
