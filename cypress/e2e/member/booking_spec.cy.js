/// <reference types="cypress" />

describe("Member Class Booking", () => {
    beforeEach(() => {
        // Log in as an active member before each test
        // This user is from UserSeeder.php and has an active membership
        cy.login("member@gritfitness.com", "password");
        // Visit the classes page
        cy.visit("/classes");
    });

    it("should allow an active member to book an available class", () => {
        // 1. Find an available class and book it
        // We look for the first button that contains "Booking Kelas" and is not disabled.
        // This ensures we don't try to book a full or already booked class.
        cy.contains("button", "Booking Kelas")
            .not("[disabled]")
            .first()
            .click();

        // 2. Verify the booking was successful
        // After booking, the user is likely redirected back to the classes page
        // with a success message.
        cy.url().should("include", "/classes");

        // Check for the success message from the controller.
        // Let's assume the message is "Booking berhasil".
        cy.contains("Kelas berhasil di-booking!").should("be.visible");

        // 3. Verify the class now shows as "Sudah Dibooking"
        // We can check that the button we just clicked (or a similar one) is now disabled.
        // This is a good sign that the state has updated.
        // The original button might not exist anymore, so we check for the text on the page.
        cy.contains("Sudah Dibooking").should("be.visible");

        // 4. Verify the booking appears in the member's booking history
        cy.visit("/member/bookings");
        cy.url().should("include", "/member/bookings");

        // Check that there is at least one booking card/row in the history.
        // A more specific check would be to get the name of the class we booked
        // and verify it exists on this page.
        cy.get(".card").should("have.length.gt", 0); // Assumes booking history uses cards.
    });
});
