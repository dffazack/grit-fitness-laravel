/// <reference types="cypress" />

describe("Admin Membership Package Management", () => {
    beforeEach(() => {
        // Log in as an admin before each test
        cy.adminLogin("admin@gritfitness.com", "admin123");
        // Visit the memberships page
        cy.visit("/admin/memberships");
    });

    it("should create a new membership package using the modal", () => {
        const packageName = "Cypress Test P";
        const packagePrice = "500000";
        const packageDuration = "3";
        const packageFeature = "Access to all Cypress tests";

        // 1. Open the "Add Package" modal
        cy.get('button[data-bs-target="#addPackageModal"]').click();
        cy.get("#addPackageModal").should("be.visible");

        // 2. Fill out the form inside the modal
        cy.get("#addPackageModal").within(() => {
            cy.get("#add_name").type(packageName);
            cy.get("#add_price").type(packagePrice);
            cy.get("#add_duration_months").type(packageDuration);

            // Handle the dynamic feature input
            cy.get('input[name="features[]"]').first().type(packageFeature);

            // Submit the form
            cy.get('button[type="submit"]').contains("Simpan Paket").click();
        });

        // 3. Verify the creation
        cy.get("#addPackageModal").should("not.be.visible");

        // Check for the success message
        // Note: The exact text might vary. Adjust if necessary.
        cy.contains("Paket membership berhasil ditambahkan").should(
            "be.visible"
        );

        // Verify the new package card is displayed on the page
        cy.contains("h4", packageName).should("be.visible");
        cy.contains("h2", "Rp 500.000").should("be.visible"); // Assuming the price is formatted this way
        cy.contains("li", packageFeature).should("be.visible");
    });
});
