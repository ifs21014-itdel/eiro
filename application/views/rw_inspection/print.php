<?php
$my_pdf = new Pdf();
$my_pdf->setPageOrientation('P', true, 2);
$my_pdf->SetCompression(true);
$my_pdf->setPrintHeader(true);
$my_pdf->setPrintFooter(false);
$my_pdf->SetMargins(10, 10, 10); // Memberikan margin agar tidak terlalu mepet
$my_pdf->SetFont('', '', 7);
$my_pdf->AddPage();
?>

<head>
    <style>
        table {
            page-break-inside: auto;
            border-collapse: collapse;
            width: 90%; /* Memberikan ruang margin */
            margin: 20px auto; /* Menambahkan margin kiri, kanan, dan atas */
        }
        th, td {
            border: 1px solid black;
            padding: 10px; /* Padding agar konten tidak terlalu mepet */
            text-align: center;
        }
        th {
            background-color: #ffff99;
        }
        .bold {
            font-weight: bold;
        }
        .inspection-img {
            width: 175px;
            height: 120px;
            object-fit: cover;
            padding: 5px;
        }
        .desc {
            text-align: left;
            vertical-align: top;
            padding: 10px;
        }
    </style>
</head>

<table border="1" cellpadding="5" cellspacing="5">
    <thead>
        <tr>
            <th colspan="6" class="bold"><h2>REPORT ONLINE (EIRO)</h2></th>
        </tr>
        <tr>
            <th colspan="6" class="bold">EBAKO RW INSPECTION</th>
        </tr>
        <tr>
            <th colspan="6" class="bold">END FINAL AREA</th>
        </tr>
        <tr>
            <td colspan="3">
                <font face='courier' size='2'>
                    PT EBAKO NUSANTARA <br>
                    Jl. Terboyo Industri Barat Dalam II Blok N/3C<br>
                    Kawasan Industri Terboyo Park - Semarang <br>
                    Jawa Tengah - Indonesia
                </font>
            </td>
            <td colspan="3">
                <?php
                $image = $_SERVER["HTTP_REFERER"] . 'files/logo.png';
                echo "<img src='" . $image . "' width='100'>";
                ?>
            </td>
        </tr>
    </thead>

    <tbody>
        <tr>
            <td width='20%'>Customer</td>
            <td width='2%'>:</td>
            <td width='30%'><?php echo $rw_inspection->client_name; ?></td>
            <td width='20%'>PO Client No</td>
            <td width='2%'>:</td>
            <td width='30%'><?php echo $rw_inspection->po_client_no; ?></td>
        </tr>
        <tr>
            <td>Cust. Code</td>
            <td>:</td>
            <td><?php echo $rw_inspection->customer_code; ?></td>
            <td>RW Inspection Date</td>
            <td>:</td>
            <td><?php echo date('d F Y', strtotime($rw_inspection->rw_inspection_date)); ?></td>
        </tr>
        <tr>
            <td>Ebako Code</td>
            <td>:</td>
            <td><?php echo $rw_inspection->ebako_code; ?></td>
            <td>Inspector</td>
            <td>:</td>
            <td><?php echo $rw_inspection->user_added; ?></td>
        </tr>

        <tr>
            <th colspan="6" class="bold">RW INSPECTION DOCUMENTATION</th>
        </tr>

        <?php
        foreach ($rw_inspection_detail as $result) {
            if ($result->filename == null) {
                continue;
            }
        ?>
            <tr>
                <td colspan="3" class="center">
                    <b><?php echo $result->view_position; ?></b><br>
                    <?php
                    $image = $_SERVER["HTTP_REFERER"] . 'files/rw_inspection/' . $result->rw_inspection_id . "/" . $result->filename;
                    echo "<img src='" . $image . "' class='inspection-img'>";
                    ?>
                </td>
                <td colspan="3" class="desc">
                    <?php echo nl2br($result->description); ?>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<?php
//$my_pdf->writeHTML($tbl, true, false, true, false, '');
//$file_name = $shipment->shipment_no . '.pdf';
//$my_pdf->Output($file_name, 'D');
?>
