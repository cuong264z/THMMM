<?php include 'app/views/shares/header.php'; ?>

<?php

$total = 0;

if (isset($_SESSION['cart']))
{
    foreach ($_SESSION['cart'] as $item)
    {
        $total +=
            $item['price']
            * $item['quantity'];
    }
}

// THÔNG TIN BANK
$bankId = "MBBank";

$accountNo = "0123456789";

$accountName = "NGUYEN VAN A";

// QR URL
$qrUrl =
    "https://img.vietqr.io/image/"
    . $bankId . "-"
    . $accountNo
    . "-compact2.png"
    . "?amount=" . $total
    . "&addInfo=Thanh toan don hang"
    . "&accountName="
    . urlencode($accountName);

?>

<div class="row justify-content-center">

    <div class="col-lg-8">

        <div class="card shadow border-0">

            <div class="card-body p-5 text-center">

                <h1 class="mb-4">

                    <i class="fas fa-qrcode"></i>

                    Thanh toán VNPAY QR

                </h1>

                <h3 class="text-danger mb-4">

                    <?php
                    echo number_format(
                        $total,
                        0,
                        ',',
                        '.'
                    );
                    ?> VNĐ

                </h3>

                <!-- QR -->
                <img
                    src="<?php echo $qrUrl; ?>"
                    class="img-fluid mb-4"
                    style="max-width:300px;"
                >

                <p class="text-muted">

                    Quét mã bằng app ngân hàng
                    hoặc VNPAY để thanh toán

                </p>

                <!-- FORM -->
                <form
                    method="POST"
                    action="/Product/processCheckout"
                >

                    <input
                        type="hidden"
                        name="name"
                        value="Khách hàng"
                    >

                    <input
                        type="hidden"
                        name="phone"
                        value="0123456789"
                    >

                    <input
                        type="hidden"
                        name="address"
                        value="Online"
                    >

                    <button
                        type="submit"
                        class="btn btn-success btn-lg mt-3"
                    >

                        <i class="fas fa-check-circle"></i>

                        Tôi đã thanh toán

                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

<?php include 'app/views/shares/footer.php'; ?>