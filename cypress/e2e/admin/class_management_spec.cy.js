/// <reference types="cypress" />

describe("Admin Class Management (Schedules)", () => {
    beforeEach(() => {
        // This block now simply calls the custom login command.
        // The command itself handles visiting the page, logging in, and verifying the redirect.
        const adminEmail = "admin@gritfitness.com";
        const adminPassword = "admin123";
        cy.adminLogin(adminEmail, adminPassword);
    });

    it("Should create, edit, and verify a new class schedule using modals", () => {
        const newClassName = "Cypress Yoga Test";
        const editedClassName = "Cypress Power Yoga Test";

        // --- 0. Visit the page ---
        cy.visit("/admin/schedules");
        cy.url().should("include", "/admin/schedules");

        // --- 1. CREATE ---
        cy.contains("span", "Tambah Jadwal").click();
        cy.get("#addScheduleModal").should("be.visible");

        // Interact with elements inside the ADD modal with corrected logic
        cy.get("#addScheduleModal").within(() => {
            // First, select 'other' to reveal the custom name input
            cy.get("#add_class_list_id").select("other");
            cy.get("#add_custom_class_name_group").should("be.visible");

            // Then, type the custom name
            cy.get("#add_custom_class_name").type(newClassName);

            // Now, fill out the rest of the form
            cy.get("#add_day").select("Senin");
            cy.get("#add_start_time").type("10:00");
            cy.get("#add_end_time").type("11:00");

            // Select the second option in the trainer dropdown to avoid the placeholder
            // and not rely on a hardcoded ID '1'.
            cy.get("#add_trainer_id").select(1);

            cy.get("#add_max_quota").type("20");

            // As per user feedback, fill in the "script" (description)
            cy.get("#add_description").type(
                "Ini adalah deskripsi untuk kelas tes Cypress."
            );

            // Submit the form
            cy.get('button[type="submit"]').contains("Simpan Jadwal").click();
        });
    });
});
