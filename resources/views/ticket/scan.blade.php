@extends('layouts.app')

@section('title', 'Dashboard')

@include('layouts.all')

@section('content')

<style>
    .outer {
        height: 350px;
        position: relative; /* For absolute positioning of the inner div */
    }

    /* Apply styles to the inner div */
    .inner {
        width: 100%; /* Fill the width of the outer div */
        height: 100%; /* Fill the height of the outer div */
        background-color: #ccc; /* Just for visualization */
    }
</style>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-sm-12">
            <div class="card">
                <div class="card-header">Scan Your QR Code</div>
                <div class="card-body">
                    <div class="outer">
                        <div id="scanner" class="inner" ></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/quagga/dist/quagga.min.js"></script>

<script type="text/javascript">


function scanQRCode() {
    Quagga.init({
        inputStream: {
            name: "Live",
            type: "LiveStream",
            target: document.querySelector("#scanner"), // Target the scanning area
            constraints: {
                width: 300,
                height: 200,
                facingMode: "environment", // Use the device's rear camera
            },
        },
        decoder: {
            readers: ["code_128_reader", "ean_reader", "ean_8_reader", "code_39_reader"], // Adjust readers based on your requirements
        },
    }, function(err) {
        if (err) {
            console.error(err);
            return;
        }
        console.log("Initialization finished. Ready to start");
        Quagga.start();
        
        // Listen for scan events
        Quagga.onDetected(function(result) {
            console.log("Scanned:", result.codeResult.code);
            
            // After scanning, trigger the AJAX call
            $.ajax({
                url: "/scan",
                type: 'get',
                data: {
                    code: result.codeResult.code
                },
                success: function(response) {
        
                    alert(response.scannedData);
                }
            });
        });
    });
}
</script>
@endsection