<?php
// Include the FPDF library
require('fpdf/fpdf.php');

// Include the database connection file
include('config.php'); // Replace with your actual connection file

// Check if the form is submitted
if(isset($_POST['generate_pdf'])){
    $selected_request_id = $_POST['request_id'];

    // Fetch printing request details including user information
    $query = "SELECT printingorder.*, stocks.MEDIUM, status.DATE, user.Fname, user.Lname, user.EMAIL
              FROM printingorder
              JOIN stocks ON printingorder.STOCKS_ID = stocks.STOCKS_ID
              JOIN status ON printingorder.REQUEST_ID = status.REQUEST_ID
              JOIN user ON printingorder.USER_ID = user.USER_ID
              WHERE printingorder.REQUEST_ID = $selected_request_id";

    $result = mysqli_query($conn, $query);

    if($row = mysqli_fetch_assoc($result)){
        // Create a PDF document
        $pdf = new FPDF();
        $pdf->AddPage();

        // Add printing request details to the PDF
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Printing Request Details', 0, 1, 'C');

        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'User: ' . $row['Fname'] . ' ' . $row['Lname'], 0, 1);
        $pdf->Cell(0, 10, 'Email: ' . $row['EMAIL'], 0, 1);
        $pdf->Cell(0, 10, 'Medium: ' . $row['MEDIUM'], 0, 1);
        $pdf->Cell(0, 10, 'Total Price: $' . $row['TOTAL_PRICE'], 0, 1);
        $pdf->Cell(0, 10, 'Quantity: ' . $row['QUANTITY'], 0, 1);
        $pdf->Cell(0, 10, 'Date: ' . $row['DATE'], 0, 1);

        // Output the PDF
        $pdf->Output();
    } else {
        echo '<p>No printing request found with the selected ID.</p>';
    }
} else {
    echo '<p>Invalid request. Please go back and try again.</p>';
}
?>
