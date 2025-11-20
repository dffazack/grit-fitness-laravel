/// <reference types="cypress" />

describe("Member Full Payment Flow from Dashboard", () => {
    it("should navigate from dashboard to payment, and upload proof of payment", () => {
        // 1. Log in as a user who can make a payment
        // 'bob@example.com' is a 'guest' with 'pending' status.
        // This user is allowed to see the dashboard.
        cy.login("member@gritfitness.com", "password");

        cy.visit("/member/dashboard");

        // 2. Click the link to go to the Payment History page from the dashboard
        cy.contains("a.list-group-item-action", "Riwayat Pembayaran").click();

        // 3. Verify we are on the payment page and the history tab is active
        cy.url().should("include", "/member/payment");
        cy.get("#history-tab").should("have.class", "active");

        // 4. Switch to the "Upload Proof" tab
        cy.get("#upload-tab").click();
        cy.get("#upload").should("be.visible").and("have.class", "active");

        // 5. Fill out the upload form
        cy.get("#upload").within(() => {
            // Select the second option in the dropdown (index 1) to avoid the placeholder
            cy.get("select#package").select(1);

            // Attach the placeholder file to the file input
            cy.get("input#proof").selectFile(
                "cypress/fixtures/placeholder.png"
            );

            // Submit the form
            cy.get('button[type="submit"]')
                .contains("Kirim Bukti Pembayaran")
                .click();
        });
    });
});
