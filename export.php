<?php
require('fpdf/fpdf.php');
require_once('config.php'); // Assuming this file contains your database connection code

class PDF extends FPDF
{
    // Header
    function Header()
    {
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'Printing Request Receipt', 0, 1, 'C');
        $this->Ln(10);
    }

    // Footer
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }

    // Print Receipt
    function PrintReceipt($requestId)
    {
        global $conn;

        // Fetch data from the database based on $requestId
        $query = "SELECT * FROM printingorder WHERE REQUEST_ID = $requestId";
        $result = $conn->query($query);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Display information on the PDF
            $this->SetFont('Arial', '', 12);
            $this->Cell(0, 10, 'Request ID: ' . $row['REQUEST_ID'], 0, 1);
            $this->Cell(0, 10, 'User ID: ' . $row['USER_ID'], 0, 1);
            $this->Cell(0, 10, 'Stocks ID: ' . $row['STOCKS_ID'], 0, 1);
            $this->Cell(0, 10, 'P Status: ' . $row['P_STATUS'], 0, 1);
            $this->Cell(0, 10, 'Total Price: $' . $row['TOTAL_PRICE'], 0, 1);
            $this->Cell(0, 10, 'Document: ' . $row['DOCUMENT'], 0, 1);
            $this->Cell(0, 10, 'Quantity: ' . $row['QUANTITY'], 0, 1);
            $this->Cell(0, 10, 'Comment: ' . $row['COMMENT'], 0, 1);
            $this->Ln(10);
        } else {
            $this->Cell(0, 10, 'Error: Request not found', 0, 1);
        }
    }
}

// Example usage
$pdf = new PDF();
$pdf->AddPage();

// Example: Display receipt for Request ID 1
$pdf->PrintReceipt(1);

// Output PDF
$pdf->Output();
?>
