<!DOCTYPE html>
<html>
<head>
    <title>Khalti Payment</title>
    <script src="https://dev.khalti.com/static/khalti-checkout.js"></script>
</head>
<body>

<h3>Redirecting to Khalti...</h3>

<script src="https://dev.khalti.com/static/khalti-checkout.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {

        var config = {
            publicKey: "{{ $publicKey }}",
            productIdentity: "{{ $payment->txRef }}",
            productName: "Application Fee",
            productUrl: "{{ url('/') }}",
            eventHandler: {
                onSuccess(payload) {

                    fetch("{{ route('candidate.payment.khalti.verify') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            token: payload.token,
                            amount: payload.amount,
                            txRef: "{{ $payment->txRef }}"
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.href = data.redirect;
                        } else {
                            alert("Verification failed");
                        }
                    });
                },
                onError(error) {
                    console.log(error);
                    alert("Payment Failed");
                },
                onClose() {
                    console.log("Widget Closed");
                }
            }
        };

        var checkout = new KhaltiCheckout(config);

        // Delay 500ms then open
        setTimeout(function () {
            checkout.show({ amount: {{ $amount }} });
        }, 500);

    });
</script>
